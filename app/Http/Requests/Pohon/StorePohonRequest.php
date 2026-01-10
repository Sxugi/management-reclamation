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
                function ($attribute, $value, $fail) {
                    $jenisId = request('jenis_pohon_id');
                    $lahan = request()->route('lahan');
                    $pohon = \App\Models\Pohon::where('lahan_id', $lahan->lahan_id)
                        ->where('jenis_pohon_id', $jenisId)
                        ->first();

                    if ($pohon) {
                        $exists = \App\Models\DataPohon::where('pohon_id', $pohon->pohon_id)
                            ->where('tahun', $value)
                            ->exists();

                        if ($exists) {
                            $fail("Data pohon untuk jenis terpilih di tahun {$value} sudah ada.");
                        }
                    }
                }
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
            'jenis_pohon_id.exists' => 'Jenis pohon yang dipilih tidak valid.',
            'tahun.required' => 'Tahun wajib diisi.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.min' => 'Jumlah minimal 1.',
        ];
    }
}
