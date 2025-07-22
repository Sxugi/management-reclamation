<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LahanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlotController;
use App\Http\Controllers\RencanaReklamasiController;
use App\Http\Controllers\RencanaBiayaController;
use App\Http\Controllers\RekapitulasiReklamasiController;
use App\Http\Controllers\RekapitulasiBiayaController;
use App\Http\Controllers\KriteriaKeberhasilanController;
use App\Http\Controllers\DokumentasiController;
use App\Http\Controllers\FileRencanaController;
use App\Http\Controllers\FileLaporanController;
use Illuminate\Support\Facades\Route;

// Route for profile
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

// Route for lahan management
Route::middleware(['auth'])->group(function () {
    Route::get('/lahan', [LahanController::class, 'index'])
        ->name('lahan.index');
    Route::patch('lahan/{lahan}/status', [LahanController::class, 'updateStatus'])
        ->name('lahan.update-status');
    Route::resource('lahan', LahanController::class);
});

// Route for progress management
Route::middleware(['auth'])->group(function () {
    Route::get('lahan/{lahan}/dashboard', [DashboardController::class, 'dashboard'])
        ->name('detail-lahan.dashboard');
    Route::resource('lahan.plot', PlotController::class)
        ->shallow();
});

// Route for administration
Route::middleware(['auth'])->group(function () {
    Route::resource('lahan.rencana-reklamasi', RencanaReklamasiController::class)
        ->except('show', 'destroy');
    Route::get('lahan/{lahan}/rencana-reklamasi/pdf', [RencanaReklamasiController::class, 'generatePDF'])
        ->name('lahan.rencana-reklamasi.pdf');
    Route::resource('lahan.rencana-biaya', RencanaBiayaController::class)
        ->except('show', 'destroy');
    Route::get('lahan/{lahan}/rencana-biaya/pdf', [RencanaBiayaController::class, 'generatePDF'])
        ->name('lahan.rencana-biaya.pdf');
    Route::resource('lahan.rekapitulasi-reklamasi', RekapitulasiReklamasiController::class)
        ->except('show', 'destroy');
    Route::get('lahan/{lahan}/rekapitulasi-reklamasi/pdf', [RekapitulasiReklamasiController::class, 'generatePDF'])
        ->name('lahan.rekapitulasi-reklamasi.pdf');
    Route::resource('lahan.rekapitulasi-biaya', RekapitulasiBiayaController::class)
        ->except('show', 'destroy');
    Route::get('lahan/{lahan}/rekapitulasi-biaya/pdf', [RekapitulasiBiayaController::class, 'generatePDF'])
        ->name('lahan.rekapitulasi-biaya.pdf');
    Route::prefix('lahan/{lahan}/kriteria-keberhasilan')
        ->name('lahan.kriteria-keberhasilan.')->group(function () {
            Route::get('/', [KriteriaKeberhasilanController::class, 'show'])
                ->name('show');
            Route::get('/edit', [KriteriaKeberhasilanController::class, 'edit'])
                ->name('edit');
            Route::patch('/penatagunaan', [KriteriaKeberhasilanController::class, 'updatePenatagunaan'])
                ->name('update.penatagunaan');
            Route::patch('/revegetasi', [KriteriaKeberhasilanController::class, 'updateRevegetasi'])
                ->name('update.revegetasi');
            Route::patch('/penyelesaian', [KriteriaKeberhasilanController::class, 'updatePenyelesaian'])
                ->name('update.penyelesaian');
            Route::get('/pdf', [KriteriaKeberhasilanController::class, 'generatePDF'])
                ->name('pdf');
    });
    Route::resource('lahan.dokumentasi', DokumentasiController::class);
    Route::resource('lahan.file-rencana', FileRencanaController::class)
        ->only(['index', 'store', 'destroy']);
    Route::get('lahan/{lahan}/file-rencana/preview', [FileRencanaController::class, 'preview'])
        ->name('lahan.file-rencana.preview');
    Route::resource('lahan.file-laporan', FileLaporanController::class)
        ->only(['index', 'store', 'destroy']);
    Route::get('lahan/{lahan}/file-laporan/preview', [FileLaporanController::class, 'preview'])
        ->name('lahan.file-laporan.preview');
});


require __DIR__.'/auth.php';
