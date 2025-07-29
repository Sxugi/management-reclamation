<?php

namespace App\Http\Requests\Gudang;

use Illuminate\Foundation\Http\FormRequest;

class CreateDataGudangRequest extends FormRequest
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
            'tanggal_masuk' => 'required|date',
            'jenis_barang' => 'required|string|max:255',
            'nama_barang' => 'required|string|max:255',
            'jumlah_barang' => 'required|integer|min:1',
            'lokasi_penyimpanan' => 'required|string|max:255',
            'status_barang' => 'required|string|in:Tersedia,Kosong,Rusak,Digunakan',
            'catatan' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'tanggal_masuk.required' => 'Tanggal masuk barang harus diisi.',
            'jenis_barang.required' => 'Jenis barang harus diisi.',
            'nama_barang.required' => 'Nama barang harus diisi.',
            'jumlah_barang.required' => 'Jumlah barang harus diisi.',
            'lokasi_penyimpanan.required' => 'Lokasi penyimpanan harus diisi.',
            'status_barang.required' => 'Status barang harus diisi.',
            'catatan.max' => 'Catatan tidak boleh lebih dari 500 karakter.',
        ];
    }
}
