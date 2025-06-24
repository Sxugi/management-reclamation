<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LahanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlotController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/lahan', [LahanController::class, 'index'])->name('lahan.index');
    Route::patch('lahan/{lahan}/status', [LahanController::class, 'updateStatus'])->name('lahan.update-status');
    Route::resource('lahan', LahanController::class);
});

Route::middleware(['auth'])->group(function () {
    Route::get('lahan/{lahan}/dashboard', [DashboardController::class, 'dashboard'])->name('detail-lahan.dashboard');
    Route::resource('lahan.plot', PlotController::class)->shallow();
});

require __DIR__.'/auth.php';
