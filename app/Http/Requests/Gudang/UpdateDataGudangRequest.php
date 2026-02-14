<?php

namespace App\Http\Requests\Gudang;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;
use App\Models\DataGudang;

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
                Rule::in(['Tersedia', 'Rusak', 'Digunakan', 'Kosong']),
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

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $gudang = $this->route('gudang');
            $jenisTransaksi = $this->input('jenis_transaksi');
            $jenisTransaksiLama = $gudang->jenis_transaksi;
            $statusBarang = $this->input('status_barang');
            $jumlahBarang = (int) $this->input('jumlah_barang', 0);
            $catatan = trim($this->input('catatan', ''));

            // Rusak → Catatan Required
            if ($statusBarang === 'Rusak' && empty($catatan)) {
                $validator->errors()->add('catatan', 'Harap jelaskan rincian kerusakan pada catatan.');
            }

            // Digunakan → Catatan Required
            if ($statusBarang === 'Digunakan' && empty($catatan)) {
                $validator->errors()->add('catatan', 'Harap jelaskan rincian penggunaan pada catatan.');
            }

            // Prevent changing transaction type if it affects other transactions
            if ($jenisTransaksi !== $jenisTransaksiLama) {
                $this->validateTransactionTypeChange($validator);
            }

            // Validate stock for KELUAR transaction
            if ($jenisTransaksi === 'KELUAR') {
                $this->validateStockKeluar($validator);
                $this->validateStatusKosong($validator);
            }

            // Validate stock for MASUK transaction (prevent negative stock when reducing)
            if ($jenisTransaksi === 'MASUK') {
                $this->validateStockMasuk($validator);
            }

            // MASUK transaction must have jumlah > 0
            if ($jenisTransaksi === 'MASUK' && $jumlahBarang <= 0) {
                $validator->errors()->add('jumlah_barang', 'Transaksi masuk harus memiliki jumlah barang lebih dari 0.');
            }

            // Validate nama_barang change
            if ($this->input('nama_barang') !== $gudang->nama_barang) {
                $this->validateNamaBarangChange($validator);
            }
        });
    }

    /**
     * Validate changing transaction type (MASUK ↔ KELUAR)
     */
    protected function validateTransactionTypeChange($validator): void
    {
        $lahan = $this->route('lahan');
        $gudang = $this->route('gudang');
        $namaBarang = $gudang->nama_barang; // Use original nama_barang
        $jenisTransaksiBaru = $this->input('jenis_transaksi');
        $jumlahBarangBaru = $this->input('jumlah_barang');
        $satuan = $this->input('satuan');

        // Calculate stock excluding current transaction
        $stokTanpaTransaksiIni = DataGudang::where('lahan_id', $lahan->lahan_id)
            ->where('nama_barang', $namaBarang)
            ->where('data_gudang_id', '!=', $gudang->data_gudang_id)
            ->selectRaw("SUM(CASE WHEN jenis_transaksi = 'MASUK' THEN jumlah_barang ELSE -jumlah_barang END) as sisa")
            ->value('sisa') ?? 0;

        // If changing from MASUK to KELUAR, check if stock is sufficient
        if ($gudang->jenis_transaksi === 'MASUK' && $jenisTransaksiBaru === 'KELUAR') {
            // Removing MASUK and adding KELUAR: need stock >= (jumlah_lama + jumlah_baru)
            $totalDiperlukan = $gudang->jumlah_barang + $jumlahBarangBaru;
            
            if ($stokTanpaTransaksiIni < $totalDiperlukan) {
                $validator->errors()->add(
                    'jenis_transaksi',
                    "❌ Tidak Bisa Mengubah! Mengubah dari MASUK ke KELUAR memerlukan stok {$totalDiperlukan} {$satuan}, tetapi hanya tersedia {$stokTanpaTransaksiIni} {$satuan}."
                );
            }
        }

        // If changing from KELUAR to MASUK, check if removing KELUAR causes negative stock
        if ($gudang->jenis_transaksi === 'KELUAR' && $jenisTransaksiBaru === 'MASUK') {
            // Check if other KELUAR transactions exceed available MASUK
            $totalKeluar = DataGudang::where('lahan_id', $lahan->lahan_id)
                ->where('nama_barang', $namaBarang)
                ->where('data_gudang_id', '!=', $gudang->data_gudang_id)
                ->where('jenis_transaksi', 'KELUAR')
                ->sum('jumlah_barang') ?? 0;

            $totalMasuk = DataGudang::where('lahan_id', $lahan->lahan_id)
                ->where('nama_barang', $namaBarang)
                ->where('data_gudang_id', '!=', $gudang->data_gudang_id)
                ->where('jenis_transaksi', 'MASUK')
                ->sum('jumlah_barang') ?? 0;

            if ($totalKeluar > $totalMasuk) {
                $validator->errors()->add(
                    'jenis_transaksi',
                    "❌ Tidak Bisa Mengubah! Mengubah dari KELUAR ke MASUK akan menyebabkan stok negatif. Total KELUAR lainnya: {$totalKeluar} {$satuan}, Total MASUK lainnya: {$totalMasuk} {$satuan}."
                );
            }
        }
    }

    /**
     * Validate stock for KELUAR transaction
     */
    protected function validateStockKeluar($validator): void
    {
        $lahan = $this->route('lahan');
        $gudang = $this->route('gudang');
        $namaBarangBaru = $this->input('nama_barang');
        $namaBarangLama = $gudang->nama_barang;
        $jumlahBarang = $this->input('jumlah_barang');
        $satuan = $this->input('satuan');

        // Use the appropriate nama_barang based on whether it changed
        $namaBarangForCheck = $namaBarangBaru;

        // Stock excluding current transaction
        $stokExisting = DataGudang::where('lahan_id', $lahan->lahan_id)
            ->where('nama_barang', $namaBarangForCheck)
            ->where('data_gudang_id', '!=', $gudang->data_gudang_id)
            ->selectRaw("SUM(CASE WHEN jenis_transaksi = 'MASUK' THEN jumlah_barang ELSE -jumlah_barang END) as sisa")
            ->value('sisa') ?? 0;

        if ($stokExisting <= 0) {
            $validator->errors()->add(
                'jumlah_barang',
                "❌ Stok Habis! Tidak dapat melakukan transaksi keluar karena stok {$namaBarangForCheck} sudah habis."
            );
            return;
        }

        if ($jumlahBarang > $stokExisting) {
            $validator->errors()->add(
                'jumlah_barang',
                "❌ Stok Tidak Cukup! Stok {$namaBarangForCheck} yang tersedia (selain transaksi ini) hanya: {$stokExisting} {$satuan}"
            );
        }
    }

    /**
     * Validate MASUK update doesn't cause negative stock
     */
    protected function validateStockMasuk($validator): void
    {
        $lahan = $this->route('lahan');
        $gudang = $this->route('gudang');
        $namaBarangBaru = $this->input('nama_barang');
        $namaBarangLama = $gudang->nama_barang;
        $jumlahBarangBaru = $this->input('jumlah_barang');
        $jumlahBarangLama = $gudang->jumlah_barang;
        $satuan = $this->input('satuan');

        // Use the appropriate nama_barang based on whether it changed
        $namaBarangForCheck = $namaBarangBaru;

        // Calculate difference (reduction)
        $selisih = $jumlahBarangLama - $jumlahBarangBaru;

        // Only validate if user REDUCES the amount
        if ($selisih > 0) {
            // Calculate stock excluding this transaction
            $stokTanpaTransaksiIni = DataGudang::where('lahan_id', $lahan->lahan_id)
                ->where('nama_barang', $namaBarangForCheck)
                ->where('data_gudang_id', '!=', $gudang->data_gudang_id)
                ->selectRaw("SUM(CASE WHEN jenis_transaksi = 'MASUK' THEN jumlah_barang ELSE -jumlah_barang END) as sisa")
                ->value('sisa') ?? 0;

            // If reducing MASUK causes negative stock, reject
            if ($selisih > $stokTanpaTransaksiIni) {
                $validator->errors()->add(
                    'jumlah_barang',
                    "❌ Tidak Bisa Mengurangi! Jumlah masuk tidak bisa dikurangi karena stok sudah terpakai. Stok tersedia (selain transaksi ini): {$stokTanpaTransaksiIni} {$satuan}. Anda mencoba mengurangi: {$selisih} {$satuan}."
                );
            }
        }
    }

    /**
     * Validate nama_barang change
     */
    protected function validateNamaBarangChange($validator): void
    {
        $lahan = $this->route('lahan');
        $gudang = $this->route('gudang');
        $namaBarangLama = $gudang->nama_barang;
        $jenisTransaksi = $this->input('jenis_transaksi');
        $jumlahBarang = $gudang->jumlah_barang;
        $satuan = $this->input('satuan');

        // Check if changing nama_barang will cause negative stock for old item
        if ($jenisTransaksi === 'MASUK') {
            // Removing this MASUK from old item
            $stokBarangLamaTanpaIni = DataGudang::where('lahan_id', $lahan->lahan_id)
                ->where('nama_barang', $namaBarangLama)
                ->where('data_gudang_id', '!=', $gudang->data_gudang_id)
                ->selectRaw("SUM(CASE WHEN jenis_transaksi = 'MASUK' THEN jumlah_barang ELSE -jumlah_barang END) as sisa")
                ->value('sisa') ?? 0;

            if ($jumlahBarang > $stokBarangLamaTanpaIni) {
                $validator->errors()->add(
                    'nama_barang',
                    "❌ Tidak Bisa Mengubah Nama Barang! Mengubah nama barang dari '{$namaBarangLama}' akan menyebabkan stok negatif. Stok '{$namaBarangLama}' yang tersedia (selain transaksi ini): {$stokBarangLamaTanpaIni} {$satuan}, tetapi transaksi ini mengurangi: {$jumlahBarang} {$satuan}."
                );
            }
        }

        if ($jenisTransaksi === 'KELUAR') {
            // Adding this KELUAR back to old item (as positive)
            // This is generally safe, but we warn the user
            $validator->errors()->add(
                'nama_barang',
                "⚠️ Perhatian! Mengubah nama barang pada transaksi KELUAR akan mempengaruhi perhitungan stok '{$namaBarangLama}'."
            );
        }
    }

    /**
     * Validate "Kosong" status for KELUAR transaction
     */
    protected function validateStatusKosong(Validator $validator): void
    {
        $lahan = $this->route('lahan');
        $gudang = $this->route('gudang');
        $namaBarang = $this->input('nama_barang');
        $jumlahBarang = $this->input('jumlah_barang');
        $statusBarang = $this->input('status_barang');
        $satuan = $this->input('satuan');

        // Calculate stock excluding current transaction
        $stokTanpaTransaksiIni = DataGudang::where('lahan_id', $lahan->lahan_id)
            ->where('nama_barang', $namaBarang)
            ->where('data_gudang_id', '!=', $gudang->data_gudang_id)
            ->selectRaw("SUM(CASE WHEN jenis_transaksi = 'MASUK' THEN jumlah_barang ELSE -jumlah_barang END) as sisa")
            ->value('sisa') ?? 0;

        $stokSetelahTransaksi = $stokTanpaTransaksiIni - $jumlahBarang;

        // if status is "Kosong" but stock remains after transaction
        if ($statusBarang === 'Kosong' && $stokSetelahTransaksi > 0) {
            $validator->errors()->add(
                'status_barang',
                "⚠️ Status \"Kosong\" tidak sesuai. Setelah transaksi ini, stok {$namaBarang} masih tersisa: {$stokSetelahTransaksi} {$satuan}. Pilih status lain atau ubah jumlah barang."
            );
        }
    }
}