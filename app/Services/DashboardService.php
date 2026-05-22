<?php

namespace App\Services;

use App\Models\Plot;
use App\Models\Lahan;
use App\Models\PlotProgres;
use App\Models\ProgresSnapshot;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardService
{
    // Cache duration constants
    private const CACHE_HISTORICAL_LONG = 1800;
    private const CACHE_HISTORICAL_SHORT = 300;   
    private const CACHE_STATS_SHORT = 60;       
    private const CACHE_STATS_LONG = 300; 

    /**
     * Smart cache key generation based on data freshness
     */
    private static function getCacheConfig(int $lahanId, string $period, ?bool $hasRecentActivity = null): array
    {
        // Check if there's recent activity (last 24 hours) if not provided
        if ($hasRecentActivity === null) {
            $hasRecentActivity = DB::table('progres')
                ->join('plot', 'progres.plot_id', '=', 'plot.plot_id')
                ->where('plot.lahan_id', $lahanId)
                ->where('progres.created_at', '>=', Carbon::now()->subDay())
                ->exists();
        }

        // Determine cache duration based on activity and period
        if ($hasRecentActivity) {
            // Recent activity = shorter cache for real-time feel
            $duration = match($period) {
                '7days' => 60,
                '30days' => 180,
                '90days' => 300,
                '1year' => 600, 
                default => 180
            };
        } else {
            // No recent activity = longer cache for stability
            $duration = match($period) {
                '7days' => 300,     
                '30days' => 600, 
                '90days' => 900,   
                '1year' => 1800, 
                default => 600
            };
        }

        $cacheKey = "historical_progress_{$lahanId}_{$period}_" . ($hasRecentActivity ? 'active' : 'stable');
        
        return [
            'key' => $cacheKey,
            'duration' => $duration,
            'has_recent_activity' => $hasRecentActivity
        ];
    }

    /**
     * Enhanced method to check if lahan is truly new/empty
     */
    public static function isNewLahan(int $lahanId): bool
    {
        // Check if any plots have progress records
        $hasProgress = DB::table('progres')
            ->join('plot', 'progres.plot_id', '=', 'plot.plot_id')
            ->where('plot.lahan_id', $lahanId)
            ->exists();

        // Check if any plots have targets set
        $hasTargets = DB::table('target')
            ->join('plot', 'target.plot_id', '=', 'plot.plot_id')
            ->where('plot.lahan_id', $lahanId)
            ->exists();

        // Check if any plots have non-zero progress
        $hasNonZeroProgress = DB::table('plot_progres')
            ->join('plot', 'plot_progres.plot_id', '=', 'plot.plot_id')
            ->where('plot.lahan_id', $lahanId)
            ->where('plot_progres.percent', '>', 0)
            ->exists();

        return !($hasProgress || $hasTargets || $hasNonZeroProgress);
    }

    /**
     * Get comprehensive dashboard statistics for a lahan
     */
    public static function getDashboardStats(int $lahanId): array
    {
        $isNew = self::isNewLahan($lahanId);
        
        // Check for recent activity to determine cache duration
        $hasRecentActivity = DB::table('progres')
            ->join('plot', 'progres.plot_id', '=', 'plot.plot_id')
            ->where('plot.lahan_id', $lahanId)
            ->where('progres.created_at', '>=', Carbon::now()->subHours(2))
            ->exists();

        $cacheKey = "dashboard_stats_{$lahanId}" . ($hasRecentActivity ? '_active' : '_stable');
        $cacheTime = match(true) {
            $isNew => 30, 
            $hasRecentActivity => 60, 
            default => 300      
        };

        return Cache::remember($cacheKey, $cacheTime, function () use ($lahanId, $isNew, $hasRecentActivity) {
            $stats = [
                'jumlah_blok_lahan' => self::getJumlahBlokLahan($lahanId),
                'total_luas_area' => self::getTotalLuasArea($lahanId),
                'progres_hari_ini' => self::calculateDailyProgress($lahanId),
                'progres_minggu_ini' => self::calculateWeeklyProgress($lahanId),
                'aktivitas_terakhir' => self::getAktivitasTerakhir($lahanId),
                'luas_area' => self::calculateAreaStats($lahanId),
                'revegetasi' => self::getRevgetasiStats($lahanId),
                'input_resources' => self::getInputResourcesStats($lahanId),
                'maintenance' => self::getMaintenanceStats($lahanId),
                'monitoring' => self::getMonitoringStats($lahanId),
                'is_new_lahan' => $isNew,
                'has_recent_activity' => $hasRecentActivity,
                'cache_info' => [
                    'generated_at' => now()->toISOString(),
                    'cache_type' => $hasRecentActivity ? 'active' : 'stable'
                ]
            ];

            // Add helpful messages for new lahan
            if ($isNew) {
                $stats['messages'] = [
                    'status' => 'new_lahan',
                    'title' => 'Lahan Baru',
                    'description' => 'Belum ada data progres. Mulai dengan menambahkan target dan aktivitas reklamasi.',
                    'next_steps' => [
                        'Atur target indikator untuk setiap blok',
                        'Mulai mencatat aktivitas reklamasi',
                        'Upload dokumentasi progres'
                    ]
                ];
            }

            return $stats;
        });
    }

    /**
     * Clear cache for specific lahan when new data is added
     */
    public static function clearLahanCache(int $lahanId): void
    {
        $patterns = [
            "dashboard_stats_{$lahanId}_active",
            "dashboard_stats_{$lahanId}_stable",
            "historical_progress_{$lahanId}_7days_active",
            "historical_progress_{$lahanId}_7days_stable", 
            "historical_progress_{$lahanId}_30days_active",
            "historical_progress_{$lahanId}_30days_stable",
            "historical_progress_{$lahanId}_90days_active",
            "historical_progress_{$lahanId}_90days_stable",
            "historical_progress_{$lahanId}_1year_active",
            "historical_progress_{$lahanId}_1year_stable",
            "indicator_progress_{$lahanId}",
            "block_historical_{$lahanId}_*",
            "planted_trees_distribution_{$lahanId}",
        ];

        foreach ($patterns as $pattern) {
            if (strpos($pattern, '*') !== false) {
                // Clear wildcard patterns (you might need to implement tag-based cache)
                $baseKey = str_replace('*', '', $pattern);
                // Clear known variations
                for ($i = 1; $i <= 50; $i++) { // Assuming max 50 plots per lahan
                    Cache::forget($baseKey . $i . '_7days');
                    Cache::forget($baseKey . $i . '_30days');
                    Cache::forget($baseKey . $i . '_90days');
                    Cache::forget($baseKey . $i . '_1year');
                }
            } else {
                Cache::forget($pattern);
            }
        }
    }

    /**
     * Force refresh cache when new progress is added
     */
    public static function refreshCacheAfterProgressUpdate(int $lahanId): void
    {
        // Clear existing cache
        self::clearLahanCache($lahanId);
        
        // Pre-warm important caches with fresh data
        self::getDashboardStats($lahanId);
        self::getHistoricalProgress($lahanId, '7days');
        self::getHistoricalProgress($lahanId, '30days');
    }

    /**
     * Get total number of active plots for a lahan
     */
    private static function getJumlahBlokLahan(int $lahanId): int
    {
        return Plot::where('lahan_id', $lahanId)->count();
    }

    /**
     * Calculate average progress percentage across all plots (simple average like map handler)
     */
    private static function getTotalProgresReklamasi(int $lahanId): float
    {
        // Get ALL plots for this lahan with their progress (including 0% plots)
        $allPlots = DB::table('plot')
            ->leftJoin('plot_progres', 'plot.plot_id', '=', 'plot_progres.plot_id')
            ->where('plot.lahan_id', $lahanId)
            ->select(DB::raw('COALESCE(plot_progres.percent, 0) as percent'))
            ->get();

        if ($allPlots->isEmpty()) return 0;

        // Calculate simple average (same as map handler)
        $totalProgress = $allPlots->sum('percent');
        $totalBlocks = $allPlots->count();

        return $totalBlocks > 0 ? ($totalProgress / $totalBlocks) : 0;
    }

    /**
     * Get total luas area from actual plots (not lahan total)
     */
    private static function getTotalLuasArea(int $lahanId): float
    {
        $totalLuas = DB::table('plot')
            ->where('lahan_id', $lahanId)
            ->sum('luas_area');
        
        return (float)$totalLuas;
    }

    /**
     * Get number of completed plots (progress >= 100%)
     */
    private static function getAktivitasTerakhir(int $lahanId): ?string
    {
        // Aktivitas Terakhir
        $aktivitasTerakhir = DB::table('progres')
            ->join('plot', 'progres.plot_id', '=', 'plot.plot_id')
            ->where('plot.lahan_id', $lahanId)
            ->max('progres.tanggal');

        return $aktivitasTerakhir;
    }

    
    /**
     * Get field value from progres_field_values
     */
    private static function getFieldValue(int $progresId, string $fieldKey): ?string
    {
        return DB::table('progres_field_values')
            ->join('field_definitions', 'progres_field_values.field_definition_id', '=', 'field_definitions.field_definition_id')
            ->where('progres_field_values.progres_id', $progresId)
            ->where('field_definitions.field_key', $fieldKey)
            ->value('progres_field_values.field_value');
    }

    /**
     * Get sum of field values for specific field_key
     */
    private static function sumFieldValues(int $lahanId, array $jenisAktivitasFields, string $fieldKey): float
    {
        return DB::table('progres')
            ->join('plot', 'progres.plot_id', '=', 'plot.plot_id')
            ->join('jenis_aktivitas', 'progres.jenis_aktivitas_id', '=', 'jenis_aktivitas.jenis_aktivitas_id')
            ->join('progres_field_values', 'progres.progres_id', '=', 'progres_field_values.progres_id')
            ->join('field_definitions', 'progres_field_values.field_definition_id', '=', 'field_definitions.field_definition_id')
            ->where('plot.lahan_id', $lahanId)
            ->whereIn('jenis_aktivitas.field', $jenisAktivitasFields)
            ->where('field_definitions.field_key', $fieldKey)
            ->sum(DB::raw("CAST(progres_field_values.field_value AS NUMERIC)"));
    }

    /**
     * Get average of field values
     */
    private static function avgFieldValues(int $lahanId, array $jenisAktivitasFields, string $fieldKey): ?float
    {
        $result = DB::table('progres')
            ->join('plot', 'progres.plot_id', '=', 'plot.plot_id')
            ->join('jenis_aktivitas', 'progres.jenis_aktivitas_id', '=', 'jenis_aktivitas.jenis_aktivitas_id')
            ->join('progres_field_values', 'progres.progres_id', '=', 'progres_field_values.progres_id')
            ->join('field_definitions', 'progres_field_values.field_definition_id', '=', 'field_definitions.field_definition_id')
            ->where('plot.lahan_id', $lahanId)
            ->whereIn('jenis_aktivitas.field', $jenisAktivitasFields)
            ->where('field_definitions.field_key', $fieldKey)
            ->avg(DB::raw("CAST(progres_field_values.field_value AS NUMERIC)"));
        
        return $result ? (float)$result : null;
    }

    /**
     * Get revegetasi statistics
     */
    private static function getRevgetasiStats(int $lahanId): array
    {
        // Total Bibit Ditanam
        $bibitProgres = self::sumFieldValues(
            $lahanId,
            ['penanaman_pionir', 'penanaman_lokal', 'penanaman_mpts'],
            'jumlah_bibit'
        );

        // Total Bibit dari Data Pohon Manual
        $bibitManual = DB::table('data_pohon_manual')
            ->join('pohon', 'data_pohon_manual.pohon_id', '=', 'pohon.pohon_id')
            ->where('pohon.lahan_id', $lahanId)
            ->sum('data_pohon_manual.jumlah_batang');

        // Sum both sources
        $totalBibit = $bibitProgres + $bibitManual;

        // Total Berat Benih Cover Crops (SEPARATE METRIC)
        $totalBeratBenih = self::sumFieldValues(
            $lahanId,
            ['penanaman_cover_crops'],
            'berat_benih'
        );

        // Total Area Bervegetasi and Persentase Area Bervegetasi with weighting by luas_area
        $plots = DB::table('plot')
            ->leftJoin('plot_progres', 'plot.plot_id', '=', 'plot.plot_id')
            ->where('plot.lahan_id', $lahanId)
            ->select('plot.luas_area', DB::raw('COALESCE(plot_progres.percent, 0) as percent'))
            ->get();

        $totalLuasArea = 0;
        $areaBervegetasi = 0;

        foreach ($plots as $plot) {
            $luas = (float) $plot->luas_area;
            $persen = min(100, max(0, (float) $plot->percent));
            $totalLuasArea += $luas;
            $areaBervegetasi += ($luas * ($persen / 100));
        }

        $persentaseAreaBervegetasi = $totalLuasArea > 0 
            ? ($areaBervegetasi / $totalLuasArea) * 100 
            : 0;

        return [
            'total_bibit_ditanam' => (int)$totalBibit,
            'total_berat_benih_cover_crops' => round($totalBeratBenih, 2), 
            'total_area_bervegetasi' => round((float)$areaBervegetasi, 2),
            'persentase_area_bervegetasi' => round((float)$persentaseAreaBervegetasi, 1),
        ];
    }

    /**
     * Get input resources statistics
     */
    private static function getInputResourcesStats(int $lahanId): array
    {
        // Total Kompos digunakan
        $totalKompos = self::sumFieldValues(
            $lahanId,
            ['aplikasi_kompos'],
            'total_berat'
        );

        // Total Pupuk Anorganik digunakan
        $totalPupuk = self::sumFieldValues(
            $lahanId,
            ['aplikasi_pupuk_dasar', 'pemupukan'],
            'total_pupuk'
        );

        return [
            'total_kompos' => round($totalKompos, 0),
            'total_pupuk_anorganik' => round($totalPupuk, 0),
        ];
    }

    /**
     * Get maintenance statistics
     */
    private static function getMaintenanceStats(int $lahanId): array
    {
        // Total Aktivitas Pemeliharaan (count progres records)
        $totalAktivitas = DB::table('progres')
            ->join('plot', 'progres.plot_id', '=', 'plot.plot_id')
            ->join('jenis_aktivitas', 'progres.jenis_aktivitas_id', '=', 'jenis_aktivitas.jenis_aktivitas_id')
            ->where('plot.lahan_id', $lahanId)
            ->whereIn('jenis_aktivitas.field', ['penyiangan', 'pemupukan', 'pengendalian_hama', 'penyulaman'])
            ->count();

        $totalTanamanDisulam = DB::table('progres')
            ->join('plot', 'progres.plot_id', '=', 'plot.plot_id')
            ->join('jenis_aktivitas', 'progres.jenis_aktivitas_id', '=', 'jenis_aktivitas.jenis_aktivitas_id')
            ->where('plot.lahan_id', $lahanId)
            ->where('jenis_aktivitas.field', 'penyulaman')
            ->sum(DB::raw("CAST(
                (SELECT field_value FROM progres_field_values pfv
                JOIN field_definitions fd ON pfv.field_definition_id = fd.field_definition_id
                WHERE pfv.progres_id = progres.progres_id AND fd.field_key = 'jumlah_tanaman') 
                AS NUMERIC)"));

        return [
            'total_aktivitas_pemeliharaan' => (int)$totalAktivitas,
            'total_tanaman_disulam' => (int)$totalTanamanDisulam,
        ];
    }

    /**
     * Get monitoring/quality statistics
     */
    private static function getMonitoringStats(int $lahanId): array
    {
        // Calculate Survival Rate and Pertumbuhan from monitoring activities
        $rawLogs = DB::table('progres as p')
            ->join('plot as pl', 'p.plot_id', '=', 'pl.plot_id')
            ->join('jenis_aktivitas as ja', 'p.jenis_aktivitas_id', '=', 'ja.jenis_aktivitas_id')
            ->join('progres_field_values as pfv', 'p.progres_id', '=', 'pfv.progres_id')
            ->join('field_definitions as fd', 'pfv.field_definition_id', '=', 'fd.field_definition_id')
            ->where('pl.lahan_id', $lahanId)
            ->whereIn('ja.field', ['monitoring_survival_rate', 'monitoring_pertumbuhan'])
            ->select(
                'p.progres_id',
                'p.tanggal',
                'ja.field as activity_type',
                'fd.field_key',
                'pfv.field_value'
            )
            ->get();

        // Group logs by progres_id to reconstruct each monitoring snapshot
        $groupedLogs = $rawLogs->groupBy('progres_id')->map(function ($rows) {
            $first = $rows->first();
            $fields = $rows->pluck('field_value', 'field_key');
            return [
                'tanggal' => $first->tanggal,
                'type' => $first->activity_type,
                'pohon_id' => $fields['jenis_pohon_id'] ?? 'unknown',
                'fields' => $fields
            ];
        });

        // Process Survival Rate (Latest snapshot per pohon)
        $srLogs = $groupedLogs->where('type', 'monitoring_survival_rate');
        
        // Group by pohon_id and take the latest snapshot for each pohon to calculate survival rate per pohon, then average across pohons
        $srPerPohon = $srLogs->groupBy('pohon_id')->map(function ($logs) {
            $latest = $logs->sortByDesc('tanggal')->first();
            $fields = $latest['fields'];
            
            $hidup = (int)($fields['jumlah_bibit_hidup'] ?? 0);
            $mati = (int)($fields['jumlah_bibit_mati'] ?? 0);
            $total = $hidup + $mati;
            
            return $total > 0 ? ($hidup / $total) * 100 : 0;
        });

        // Process Pertumbuhan (Latest snapshot per pohon)
        $growthLogs = $groupedLogs->where('type', 'monitoring_pertumbuhan');

        $heightPerPohon = $growthLogs->groupBy('pohon_id')->map(function ($logs) {
            $latest = $logs->sortByDesc('tanggal')->first();
            return (float)($latest['fields']['tinggi_tanaman_rata'] ?? 0);
        });

        return [
            'survival_rate_rata' => $srPerPohon->isNotEmpty() ? round($srPerPohon->avg(), 1) : null,
            'tinggi_tanaman_rata' => $heightPerPohon->isNotEmpty() ? round($heightPerPohon->avg(), 0) : null,
        ];
    }

    /**
     * Calculate daily progress comparison - Shows actual daily activity
     */
    private static function calculateDailyProgress(int $lahanId): array
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // Count today's activities (for context)
        $todayProgressCount = DB::table('progres')
            ->join('plot', 'progres.plot_id', '=', 'plot.plot_id')
            ->where('plot.lahan_id', $lahanId)
            ->whereDate('progres.tanggal', $today)
            ->count();

        // Get today's snapshot (always up-to-date due to auto-update)
        $todayAvg = DB::table('progres_snapshots')
            ->join('plot', 'progres_snapshots.plot_id', '=', 'plot.plot_id')
            ->where('plot.lahan_id', $lahanId)
            ->whereDate('progres_snapshots.date', $today)
            ->avg('progres_snapshots.percent');

        // Get yesterday's snapshot for comparison
        $yesterdayAvg = DB::table('progres_snapshots')
            ->join('plot', 'progres_snapshots.plot_id', '=', 'plot.plot_id')
            ->where('plot.lahan_id', $lahanId)
            ->whereDate('progres_snapshots.date', $yesterday)
            ->avg('progres_snapshots.percent');

        // Handle edge cases
        if ($todayAvg === null && $yesterdayAvg === null) {
            // Brand new lahan - no data yet
            return [
                'delta_percent' => 0,
                'today_total' => 0,
                'yesterday_total' => 0,
                'delta_type' => 'no_data',
                'has_activity_today' => false,
                'activity_count' => 0,
                'message' => 'Belum ada data historis'
            ];
        }

        // Find comparison baseline if yesterday snapshot missing
        $comparisonDate = 'kemarin';
        if ($yesterdayAvg === null) {
            $latestSnapshot = DB::table('progres_snapshots')
                ->join('plot', 'progres_snapshots.plot_id', '=', 'plot.plot_id')
                ->where('plot.lahan_id', $lahanId)
                ->where('progres_snapshots.date', '<', $today)
                ->orderBy('progres_snapshots.date', 'desc')
                ->first();

            if ($latestSnapshot) {
                $yesterdayAvg = $latestSnapshot->percent;
                $comparisonDate = Carbon::parse($latestSnapshot->date)->diffInDays($today) === 1 
                    ? 'kemarin' 
                    : Carbon::parse($latestSnapshot->date)->format('d M');
            } else {
                // No historical data - start from 0
                $yesterdayAvg = 0;
                $comparisonDate = 'awal';
            }
        }

        // Fallback for today if snapshot doesn't exist yet
        if ($todayAvg === null) {
            $todayAvg = DB::table('plot_progres')
                ->join('plot', 'plot_progres.plot_id', '=', 'plot.plot_id')
                ->where('plot.lahan_id', $lahanId)
                ->avg('plot_progres.percent') ?? 0;
        }

        $todayAvg = (float)$todayAvg;
        $yesterdayAvg = (float)$yesterdayAvg;
        
        // Calculate the DELTA (change)
        $delta = $todayAvg - $yesterdayAvg;

        // Generate contextual message
        $message = match(true) {
            $todayProgressCount === 0 && $delta == 0 => 'Tidak ada aktivitas hari ini',
            $delta > 0 => abs(round($delta, 1)) . "% meningkat dari {$comparisonDate}",
            $delta < 0 => abs(round($delta, 1)) . "% menurun dari {$comparisonDate}",
            default => "Tidak ada perubahan dari {$comparisonDate}"
        };

        return [
            'delta_percent' => round($delta, 2), 
            'today_total' => round($todayAvg, 2),
            'yesterday_total' => round($yesterdayAvg, 2),
            'delta_type' => $delta > 0 ? 'increase' : ($delta < 0 ? 'decrease' : 'stable'),
            'has_activity_today' => $todayProgressCount > 0,
            'activity_count' => $todayProgressCount,
            'comparison_date' => $comparisonDate,
            'message' => $message
        ];
    }

    /**
     * Calculate weekly progress comparison
     */
    private static function calculateWeeklyProgress(int $lahanId): array
    {
        $thisWeekStart = Carbon::now()->startOfWeek();
        $thisWeekEnd = Carbon::now()->endOfWeek();
        $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();

        $thisWeekAvg = DB::table('progres_snapshots')
            ->join('plot', 'progres_snapshots.plot_id', '=', 'plot.plot_id')
            ->where('plot.lahan_id', $lahanId)
            ->whereBetween('date', [$thisWeekStart, $thisWeekEnd])
            ->avg('percent') ?? 0;

        $lastWeekAvg = DB::table('progres_snapshots')
            ->join('plot', 'progres_snapshots.plot_id', '=', 'plot.plot_id')
            ->where('plot.lahan_id', $lahanId)
            ->whereBetween('date', [$lastWeekStart, $lastWeekEnd])
            ->avg('percent') ?? 0;

        $delta = $thisWeekAvg - $lastWeekAvg;

        return [
            'this_week_percent' => round($thisWeekAvg, 2),
            'last_week_percent' => round($lastWeekAvg, 2),
            'delta' => round($delta, 2),
            'delta_type' => $delta >= 0 ? 'increase' : 'decrease'
        ];
    }

    /**
     * Calculate area statistics breakdown
     */
    private static function calculateAreaStats(int $lahanId): array
    {
        $stats = DB::table('plot')
            ->leftJoin('plot_progres', 'plot.plot_id', '=', 'plot_progres.plot_id')
            ->where('plot.lahan_id', $lahanId)
            ->selectRaw('
                SUM(plot.luas_area) as total_luas,
                SUM(CASE WHEN COALESCE(plot_progres.percent, 0) >= 100 THEN plot.luas_area ELSE 0 END) as luas_selesai,
                SUM(CASE WHEN COALESCE(plot_progres.percent, 0) > 0 AND COALESCE(plot_progres.percent, 0) < 100 THEN plot.luas_area ELSE 0 END) as luas_dalam_proses,
                SUM(CASE WHEN COALESCE(plot_progres.percent, 0) = 0 THEN plot.luas_area ELSE 0 END) as luas_belum_mulai
            ')
            ->first();

        return [
            'total_luas' => round((float)$stats->total_luas, 2),
            'luas_selesai' => round((float)$stats->luas_selesai, 2),
            'luas_dalam_proses' => round((float)$stats->luas_dalam_proses, 2),
            'luas_belum_mulai' => round((float)$stats->luas_belum_mulai, 2),
            'persentase_selesai' => $stats->total_luas > 0 ? round(($stats->luas_selesai / $stats->total_luas) * 100, 2) : 0
        ];
    }

    /**
     * Get progress data per block/plot
     */
    public static function getProgressPerBlok(int $lahanId): array
    {
        $rows = DB::table('plot')
            ->leftJoin('plot_progres', 'plot.plot_id', '=', 'plot_progres.plot_id')
            ->where('plot.lahan_id', $lahanId)
            ->select(
                'plot.plot_id',
                'plot.nama_plot',
                'plot.luas_area',
                DB::raw('COALESCE(plot_progres.percent, 0) as percent')
            )
            ->orderBy('plot.nama_plot')
            ->get();

        return $rows->map(function ($r) {
            $percent = is_null($r->percent) ? 0 : (float) $r->percent;
            return [
                'plot_id' => $r->plot_id,
                'nama_plot' => $r->nama_plot,
                'luas_area' => (float) $r->luas_area,
                'percent' => round($percent, 2),
                'status' => $percent >= 100 ? 'selesai' : 'dalam_proses'
            ];
        })->toArray();
    }

    /**
     * Get map data with plot polygons and progress
     */
    public static function getMapData(int $lahanId): array
    {
        try {
            // Get lahan center coordinates first
            $lahanCenter = DB::table('lahan')
                ->where('lahan_id', $lahanId)
                ->selectRaw('
                    nama_lahan,
                    ST_X(location) as longitude, 
                    ST_Y(location) as latitude,
                    ST_AsText(location) as location_text
                ')
                ->first();

            // Get plot data
            $rows = DB::table('plot')
                ->leftJoin('plot_progres', 'plot.plot_id', '=', 'plot_progres.plot_id')
                ->where('plot.lahan_id', $lahanId)
                ->selectRaw('plot.plot_id, plot.nama_plot, plot.luas_area, plot.polygon, COALESCE(plot_progres.percent, 0) as percent, ST_AsGeoJSON(plot.polygon) as geojson')
                ->get();

            $features = [];
            foreach ($rows as $r) {
                if (empty($r->geojson)) continue;
                $geometry = json_decode($r->geojson, true);
                if (!$geometry) continue;

                $features[] = [
                    'type' => 'Feature',
                    'geometry' => $geometry,
                    'properties' => [
                        'plot_id' => $r->plot_id,
                        'nama_plot' => $r->nama_plot,
                        'luas_area' => (float)$r->luas_area,
                        'progress_percent' => round((float)$r->percent, 2),
                        'status' => ((float)$r->percent >= 100) ? 'completed' : 'in_progress'
                    ]
                ];
            }

            // Extract longitude and latitude from PostGIS results
            $longitude = null;
            $latitude = null;
            $hasCoordinates = false;

            if ($lahanCenter) {
                $longitude = (float)$lahanCenter->longitude;
                $latitude = (float)$lahanCenter->latitude;
                $hasCoordinates = !is_null($longitude) && !is_null($latitude) && 
                                $longitude != 0 && $latitude != 0;
            }

            return [
                'type' => 'FeatureCollection',
                'features' => $features,
                'lahan_center' => [
                    'longitude' => $longitude,
                    'latitude' => $latitude,
                    'nama_lahan' => $lahanCenter ? $lahanCenter->nama_lahan : null,
                    'has_coordinates' => $hasCoordinates,
                    'location_text' => $lahanCenter ? $lahanCenter->location_text : null  // For debugging
                ]
            ];
        } catch (\Exception $e) {
            Log::error('DashboardService getMapData error: ' . $e->getMessage(), [
                'lahan_id' => $lahanId,
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'type' => 'FeatureCollection',
                'features' => [],
                'lahan_center' => [
                    'longitude' => null,
                    'latitude' => null,
                    'nama_lahan' => null,
                    'has_coordinates' => false
                ],
                'error' => 'Failed to load map data: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get historical progress data
     */
    public static function getHistoricalProgress(int $lahanId, string $period = '30days', string $groupBy = 'daily'): array
    {
        $cacheConfig = self::getCacheConfig($lahanId, $period);
        
        return Cache::remember($cacheConfig['key'], $cacheConfig['duration'], function () use ($lahanId, $period, $groupBy, $cacheConfig) {
            $days = match ($period) {
                '7days' => 7,
                '30days' => 30,
                '90days' => 90,
                '1year' => 365,
                default => 30,
            };

            $startDate = Carbon::now()->subDays($days - 1)->toDateString();
            $endDate = Carbon::now()->toDateString();

            $dateFormat = match ($groupBy) {
                'weekly' => "DATE_TRUNC('week', date)",
                'monthly' => "DATE_TRUNC('month', date)",
                default => "DATE(date)",
            };

            // Check if this lahan has any progress data at all
            $hasAnyProgress = DB::table('progres')
                ->join('plot', 'progres.plot_id', '=', 'plot.plot_id')
                ->where('plot.lahan_id', $lahanId)
                ->exists();

            if (!$hasAnyProgress) {
                return []; // Return empty array instead of fake data
            }

            // Query for actual snapshots
            $snapshots = DB::table('progres_snapshots')
                ->join('plot', 'progres_snapshots.plot_id', '=', 'plot.plot_id')
                ->where('plot.lahan_id', $lahanId)
                ->whereBetween('date', [$startDate, $endDate])
                ->selectRaw("{$dateFormat} as period, AVG(percent) as avg_percent")
                ->groupByRaw($dateFormat)
                ->orderBy('period')
                ->get();

            $data = $snapshots->map(function ($snapshot) {
                return [
                    'date' => $snapshot->period,
                    'avg_percent' => round((float)$snapshot->avg_percent, 2)
                ];
            })->values()->toArray();

            if ($groupBy === 'daily') {
                $todayStr = $endDate;
                
                $hasTodayInSnapshot = collect($data)->contains('date', $todayStr);

                if (!$hasTodayInSnapshot) {
                    $currentRealtimeAvg = DB::table('plot_progres')
                        ->join('plot', 'plot_progres.plot_id', '=', 'plot.plot_id')
                        ->where('plot.lahan_id', $lahanId)
                        ->avg('plot_progres.percent');
                    
                    if ($currentRealtimeAvg !== null) {
                        $data[] = [
                            'date' => $todayStr,
                            'avg_percent' => round((float)$currentRealtimeAvg, 2)
                        ];
                    }
                }
            }

            return $data;
        });
    }

    /**
     * Get indicator progress data (only indicators with actual data)
     */
    public static function getIndicatorProgress(int $lahanId): array
    {
        $cacheKey = "indicator_progress_{$lahanId}";

        return Cache::remember($cacheKey, 300, function () use ($lahanId) {
            $indicatorsWithData = DB::table('progres')
                ->join('plot', 'progres.plot_id', '=', 'plot.plot_id')
                ->join('indikator', 'progres.indikator_id', '=', 'indikator.indikator_id')
                ->where('plot.lahan_id', $lahanId)
                ->where('indikator.is_active', true)
                ->selectRaw('
                    indikator.indikator_id,
                    indikator.nama as nama_indikator,
                    indikator.label,
                    indikator.satuan,
                    COUNT(progres.progres_id) as total_records,
                    SUM(progres.value) as total_value,
                    AVG(progres.value) as avg_value,
                    MAX(progres.value) as max_value,
                    SUM(plot.luas_area) as total_area
                ')
                ->groupBy('indikator.indikator_id', 'indikator.nama', 'indikator.label', 'indikator.satuan')
                ->orderBy('indikator.indikator_id')
                ->get();

            if ($indicatorsWithData->isEmpty()) {
                return [];
            }

            $indicatorProgress = [];

            foreach ($indicatorsWithData as $indicator) {
                $targetValue = DB::table('target')
                    ->join('plot', 'target.plot_id', '=', 'plot.plot_id')
                    ->where('plot.lahan_id', $lahanId)
                    ->where('target.indikator_id', $indicator->indikator_id)
                    ->sum('target.value');

                $percent = 0;
                if ($targetValue > 0) {
                    $percent = min(100, ($indicator->total_value / $targetValue) * 100);
                } else {
                    $plotsProgress = DB::table('plot_progres')
                        ->join('plot', 'plot_progres.plot_id', '=', 'plot.plot_id')
                        ->where('plot.lahan_id', $lahanId)
                        ->avg('plot_progres.percent') ?? 0;
                    
                    $percent = min(100, $plotsProgress);
                }

                $completedArea = ($indicator->total_area * $percent) / 100;

                $indicatorProgress[] = [
                    'indikator_id' => $indicator->indikator_id,
                    'nama' => $indicator->nama_indikator,
                    'label' => $indicator->label,
                    'percent' => round($percent, 2),
                    'total_records' => (int)$indicator->total_records,
                    'total_value' => round((float)$indicator->total_value, 2),
                    'avg_value' => round((float)$indicator->avg_value, 2),
                    'max_value' => round((float)$indicator->max_value, 2),
                    'total_area' => round((float)$indicator->total_area, 2),
                    'completed_area' => round($completedArea, 2),
                    'unit' => $indicator->satuan,
                    'target_value' => round((float)$targetValue, 2),
                    'has_target' => $targetValue > 0
                ];
            }

            return $indicatorProgress;
        });
    }

    /**
     * Get all available indicators for dropdown
     */
    public static function getAllIndicators(): array
    {
        return DB::table('indikator')
            ->where('is_active', true)
            ->select('indikator_id', 'nama', 'label', 'satuan')
            ->orderBy('indikator_id')
            ->get()
            ->toArray();
    }

    /**
     * Get specific indicator progress over time
     */
    public static function getSpecificIndicatorProgress(int $lahanId, int $indicatorId, string $period = '30days'): array
    {
        $days = match ($period) {
            '7days' => 7,
            '30days' => 30,
            '90days' => 90,
            '1year' => 365,
            default => 30,
        };

        $startDate = Carbon::now()->subDays($days - 1)->toDateString();
        $endDate = Carbon::now()->toDateString();

        // Get indicator info
        $indicator = DB::table('indikator')
            ->where('indikator_id', $indicatorId)
            ->where('is_active', true)
            ->first();

        if (!$indicator) {
            return ['error' => 'Indicator not found'];
        }

        // Get progress data
        $progressData = DB::table('progres')
            ->join('plot', 'progres.plot_id', '=', 'plot.plot_id')
            ->where('plot.lahan_id', $lahanId)
            ->where('progres.indikator_id', $indicatorId)
            ->whereBetween('progres.tanggal', [$startDate, $endDate])
            ->selectRaw('
                DATE(progres.tanggal) as date,
                SUM(progres.value) as daily_value,
                COUNT(*) as record_count
            ')
            ->groupByRaw('DATE(progres.tanggal)')
            ->orderBy('date')
            ->get();

        if ($progressData->isEmpty()) {
            return [
                'indicator' => $indicator,
                'data' => [],
                'message' => 'Tidak ada data progres untuk indikator ini dalam periode yang dipilih'
            ];
        }

        // Get target value
        $targetValue = DB::table('target')
            ->join('plot', 'target.plot_id', '=', 'plot.plot_id')
            ->where('plot.lahan_id', $lahanId)
            ->where('target.indikator_id', $indicatorId)
            ->sum('target.value');

        // Calculate cumulative progress
        $cumulativeValue = 0;
        $chartData = [];

        foreach ($progressData as $daily) {
            $cumulativeValue += $daily->daily_value;
            
            $percentage = 0;
            if ($targetValue > 0) {
                $percentage = min(100, ($cumulativeValue / $targetValue) * 100);
            }

            $chartData[] = [
                'date' => $daily->date,
                'daily_value' => round((float)$daily->daily_value, 2),
                'cumulative_value' => round($cumulativeValue, 2),
                'percentage' => round($percentage, 2),
                'record_count' => (int)$daily->record_count
            ];
        }

        return [
            'indicator' => [
                'indikator_id' => $indicator->indikator_id,
                'nama' => $indicator->nama,
                'label' => $indicator->label,
                'satuan' => $indicator->satuan
            ],
            'target_value' => round((float)$targetValue, 2),
            'has_target' => $targetValue > 0,
            'data' => $chartData,
            'summary' => [
                'total_records' => count($progressData),
                'total_value' => round($cumulativeValue, 2),
                'final_percentage' => $targetValue > 0 ? round(min(100, ($cumulativeValue / $targetValue) * 100), 2) : 0
            ]
        ];
    }

    /**
     * Get block historical data
     */
    public static function getBlockHistorical(int $plotId, string $period = '30days'): array
    {
        // Check if there's recent activity in this plot to determine caching strategy
        $hasRecentActivity = DB::table('progres')
            ->where('plot_id', $plotId)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->exists();

        // Cache key includes activity status to allow more frequent updates for active plots
        $cacheKey = "block_historical_{$plotId}_{$period}";
        if ($hasRecentActivity) {
            $cacheKey .= '_active';
        }
        
        $cacheTime = $hasRecentActivity ? 120 : 600;

        // For active plots, we want fresher data, so we cache for a shorter time. For inactive plots, we can cache longer since data won't change often.
        return Cache::remember($cacheKey, $cacheTime, function () use ($plotId, $period) {
            $days = match ($period) {
                '7days' => 7,
                '30days' => 30,
                '90days' => 90,
                '1year' => 365,
                default => 30,
            };

            $startDate = now()->subDays($days - 1)->toDateString();
            $endDate = now()->toDateString();

            // Get the LAST value BEFORE this period starts
            $previousPercent = DB::table('progres_snapshots')
                ->where('plot_id', $plotId)
                ->where('date', '<', $startDate)
                ->selectRaw('MAX(percent) as max_percent')
                ->value('max_percent');
            
            $lastKnownPercent = $previousPercent ? round((float)$previousPercent, 2) : 0.0;

            // Get snapshots within the period
            $rows = DB::table('progres_snapshots')
                ->where('plot_id', $plotId)
                ->whereBetween('date', [$startDate, $endDate])
                ->selectRaw('DATE(date) as date, MAX(percent) as max_percent')
                ->groupByRaw('DATE(date)')
                ->orderBy('date')
                ->get();

            $dataMap = $rows->keyBy('date')
                ->map(fn($r) => round((float)$r->max_percent, 2))
                ->toArray();

            // Fill complete date range
            $result = [];

            for ($i = $days - 1; $i >= 0; $i--) {
                $currentDate = now()->subDays($i)->format('Y-m-d');
                
                if (isset($dataMap[$currentDate])) {
                    $lastKnownPercent = max($dataMap[$currentDate], $lastKnownPercent);
                }
                
                $result[] = [
                    'date' => $currentDate,
                    'percent' => $lastKnownPercent
                ];
            }

            return $result;
        });
    }

    /**
     * Get progress summary for map overlay
     */
    public static function getProgressSummary(int $lahanId): array
    {
        $summary = DB::table('plot')
            ->leftJoin('plot_progres', 'plot.plot_id', '=', 'plot_progres.plot_id')
            ->where('plot.lahan_id', $lahanId)
            ->selectRaw('
                COUNT(*) as total_plots,
                AVG(COALESCE(plot_progres.percent, 0)) as avg_progress,
                COUNT(CASE WHEN COALESCE(plot_progres.percent, 0) >= 100 THEN 1 END) as completed_plots,
                COUNT(CASE WHEN COALESCE(plot_progres.percent, 0) > 0 AND COALESCE(plot_progres.percent, 0) < 100 THEN 1 END) as in_progress_plots,
                COUNT(CASE WHEN COALESCE(plot_progres.percent, 0) = 0 THEN 1 END) as not_started_plots
            ')
            ->first();

        return [
            'total_plots' => (int)$summary->total_plots,
            'avg_progress' => round((float)$summary->avg_progress, 2),
            'completed_plots' => (int)$summary->completed_plots,
            'in_progress_plots' => (int)$summary->in_progress_plots,
            'not_started_plots' => (int)$summary->not_started_plots,
        ];
    }

    /**
     * Generate synthetic historical data when no snapshots available
     */
    private static function generateSyntheticHistoricalData(int $lahanId, int $days, string $groupBy): array
    {
        // Only generate if there's actual current progress
        $currentAvg = DB::table('plot_progres')
            ->join('plot', 'plot_progres.plot_id', '=', 'plot.plot_id')
            ->where('plot.lahan_id', $lahanId)
            ->avg('plot_progres.percent') ?? 0;

        // If no current progress, don't generate fake history
        if ($currentAvg <= 0) {
            return [];
        }

        // Only generate very minimal recent history if current progress exists
        $today = Carbon::now()->format('Y-m-d');
        
        return [
            [
                'date' => $today,
                'avg_percent' => round($currentAvg, 2)
            ]
        ];
    }

    /**
     * Get enhanced indicator progress with percentage-based calculation and smart weighting
     */
    public static function getEnhancedIndicatorProgress(int $lahanId, int $indicatorId, string $period = '30days', ?int $plotId = null, string $weightMethod = 'target_weighted'): array
    {
        $days = match ($period) {
            '7days' => 7,
            '30days' => 30,
            '90days' => 90,
            '1year' => 365,
            default => 30,
        };

        $startDate = Carbon::now()->subDays($days - 1)->toDateString();
        $endDate = Carbon::now()->toDateString();

        // Get indicator info
        $indicator = DB::table('indikator')
            ->where('indikator_id', $indicatorId)
            ->where('is_active', true)
            ->first();

        if (!$indicator) {
            return [
                'error' => 'Indikator tidak ditemukan',
                'data' => [],
                'blocks' => []
            ];
        }

        if ($plotId) {
            // Return specific block data
            return self::getSpecificBlockPercentageData($plotId, $indicatorId, $period, $startDate, $endDate, $indicator);
        } else {
            // Return weighted overall data
            return self::getWeightedPercentageOverallData($lahanId, $indicatorId, $period, $startDate, $endDate, $indicator, $weightMethod);
        }
    }

    /**
     * Get weighted percentage-based overall data across all blocks
     */
    private static function getWeightedPercentageOverallData(int $lahanId, int $indicatorId, string $period, string $startDate, string $endDate, $indicator, string $weightMethod): array
    {
        // Get blocks with their targets and current progress
        $blocksWithData = DB::table('plot')
            ->leftJoin('target', function($join) use ($indicatorId) {
                $join->on('plot.plot_id', '=', 'target.plot_id')
                     ->where('target.indikator_id', '=', $indicatorId);
            })
            ->leftJoin(DB::raw('(SELECT plot_id, SUM(value) as total_progress FROM progres WHERE indikator_id = ' . $indicatorId . ' GROUP BY plot_id) as progress_summary'), 
                'plot.plot_id', '=', 'progress_summary.plot_id')
            ->where('plot.lahan_id', $lahanId)
            ->whereNotNull('target.value')
            ->where('target.value', '>', 0)
            ->select(
                'plot.plot_id',
                'plot.nama_plot',
                'plot.luas_area',
                'target.value as target_value',
                DB::raw('COALESCE(progress_summary.total_progress, 0) as current_progress')
            )
            ->get();

        if ($blocksWithData->isEmpty()) {
            return [
                'indicator' => [
                    'indikator_id' => $indicator->indikator_id,
                    'nama' => $indicator->nama,
                    'label' => $indicator->label,
                    'satuan' => $indicator->satuan
                ],
                'view_type' => 'weighted_percentage_overall',
                'weight_method' => $weightMethod,
                'data' => [],
                'blocks' => [],
                'summary' => ['message' => 'Tidak ada blok dengan target untuk indikator ini']
            ];
        }

        // Calculate individual block percentages
        $blockPercentages = $blocksWithData->map(function($block) {
            $percentage = min(100, ($block->current_progress / $block->target_value) * 100);
            return (object)[
                'plot_id' => $block->plot_id,
                'nama_plot' => $block->nama_plot,
                'luas_area' => $block->luas_area,
                'target_value' => $block->target_value,
                'current_progress' => $block->current_progress,
                'percentage' => round($percentage, 2),
                'status' => self::getProgressStatus($percentage)
            ];
        });

        // Get daily progress data for time series
        $plotIds = $blocksWithData->pluck('plot_id')->toArray();
        $dailyProgress = DB::table('progres')
            ->whereIn('plot_id', $plotIds)
            ->where('indikator_id', $indicatorId)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->selectRaw('
                DATE(tanggal) as date,
                plot_id,
                SUM(value) as daily_value
            ')
            ->groupBy(['date', 'plot_id'])
            ->orderBy('date')
            ->get();

        // Calculate cumulative progress and weighted percentages over time
        $chartData = self::calculateWeightedProgressTimeSeries(
            $dailyProgress, 
            $blocksWithData, 
            $weightMethod, 
            $startDate, 
            $endDate
        );

        // Calculate summary statistics
        $summaryStats = self::calculateSummaryStatistics($blockPercentages, $weightMethod);

        return [
            'indicator' => [
                'indikator_id' => $indicator->indikator_id,
                'nama' => $indicator->nama,
                'label' => $indicator->label,
                'satuan' => $indicator->satuan
            ],
            'view_type' => 'weighted_percentage_overall',
            'weight_method' => $weightMethod,
            'data' => $chartData,
            'blocks' => $blockPercentages->toArray(),
            'period' => $period,
            'summary' => $summaryStats
        ];
    }

    /**
     * Calculate weighted progress time series
     */
    private static function calculateWeightedProgressTimeSeries($dailyProgress, $blocksWithData, string $weightMethod, string $startDate, string $endDate): array
    {
        // Group daily progress by date
        $progressByDate = $dailyProgress->groupBy('date');
        
        // Initialize cumulative progress tracking for each block
        $blockCumulativeProgress = [];
        foreach ($blocksWithData as $block) {
            $blockCumulativeProgress[$block->plot_id] = 0;
        }

        // Generate all dates in period
        $dateRange = [];
        $currentDate = Carbon::parse($startDate);
        $endDateCarbon = Carbon::parse($endDate);
        
        while ($currentDate->lte($endDateCarbon)) {
            $dateRange[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        $chartData = [];
        
        foreach ($dateRange as $date) {
            // Update cumulative progress for this date
            if (isset($progressByDate[$date])) {
                foreach ($progressByDate[$date] as $dayProgress) {
                    $blockCumulativeProgress[$dayProgress->plot_id] += $dayProgress->daily_value;
                }
            }

            // Calculate weighted percentage for this date
            $blockPercentagesForDate = [];
            $totalWeight = 0;
            $weightedSum = 0;
            $activeBlocks = 0;
            $totalAchievement = 0;
            $totalTarget = 0;

            foreach ($blocksWithData as $block) {
                $cumulativeValue = $blockCumulativeProgress[$block->plot_id];
                $percentage = min(100, ($cumulativeValue / $block->target_value) * 100);
                
                // Calculate weight based on method
                $weight = match ($weightMethod) {
                    'target_weighted' => $block->target_value,
                    'area_weighted' => $block->luas_area,
                    'equal_weight' => 1,
                    default => $block->target_value
                };

                $blockPercentagesForDate[] = [
                    'plot_id' => $block->plot_id,
                    'percentage' => $percentage,
                    'weight' => $weight,
                    'cumulative_value' => $cumulativeValue
                ];

                $totalWeight += $weight;
                $weightedSum += ($percentage * $weight);
                $totalAchievement += $cumulativeValue;
                $totalTarget += $block->target_value;
                
                if ($cumulativeValue > 0) {
                    $activeBlocks++;
                }
            }

            $weightedPercentage = $totalWeight > 0 ? ($weightedSum / $totalWeight) : 0;

            $chartData[] = [
                'date' => $date,
                'weighted_percentage' => round($weightedPercentage, 2),
                'active_blocks' => $activeBlocks,
                'total_blocks' => count($blocksWithData),
                'total_achievement' => round($totalAchievement, 2),
                'total_target' => round($totalTarget, 2),
                'overall_percentage' => $totalTarget > 0 ? round(($totalAchievement / $totalTarget) * 100, 2) : 0,
                'block_details' => $blockPercentagesForDate
            ];
        }

        return $chartData;
    }

    /**
     * Get specific block percentage-based data
     */
    private static function getSpecificBlockPercentageData(int $plotId, int $indicatorId, string $period, string $startDate, string $endDate, $indicator): array
    {
        // Get block info with target
        $block = DB::table('plot')
            ->leftJoin('target', function($join) use ($indicatorId) {
                $join->on('plot.plot_id', '=', 'target.plot_id')
                     ->where('target.indikator_id', '=', $indicatorId);
            })
            ->where('plot.plot_id', $plotId)
            ->select('plot.plot_id', 'plot.nama_plot', 'plot.luas_area', 'target.value as target_value')
            ->first();

        if (!$block) {
            return [
                'error' => 'Blok tidak ditemukan',
                'data' => []
            ];
        }

        $blockTarget = (float)($block->target_value ?? 0);

        // Get daily progress data for this block
        $dailyProgress = DB::table('progres')
            ->where('plot_id', $plotId)
            ->where('indikator_id', $indicatorId)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->selectRaw('
                DATE(tanggal) as date,
                SUM(value) as daily_value,
                COUNT(*) as record_count
            ')
            ->groupByRaw('DATE(tanggal)')
            ->orderBy('date')
            ->get();

        // Calculate cumulative progress and percentages
        $cumulativeValue = 0;
        $chartData = [];

        // Generate complete date range
        $currentDate = Carbon::parse($startDate);
        $endDateCarbon = Carbon::parse($endDate);
        $progressByDate = $dailyProgress->keyBy('date');

        while ($currentDate->lte($endDateCarbon)) {
            $dateStr = $currentDate->format('Y-m-d');
            
            // Add daily progress if exists
            if (isset($progressByDate[$dateStr])) {
                $cumulativeValue += $progressByDate[$dateStr]->daily_value;
            }
            
            // Calculate percentage
            $percentage = $blockTarget > 0 ? min(100, ($cumulativeValue / $blockTarget) * 100) : 0;
            
            $chartData[] = [
                'date' => $dateStr,
                'daily_value' => isset($progressByDate[$dateStr]) ? round((float)$progressByDate[$dateStr]->daily_value, 2) : 0,
                'cumulative_value' => round($cumulativeValue, 2),
                'percentage' => round($percentage, 2),
                'record_count' => isset($progressByDate[$dateStr]) ? (int)$progressByDate[$dateStr]->record_count : 0,
                'status' => self::getProgressStatus($percentage)
            ];
            
            $currentDate->addDay();
        }

        return [
            'indicator' => [
                'indikator_id' => $indicator->indikator_id,
                'nama' => $indicator->nama,
                'label' => $indicator->label,
                'satuan' => $indicator->satuan
            ],
            'view_type' => 'specific_block_percentage',
            'block' => [
                'plot_id' => $block->plot_id,
                'nama_plot' => $block->nama_plot,
                'luas_area' => (float)$block->luas_area,
                'target_value' => $blockTarget
            ],
            'data' => $chartData,
            'period' => $period,
            'summary' => [
                'total_achievement' => round($cumulativeValue, 2),
                'target_value' => $blockTarget,
                'final_percentage' => $blockTarget > 0 ? round(min(100, ($cumulativeValue / $blockTarget) * 100), 2) : 0,
                'target_achieved' => $blockTarget > 0 && $cumulativeValue >= $blockTarget,
                'has_target' => $blockTarget > 0,
                'status' => self::getProgressStatus($blockTarget > 0 ? min(100, ($cumulativeValue / $blockTarget) * 100) : 0)
            ]
        ];
    }

    /**
     * Calculate summary statistics for blocks
     */
    private static function calculateSummaryStatistics($blockPercentages, string $weightMethod): array
    {
        $totalBlocks = $blockPercentages->count();
        $completedBlocks = $blockPercentages->where('percentage', '>=', 100)->count();
        $inProgressBlocks = $blockPercentages->where('percentage', '>', 0)->where('percentage', '<', 100)->count();
        $notStartedBlocks = $blockPercentages->where('percentage', '=', 0)->count();
        
        // Calculate distribution by status
        $statusDistribution = [
            'completed' => $completedBlocks,
            'nearly_done' => $blockPercentages->whereBetween('percentage', [75, 99.99])->count(),
            'halfway' => $blockPercentages->whereBetween('percentage', [50, 74.99])->count(),
            'started' => $blockPercentages->whereBetween('percentage', [0.01, 49.99])->count(),
            'not_started' => $notStartedBlocks
        ];

        // Calculate weighted average
        $totalWeight = 0;
        $weightedSum = 0;
        
        foreach ($blockPercentages as $block) {
            $weight = match ($weightMethod) {
                'target_weighted' => $block->target_value,
                'area_weighted' => $block->luas_area,
                'equal_weight' => 1,
                default => $block->target_value
            };
            
            $totalWeight += $weight;
            $weightedSum += ($block->percentage * $weight);
        }
        
        $weightedAverage = $totalWeight > 0 ? ($weightedSum / $totalWeight) : 0;
        
        return [
            'total_blocks' => $totalBlocks,
            'completed_blocks' => $completedBlocks,
            'in_progress_blocks' => $inProgressBlocks,
            'not_started_blocks' => $notStartedBlocks,
            'completion_rate' => $totalBlocks > 0 ? round(($completedBlocks / $totalBlocks) * 100, 2) : 0,
            'weighted_average_percentage' => round($weightedAverage, 2),
            'equal_weight_average' => round($blockPercentages->avg('percentage'), 2),
            'weight_method' => $weightMethod,
            'status_distribution' => $statusDistribution,
            'total_achievement' => round($blockPercentages->sum('current_progress'), 2),
            'total_target' => round($blockPercentages->sum('target_value'), 2)
        ];
    }

    /**
     * Get progress status with color
     */
    private static function getProgressStatus(float $percentage): array
    {
        if ($percentage >= 100) return ['status' => 'completed', 'color' => '#10b981', 'label' => 'Selesai'];
        if ($percentage >= 75) return ['status' => 'nearly_done', 'color' => '#3b82f6', 'label' => 'Hampir Selesai'];
        if ($percentage >= 50) return ['status' => 'halfway', 'color' => '#f59e0b', 'label' => 'Separuh Jalan'];
        if ($percentage >= 25) return ['status' => 'started', 'color' => '#f97316', 'label' => 'Dalam Progress'];
        if ($percentage > 0) return ['status' => 'minimal', 'color' => '#ef4444', 'label' => 'Baru Dimulai'];
        return ['status' => 'not_started', 'color' => '#6b7280', 'label' => 'Belum Dimulai'];
    }

    /**
     * Get available blocks for enhanced indicator dropdown
     */
    public static function getEnhancedBlocksForIndicator(int $lahanId, int $indicatorId): array
    {
        return DB::table('plot')
            ->leftJoin('target', function($join) use ($indicatorId) {
                $join->on('plot.plot_id', '=', 'target.plot_id')
                     ->where('target.indikator_id', '=', $indicatorId);
            })
            ->leftJoin(DB::raw('(SELECT plot_id, SUM(value) as total_progress, COUNT(*) as record_count FROM progres WHERE indikator_id = ' . $indicatorId . ' GROUP BY plot_id) as progress_summary'), 
                'plot.plot_id', '=', 'progress_summary.plot_id')
            ->where('plot.lahan_id', $lahanId)
            ->selectRaw('
                plot.plot_id,
                plot.nama_plot,
                plot.luas_area,
                COALESCE(target.value, 0) as target_value,
                COALESCE(progress_summary.total_progress, 0) as current_progress,
                COALESCE(progress_summary.record_count, 0) as progress_records
            ')
            ->orderBy('plot.nama_plot')
            ->get()
            ->map(function($block) {
                $percentage = $block->target_value > 0 ? 
                    min(100, ($block->current_progress / $block->target_value) * 100) : 0;
                
                return [
                    'plot_id' => $block->plot_id,
                    'nama_plot' => $block->nama_plot,
                    'luas_area' => (float)$block->luas_area,
                    'target_value' => (float)$block->target_value,
                    'current_progress' => (float)$block->current_progress,
                    'percentage' => round($percentage, 2),
                    'has_data' => $block->progress_records > 0,
                    'has_target' => $block->target_value > 0,
                    'status' => self::getProgressStatus($percentage)
                ];
            })
            ->toArray();
    }

    /**
     * Get distribution of planted trees by species and category, combining data from progress logs and manual inputs, with caching for performance
     */
    public static function getPlantedTreesDistribution(int $lahanId): array
    {
        $cacheKey = "planted_trees_distribution_{$lahanId}";
        $cacheTime = 600;

        return Cache::remember($cacheKey, $cacheTime, function () use ($lahanId) {
            
            // Get data from two sources: progres logs and manual entries
            $progresPohon = DB::table('progres')
                ->join('plot', 'progres.plot_id', '=', 'plot.plot_id')
                ->join('jenis_aktivitas', 'progres.jenis_aktivitas_id', '=', 'jenis_aktivitas.jenis_aktivitas_id')
                ->join('progres_field_values as pfv_jenis', 'progres.progres_id', '=', 'pfv_jenis.progres_id')
                ->join('field_definitions as fd_jenis', function($join) {
                    $join->on('pfv_jenis.field_definition_id', '=', 'fd_jenis.field_definition_id')
                         ->where('fd_jenis.field_key', '=', 'jenis_pohon_id');
                })
                ->join('jenis_pohon', DB::raw('CAST(pfv_jenis.field_value AS BIGINT)'), '=', 'jenis_pohon.jenis_pohon_id')
                ->leftJoin('progres_field_values as pfv_jumlah', function($join) {
                    $join->on('progres.progres_id', '=', 'pfv_jumlah.progres_id')
                         ->join('field_definitions as fd_jumlah', function($join) {
                             $join->on('pfv_jumlah.field_definition_id', '=', 'fd_jumlah.field_definition_id')
                                  ->where('fd_jumlah.field_key', 'jumlah_bibit'); 
                         });
                })
                ->where('plot.lahan_id', $lahanId)
                ->whereIn('jenis_aktivitas.field', ['penanaman_pionir', 'penanaman_lokal', 'penanaman_mpts']) 
                ->select(
                    'jenis_pohon.jenis_pohon_id',
                    'jenis_pohon.nama_pohon',
                    'jenis_pohon.kategori',
                    'pfv_jumlah.field_value as quantity'
                )
                ->get();

            // Data from manual entries
            $manualData = DB::table('data_pohon_manual')
                ->join('pohon', 'data_pohon_manual.pohon_id', '=', 'pohon.pohon_id')
                ->join('jenis_pohon', 'pohon.jenis_pohon_id', '=', 'jenis_pohon.jenis_pohon_id')
                ->where('pohon.lahan_id', $lahanId)
                ->select(
                    'jenis_pohon.jenis_pohon_id',
                    'jenis_pohon.nama_pohon',
                    'jenis_pohon.kategori',
                    'data_pohon_manual.jumlah_batang as quantity'
                )
                ->get();

            // Aggregation
            $categoryData = [];
            $speciesData = [];
            $totalTrees = 0;

            $processRow = function($row) use (&$categoryData, &$speciesData, &$totalTrees) {
                $qty = (float) $row->quantity;
                if ($qty <= 0) return;

                // Init Category
                if (!isset($categoryData[$row->kategori])) {
                    $categoryData[$row->kategori] = [
                        'kategori' => $row->kategori,
                        'total_trees' => 0,
                        'species_count' => 0,
                        'unit' => 'batang'
                    ];
                }

                // Init Species
                $speciesKey = "{$row->kategori}_{$row->jenis_pohon_id}";
                if (!isset($speciesData[$speciesKey])) {
                    $speciesData[$speciesKey] = [
                        'jenis_pohon_id' => $row->jenis_pohon_id,
                        'kategori' => $row->kategori,
                        'nama' => $row->nama_pohon,
                        'total_trees' => 0,
                        'unit' => 'batang'
                    ];
                    $categoryData[$row->kategori]['species_count']++;
                }

                // Accumulate
                $categoryData[$row->kategori]['total_trees'] += $qty;
                $speciesData[$speciesKey]['total_trees'] += $qty;
                $totalTrees += $qty;
            };

            // Process both data sources
            foreach ($progresPohon as $row) $processRow($row);
            foreach ($manualData as $row)   $processRow($row);

            // Format final arrays
            $byCategory = array_values($categoryData);
            $bySpecies = array_values($speciesData);

            usort($byCategory, function($a, $b) {
                $order = ['PIONIR' => 1, 'LOKAL' => 2, 'MPTS' => 3, 'COVER_CROP' => 4];
                return ($order[$a['kategori']] ?? 99) <=> ($order[$b['kategori']] ?? 99);
            });

            return [
                'by_category' => $byCategory,
                'by_species' => $bySpecies,
                'summary' => [
                    'total_trees' => $totalTrees,
                    'total_species' => count($bySpecies),
                    'total_categories' => count($byCategory)
                ]
            ];
        });
    }

    /**
     * Clear planted trees distribution cache when new planting data is added
     */
    public static function clearPlantedTreesCache(int $lahanId): void
    {
        Cache::forget("planted_trees_distribution_{$lahanId}");
    }
}
