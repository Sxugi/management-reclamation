<?php

namespace App\Http\Requests\Lahan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLahanRequest extends FormRequest
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
            'nama_lahan' => ['required', 'string', 'max:255', Rule::unique('lahan', 'nama_lahan')->ignore($this->route('lahan'), 'lahan_id')],
            'luas_lahan' => 'required|numeric|min:0.01',
            'tahun_awal' => 'required|integer',
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
            'pic_id' => 'nullable|exists:users,user_id',
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
            'pic_id' => 'PIC Reklamasi',
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
            'nama_lahan.required' => 'Nama Lahan wajib diisi.',
            'nama_lahan.unique' => 'Nama Lahan sudah digunakan. Silakan gunakan nama lain.',
            'luas_lahan.required' => 'Luas Lahan wajib diisi.',
            'tahun_awal.required' => 'Tahun Awal wajib diisi.',
            'tahun_akhir.required' => 'Tahun Akhir wajib diisi.',
            'pic_id.exists' => 'PIC Reklamasi tidak ditemukan.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Get pic_id from request
        $picId = $this->input('pic_id');

        // Handle 'null' string to actual null
        if ($picId === 'null' || $picId === '') {
            $picId = null;
        }

        // Determine fase based on pic_id
        if ($this->has('pic_id')) {
            $fase = $picId === null ? 'Selesai' : 'Lahan Baru';
            
            $this->merge([
                'pic_id' => $picId,
                'fase'   => $fase
            ]);
        }
    }
}