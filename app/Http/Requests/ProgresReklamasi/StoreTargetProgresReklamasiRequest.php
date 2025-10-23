<?php

namespace App\Http\Requests\ProgresReklamasi;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\IndikatorProgresReklamasi;
use App\Models\TargetProgresReklamasi;
use App\Models\ProgresReklamasi;
use App\Models\Plot;
use App\Services\TargetProgresService;

class StoreTargetProgresReklamasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $validKeys = IndikatorProgresReklamasi::pluck('nama')->toArray();

        return [
            'selected'     => ['nullable', 'array'],
            'selected.*'   => ['string', 'in:' . implode(',', $validKeys)],
            'value'        => ['nullable', 'array'],
        ];
    }

    public function messages(): array
    {
        return [
            'selected.array' => 'Data indikator harus berupa array.',
            'selected.*.in'  => 'Indikator yang dipilih tidak valid.',
            'value.array'    => 'Data nilai harus berupa array.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $selected = $this->input('selected', []);
            $values   = $this->input('value', []);
            $plotArea = $this->getPlotArea();

            // No duplicate in selected
            if (count($selected) !== count(array_unique($selected))) {
                $validator->errors()->add('selected', 'Tidak boleh memilih indikator yang sama lebih dari satu kali.');
            }

            // Validate each selected indicator
            foreach ($selected as $key) {
                $label = $this->getIndicatorLabel($key);
                $unit  = $this->getIndicatorUnit($key);

                // value must be present and numeric
                $v = $values[$key] ?? null;
                if ($v === null || $v === '' || !is_numeric($v)) {
                    $validator->errors()->add("value.$key", "Nilai untuk $label harus diisi dan berupa angka.");
                    continue;
                }
                $v = (float) $v;

                // Non-negatif
                if ($v < 0) {
                    $validator->errors()->add("value.$key", "Nilai untuk $label tidak boleh negatif.");
                }

                // Maximum based on plot area
                $max = $this->getMaxValueForIndicator($key, $plotArea);
                if ($v > $max) {
                    $validator->errors()->add("value.$key", "Nilai untuk $label ($v $unit) tidak boleh lebih dari batas maksimum ($max $unit).");
                }
            }

            $plotId = $this->route('plot')?->plot_id ?? $this->route('plot');
            if (!$plotId) return;

            // Get all existing targets for this plot
            $existingTargets = TargetProgresReklamasi::where('plot_id', $plotId)->with('indikator')->get();

            // Find targets to delete (not in selected)
            $toDelete = $existingTargets->filter(function($target) use ($selected) {
                return !in_array($target->indikator->nama, $selected);
            });

            foreach ($toDelete as $target) {
                $hasProgres = TargetProgresService::hasProgres($target->plot_id, $target->indikator_id);

                if ($hasProgres) {
                    $validator->errors()->add(
                        'target', "Target {$target->indikator->label} tidak dapat dihapus karena sudah ada progres berjalan.");
                }
            }
        });
    }

    private function getPlotArea(): ?float
    {
        $plotId = $this->route('plot')?->plot_id ?? $this->route('plot');
        if (!$plotId) return null;
        $plot = $plotId instanceof Plot ? $plotId : Plot::find($plotId);
        return $plot?->luas_area ? (float) $plot->luas_area : null;
    }

    private function getMaxValueForIndicator(string $indicatorKey, ?float $plotArea): float
    {
        $ind = config('indicators.targets')[$indicatorKey] ?? null;
        if (!$ind) return 999999;
        $unit = $ind['satuan'] ?? '';

        switch ($unit) {
            case 'ha':   return $plotArea ?: 999999;
            case 'm':    return $plotArea ? ($plotArea * 10000) : 100000;
            case 'm³':   return $plotArea ? ($plotArea * 1000) : 100000;
            case 'unit': return $plotArea ? ($plotArea * 50) : 1000;
            default:     return 999999;
        }
    }

    private function getIndicatorLabel(string $key): string
    {
        $ind = config('indicators.targets')[$key] ?? null;
        return $ind['label'] ?? ucfirst(str_replace('_', ' ', $key));
    }

    private function getIndicatorUnit(string $key): string
    {
        $ind = config('indicators.targets')[$key] ?? null;
        return $ind['satuan'] ?? '';
    }
}