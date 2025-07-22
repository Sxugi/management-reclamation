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
        Schema::create('reklamasi_file', function (Blueprint $table) {
            $table->bigIncrements('reklamasi_file_id');
            $table->foreignId('lahan_id')->constrained('lahan', 'lahan_id')->onDelete('cascade');
            $table->enum('type', ['rencana', 'laporan']);
            $table->string('file_name');
            $table->string('file_path');
            $table->bigInteger('file_size');
            $table->string('mime_type');
            $table->year('tahun')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reklamasi_file');
    }
};
