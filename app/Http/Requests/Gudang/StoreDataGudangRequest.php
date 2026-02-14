<?php

namespace App\Http\Requests\Gudang;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;
use App\Models\DataGudang;

class StoreDataGudangRequest extends FormRequest
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
            'jenis_transaksi' => ['required', 'in:MASUK,KELUAR'],
            'sku' => ['nullable', 'string', 'max:50'],
            'satuan' => ['required', 'string', 'max:50'],
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
            'jenis_transaksi.required' => 'Jenis transaksi harus diisi.',
            'jenis_transaksi.in' => 'Jenis transaksi tidak valid.',
            'sku.max' => 'SKU terlalu panjang (maks 50 karakter).',
            'satuan.required' => 'Satuan harus diisi.',
            'satuan.max' => 'Satuan terlalu panjang (maks 50 karakter).',
            
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

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $jenisTransaksi = $this->input('jenis_transaksi');
            $statusBarang = $this->input('status_barang');
            $jumlahBarang = (int) $this->input('jumlah_barang', 0);
            $catatan = trim($this->input('catatan', ''));

            // Validate stock for KELUAR transaction
            if ($jenisTransaksi === 'KELUAR') {
                $this->validateStock($validator);
                $this->validateStatusKosong($validator);
            }

            // Rusak → Catatan Required
            if ($statusBarang === 'Rusak' && empty($catatan)) {
                $validator->errors()->add('catatan', 'Harap jelaskan rincian kerusakan pada catatan.');
            }

            // Digunakan → Catatan Required
            if ($statusBarang === 'Digunakan' && empty($catatan)) {
                $validator->errors()->add('catatan', 'Harap jelaskan rincian penggunaan pada catatan.');
            }

            // Kosong status validation for MASUK
            if ($statusBarang === 'Kosong' && $jenisTransaksi === 'MASUK' && $jumlahBarang > 0) {
                $validator->errors()->add('status_barang', 'Status "Kosong" tidak sesuai untuk transaksi masuk dengan jumlah barang lebih dari 0.');
            }

            // MASUK transaction must have jumlah > 0
            if ($jenisTransaksi === 'MASUK' && $jumlahBarang <= 0) {
                $validator->errors()->add('jumlah_barang', 'Transaksi masuk harus memiliki jumlah barang lebih dari 0.');
            }
        });
    }

    /*
    * Validate stock availability for KELUAR transaction
    */
    protected function validateStock(Validator $validator): void
    {
        $lahan = $this->route('lahan');
        $namaBarang = $this->input('nama_barang');
        $jumlahBarang = $this->input('jumlah_barang');
        $satuan = $this->input('satuan');

        // Calculate available stock (MASUK - KELUAR)
        $stokExisting = DataGudang::where('lahan_id', $lahan->lahan_id)
            ->where('nama_barang', $namaBarang)
            ->selectRaw("SUM(CASE WHEN jenis_transaksi = 'MASUK' THEN jumlah_barang ELSE -jumlah_barang END) as sisa")
            ->value('sisa') ?? 0;

        if ($stokExisting <= 0) {
            $validator->errors()->add(
                'jumlah_barang',
                "❌ Stok Habis! Tidak dapat melakukan transaksi keluar karena stok {$namaBarang} sudah habis."
            );
            return;
        }

        if ($jumlahBarang > $stokExisting) {
            $validator->errors()->add(
                'jumlah_barang',
                "❌ Stok Tidak Cukup! Stok {$namaBarang} yang tersedia hanya: {$stokExisting} {$satuan}"
            );
        }
    }

    /*
    * Validate "Kosong" status consistency
    */
    protected function validateStatusKosong(Validator $validator): void
    {
        $lahan = $this->route('lahan');
        $namaBarang = $this->input('nama_barang');
        $jumlahBarang = $this->input('jumlah_barang');
        $statusBarang = $this->input('status_barang');
        $satuan = $this->input('satuan');

        // Calculate stock after this transaction
        $stokExisting = DataGudang::where('lahan_id', $lahan->lahan_id)
            ->where('nama_barang', $namaBarang)
            ->selectRaw("SUM(CASE WHEN jenis_transaksi = 'MASUK' THEN jumlah_barang ELSE -jumlah_barang END) as sisa")
            ->value('sisa') ?? 0;

        $stokSetelahTransaksi = $stokExisting - $jumlahBarang;

        // If status is "Kosong" but stock remains after transaction
        if ($statusBarang === 'Kosong' && $stokSetelahTransaksi > 0) {
            $validator->errors()->add(
                'status_barang',
                "⚠️ Status \"Kosong\" tidak sesuai. Setelah transaksi ini, stok {$namaBarang} masih tersisa: {$stokSetelahTransaksi} {$satuan}. Pilih status lain atau ubah jumlah barang."
            );
        }
    }
}