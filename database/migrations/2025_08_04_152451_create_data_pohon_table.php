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
        Schema::create('jenis_pohon', function (Blueprint $table) {
            $table->bigIncrements('jenis_pohon_id');
            $table->string('nama_pohon')->unique();
            $table->timestamps();
        });

        Schema::create('pohon', function (Blueprint $table) {
            $table->bigIncrements('pohon_id');
            $table->foreignId('lahan_id')->constrained('lahan', 'lahan_id')->onDelete('cascade');
            $table->foreignId('jenis_pohon_id')->constrained('jenis_pohon', 'jenis_pohon_id')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('data_pohon', function (Blueprint $table) {
            $table->bigIncrements('data_pohon_id');
            $table->foreignId('pohon_id')->constrained('pohon', 'pohon_id')->onDelete('cascade');
            $table->year('tahun');
            $table->unsignedInteger('jumlah')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_pohon');
        Schema::dropIfExists('pohon');
        Schema::dropIfExists('data_pohon');
    }
};
