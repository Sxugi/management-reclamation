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
        Schema::create('kriteria_keberhasilan', function (Blueprint $table) {
            $table->bigIncrements('kriteria_keberhasilan_id');
            $table->foreignId('lahan_id')->constrained('lahan', 'lahan_id')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('detail_kriteria_keberhasilan', function (Blueprint $table) {
            $table->bigIncrements('detail_kriteria_keberhasilan_id');
            $table->foreignId('kriteria_keberhasilan_id')->constrained('kriteria_keberhasilan', 'kriteria_keberhasilan_id')->onDelete('cascade');
            $table->string('kategori')->nullable();
            $table->string('indikator')->nullable();
            $table->string('rencana')->nullable();
            $table->string('realisasi')->nullable();
            $table->string('hasil_evaluasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_kriteria_keberhasilan');
        Schema::dropIfExists('kriteria_keberhasilan');
    }
};