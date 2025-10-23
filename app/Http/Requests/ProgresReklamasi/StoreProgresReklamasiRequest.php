<?php

namespace App\Http\Requests\ProgresReklamasi;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\FieldDefinition;
use App\Models\Plot;
use App\Models\IndikatorProgresReklamasi;
use App\Models\TargetProgresReklamasi;
use Illuminate\Support\Facades\Log;

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
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string|max:500',
            'dokumentasi' => 'nullable|array',
            'dokumentasi.*' => 'nullable|image|max:5120', // 5MB per image
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
            // Get plot from route parameter
            $plotId = $this->route('plot')?->plot_id ?? $this->route('plot');
            
            if (!$plotId) {
                return null;
            }
            
            $plot = $plotId instanceof Plot ? $plotId : Plot::find($plotId);
            
            if (!$plot || !$plot->luas_area) {
                return null;
            }
            
            return (float) $plot->luas_area;
            
        } catch (\Exception $e) {
            Log::error('Error getting plot area limit', [
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
            '*.max' => $plotAreaLimit 
                ? "Nilai tidak boleh lebih dari luas blok lahan ({$plotAreaLimit} ha)." 
                : 'Nilai melebihi batas maksimum yang diizinkan.',
        ];
    }

    /**
     * Handle validation failure with logging
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $plotAreaLimit = $this->getPlotAreaLimit();
        
        Log::error('Progress creation validation failed', [
            'errors' => $validator->errors()->toArray(),
            'activity_id' => $this->input('jenis_aktivitas_id'),
            'plot_area_limit' => $plotAreaLimit
        ]);

        parent::failedValidation($validator);
    }
}