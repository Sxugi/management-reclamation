<?php

namespace App\Http\Requests\Pohon;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePohonRequest extends FormRequest
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
        $lahan = $this->route('lahan');
        $pohon = $this->route('pohon');
        $dataPohon = $this->route('dataPohon');

        return [
            'jenis_pohon_id' => [
                'required',
                'exists:jenis_pohon,jenis_pohon_id',
                Rule::unique('pohon', 'jenis_pohon_id')
                    ->where(fn ($query) => $query->where('lahan_id', $lahan->lahan_id))
                    ->ignore($pohon?->pohon_id, 'pohon_id'),
            ],
            'tahun' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) use ($pohon, $dataPohon) {
                    $exists = \App\Models\DataPohon::where('pohon_id', $pohon->pohon_id)
                        ->where('tahun', $value)
                        ->when($dataPohon, fn ($q) => $q->where('data_pohon_id', '!=', $dataPohon->data_pohon_id))
                        ->exists();

                    if ($exists) {
                        $fail("Data untuk tahun {$value} sudah ada untuk pohon ini.");
                    }
                },
            ],
            'jumlah' => 'required|integer|min:1',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'jenis_pohon_id.required' => 'Jenis pohon wajib dipilih.',
            'jenis_pohon_id.exists' => 'Jenis pohon tidak valid.',
            'jenis_pohon_id.unique' => 'Jenis pohon ini sudah ada di lahan ini.',
            'tahun.required' => 'Tahun wajib diisi.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.min' => 'Jumlah minimal 1.',
        ];
    }
}
