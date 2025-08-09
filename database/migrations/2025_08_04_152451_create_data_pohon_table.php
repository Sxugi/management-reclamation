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
        Schema::create('pohon', function (Blueprint $table) {
            $table->bigIncrements('pohon_id');
            $table->foreignId('lahan_id')->constrained('lahan', 'lahan_id')->onDelete('cascade');
            $table->string('jenis_pohon', 100);
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
        Schema::dropIfExists('data_pohon');
    }
};
