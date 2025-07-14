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
        Schema::create('plot', function (Blueprint $table) {
            $table->bigIncrements('plot_id');
            $table->string('nama_plot');
            $table->decimal('luas_area', 10, 2);
            $table->magellanPolygon('polygon', 4326)->nullable();
            $table->foreignId('lahan_id')->constrained('lahan', 'lahan_id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plot');
    }
};