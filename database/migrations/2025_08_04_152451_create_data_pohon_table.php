<?php
// database/migrations/2025_08_04_152451_create_data_pohon_tables.php

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
            $table->string('nama_pohon', 100)->unique();
            $table->string('kategori', 50)->default('LOKAL');
            $table->timestamps();

            $table->index('kategori');
        });

        Schema::create('pohon', function (Blueprint $table) {
            $table->bigIncrements('pohon_id');
            $table->foreignId('lahan_id')
                  ->constrained('lahan', 'lahan_id')
                  ->onDelete('cascade');
            $table->foreignId('jenis_pohon_id')
                  ->constrained('jenis_pohon', 'jenis_pohon_id')
                  ->onDelete('cascade');
            $table->timestamps();

            // Unique: 1 jenis per lahan
            $table->unique(['lahan_id', 'jenis_pohon_id']);
        });

        Schema::create('data_pohon_realisasi', function (Blueprint $table) {
            $table->bigIncrements('data_pohon_realisasi_id');
            
            $table->foreignId('pohon_id')
                  ->constrained('pohon', 'pohon_id')
                  ->onDelete('cascade');
            
            $table->foreignId('plot_id')
                  ->constrained('plot', 'plot_id')
                  ->onDelete('cascade');
            
            $table->year('tahun');
            $table->integer('jumlah_batang')->default(0);
            
            $table->timestamps();

            // Unique: 1 record per pohon-plot-tahun
            $table->unique(['pohon_id', 'plot_id', 'tahun']);

            // Indexes
            $table->index(['pohon_id', 'tahun']);
            $table->index(['plot_id', 'tahun']);
        });

        Schema::create('data_pohon_manual', function (Blueprint $table) {
            $table->bigIncrements('data_pohon_manual_id');
            
            $table->foreignId('pohon_id')
                  ->constrained('pohon', 'pohon_id')
                  ->onDelete('cascade');
            
            $table->year('tahun');
            $table->integer('jumlah_batang')->default(0);
            
            $table->timestamps();

            // Unique: 1 manual record per pohon-tahun
            $table->unique(['pohon_id', 'tahun']);

            // Indexes
            $table->index(['pohon_id', 'tahun']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_pohon_manual');
        Schema::dropIfExists('data_pohon_realisasi');
        Schema::dropIfExists('pohon');
        Schema::dropIfExists('jenis_pohon');
    }
};