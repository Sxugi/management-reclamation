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
        Schema::create('plots', function (Blueprint $table) {
            $table->bigIncrements('plot_id'); // Using plot_id as the primary key
            $table->string('nama_plot');
            $table->json('coordinates'); // Stores the polygon coordinates as JSON
            $table->decimal('area', 10, 2)->nullable(); // Area in square meters (or your preferred unit)
            $table->foreignId('lahan_id')->constrained('lahans', 'lahan_id')->onDelete('cascade'); // Foreign key to lahan table
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plots');
    }
};