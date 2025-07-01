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
        Schema::create('rencana_biayas', function (Blueprint $table) {
            $table->bigIncrements('rencana_biaya_id');
            $table->foreignId('lahan_id')->constrained('lahans', 'lahan_id')->onDelete('cascade');
            $table->integer('tahun');
            $table->json('biaya_langsung');
            $table->json('biaya_tidak_langsung');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rencana_biayas');
    }
};
