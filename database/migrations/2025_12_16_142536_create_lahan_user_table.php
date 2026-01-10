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
        Schema::create('lahan_user', function (Blueprint $table) {
            $table->bigIncrements('lahan_user_id');
            
            // Foreign keys
            $table->foreignId('lahan_id')
                ->constrained('lahan', 'lahan_id')
                ->onDelete('cascade');
            
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');
            
            // Role in this lahan
            $table->enum('role', ['owner', 'editor', 'viewer'])
                ->default('viewer')
                ->comment('owner:  full control, editor: can edit, viewer: read-only');
            
            $table->timestamps();
            
            // Prevent duplicate assignments
            $table->unique(['lahan_id', 'user_id']);
            
            // Index for performance
            $table->index('user_id');
            $table->index('lahan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lahan_user');
    }
};
