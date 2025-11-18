<?php

namespace App\Http\Requests\Gudang;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UpdateDataGudangRequest extends FormRequest
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
            'tanggal_masuk' => ['required', 'date', 'before_or_equal:today'],
            'jenis_barang' => ['required', 'string', 'max:255'],
            'nama_barang' => ['required', 'string', 'max:255'],
            'jumlah_barang' => ['required', 'integer', 'min:0', 'max:1000000'],
            'lokasi_penyimpanan' => ['required', 'string', 'max:255'],
            'status_barang' => [
                'required',
                'string',
                Rule::in(['Tersedia', 'Kosong', 'Rusak', 'Digunakan']),
            ],
            'catatan' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'tanggal_masuk.required' => 'Tanggal masuk barang harus diisi.',
            'tanggal_masuk.date' => 'Tanggal masuk tidak valid.',
            'tanggal_masuk.before_or_equal' => 'Tanggal masuk tidak boleh mendahului dari hari ini.',

            'jenis_barang.required' => 'Jenis barang harus diisi.',
            'jenis_barang.max' => 'Jenis barang terlalu panjang (maks 255 karakter).',

            'nama_barang.required' => 'Nama barang harus diisi.',
            'nama_barang.max' => 'Nama barang terlalu panjang (maks 255 karakter).',

            'jumlah_barang.required' => 'Jumlah barang harus diisi.',
            'jumlah_barang.integer' => 'Jumlah barang harus berupa bilangan bulat.',
            'jumlah_barang.min' => 'Jumlah barang minimal :min.',
            'jumlah_barang.max' => 'Jumlah barang melebihi batas maksimum yang diizinkan.',

            'lokasi_penyimpanan.required' => 'Lokasi penyimpanan harus diisi.',
            'lokasi_penyimpanan.max' => 'Nama lokasi terlalu panjang (maks 255 karakter).',

            'status_barang.required' => 'Status barang harus diisi.',
            'status_barang.in' => 'Status barang tidak valid.',

            'catatan.max' => 'Catatan tidak boleh lebih dari 500 karakter.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->input('status_barang') === 'Rusak') {
                $catatan = $this->input('catatan');
                if (empty($catatan) || trim($catatan) === '') {
                    $validator->errors()->add('catatan', 'Harap jelaskan alasan/rincian ketika status barang "Rusak".');
                }
            }

            $table = 'data_gudang';
            $jenis = trim((string) $this->input('jenis_barang', ''));
            $nama  = trim((string) $this->input('nama_barang', ''));
            $lokasi = trim((string) $this->input('lokasi_penyimpanan', ''));
            $tanggal = $this->input('tanggal_masuk');

            $excludeId = null;
            $routeParam = $this->route('data_gudang') ?? $this->route('gudang') ?? null;

            if ($routeParam) {
                if (is_object($routeParam)) {
                    $excludeId = $routeParam->data_gudang_id ?? $routeParam->id ?? null;
                } else {
                    $excludeId = $routeParam;
                }
            }

            if ($jenis !== '' && $nama !== '' && $lokasi !== '' && $tanggal) {
                $q = DB::table($table)
                    ->where('jenis_barang', $jenis)
                    ->where('nama_barang', $nama)
                    ->where('lokasi_penyimpanan', $lokasi)
                    ->whereDate('tanggal_masuk', $tanggal);

                if ($excludeId) {
                    $q->where('data_gudang_id', '!=', (int) $excludeId);
                }

                if ($q->exists()) {
                    $validator->errors()->add('nama_barang', "Entri untuk barang $nama di lokasi $lokasi pada tanggal $tanggal sudah ada.");
                }
            }

            if ($this->input('status_barang') === 'Kosong' && (int)$this->input('jumlah_barang', 0) > 0) {
                $validator->errors()->add('status_barang', 'Status "Kosong" hanya boleh jika jumlah barang = 0.');
            }
        });
    }
}