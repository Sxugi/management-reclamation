<?php

namespace App\Http\Requests\Lahan;

use Illuminate\Foundation\Http\FormRequest;

class CreateLahanRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'nama_lahan' => 'required|string|max:255',
            'luas_lahan' => 'required|numeric|min:0.01',
            'tahun_awal' => 'required|integer|min:' . (date('Y') - 5) . '|max:' . (date('Y') + 5),
            'tahun_akhir' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $tahunAwal = $this->input('tahun_awal');
                    $expectedTahunAkhir = $tahunAwal + 4; // 5 years period (inclusive)
                    
                    if ((int)$value !== $expectedTahunAkhir) {
                        $fail("Tahun Akhir harus tepat 4 tahun setelah Tahun Awal.");
                    }
                }
            ],
            'pic_reklamasi' => 'required|string|max:255',
            'longitude' => 'required|numeric|between:-180,180',
            'latitude' => 'required|numeric|between:-90,90',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nama_lahan' => 'Nama Lahan',
            'luas_lahan' => 'Luas Lahan',
            'tahun_awal' => 'Tahun Awal',
            'tahun_akhir' => 'Tahun Akhir',
            'pic_reklamasi' => 'PIC Reklamasi',
            'longitude' => 'Koordinat Bujur',
            'latitude' => 'Koordinat Lintang',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'longitude.required' => 'Silakan pilih lokasi lahan pada peta.',
        ];
    }
}