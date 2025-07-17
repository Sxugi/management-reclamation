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

            // Penatagunaan Lahan
            $table->float('rencana_luas_ditata')->nullable();
            $table->float('realisasi_luas_ditata')->nullable();
            $table->string('evaluasi_luas_ditata')->nullable();
            $table->string('rencana_stabilitas_ditata')->nullable();
            $table->string('realisasi_stabilitas_ditata')->nullable();
            $table->string('evaluasi_stabilitas_ditata')->nullable();
            $table->float('rencana_luas_ditimbun')->nullable();
            $table->float('realisasi_luas_ditimbun')->nullable();
            $table->string('evaluasi_luas_ditimbun')->nullable();
            $table->string('rencana_stabilitas_ditimbun')->nullable();
            $table->string('realisasi_stabilitas_ditimbun')->nullable();
            $table->string('evaluasi_stabilitas_ditimbun')->nullable();
            $table->float('rencana_luas_ditebar')->nullable();
            $table->float('realisasi_luas_ditebar')->nullable();
            $table->string('evaluasi_luas_ditebar')->nullable();
            $table->float('rencana_ph_tanah')->nullable();
            $table->float('realisasi_ph_tanah')->nullable();
            $table->string('evaluasi_ph_tanah')->nullable();
            $table->string('rencana_saluran_drainase')->nullable();
            $table->string('realisasi_saluran_drainase')->nullable();
            $table->string('evaluasi_saluran_drainase')->nullable();
            $table->string('rencana_pengendalian_erosi')->nullable();
            $table->string('realisasi_pengendalian_erosi')->nullable();
            $table->string('evaluasi_pengendalian_erosi')->nullable();

            // Revegetasi
            $table->string('rencana_luas_penanaman')->nullable();
            $table->string('realisasi_luas_penanaman')->nullable();
            $table->string('evaluasi_luas_penanaman')->nullable();
            $table->string('rencana_pertumbuhan_tanaman')->nullable();
            $table->string('realisasi_pertumbuhan_tanaman')->nullable();
            $table->string('evaluasi_pertumbuhan_tanaman')->nullable();
            $table->string('rencana_pengelolaan_material')->nullable();
            $table->string('realisasi_pengelolaan_material')->nullable();
            $table->string('evaluasi_pengelolaan_material')->nullable();
            $table->string('rencana_bangunan_erosi')->nullable();
            $table->string('realisasi_bangunan_erosi')->nullable();
            $table->string('evaluasi_bangunan_erosi')->nullable();
            $table->string('rencana_kolam_sedimen')->nullable();
            $table->string('realisasi_kolam_sedimen')->nullable();
            $table->string('evaluasi_kolam_sedimen')->nullable();

            // Penyelesaian Akhir
            $table->float('rencana_penutupan_tajuk')->nullable();
            $table->float('realisasi_penutupan_tajuk')->nullable();
            $table->string('evaluasi_penutupan_tajuk')->nullable();
            $table->string('rencana_pemupukan')->nullable();
            $table->string('realisasi_pemupukan')->nullable();
            $table->string('evaluasi_pemupukan')->nullable();
            $table->string('rencana_pengendalian_hama')->nullable();
            $table->string('realisasi_pengendalian_hama')->nullable();
            $table->string('evaluasi_pengendalian_hama')->nullable();
            $table->string('rencana_penyulaman')->nullable();
            $table->string('realisasi_penyulaman')->nullable();
            $table->string('evaluasi_penyulaman')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kriteria_keberhasilan');
    }
};
