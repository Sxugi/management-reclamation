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
        Schema::create('detail_reklamasi', function (Blueprint $table) {
            $table->bigIncrements('detail_reklamasi_id');
            $table->foreignId('data_reklamasi_id')->constrained('data_reklamasi', 'data_reklamasi_id')->onDelete('cascade');
            $table->string('kegiatan');
            $table->string('kategori');
            $table->string('satuan')->nullable();
            $table->decimal('volume', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_reklamasi');
    }
};
