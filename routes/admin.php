<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JenisPohonController;
use App\Http\Controllers\Admin\KategoriAnggaranController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    
    // User Management
    Route::resource('users', UserController::class);

    // Jenis Pohon Management
    Route::resource('jenis-pohon', JenisPohonController::class)->except(['create', 'edit']);

    // Kategori Anggaran Management
    Route::resource('kategori-anggaran', KategoriAnggaranController::class)->except(['create', 'edit']);
});