<?php

namespace App\Http\Requests\Plot;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePlotRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_plot' => [
                'required',
                'string',
                'max:255',
                Rule::unique('plot', 'nama_plot')
                    ->where('lahan_id', $this->route('lahan')->lahan_id ?? $this->route('plot')->lahan_id ?? request()->input('lahan_id'))
                    ->ignore($this->route('plot')->plot_id, 'plot_id')
            ],
            'polygon' => ['required', 'json', function ($attribute, $value, $fail) {
                $data = json_decode($value, true);

                if (!is_array($data) || empty($data)) {
                    return $fail('Polygon harus berupa array dan tidak boleh kosong.');
                }

                // Handle format [ [ [lng, lat], [lng, lat], ... ] ]
                $coords = $data[0] ?? [];

                $validCoords = array_filter($coords, function ($coord) {
                    return is_array($coord)
                        && count($coord) === 2
                        && is_numeric($coord[0])
                        && is_numeric($coord[1])
                        && $coord[0] >= -180 && $coord[0] <= 180
                        && $coord[1] >= -90 && $coord[1] <= 90;
                });

                if (count($validCoords) !== count($coords)) {
                    return $fail('Semua koordinat harus berisi coordinat valid latitude (-90 sampai 90) dan longitude (-180 sampai 180).');
                }

                if (count($validCoords) < 3) {
                    return $fail('Polygon harus memiliki minimal 3 koordinat yang valid.');
                }
            }],
            'luas_area' => 'required|numeric',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nama_plot' => 'Nama Plot',
            'polygon' => 'Polygon',
            'luas_area' => 'Luas Area',
        ];
    }

    /**
     * Get the validation messages.
     */
    public function messages(): array
    {
        return [
            'nama_plot.required' => 'Nama Plot harus diisi.',
            'nama_plot.unique' => 'Nama Plot sudah digunakan dalam lahan ini. Silakan gunakan nama yang berbeda.',
            'polygon.required' => 'Polygon harus diisi.',
            'luas_area.required' => 'Luas Area harus diisi.',
            'luas_area.numeric' => 'Luas Area harus berupa angka.',
        ];
    }
}
