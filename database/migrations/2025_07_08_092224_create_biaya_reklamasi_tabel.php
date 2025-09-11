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
        Schema::create('biaya_reklamasi', function (Blueprint $table) {
            $table->bigIncrements('biaya_reklamasi_id');
            $table->foreignId('lahan_id')->constrained('lahan', 'lahan_id')->onDelete('cascade');
            $table->integer('tahun');
            $table->enum('tipe', ['rencana', 'rekapitulasi']);
            $table->enum('currency', ['IDR', 'USD'])->default('IDR');
            $table->decimal('subtotal_1', 20, 2)->default(0);
            $table->decimal('subtotal_2', 20, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('detail_biaya_reklamasi', function (Blueprint $table) {
            $table->bigIncrements('detail_biaya_reklamasi_id');
            $table->foreignId('biaya_reklamasi_id')->constrained('biaya_reklamasi', 'biaya_reklamasi_id')->onDelete('cascade');
            $table->string('kategori');
            $table->string('kegiatan');
            $table->decimal('biaya', 20, 2)->default(0);
            $table->timestamps();
        });
  
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_biaya_reklamasi');
        Schema::dropIfExists('biaya_reklamasi');
    }
};
