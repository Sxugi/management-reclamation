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
        Schema::create('lahan', function (Blueprint $table) {
            $table->bigIncrements('lahan_id');
            $table->string('nama_lahan');
            $table->decimal('luas_lahan', 10, 2);
            $table->decimal('luas_lahan_original', 10, 2);
            $table->year('tahun_awal');
            $table->year('tahun_akhir');
            $table->unsignedBigInteger('pic_id')->nullable();
            $table->foreign('pic_id')->references('user_id')->on('users')->onDelete('restrict');
            $table->magellanPoint('location', 4326);
            $table->string('fase')->default('Lahan Baru');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lahan');
    }
};
