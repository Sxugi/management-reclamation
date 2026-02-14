<?php

namespace App\Http\Requests\Pohon;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePohonRequest extends FormRequest
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
            'jenis_pohon_id' => 'required|exists:jenis_pohon,jenis_pohon_id',
            'tahun' => [
                'required',
                'integer',
                'min:1900',
                'max:' . (date('Y') + 1),
                function ($attribute, $value, $fail) {
                    $jenisId = request('jenis_pohon_id');
                    $lahan = request()->route('lahan');
                    
                    $pohon = \App\Models\Pohon::where('lahan_id', $lahan->lahan_id)
                        ->where('jenis_pohon_id', $jenisId)
                        ->first();

                    if ($pohon) {
                        $exists = \App\Models\DataPohonManual::where('pohon_id', $pohon->pohon_id)
                            ->where('tahun', $value)
                            ->exists();

                        if ($exists) {
                            $fail("Data stok manual untuk jenis pohon ini di tahun {$value} sudah ada.");
                        }
                    }
                }
            ],
            'jumlah_batang' => 'required|integer|min:1|max:1000000',
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
            'tahun.required' => 'Tahun wajib diisi.',
            'tahun.integer' => 'Tahun harus berupa angka.',
            'tahun.min' => 'Tahun tidak valid.',
            'tahun.max' => 'Tahun tidak valid.',
            'jumlah_batang.required' => 'Jumlah batang wajib diisi.', 
            'jumlah_batang.integer' => 'Jumlah batang harus berupa angka.',
            'jumlah_batang.min' => 'Jumlah batang minimal 1.', 
            'jumlah_batang.max' => 'Jumlah batang terlalu besar.',
        ];
    }
}
