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
            $table->uuid('uuid')->unique();
            $table->string('nama_plot');
            $table->decimal('luas_area', 10, 2);
            $table->magellanPolygon('polygon', 4326)->nullable();
            $table->foreignId('lahan_id')
                ->constrained('lahan', 'lahan_id')
                ->cascadeOnDelete();
            $table->timestamps();

            $table->index('lahan_id');
        });

        Schema::create('indikator', function (Blueprint $table) {
            $table->bigIncrements('indikator_id');
            $table->string('nama')->unique();
            $table->string('label');
            $table->string('satuan', 30);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('is_active');
        });

        Schema::create('kategori_aktivitas', function (Blueprint $table) {
            $table->bigIncrements('kategori_id');
            $table->string('field')->unique();
            $table->string('label');
            $table->timestamps();
        });

        Schema::create('jenis_aktivitas', function (Blueprint $table) {
            $table->bigIncrements('jenis_aktivitas_id');
            $table->foreignId('kategori_id')
                ->constrained('kategori_aktivitas', 'kategori_id')
                ->cascadeOnDelete();
            $table->string('field');
            $table->string('label');
            $table->timestamps();
            $table->unique(['kategori_id', 'field'], 'kategori_jenis_unique');
        });

        Schema::create('field_definitions', function (Blueprint $table) {
            $table->bigIncrements('field_definition_id');
            $table->foreignId('jenis_aktivitas_id')
                ->constrained('jenis_aktivitas', 'jenis_aktivitas_id')
                ->cascadeOnDelete();
            $table->string('field_key');
            $table->string('field_label');
            $table->enum('field_type', ['number', 'text', 'select', 'date', 'textarea', 'dynamic_select']);
            $table->string('satuan')->nullable();
            $table->string('indicator_key')->nullable();
            $table->timestamps();
            $table->unique(['jenis_aktivitas_id', 'field_key'], 'jenis_field_unique');
        });

        Schema::create('target', function (Blueprint $table) {
            $table->bigIncrements('target_id');
            $table->foreignId('plot_id')
                ->constrained('plot', 'plot_id')
                ->cascadeOnDelete();
            $table->foreignId('indikator_id')
                ->constrained('indikator', 'indikator_id')
                ->cascadeOnDelete();
            $table->decimal('value', 20, 2)->default(0);
            $table->timestamps();
            $table->unique(['plot_id','indikator_id'], 'target_plot_indikator_unique');
        });

        Schema::create('progres', function (Blueprint $table) {
            $table->bigIncrements('progres_id');
            $table->foreignId('plot_id')
                ->constrained('plot', 'plot_id')
                ->cascadeOnDelete();
            $table->foreignId('indikator_id')
                ->nullable()
                ->constrained('indikator', 'indikator_id')
                ->cascadeOnDelete();
            $table->foreignId('jenis_aktivitas_id')
                ->constrained('jenis_aktivitas', 'jenis_aktivitas_id')
                ->cascadeOnDelete();
            $table->date('tanggal');
            $table->decimal('value', 20, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->index(['plot_id','indikator_id','jenis_aktivitas_id','tanggal'], 'progres_lookup_idx');
        });

        Schema::create('progres_snapshots', function (Blueprint $table) {
            $table->bigIncrements('progres_snapshot_id');
            $table->foreignId('plot_id')
                ->constrained('plot', 'plot_id')
                ->cascadeOnDelete();
            $table->date('date');
            $table->decimal('percent', 5, 2);
            $table->timestamps();

            $table->unique(['plot_id', 'date'], 'plot_date_unique');
            $table->index(['plot_id', 'date']);
        });

        Schema::create('plot_progres', function (Blueprint $table) {
            $table->bigIncrements('plot_progres_id');
            $table->foreignId('plot_id')
                ->constrained('plot', 'plot_id')
                ->cascadeOnDelete();
            $table->decimal('percent', 5, 2);
            $table->timestamps();

            $table->unique('plot_id');
            $table->index('plot_id');
        });

        Schema::create('progres_field_values', function (Blueprint $table) {
            $table->bigIncrements('progres_field_value_id');
            $table->foreignId('progres_id')
                ->constrained('progres', 'progres_id')
                ->cascadeOnDelete();
            $table->foreignId('field_definition_id')
                ->constrained('field_definitions', 'field_definition_id')
                ->cascadeOnDelete();
            $table->text('field_value');
            $table->timestamps();
            
            $table->unique(['progres_id', 'field_definition_id'], 'progres_field_unique');
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->bigIncrements('log_id');
            $table->foreignId('plot_id')
                ->constrained('plot', 'plot_id')
                ->cascadeOnDelete();
            $table->string('user_name');
            $table->string('action');
            $table->string('table_name');
            $table->unsignedBigInteger('record_id');
            $table->text('description')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['plot_id','table_name','record_id']);
        });

        Schema::create('progres_dokumentasi', function (Blueprint $table) {
            $table->bigIncrements('progres_dokumentasi_id');
            $table->foreignId('progres_id')
                ->constrained('progres', 'progres_id')
                ->cascadeOnDelete();
            $table->magellanPoint('location', 4326)->nullable();
            $table->string('image_path');
            $table->timestamps();
            $table->index(['progres_id']);
        });

        Schema::create('plot_handovers', function (Blueprint $table) {
            $table->bigIncrements('plot_handover_id');
            $table->foreignId('plot_id')->constrained('plot', 'plot_id')->onDelete('cascade');
            $table->decimal('luas', 10, 2);
            $table->string('lokasi');
            $table->date('tanggal');
            $table->timestamps();
        });

        Schema::create('plot_handover_files', function (Blueprint $table) {
            $table->bigIncrements('plot_handover_file_id');
            $table->foreignId('plot_handover_id')->constrained('plot_handovers', 'plot_handover_id')->onDelete('cascade');
            $table->enum('type', ['surat', 'peta']);
            $table->string('file_path');
            $table->string('file_name');
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plot_handover_files');
        Schema::dropIfExists('plot_handovers');
        Schema::dropIfExists('progres_dokumentasi');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('progres_field_values');
        Schema::dropIfExists('progres');
        Schema::dropIfExists('target');
        Schema::dropIfExists('field_definitions');
        Schema::dropIfExists('jenis_aktivitas');
        Schema::dropIfExists('kategori_aktivitas');
        Schema::dropIfExists('indikator');
        Schema::dropIfExists('plot');
    }
};