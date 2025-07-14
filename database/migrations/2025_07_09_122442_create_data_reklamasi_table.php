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
        Schema::create('data_reklamasi', function (Blueprint $table) {
            $table->bigIncrements('data_reklamasi_id');
            $table->foreignId('lahan_id')->constrained('lahan', 'lahan_id')->onDelete('cascade');
            $table->integer('tahun');
            $table->enum('type', ['rencana', 'rekapitulasi']);
            $table->float('area_penambangan');
            $table->float('timbuman_tanah_pengakaran');
            $table->float('timbuman_batuan_samping');
            $table->float('timbuman_komoditas_tambang');
            $table->float('timbuman_limbah_fasilitas')->nullable();
            $table->float('jalan_tambang');
            $table->float('kolam_sedimen');
            $table->float('fasilitas_pengolahan');
            $table->float('kantor_perumahan');
            $table->float('bengkel');
            $table->float('fasilitas_penunjang');
            $table->float('lahan_selesai_ditambang');
            $table->float('lahan_aktif_ditambang');
            $table->float('volume_batuan_samping');
            $table->float('penimbunan_bekas_tambang');
            $table->float('penimbunan_diluar_bekas_tambang');
            $table->float('volume_bekas_tambang');
            $table->float('volume_diluar_bekas_tambang');
            $table->float('penataan_tanah');
            $table->float('penebaran_tanah_pengakaran');
            $table->float('pengendalian_erosi');
            $table->float('kualitas_tanah')->nullable();
            $table->float('pemupukan');
            $table->float('pengadaan_bibit');
            $table->float('penanaman');
            $table->float('pemeliharaan_tanaman');
            $table->float('pencegahan_air_asam')->nullable();
            $table->float('pekerjaan_sipil');
            $table->float('stabilisasi_lereng');
            $table->float('pengamanan_lubang');
            $table->float('pemulihan_kualitas_air');
            $table->float('pemeliharaan_lubang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_reklamasi');
    }
};
