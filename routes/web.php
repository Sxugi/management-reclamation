<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LahanController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', [LahanController::class, 'index'])->name('dashboard');
    Route::resource('lahans', LahanController::class);
});

require __DIR__.'/auth.php';
