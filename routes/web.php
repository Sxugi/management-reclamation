<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LahanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlotController;
use App\Http\Controllers\TargetProgresReklamasiController;
use App\Http\Controllers\ProgresReklamasiController;
use App\Http\Controllers\AnggaranReklamasiController;
use App\Http\Controllers\PohonController;
use App\Http\Controllers\DataGudangController;
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
    // Dashboard routes
    Route::get('lahan/{lahan}/dashboard', [DashboardController::class, 'dashboard'])
        ->name('detail-lahan.dashboard');
    Route::prefix('lahan/{lahan}/dashboard')->name('dashboard.')->group(function () {
        // Consolidated dashboard data endpoint
        Route::get('/data', [DashboardController::class, 'getDashboardData'])
            ->name('data');
        Route::get('/historical', [DashboardController::class, 'getHistoricalData'])
            ->name('historical');
        Route::get('/indicators', [DashboardController::class, 'getIndicatorData'])
            ->name('indicators');
        // Individual endpoints for backward compatibility
        Route::get('/stats', [DashboardController::class, 'getStats'])
            ->name('stats');
        Route::get('/progress', [DashboardController::class, 'getProgressPerBlok'])
            ->name('progress');
        Route::get('/history', [DashboardController::class, 'getHistoricalProgress'])
            ->name('history');
        Route::get('/indicator', [DashboardController::class, 'getIndicatorProgress'])
            ->name('indicator');
        Route::get('/all-indicators', [DashboardController::class, 'getAllIndicators'])
            ->name('all-indicators');
        Route::get('/specific', [DashboardController::class, 'getSpecificIndicatorProgress'])
            ->name('specific');
        Route::get('/block-history', [DashboardController::class, 'getBlockHistorical'])
            ->name('block-history');
        Route::get('/map', [DashboardController::class, 'getMapData'])
            ->name('map');
        Route::get('/summary', [DashboardController::class, 'getProgressSummary'])
            ->name('summary');
        Route::get('/enhanced', [DashboardController::class, 'getEnhancedIndicatorProgress'])
            ->name('enhanced');
        Route::get('/blocks', [DashboardController::class, 'getEnhancedBlocksForIndicator'])
            ->name('blocks');
    });

    // Plot and related resources
    Route::resource('lahan.plot', PlotController::class)
        ->shallow();
    Route::prefix('plot')->name('plot.')->group(function () {
        Route::post('{plot}/target', [TargetProgresReklamasiController::class, 'store'])
            ->name('target.store');
        Route::resource('{plot}/progres', ProgresReklamasiController::class)
            ->parameters(['progres' => 'progres']) 
            ->except('index', 'show');
        Route::get('{plot}/activity-logs', [PlotController::class, 'getActivityLogs']);
    });

    // Anggaran Reklamasi routes
    Route::resource('lahan.anggaran', AnggaranReklamasiController::class)
        ->except('show');

    // Pohon and related resources
    Route::prefix('lahan/{lahan}/pohon')->name('lahan.pohon.')->group(function () {
        Route::resource('/', PohonController::class)
            ->parameters(['' => 'pohon'])
            ->only(['index', 'create', 'store']);

        Route::get('{pohon}/data-pohon/{dataPohon}/edit', [PohonController::class, 'edit'])
            ->name('edit');
        Route::put('{pohon}/data-pohon/{dataPohon}', [PohonController::class, 'update'])
            ->name('update');
        Route::delete('{pohon}/tahun/{tahun}', [PohonController::class, 'destroy'])
            ->name('destroy');
    });

    // Data Gudang routes
    Route::resource('lahan.gudang', DataGudangController::class)
        ->except('show');
});

// Route for administration
Route::middleware(['auth'])->group(function () {
    // Rencana and Rekapitulasi routes
    Route::resource('lahan.rencana-reklamasi', RencanaReklamasiController::class)
        ->except('show', 'destroy');
    Route::get('lahan/{lahan}/rencana-reklamasi/pdf', [RencanaReklamasiController::class, 'generatePDF'])
        ->name('lahan.rencana-reklamasi.pdf');

    // Rencana Biaya routes
    Route::resource('lahan.rencana-biaya', RencanaBiayaController::class)
        ->except('show', 'destroy');
    Route::get('lahan/{lahan}/rencana-biaya/pdf', [RencanaBiayaController::class, 'generatePDF'])
        ->name('lahan.rencana-biaya.pdf');

    // Rekapitulasi routes
    Route::resource('lahan.rekapitulasi-reklamasi', RekapitulasiReklamasiController::class)
        ->except('show', 'destroy');
    Route::get('lahan/{lahan}/rekapitulasi-reklamasi/pdf', [RekapitulasiReklamasiController::class, 'generatePDF'])
        ->name('lahan.rekapitulasi-reklamasi.pdf');

    // Rekapitulasi Biaya routes
    Route::resource('lahan.rekapitulasi-biaya', RekapitulasiBiayaController::class)
        ->except('show', 'destroy');
    Route::get('lahan/{lahan}/rekapitulasi-biaya/pdf', [RekapitulasiBiayaController::class, 'generatePDF'])
        ->name('lahan.rekapitulasi-biaya.pdf');

    // Kriteria Keberhasilan routes
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

    // Dokumentasi and File routes
    Route::resource('lahan.dokumentasi', DokumentasiController::class)
        ->except('show');
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
