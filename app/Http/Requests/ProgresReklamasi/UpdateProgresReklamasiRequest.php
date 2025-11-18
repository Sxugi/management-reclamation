<?php

namespace App\Http\Requests\ProgresReklamasi;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\FieldDefinition;
use App\Models\Plot;
use App\Models\IndikatorProgresReklamasi;
use App\Models\TargetProgresReklamasi;
use App\Models\ProgresReklamasi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UpdateProgresReklamasiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get validation rules including dynamic field rules, plot area limit and file removal
     */
    public function rules(): array
    {
        $coreRules = [
            'jenis_aktivitas_id' => 'required|exists:jenis_aktivitas,jenis_aktivitas_id',
            'tanggal' => [
                'required',
                'date',
                'before_or_equal:today' 
            ],
            'catatan' => 'nullable|string|max:500',
            'dokumentasi' => 'nullable|array',
            'dokumentasi.*' => 'nullable|image|max:5120', // 5MB per image
            'removed_files' => 'nullable|string',
        ];

        // Add dynamic field validation rules with plot area limits
        $activityId = $this->input('jenis_aktivitas_id');
        
        if ($activityId && is_numeric($activityId)) {
            try {
                $dynamicRules = $this->buildDynamicFieldRules($activityId);
                $coreRules = array_merge($coreRules, $dynamicRules);
            } catch (\Exception $e) {
                Log::error('Error building dynamic validation rules in update request', [
                    'activity_id' => $activityId,
                    'error' => $e->getMessage()
                ]);
                
                if (config('app.debug')) {
                    throw $e;
                }
            }
        }

        return $coreRules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $plotId = $this->getPlotId();
            $activityId = $this->input('jenis_aktivitas_id');

            if (!$plotId || !$activityId) {
                return;
            }

            // Check target exists
            $this->validateTargetExists($validator, $plotId, $activityId);
            
            // Check cumulative limits based on target and field type (excluding current record)
            $this->validateCumulativeLimits($validator, $plotId, $activityId);
        });
    }

    /**
     * Validate that target exists for the indicator
     */
    private function validateTargetExists($validator, $plotId, $activityId)
    {
        $indikatorId = null;

        $indicatorField = FieldDefinition::where('jenis_aktivitas_id', $activityId)
            ->whereNotNull('indicator_key')
            ->first();

        if ($indicatorField) {
            $indikatorRow = IndikatorProgresReklamasi::where('nama', $indicatorField->indicator_key)->first();
            $indikatorId = $indikatorRow ? $indikatorRow->indikator_id : null;
        }

        if (!$indikatorId) {
            return;
        }

        $hasTarget = TargetProgresReklamasi::where('plot_id', $plotId)
            ->where('indikator_id', $indikatorId)
            ->exists();

        if (!$hasTarget) {
            $validator->errors()->add('target', "Tidak dapat update progres karena target untuk {$indicatorField->field_label} belum dibuat!");
        }
    }

    /**
     * Validate cumulative limits based on field type and target (excluding current record)
     */
    private function validateCumulativeLimits($validator, $plotId, $activityId)
    {
        $plot = Plot::find($plotId);
        if (!$plot) {
            return;
        }

        $currentProgresId = $this->getCurrentProgresId();
        $fieldDefinitions = FieldDefinition::where('jenis_aktivitas_id', $activityId)->get();

        foreach ($fieldDefinitions as $fieldDef) {
            $fieldKey = $fieldDef->field_key;
            $newValue = $this->input($fieldKey);

            if (!is_numeric($newValue) || $newValue <= 0) {
                continue;
            }

            $newValue = (float) $newValue;

            // Determine validation type based on field characteristics
            $validationType = $this->determineValidationType($fieldDef, $plot);
            
            if ($validationType === 'none') {
                continue;
            }

            // Get appropriate limit based on validation type
            $limit = $this->getFieldLimit($fieldDef, $plot, $plotId, $validationType);
            
            if (!$limit) {
                continue;
            }

            // Get cumulative value for this field (excluding current record)
            $cumulativeValue = $this->getCumulativeFieldValue($plotId, $fieldKey, $currentProgresId);
            $totalValue = $cumulativeValue + $newValue;

            if ($totalValue > $limit) {
                $this->addLimitValidationError($validator, $fieldDef, $limit, $cumulativeValue, $totalValue, $validationType);
            }
        }
    }

    /**
     * Get current progres ID being updated
     */
    private function getCurrentProgresId(): ?int
    {
        // Get progres ID from route parameter
        $progres = $this->route('progres');
        
        if ($progres instanceof ProgresReklamasi) {
            return $progres->progres_id;
        }
        
        if (is_numeric($progres)) {
            return (int) $progres;
        }

        return null;
    }

    /**
     * Get plot ID from various route sources
     */
    private function getPlotId(): ?int
    {
        // Try to get from plot route parameter
        $plotId = $this->route('plot')?->plot_id ?? $this->route('plot');
        
        if ($plotId) {
            return is_numeric($plotId) ? (int) $plotId : $plotId->plot_id;
        }

        // If not in plot route, try to get from progres relation
        $progresId = $this->getCurrentProgresId();
        if ($progresId) {
            $progres = ProgresReklamasi::find($progresId);
            return $progres?->plot_id;
        }

        return null;
    }

    /**
     * Determine what type of validation should be applied to the field
     */
    private function determineValidationType(FieldDefinition $fieldDef, Plot $plot): string
    {
        $fieldKey = $fieldDef->field_key;
        $satuan = strtolower($fieldDef->satuan ?? '');

        // Area-related fields (should be limited by plot area)
        if ($this->isAreaRelatedField($fieldDef)) {
            return 'area'; // Compare with plot area
        }

        // Volume fields (m³) - should be limited by target value  
        if ($satuan === 'm³' || $this->isVolumeField($fieldKey)) {
            return 'target'; // Compare with target value
        }

        // Length fields (m) - should be limited by target value
        if ($satuan === 'm' || $this->isLengthField($fieldKey)) {
            return 'target'; // Compare with target value
        }

        // Unit fields (unit, batang, buah) - should be limited by target value
        if (in_array($satuan, ['unit', 'batang', 'buah']) || $this->isUnitField($fieldKey)) {
            return 'target'; // Compare with target value
        }

        return 'none'; // No cumulative validation for other fields
    }

    /**
     * Get limit value based on validation type
     */
    private function getFieldLimit(FieldDefinition $fieldDef, Plot $plot, $plotId, string $validationType): ?float
    {
        switch ($validationType) {
            case 'area':
                return $plot->luas_area ? (float) $plot->luas_area : null;
                
            case 'target':
                return $this->getTargetValue($plotId, $fieldDef);
                
            default:
                return null;
        }
    }

    /**
     * Get target value for a specific field
     */
    private function getTargetValue($plotId, FieldDefinition $fieldDef): ?float
    {
        try {
            // Find indicator for this field's activity
            $indicatorField = FieldDefinition::where('jenis_aktivitas_id', $fieldDef->jenis_aktivitas_id)
                ->whereNotNull('indicator_key')
                ->first();

            if (!$indicatorField) {
                return null;
            }

            $indikatorRow = IndikatorProgresReklamasi::where('nama', $indicatorField->indicator_key)->first();
            
            if (!$indikatorRow) {
                return null;
            }

            // Get target value
            $target = TargetProgresReklamasi::where('plot_id', $plotId)
                ->where('indikator_id', $indikatorRow->indikator_id)
                ->first();

            return $target ? (float) $target->target_value : null;

        } catch (\Exception $e) {
            Log::error('Error getting target value in update request', [
                'plot_id' => $plotId,
                'field_key' => $fieldDef->field_key,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Get cumulative value for a specific field from progres_field_values (excluding current record)
     */
    private function getCumulativeFieldValue($plotId, $fieldKey, $excludeProgresId = null): float
    {
        try {
            // Get field definition
            $activityId = $this->input('jenis_aktivitas_id');
            $fieldDefinition = FieldDefinition::where('jenis_aktivitas_id', $activityId)
                ->where('field_key', $fieldKey)
                ->first();

            if (!$fieldDefinition) {
                Log::warning('Field definition not found in update request', [
                    'field_key' => $fieldKey,
                    'activity_id' => $activityId
                ]);
                return 0;
            }

            // Calculate cumulative
            $query = DB::table('progres')
                ->join('progres_field_values', 'progres.progres_id', '=', 'progres_field_values.progres_id')
                ->where('progres.plot_id', $plotId)
                ->where('progres.jenis_aktivitas_id', $activityId)
                ->where('progres_field_values.field_definition_id', $fieldDefinition->field_definition_id);

            // Exclude current record if updating
            if ($excludeProgresId) {
                $query->where('progres.progres_id', '!=', $excludeProgresId);
            }

            $cumulativeValue = $query->sum(DB::raw('CAST(progres_field_values.field_value AS DECIMAL(10,2))'));

            return (float) $cumulativeValue;

        } catch (\Exception $e) {
            Log::error('Error calculating cumulative field value in update request', [
                'plot_id' => $plotId,
                'field_key' => $fieldKey,
                'exclude_progres_id' => $excludeProgresId,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Add appropriate validation error based on validation type
     */
    private function addLimitValidationError($validator, FieldDefinition $fieldDef, $limit, $cumulativeValue, $totalValue, $validationType)
    {
        $fieldLabel = $fieldDef->field_label ?? ucfirst($fieldDef->field_key);
        $fieldKey = $fieldDef->field_key;
        $remaining = $limit - $cumulativeValue;
        $satuan = $fieldDef->satuan ?? '';

        if ($remaining <= 0) {
            if ($validationType === 'area') {
                $validator->errors()->add($fieldKey, 
                    "{$fieldLabel} sudah mencapai batas maksimum luas blok ({$limit} ha). " .
                    "Total area yang sudah diinput: {$cumulativeValue} ha."
                );
            } else {
                $validator->errors()->add($fieldKey, 
                    "{$fieldLabel} sudah mencapai target maksimum ({$limit} {$satuan}). " .
                    "Total yang sudah diinput: {$cumulativeValue} {$satuan}."
                );
            }
        } else {
            if ($validationType === 'area') {
                $validator->errors()->add($fieldKey, 
                    "{$fieldLabel} melebihi sisa area yang tersedia. " .
                    "Maksimal yang bisa diinput: {$remaining} ha " .
                    "(Total area sudah digunakan: {$cumulativeValue} ha dari {$limit} ha)."
                );
            } else {
                $validator->errors()->add($fieldKey, 
                    "{$fieldLabel} melebihi sisa target yang tersedia. " .
                    "Maksimal yang bisa diinput: {$remaining} {$satuan} " .
                    "(Total sudah diinput: {$cumulativeValue} {$satuan} dari target {$limit} {$satuan})."
                );
            }
        }
    }

    /**
     * Build validation rules for dynamic fields
     */
    private function buildDynamicFieldRules(int $activityId): array
    {
        $dynamicRules = [];
        $fieldDefinitions = FieldDefinition::where('jenis_aktivitas_id', $activityId)->get();
        
        foreach ($fieldDefinitions as $fieldDef) {
            $fieldRules = $this->buildFieldValidationRules($fieldDef);
            if (!empty($fieldRules)) {
                $dynamicRules[$fieldDef->field_key] = $fieldRules;
            }
        }

        return $dynamicRules;
    }

    /**
     * Build validation rules for individual field
     */
    private function buildFieldValidationRules(FieldDefinition $fieldDef): array
    {
        $fieldConfig = $fieldDef->config ?? [];
        $rules = [];
        
        // Set required or nullable
        $isRequired = ($fieldConfig['required'] ?? false) === true;
        $rules[] = $isRequired ? 'required' : 'nullable';

        // Add type-specific validation rules
        switch ($fieldDef->field_type) {
            case 'number':
                $rules[] = 'numeric';
                $this->addNumericRangeRules($rules, $fieldConfig);
                break;
                
            case 'select':
                $rules[] = 'string';
                $this->addSelectOptionsRules($rules, $fieldConfig);
                break;
                
            case 'text':
                $rules[] = 'string';
                $maxLength = $fieldConfig['max_length'] ?? 255;
                $rules[] = 'max:' . $maxLength;
                break;
                
            case 'date':
                $rules[] = 'date';
                $rules[] = 'before_or_equal:today'; // Tidak boleh tanggal masa depan
                break;
                
            case 'textarea':
                $rules[] = 'string';
                $maxLength = $fieldConfig['max_length'] ?? 1000;
                $rules[] = 'max:' . $maxLength;
                break;
                
            case 'file':
                if ($fieldConfig['multiple'] ?? false) {
                    $rules = ['nullable', 'array'];
                } else {
                    $rules[] = 'file';
                    $rules[] = 'max:5120'; // 5MB default
                }
                break;
                
            default:
                $rules[] = 'string';
                $rules[] = 'max:255';
                break;
        }

        return $rules;
    }

    /**
     * Add numeric range validation rules
     */
    private function addNumericRangeRules(array &$rules, array $fieldConfig): void
    {
        // Add minimum value validation
        if (isset($fieldConfig['min']) && is_numeric($fieldConfig['min'])) {
            $rules[] = 'min:' . $fieldConfig['min'];
        }
        
        // Add maximum value validation (cumulative validation will be handled separately)
        if (isset($fieldConfig['max']) && is_numeric($fieldConfig['max'])) {
            $rules[] = 'max:' . $fieldConfig['max'];
        }
    }

    /**
     * Check if field is area-related (hectare-based)
     */
    private function isAreaRelatedField(FieldDefinition $fieldDef): bool
    {
        $areaRelatedKeys = [
            'luas_area_dirata',
            'luas_area_ditimbun', 
            'luas_area_dikupas',
            'luas_area_disebar',
            'luas_area_ditanam',
            'luas_area_penanaman',
            'luas_area_disiangi',
            'luas_area_dipupuk',
            'luas_area_dilindungi',
            'luas_area_disulam',
            'luas_area_sampling'
        ];

        $fieldKey = $fieldDef->field_key;
        $satuan = strtolower($fieldDef->satuan ?? '');

        // Check if unit is hectare-based
        if (in_array($satuan, ['ha', 'hektar', 'hectare'])) {
            return true;
        }
        
        // Check exact matches for area-related keys dari config asli
        return in_array($fieldKey, $areaRelatedKeys);
    }

    /**
     * Check if field is volume-related (cubic meter-based)
     */
    private function isVolumeField(string $fieldKey): bool
    {
        $volumeKeys = [
            'volume_material_dipindah',
            'volume_material_timbunan', 
            'volume_topsoil_dikupas',
            'volume_topsoil_disebar'
        ];
        
        return in_array($fieldKey, $volumeKeys);
    }

    /**
     * Check if field is length-related (meter-based)
     */
    private function isLengthField(string $fieldKey): bool
    {
        $lengthKeys = [
            'panjang_saluran'
        ];
        
        return in_array($fieldKey, $lengthKeys);
    }

    /**
     * Check if field is unit/count-related
     */
    private function isUnitField(string $fieldKey): bool
    {
        $unitKeys = [
            'jumlah_check_dam_dibuat',
            'jumlah_bibit_ditanam',
            'jumlah_bibit_sulaman',
            'jumlah_bibit_hidup',
            'jumlah_bibit_mati',
            'jumlah_sampel_diukur'
        ];
        
        return in_array($fieldKey, $unitKeys);
    }

    /**
     * Add select options validation rules
     */
    private function addSelectOptionsRules(array &$rules, array $fieldConfig): void
    {
        if (isset($fieldConfig['options']) && is_array($fieldConfig['options'])) {
            $allowedOptions = array_values($fieldConfig['options']);
            $rules[] = 'in:' . implode(',', $allowedOptions);
        }
    }

    /**
     * Get custom field labels for validation errors
     */
    public function attributes(): array
    {
        $coreAttributes = [
            'jenis_aktivitas_id' => 'Jenis Aktivitas',
            'tanggal' => 'Tanggal',
            'catatan' => 'Catatan',
            'dokumentasi' => 'Dokumentasi',
            'removed_files' => 'File yang Dihapus',
        ];

        // Add dynamic field attributes
        $activityId = $this->input('jenis_aktivitas_id');
        
        if ($activityId && is_numeric($activityId)) {
            try {
                $dynamicAttributes = $this->buildDynamicFieldAttributes($activityId);
                $coreAttributes = array_merge($coreAttributes, $dynamicAttributes);
            } catch (\Exception $e) {
                Log::error('Error building field attributes in update request', [
                    'activity_id' => $activityId,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $coreAttributes;
    }

    /**
     * Build attributes for dynamic fields
     */
    private function buildDynamicFieldAttributes(int $activityId): array
    {
        $dynamicAttributes = [];
        $fieldDefinitions = FieldDefinition::where('jenis_aktivitas_id', $activityId)->get();
        
        foreach ($fieldDefinitions as $fieldDef) {
            $dynamicAttributes[$fieldDef->field_key] = $fieldDef->field_label ?? ucfirst($fieldDef->field_key);
        }

        return $dynamicAttributes;
    }

    /**
     * Get custom validation error messages
     */
    public function messages(): array
    {
        return [
            'jenis_aktivitas_id.required' => 'Jenis aktivitas harus dipilih.',
            'jenis_aktivitas_id.exists' => 'Jenis aktivitas tidak valid.',
            'tanggal.required' => 'Data Tanggal harus diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini.',
            'catatan.max' => 'Catatan maksimal 500 karakter.',
            'dokumentasi.*.image' => 'File dokumentasi harus berupa gambar.',
            'dokumentasi.*.max' => 'Ukuran gambar maksimal 5MB.',
            'removed_files.string' => 'Data file removal tidak valid.',
            '*.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini.',
        ];
    }

    /**
     * Prepare the data for validation - handle removed files processing
     */
    protected function prepareForValidation(): void
    {
        Log::info("UpdateProgresReklamasiRequest::prepareForValidation - Starting", [
            'request_keys' => array_keys($this->all()),
            'has_removed_files' => $this->has('removed_files'),
            'removed_files_raw' => $this->input('removed_files'),
            'jenis_aktivitas_id' => $this->input('jenis_aktivitas_id')
        ]);

        // Process removed files data for validation
        if ($this->has('removed_files')) {
            $removedFiles = $this->input('removed_files');
            Log::info("UpdateProgresReklamasiRequest::prepareForValidation - Processing removed files", [
                'removed_files_type' => gettype($removedFiles),
                'removed_files_content' => $removedFiles,
                'removed_files_length' => is_string($removedFiles) ? strlen($removedFiles) : 'not_string'
            ]);
            
            // Validate JSON format
            if (is_string($removedFiles) && !empty($removedFiles)) {
                try {
                    $decoded = json_decode($removedFiles, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        Log::warning("UpdateProgresReklamasiRequest::prepareForValidation - Invalid JSON format", [
                            'json_error' => json_last_error_msg(),
                            'raw_data' => $removedFiles
                        ]);
                        // Reset to empty JSON if invalid
                        $this->merge(['removed_files' => '{}']);
                    } else {
                        Log::info("UpdateProgresReklamasiRequest::prepareForValidation - JSON validation successful", [
                            'decoded_structure' => is_array($decoded) ? array_keys($decoded) : 'not_array'
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::warning("UpdateProgresReklamasiRequest::prepareForValidation - JSON processing error", [
                        'error' => $e->getMessage(),
                        'raw_data' => $removedFiles
                    ]);
                    // Reset to empty JSON if error
                    $this->merge(['removed_files' => '{}']);
                }
            }
        }
    }

    /**
     * Get validated data with additional processing for removed files
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        
        Log::info("UpdateProgresReklamasiRequest::validated - Processing validated data", [
            'validated_keys' => array_keys($validated),
            'has_removed_files' => isset($validated['removed_files'])
        ]);

        // Ensure removed_files is properly formatted
        if (isset($validated['removed_files'])) {
            if (empty($validated['removed_files'])) {
                $validated['removed_files'] = '{}';
                Log::info("UpdateProgresReklamasiRequest::validated - Set empty removed_files to empty JSON");
            } else {
                Log::info("UpdateProgresReklamasiRequest::validated - Removed files data present", [
                    'removed_files_length' => strlen($validated['removed_files'])
                ]);
            }
        } else {
            $validated['removed_files'] = '{}';
            Log::info("UpdateProgresReklamasiRequest::validated - No removed_files, setting to empty JSON");
        }

        return $key ? data_get($validated, $key, $default) : $validated;
    }

    /**
     * Handle validation failure with comprehensive logging
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        Log::error('Progress update validation failed', [
            'errors' => $validator->errors()->toArray(),
            'activity_id' => $this->input('jenis_aktivitas_id'),
            'input_date' => $this->input('tanggal'),
            'current_progres_id' => $this->getCurrentProgresId()
        ]);

        parent::failedValidation($validator);
    }
}