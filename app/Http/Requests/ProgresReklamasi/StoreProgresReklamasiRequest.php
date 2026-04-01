<?php

namespace App\Http\Requests\ProgresReklamasi;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\FieldDefinition;
use App\Models\Plot;
use App\Models\IndikatorProgresReklamasi;
use App\Models\TargetProgresReklamasi;
use App\Models\ProgresReklamasi;
use App\Models\JenisAktivitas;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class StoreProgresReklamasiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get validation rules including dynamic field rules and plot area limit
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
            'dokumentasi.*' => 'nullable|image|max:10240', // 10MB per image
        ];

        // Add dynamic field validation rules with plot area limits
        $activityId = $this->input('jenis_aktivitas_id');
        
        if ($activityId && is_numeric($activityId)) {
            try {
                $dynamicRules = $this->buildDynamicFieldRules($activityId);
                $coreRules = array_merge($coreRules, $dynamicRules);
            } catch (\Exception $e) {
                Log::error('Error building dynamic validation rules', [
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

            // Check if activity requires target 
            if ($this->activityRequiresTarget($activityId)) {
                $this->validateTargetExists($validator, $plotId, $activityId);
                $this->validateCumulativeLimits($validator, $plotId, $activityId);
            }

            // Separate monitoring validation (NO target check)
            $this->validateMonitoringRules($validator, $plotId, $activityId);
        });
    }

    /**
     * Check if activity requires target (indicator-based activities)
     */
    private function activityRequiresTarget(int $activityId): bool
    {
        $jenisAktivitas = JenisAktivitas::find($activityId);
        
        if (!$jenisAktivitas) return false;
        
        // Monitoring activities DON'T require targets
        $monitoringActivities = [
            'monitoring_survival_rate',
            'monitoring_pertumbuhan',
        ];
        
        return !in_array($jenisAktivitas->field, $monitoringActivities);
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
            $validator->errors()->add('target', "Tidak dapat input progres karena target untuk indikator {$indicatorField->field_label} belum dibuat!");
        }
    }

    /**
     * Validate cumulative limits based on field type and target
     */
    private function validateCumulativeLimits($validator, $plotId, $activityId)
    {
        $plot = Plot::find($plotId);
        if (!$plot) {
            return;
        }

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

            // Get cumulative value for this field
            $cumulativeValue = $this->getCumulativeFieldValue($plotId, $fieldKey, $activityId);
            $totalValue = $cumulativeValue + $newValue;

            if ($totalValue > $limit) {
                $this->addLimitValidationError($validator, $fieldDef, $limit, $cumulativeValue, $totalValue, $validationType);
            }
        }
    }

    /**
     * Validate monitoring-specific rules (separate from target-based validation)
     */
    private function validateMonitoringRules($validator, $plotId, $activityId)
    {
        $jenisAktivitas = JenisAktivitas::find($activityId);
        
        if (!$jenisAktivitas) return;
        
        // Route to specific monitoring validation
        switch ($jenisAktivitas->field) {
            case 'monitoring_survival_rate':
                $this->validateSurvivalRateMonitoring($validator, $plotId);
                break;
                
            case 'monitoring_pertumbuhan':
                $this->validateGrowthMonitoring($validator, $plotId);
                break;
        }
    }

    /**
     * Validate survival rate monitoring (simplified - NO target check, NO area check)
     */
    private function validateSurvivalRateMonitoring($validator, $plotId)
    {
        $jenisPohonId = $this->input('jenis_pohon_id');
        $metodeSampling = $this->input('metode_sampling');
        $jumlahSurvey = (int)$this->input('jumlah_bibit_disurvey', 0);
        $hidup = (int)$this->input('jumlah_bibit_hidup', 0);
        $mati = (int)$this->input('jumlah_bibit_mati', 0);
        
        // 1. Validate jenis_pohon_id
        if (!$jenisPohonId) {
            $validator->errors()->add('jenis_pohon_id', 
                'Jenis pohon wajib dipilih untuk monitoring survival rate.'
            );
            return;
        }
        
        // 2. Validate hidup + mati = total survey
        if (($hidup + $mati) != $jumlahSurvey) {
            $validator->errors()->add('jumlah_bibit_disurvey', 
                "Jumlah yang di-survey ({$jumlahSurvey}) harus sama dengan " .
                "hidup ({$hidup}) + mati ({$mati}) = " . ($hidup + $mati)
            );
            return;
        }
        
        // 3. Get total planted
        $totalPlanted = $this->getTotalPlantedTrees($plotId, $jenisPohonId);
        
        if ($totalPlanted === 0) {
            $validator->errors()->add('jenis_pohon_id', 
                'Belum ada data penanaman untuk jenis pohon ini. ' .
                'Tambahkan data penanaman terlebih dahulu sebelum melakukan monitoring.'
            );
            return;
        }
        
        // 4. Validate based on sampling method
        if ($metodeSampling === 'full_census') {
            // Full census: must count ALL trees
            if ($jumlahSurvey != $totalPlanted) {
                $validator->errors()->add('jumlah_bibit_disurvey', 
                    "Metode 'Sensus Lengkap' harus menghitung SEMUA pohon ({$totalPlanted} batang). " .
                    "Anda hanya menghitung {$jumlahSurvey} pohon."
                );
            }
        } else {
            // Sampling: validate sample size
            $this->validateSampleSize($validator, $jumlahSurvey, $totalPlanted, $metodeSampling);
        }
    }

    /**
     * Validate growth monitoring (simplified - NO target check)
     */
    private function validateGrowthMonitoring($validator, $plotId)
    {
        $jenisPohonId = $this->input('jenis_pohon_id');
        $metodeSampling = $this->input('metode_sampling');
        $jumlahSampel = (int)$this->input('jumlah_sampel_diukur', 0);
        
        // 1. Validate jenis_pohon_id
        if (!$jenisPohonId) {
            $validator->errors()->add('jenis_pohon_id', 
                'Jenis pohon wajib dipilih untuk monitoring pertumbuhan.'
            );
            return;
        }
        
        // 2. Get total planted trees
        $totalPlanted = $this->getTotalPlantedTrees($plotId, $jenisPohonId);
        
        if ($totalPlanted === 0) {
            $validator->errors()->add('jenis_pohon_id', 
                'Belum ada data penanaman untuk jenis pohon ini. ' .
                'Tambahkan data penanaman terlebih dahulu sebelum melakukan monitoring.'
            );
            return;
        }
        
        // 3. Validate sample size based on method
        if ($metodeSampling === 'full_census') {
            if ($jumlahSampel != $totalPlanted) {
                $validator->errors()->add('jumlah_sampel_diukur', 
                    "Metode 'Sensus Lengkap' harus mengukur SEMUA pohon ({$totalPlanted} batang). " .
                    "Anda hanya mengukur {$jumlahSampel} pohon."
                );
            }
        } else {
            // Sampling validation
            $this->validateGrowthSampleSize($validator, $jumlahSampel, $totalPlanted, $metodeSampling);
        }
    }

    /**
     * Validate sample size for sampling methods
     */
    private function validateSampleSize($validator, int $sampleSize, int $totalPopulation, string $method)
    {
        // Statistical minimum sample size
        $minSampleSize = max(30, ceil($totalPopulation * 0.05)); // Min 30 or 5%
        $maxSampleSize = ceil($totalPopulation * 0.5); // Max 50%
        
        if ($sampleSize >= $totalPopulation) {
            $validator->errors()->add('metode_sampling', 
                "Anda menghitung {$sampleSize} dari {$totalPopulation} pohon. " .
                "Gunakan 'Sensus Lengkap' jika menghitung semua pohon."
            );
            return;
        }
        
        if ($sampleSize < $minSampleSize) {
            $validator->errors()->add('jumlah_bibit_disurvey', 
                "⚠️ Sample terlalu kecil! Minimal " . round($minSampleSize) . " pohon " .
                "untuk hasil yang representatif (5% dari {$totalPopulation} pohon).\n\n" .
                "📊 Rekomendasi: Tambah jumlah pohon yang di-survey untuk akurasi lebih baik."
            );
        }
        
        if ($sampleSize > $maxSampleSize) {
            $samplingPercentage = round(($sampleSize / $totalPopulation) * 100);
            $validator->errors()->add('jumlah_bibit_disurvey', 
                "💡 INFO: Anda sudah menghitung {$samplingPercentage}% pohon.\n" .
                "Pertimbangkan gunakan 'Sensus Lengkap' untuk akurasi maksimal."
            );
        }
    }

    /**
     * Validate sample size for growth monitoring
     */
    private function validateGrowthSampleSize($validator, int $sampleSize, int $totalPopulation, string $method)
    {
        // For growth monitoring, smaller sample is acceptable
        $minSampleSize = max(20, ceil($totalPopulation * 0.03)); // Min 20 or 3%
        $maxSampleSize = ceil($totalPopulation * 0.4); // Max 40%
        
        if ($sampleSize >= $totalPopulation) {
            $validator->errors()->add('metode_sampling', 
                "Anda mengukur {$sampleSize} dari {$totalPopulation} pohon. " .
                "Gunakan 'Sensus Lengkap' jika mengukur semua pohon."
            );
            return;
        }
        
        if ($sampleSize < $minSampleSize) {
            $validator->errors()->add('jumlah_sampel_diukur', 
                "⚠️ Sampel terlalu kecil! Minimal " . round($minSampleSize) . " pohon " .
                "untuk hasil yang representatif (3% dari {$totalPopulation} pohon).\n\n" .
                "📊 Rekomendasi untuk monitoring pertumbuhan:\n" .
                "• Plot Sampling: 10-20% dari total pohon\n" .
                "• Systematic: Setiap pohon ke-N\n" .
                "• Random: Minimal 20-30 pohon"
            );
        }
        
        if ($sampleSize > $maxSampleSize) {
            $samplingPercentage = round(($sampleSize / $totalPopulation) * 100);
            $validator->errors()->add('jumlah_sampel_diukur', 
                "💡 INFO: Anda sudah mengukur {$samplingPercentage}% pohon.\n" .
                "Pertimbangkan gunakan 'Sensus Lengkap' untuk data paling lengkap."
            );
        }
    }

    /**
     * Get total planted trees for a specific jenis_pohon in a plot
     */
    private function getTotalPlantedTrees($plotId, $jenisPohonId): int
    {
        try {
            $total = DB::table('data_pohon_realisasi as dpr')
                ->join('pohon as p', 'dpr.pohon_id', '=', 'p.pohon_id')
                ->where('dpr.plot_id', $plotId)
                ->where('p.jenis_pohon_id', $jenisPohonId)
                ->sum('dpr.jumlah_batang');

            return (int)($total ?? 0);

        } catch (\Exception $e) {
            Log::error('Error getting total planted trees', [
                'plot_id' => $plotId,
                'jenis_pohon_id' => $jenisPohonId,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Determine what type of validation should be applied to the field
     */
    private function determineValidationType(FieldDefinition $fieldDef, Plot $plot): string
    {
        $fieldKey = $fieldDef->field_key;
        $satuan = strtolower($fieldDef->satuan ?? '');

        // Area-related fields
        if ($this->isAreaRelatedField($fieldDef)) {
            return 'target'; // Compare with plot area
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
            // Find indicator for this field
            $indicatorField = FieldDefinition::where('jenis_aktivitas_id', $fieldDef->jenis_aktivitas_id)
                ->whereNotNull('indicator_key')
                ->first();

            if (!$indicatorField) {
                return null;
            }

            $indikator = IndikatorProgresReklamasi::where('nama', $indicatorField->indicator_key)->first();
            
            if (!$indikator) {
                return null;
            }

            // Get target value
            $target = TargetProgresReklamasi::where('plot_id', $plotId)
                ->where('indikator_id', $indikator->indikator_id)
                ->first();

            return $target ? (float) $target->target_value : null;

        } catch (\Exception $e) {
            Log::error('Error getting target value', [
                'plot_id' => $plotId,
                'field_key' => $fieldDef->field_key,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Get cumulative value for a specific field
     */
    private function getCumulativeFieldValue($plotId, $fieldKey, $activityId): float
    {
        try {
            // Use provided activityId or get from input
            $currentActivityId = $activityId ?? $this->input('jenis_aktivitas_id');
            
            $fieldDefinition = FieldDefinition::where('jenis_aktivitas_id', $currentActivityId)
                ->where('field_key', $fieldKey)
                ->first();

            if (!$fieldDefinition) {
                Log::warning('Field definition not found', [
                    'field_key' => $fieldKey,
                    'activity_id' => $currentActivityId
                ]);
                return 0;
            }

            $cumulativeValue = DB::table('progres')
                ->join('progres_field_values', 'progres.progres_id', '=', 'progres_field_values.progres_id')
                ->where('progres.plot_id', $plotId)
                ->where('progres.jenis_aktivitas_id', $currentActivityId)
                ->where('progres_field_values.field_definition_id', $fieldDefinition->field_definition_id)
                ->sum(DB::raw('CAST(progres_field_values.field_value AS DECIMAL(10,2))'));

            return (float) $cumulativeValue;
        } catch (\Exception $e) {
            Log::error('Error calculating cumulative field value', [
                'plot_id' => $plotId,
                'field_key' => $fieldKey,
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

            case 'dynamic_select':
                $rules[] = 'integer'; 
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
                    $rules[] = 'max:10240'; // 10MB default
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
        // Define common area-related field keys
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
        
        // Check exact matches for area-related keys from config
        return in_array($fieldKey, $areaRelatedKeys);
    }

    /**
     * Check if field is volume-related (cubic meter-based)
     */
    private function isVolumeField(string $fieldKey): bool
    {
        // Define common volume-related field keys
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
        // Define common length-related field keys
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
        // Define common unit/count-related field keys
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
        ];

        // Add dynamic field attributes
        $activityId = $this->input('jenis_aktivitas_id');
        
        if ($activityId && is_numeric($activityId)) {
            try {
                $dynamicAttributes = $this->buildDynamicFieldAttributes($activityId);
                $coreAttributes = array_merge($coreAttributes, $dynamicAttributes);
            } catch (\Exception $e) {
                Log::error('Error building field attributes', [
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
            'tanggal.before_or_equal' => 'Tanggal tidak boleh mendahului dari hari ini.',
            'catatan.max' => 'Catatan maksimal 500 karakter.',
            'dokumentasi.*.image' => 'File dokumentasi harus berupa gambar.',
            'dokumentasi.*.max' => 'Ukuran gambar maksimal 10MB.',
            '*.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini.',
            
            'metode_sampling.required' => 'Metode monitoring wajib dipilih.',
            'metode_sampling.in' => 'Metode monitoring tidak valid.',
            
            'jumlah_bibit_disurvey.required' => 'Jumlah pohon yang di-survey wajib diisi.',
            'jumlah_bibit_disurvey.integer' => 'Jumlah pohon harus berupa angka bulat.',
            'jumlah_bibit_disurvey.min' => 'Jumlah pohon minimal 1.',
            
            'jumlah_bibit_hidup.required' => 'Jumlah tanaman hidup wajib diisi.',
            'jumlah_bibit_hidup.integer' => 'Jumlah tanaman hidup harus berupa angka bulat.',
            'jumlah_bibit_hidup.min' => 'Jumlah tanaman hidup tidak boleh negatif.',
            
            'jumlah_bibit_mati.required' => 'Jumlah tanaman mati wajib diisi.',
            'jumlah_bibit_mati.integer' => 'Jumlah tanaman mati harus berupa angka bulat.',
            'jumlah_bibit_mati.min' => 'Jumlah tanaman mati tidak boleh negatif.',
            
            'jumlah_sampel_diukur.required' => 'Jumlah sampel yang diukur wajib diisi.',
            'jumlah_sampel_diukur.integer' => 'Jumlah sampel harus berupa angka bulat.',
            'jumlah_sampel_diukur.min' => 'Jumlah sampel minimal 1 pohon.',
            
            'tinggi_tanaman_rata.required' => 'Rata-rata tinggi tanaman wajib diisi.',
            'tinggi_tanaman_rata.numeric' => 'Tinggi tanaman harus berupa angka.',
            'tinggi_tanaman_rata.min' => 'Tinggi tanaman tidak boleh negatif.',
            
            'diameter_tanaman_rata.required' => 'Rata-rata diameter batang wajib diisi.',
            'diameter_tanaman_rata.numeric' => 'Diameter batang harus berupa angka.',
            'diameter_tanaman_rata.min' => 'Diameter batang tidak boleh negatif.',
            
            'umur_tanaman_bulan.required' => 'Umur tanaman wajib diisi.',
            'umur_tanaman_bulan.integer' => 'Umur tanaman harus berupa angka bulat.',
            'umur_tanaman_bulan.min' => 'Umur tanaman tidak boleh negatif.',
            
            'persentase_pohon_sehat.numeric' => 'Persentase harus berupa angka.',
            'persentase_pohon_sehat.min' => 'Persentase tidak boleh negatif.',
            'persentase_pohon_sehat.max' => 'Persentase tidak boleh lebih dari 100.',
        ];
    }

    /**
     * Handle validation failure with logging
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        Log::error('Progress creation validation failed', [
            'errors' => $validator->errors()->toArray(),
            'activity_id' => $this->input('jenis_aktivitas_id'),
            'input_date' => $this->input('tanggal')
        ]);

        parent::failedValidation($validator);
    }
}