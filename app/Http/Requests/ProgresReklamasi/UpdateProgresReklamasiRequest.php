<?php

namespace App\Http\Requests\ProgresReklamasi;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\FieldDefinition;
use App\Models\Plot;
use App\Models\IndikatorProgresReklamasi;
use App\Models\TargetProgresReklamasi;
use Illuminate\Support\Facades\Log;

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
            'tanggal' => 'required|date',
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
            $plotId = $this->route('plot')?->plot_id ?? $this->route('plot');
            $activityId = $this->input('jenis_aktivitas_id');

            if (!$plotId || !$activityId) {
                return;
            }

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
                $validator->errors()->add('target', 'Tidak dapat input progres karena target untuk indikator ini belum dibuat!');
            }
        });
    }

    /**
     * Build validation rules for dynamic fields with plot area constraints
     */
    private function buildDynamicFieldRules(int $activityId): array
    {
        $dynamicRules = [];
        $fieldDefinitions = FieldDefinition::where('jenis_aktivitas_id', $activityId)->get();
        $plotAreaLimit = $this->getPlotAreaLimit();
        
        foreach ($fieldDefinitions as $fieldDef) {
            $fieldRules = $this->buildFieldValidationRules($fieldDef, $plotAreaLimit);
            if (!empty($fieldRules)) {
                $dynamicRules[$fieldDef->field_key] = $fieldRules;
            }
        }

        return $dynamicRules;
    }

    /**
     * Build validation rules for individual field with area constraints
     */
    private function buildFieldValidationRules(FieldDefinition $fieldDef, ?float $plotAreaLimit = null): array
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
                $this->addNumericRangeRules($rules, $fieldConfig, $fieldDef, $plotAreaLimit);
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
     * Add numeric range validation rules with plot area limit consideration
     */
    private function addNumericRangeRules(array &$rules, array $fieldConfig, FieldDefinition $fieldDef, ?float $plotAreaLimit = null): void
    {
        // Add minimum value validation
        if (isset($fieldConfig['min']) && is_numeric($fieldConfig['min'])) {
            $rules[] = 'min:' . $fieldConfig['min'];
        }
        
        // For area-related fields, check against plot area limit
        if ($plotAreaLimit && $this->isAreaRelatedField($fieldDef)) {
            $configMax = $fieldConfig['max'] ?? null;
            
            // Use the smaller value between config max and plot area
            if ($configMax && is_numeric($configMax)) {
                $effectiveMax = min((float) $configMax, $plotAreaLimit);
            } else {
                $effectiveMax = $plotAreaLimit;
            }
            
            $rules[] = 'max:' . $effectiveMax;
        } elseif (isset($fieldConfig['max']) && is_numeric($fieldConfig['max'])) {
            // Use config max if no plot area constraint
            $rules[] = 'max:' . $fieldConfig['max'];
        }
    }

    /**
     * Check if field is area-related (should be limited by plot area)
     */
    private function isAreaRelatedField(FieldDefinition $fieldDef): bool
    {
        $areaRelatedKeys = [
            'luas_area_dirata',
            'luas_area_ditimbun',
            'luas_area_dikupas',
            'luas_area_disebar',
            'luas_area_penanaman',
            'luas_area_ditanam', 
            'luas_area_disiangi',
            'luas_area_dipupuk',
            'luas_area_dilindungi',
            'luas_area_disulam',
            'luas_area_sampling',
            'area'
        ];

        $fieldKey = strtolower($fieldDef->field_key);
        
        // Check exact matches
        if (in_array($fieldKey, $areaRelatedKeys)) {
            return true;
        }
        
        // Check if field key contains area-related terms
        foreach ($areaRelatedKeys as $areaKey) {
            if (strpos($fieldKey, $areaKey) !== false || strpos($fieldKey, 'luas') !== false) {
                return true;
            }
        }
        
        // Check if field has area indicator or unit
        if (isset($fieldDef->satuan) && strtolower($fieldDef->satuan) === 'ha') {
            return true;
        }
        
        if (isset($fieldDef->indicator_key) && strpos($fieldDef->indicator_key, 'lahan') !== false) {
            return true;
        }

        return false;
    }

    /**
     * Get plot area limit from route parameter
     */
    private function getPlotAreaLimit(): ?float
    {
        try {
            // Get plot from route parameter - check both plot and progres routes
            $plotId = $this->route('plot')?->plot_id ?? $this->route('plot');
            
            // If not in plot route, try to get from progres relation
            if (!$plotId) {
                $progresId = $this->route('progres')?->progres_reklamasi_id ?? $this->route('progres');
                if ($progresId) {
                    $progres = \App\Models\ProgresReklamasi::find($progresId);
                    $plotId = $progres?->plot_id;
                }
            }
            
            if (!$plotId) {
                return null;
            }
            
            $plot = $plotId instanceof Plot ? $plotId : Plot::find($plotId);
            
            if (!$plot || !$plot->luas_area) {
                return null;
            }
            
            return (float) $plot->luas_area;
            
        } catch (\Exception $e) {
            Log::error('Error getting plot area limit in update request', [
                'error' => $e->getMessage()
            ]);
            return null;
        }
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
     * Get custom validation error messages with plot area context
     */
    public function messages(): array
    {
        $plotAreaLimit = $this->getPlotAreaLimit();
        
        return [
            'jenis_aktivitas_id.required' => 'Jenis aktivitas harus dipilih.',
            'jenis_aktivitas_id.exists' => 'Jenis aktivitas tidak valid.',
            'tanggal.required' => 'Tanggal harus diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'catatan.max' => 'Catatan maksimal 500 karakter.',
            'dokumentasi.*.image' => 'File dokumentasi harus berupa gambar.',
            'dokumentasi.*.max' => 'Ukuran gambar maksimal 5MB.',
            'removed_files.string' => 'Data file removal tidak valid.',
            '*.max' => $plotAreaLimit 
                ? "Nilai tidak boleh lebih dari luas blok lahan ({$plotAreaLimit} ha)." 
                : 'Nilai melebihi batas maksimum yang diizinkan.',
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
        $plotAreaLimit = $this->getPlotAreaLimit();
        
        Log::error('Progress update validation failed', [
            'errors' => $validator->errors()->toArray(),
            'activity_id' => $this->input('jenis_aktivitas_id'),
            'plot_area_limit' => $plotAreaLimit,
            'removed_files_input' => $this->input('removed_files'),
            'has_dokumentasi' => $this->hasFile('dokumentasi')
        ]);

        parent::failedValidation($validator);
    }
}