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
        Schema::create('kategori_anggaran', function (Blueprint $table) {
            $table->bigIncrements('kategori_anggaran_id');
            $table->string('nama_kategori')->unique();
            $table->timestamps();
        });
        
        Schema::create('anggaran_reklamasi', function (Blueprint $table) {
            $table->bigIncrements('anggaran_reklamasi_id');
            $table->foreignId('lahan_id')
                  ->constrained('lahan', 'lahan_id')
                  ->onDelete('cascade');
            $table->enum('jenis_anggaran', ['actual', 'projection', 'forecast']);
            $table->foreignId('kategori_anggaran_id')
                  ->constrained('kategori_anggaran', 'kategori_anggaran_id')
                  ->onDelete('cascade');
            $table->unsignedSmallInteger('tahun');
            $table->unsignedTinyInteger('bulan');
            $table->decimal('nominal', 20, 2)->default(0);
            $table->string('quarter');
            $table->string('quarter_label');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_anggaran');
        Schema::dropIfExists('anggaran_reklamasi');
    }
};
