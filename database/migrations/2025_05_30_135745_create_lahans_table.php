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
        Schema::create('lahans', function (Blueprint $table) {
            $table->bigIncrements('lahan_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('nama_lahan');
            $table->decimal('luas_lahan', 10, 2);
            $table->year('tahun_awal');
            $table->year('tahun_akhir');
            $table->string('pic_reklamasi');
            $table->string('status')->default('Active');
            $table->timestamps();
        });
         DB::statement('ALTER TABLE lahans ADD COLUMN location GEOMETRY(Point, 4326)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lahans');
    }
};
