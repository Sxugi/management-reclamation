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
            $table->enum('type', ['rencana', 'rekapitulasi']);
            $table->float('penataan_tanah');
            $table->float('penebaran_tanah_pengakaran');
            $table->float('pengendalian_erosi');
            $table->float('kualitas_tanah');
            $table->float('pemupukan');
            $table->float('pengadaan_bibit');
            $table->float('penanaman');
            $table->float('pemeliharaan_tanaman');
            $table->float('pencegahan_air_asam');
            $table->float('pekerjaan_sipil');
            $table->float('stabilisasi_lereng');
            $table->float('pengamanan_lubang');
            $table->float('pemulihan_kualitas_air');
            $table->float('pemeliharaan_lubang');
            $table->float('subtotal_1');
            $table->float('mobilisasi_demobilisasi_alat');
            $table->float('perencanaan_reklamasi');
            $table->float('administrasi_pihak_ketiga');
            $table->float('supervisi');
            $table->float('subtotal_2');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biaya_reklamasi');
    }
};
