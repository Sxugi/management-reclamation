<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_gudang', function (Blueprint $table) {
            $table->bigIncrements('data_gudang_id');
            $table->foreignId('lahan_id')->constrained('lahan', 'lahan_id')->onDelete('cascade');
            $table->string('sku')->nullable();
            $table->enum('jenis_transaksi', ['MASUK', 'KELUAR'])
                  ->default('MASUK');
            $table->date('tanggal_masuk');
            $table->string('jenis_barang');
            $table->string('nama_barang');
            $table->integer('jumlah_barang');
            $table->string('satuan')->default('Unit');
            $table->string('lokasi_penyimpanan');
            $table->enum('status_barang', ['Tersedia', 'Kosong', 'Rusak', 'Digunakan']);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_gudang');
    }
};
