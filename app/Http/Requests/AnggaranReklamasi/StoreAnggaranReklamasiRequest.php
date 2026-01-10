<?php

namespace App\Http\Requests\AnggaranReklamasi;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\AnggaranReklamasi;
use App\Services\AnggaranReklamasiService;

class StoreAnggaranReklamasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'jenis_anggaran' => ['required', 'string', Rule::in(['actual', 'projection', 'forecast'])],
            'tahun' => ['required', 'integer', 'min:2000', 'max:2100'],
            'bulan' => ['required', 'integer', 'between:1,12'],
            'quarter' => ['required', 'string', Rule::in(['Q1', 'Q2', 'Q3', 'Q4'])],
            'nominal' => ['required', 'numeric', 'min:0'],
            'kategori_anggaran_id' => ['required', 'exists:kategori_anggaran,kategori_anggaran_id']
        ];
    }

    public function messages(): array
    {
        return [
            'jenis_anggaran.required' => 'Silakan pilih jenis anggaran.',
            'jenis_anggaran.in' => 'Jenis anggaran harus salah satu dari actual, projection, atau forecast.',
            'tahun.required' => 'Tahun anggaran harus diisi.',
            'tahun.integer' => 'Tahun harus berupa angka.',
            'tahun.min' => 'Tahun minimal 2000.',
            'tahun.max' => 'Tahun maksimal 2100.',
            'bulan.required' => 'Bulan anggaran harus diisi.',
            'bulan.integer' => 'Bulan harus berupa angka.',
            'bulan.between' => 'Bulan harus antara 1-12.',
            'quarter.required' => 'Quarter harus dipilih.',
            'quarter.in' => 'Quarter harus salah satu dari Q1, Q2, Q3, atau Q4.',
            'nominal.required' => 'Nominal anggaran harus diisi.',
            'nominal.numeric' => 'Nominal harus berupa angka.',
            'nominal.min' => 'Nominal tidak boleh negatif.',
            'kategori_anggaran_id.required' => 'Kategori anggaran wajib dipilih.',
            'kategori_anggaran_id.exists' => 'Kategori anggaran tidak valid.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $tahun = $this->tahun;
            $bulan = $this->bulan;
            $quarter = $this->quarter;
            $lahan_id = $this->route('lahan')->lahan_id;
            $jenis_anggaran = $this->jenis_anggaran;
            $kategori_anggaran_id = $this->kategori_anggaran_id;

            $service = app(AnggaranReklamasiService::class);

            if ($service->isDuplicate($quarter, $tahun, $bulan, $lahan_id, $jenis_anggaran, $kategori_anggaran_id)) {
                $validator->errors()->add('kategori_anggaran_id', "Data dengan kategori ini sudah ada dalam Quarter $quarter $tahun.");
                return;
            }

            $validation = $service->validateQuarterSequence(
                $quarter, $tahun, $bulan, $lahan_id, $jenis_anggaran, $kategori_anggaran_id
            );

            if (!$validation['valid']) {
                $validator->errors()->add('quarter', $validation['message']);
            }
        });
    }
}