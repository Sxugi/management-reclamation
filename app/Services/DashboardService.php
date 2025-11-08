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
    private static function getCacheConfig(int $lahanId, string $period, bool $hasRecentActivity = null): array
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
                'total_progres_reklamasi' => self::getTotalProgresReklamasi($lahanId),
                'progres_hari_ini' => self::calculateDailyProgress($lahanId),
                'progres_minggu_ini' => self::calculateWeeklyProgress($lahanId),
                'jumlah_blok_selesai' => self::getJumlahBlokSelesai($lahanId),
                'luas_area' => self::calculateAreaStats($lahanId),
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
            "block_historical_{$lahanId}_*"
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
     * Get number of completed plots (progress >= 100%)
     */
    private static function getJumlahBlokSelesai(int $lahanId): int
    {
        return DB::table('plot_progres')
            ->join('plot', 'plot_progres.plot_id', '=', 'plot.plot_id')
            ->where('plot.lahan_id', $lahanId)
            ->where('plot_progres.percent', '>=', 100)
            ->count();
    }

    /**
     * Calculate daily progress comparison - Shows actual daily activity
     */
    private static function calculateDailyProgress(int $lahanId): array
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // Check if there's any progress activity today
        $todayProgressCount = DB::table('progres')
            ->join('plot', 'progres.plot_id', '=', 'plot.plot_id')
            ->where('plot.lahan_id', $lahanId)
            ->whereDate('progres.created_at', $today)
            ->count();

        // If no activity today, return 0% for today's progress
        if ($todayProgressCount === 0) {
            // Get current total progress for reference
            $currentTotalProgress = DB::table('plot_progres')
                ->join('plot', 'plot_progres.plot_id', '=', 'plot.plot_id')
                ->where('plot.lahan_id', $lahanId)
                ->avg('plot_progres.percent') ?? 0;

            // Get yesterday's progress from snapshots or current progress
            $yesterdayAvg = DB::table('progres_snapshots')
                ->join('plot', 'progres_snapshots.plot_id', '=', 'plot.plot_id')
                ->where('plot.lahan_id', $lahanId)
                ->whereDate('date', $yesterday)
                ->avg('percent');

            // If no yesterday snapshot, try to get the latest snapshot
            if ($yesterdayAvg === null) {
                $latestSnapshot = DB::table('progres_snapshots')
                    ->join('plot', 'progres_snapshots.plot_id', '=', 'plot.plot_id')
                    ->where('plot.lahan_id', $lahanId)
                    ->where('date', '<', $today)
                    ->orderBy('date', 'desc')
                    ->first();

                $yesterdayAvg = $latestSnapshot ? $latestSnapshot->percent : $currentTotalProgress;
            }

            return [
                'today_percent' => 0.0,  // No activity today = 0% progress made today
                'yesterday_percent' => round($yesterdayAvg ?? 0, 2),
                'delta' => 0.0,
                'delta_type' => 'no_activity',
                'is_fallback' => false,
                'current_total_progress' => round($currentTotalProgress, 2),  // For reference
                'message' => 'Tidak ada aktivitas hari ini'
            ];
        }

        // If there is activity today, get today's and yesterday's snapshots
        $todayAvg = DB::table('progres_snapshots')
            ->join('plot', 'progres_snapshots.plot_id', '=', 'plot.plot_id')
            ->where('plot.lahan_id', $lahanId)
            ->whereDate('date', $today)
            ->avg('percent');

        $yesterdayAvg = DB::table('progres_snapshots')
            ->join('plot', 'progres_snapshots.plot_id', '=', 'plot.plot_id')
            ->where('plot.lahan_id', $lahanId)
            ->whereDate('date', $yesterday)
            ->avg('percent');

        // If no today snapshot but there is activity, create one
        if ($todayAvg === null) {
            $currentAvg = DB::table('plot_progres')
                ->join('plot', 'plot_progres.plot_id', '=', 'plot.plot_id')
                ->where('plot.lahan_id', $lahanId)
                ->avg('plot_progres.percent') ?? 0;
            
            $todayAvg = $currentAvg;

            // Create snapshots for all plots for today
            $plots = DB::table('plot')
                ->leftJoin('plot_progres', 'plot.plot_id', '=', 'plot_progres.plot_id')
                ->where('plot.lahan_id', $lahanId)
                ->select('plot.plot_id', DB::raw('COALESCE(plot_progres.percent, 0) as percent'))
                ->get();

            foreach ($plots as $plot) {
                DB::table('progres_snapshots')->updateOrInsert(
                    [
                        'plot_id' => $plot->plot_id,
                        'date' => $today->format('Y-m-d')
                    ],
                    [
                        'percent' => $plot->percent,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
            }
        }

        // If no yesterday snapshot, try to find the most recent one
        if ($yesterdayAvg === null) {
            $latestSnapshot = DB::table('progres_snapshots')
                ->join('plot', 'progres_snapshots.plot_id', '=', 'plot.plot_id')
                ->where('plot.lahan_id', $lahanId)
                ->where('date', '<', $today)
                ->orderBy('date', 'desc')
                ->first();

            if ($latestSnapshot) {
                $yesterdayAvg = $latestSnapshot->percent;
            } else {
                // If no historical data, assume yesterday was slightly lower
                $yesterdayAvg = max(0, $todayAvg - 0.5);
            }
        }

        $todayAvg = $todayAvg ?? 0;
        $yesterdayAvg = $yesterdayAvg ?? 0;
        $delta = $todayAvg - $yesterdayAvg;

        $result = [
            'today_percent' => round($todayAvg, 2),  // This shows actual progress as of today
            'yesterday_percent' => round($yesterdayAvg, 2),
            'delta' => round($delta, 2),
            'delta_type' => $delta >= 0 ? 'increase' : 'decrease',
            'is_fallback' => false,
            'activity_count' => $todayProgressCount
        ];
        
        return $result;
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
                Log::info('No historical progress data found for new lahan', [
                    'lahan_id' => $lahanId,
                    'period' => $period
                ]);
                
                return []; // Return empty array instead of fake data
            }

            // For recent periods with recent activity, include today's data even if no snapshot exists
            if ($cacheConfig['has_recent_activity'] && in_array($period, ['7days', '30days'])) {
                // Ensure today's snapshot exists if there's recent activity
                self::ensureTodaySnapshot($lahanId);
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

            if ($snapshots->isNotEmpty()) {
                return $snapshots->map(function ($snapshot) {
                    return [
                        'date' => $snapshot->period,
                        'avg_percent' => round((float)$snapshot->avg_percent, 2)
                    ];
                })->values()->toArray();
            }

            // If lahan has progress but no snapshots in date range, return empty
            Log::info('Lahan has progress but no snapshots in date range', [
                'lahan_id' => $lahanId,
                'period' => $period,
                'date_range' => [$startDate, $endDate]
            ]);

            return [];
        });
    }

    private static function ensureTodaySnapshot(int $lahanId): void
    {
        $today = Carbon::today();
        
        // Check if today's snapshots already exist
        $hasTodaySnapshot = DB::table('progres_snapshots')
            ->join('plot', 'progres_snapshots.plot_id', '=', 'plot.plot_id')
            ->where('plot.lahan_id', $lahanId)
            ->whereDate('date', $today)
            ->exists();

        if (!$hasTodaySnapshot) {
            // Check if there was activity today
            $hasActivityToday = DB::table('progres')
                ->join('plot', 'progres.plot_id', '=', 'plot.plot_id')
                ->where('plot.lahan_id', $lahanId)
                ->whereDate('progres.created_at', $today)
                ->exists();

            if ($hasActivityToday) {
                // Create snapshots for all plots for today
                $plots = DB::table('plot')
                    ->leftJoin('plot_progres', 'plot.plot_id', '=', 'plot_progres.plot_id')
                    ->where('plot.lahan_id', $lahanId)
                    ->select('plot.plot_id', DB::raw('COALESCE(plot_progres.percent, 0) as percent'))
                    ->get();

                foreach ($plots as $plot) {
                    DB::table('progres_snapshots')->updateOrInsert(
                        [
                            'plot_id' => $plot->plot_id,
                            'date' => $today->format('Y-m-d')
                        ],
                        [
                            'percent' => $plot->percent,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]
                    );
                }
            }
        }
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
        // Get lahan_id for this plot
        $lahanId = DB::table('plot')->where('plot_id', $plotId)->value('lahan_id');
        
        // Check for recent activity on this specific plot
        $hasRecentActivity = DB::table('progres')
            ->where('plot_id', $plotId)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->exists();

        $cacheKey = "block_historical_{$plotId}_{$period}" . ($hasRecentActivity ? '_active' : '_stable');
        $cacheTime = $hasRecentActivity ? 120 : 600; // 2 minutes vs 10 minutes

        return Cache::remember($cacheKey, $cacheTime, function () use ($plotId, $period) {
            $days = match ($period) {
                '7days' => 7,
                '30days' => 30,
                '90days' => 90,
                '1year' => 365,
                default => 30,
            };

            $rows = DB::table('progres_snapshots')
                ->where('plot_id', $plotId)
                ->whereBetween('date', [now()->subDays($days - 1)->toDateString(), now()->toDateString()])
                ->selectRaw('DATE(date) as date, AVG(percent) as percent')
                ->groupByRaw('DATE(date)')
                ->orderBy('date')
                ->get();

            if ($rows->isEmpty()) {
                return [];
            }

            $result = [];
            $map = $rows->keyBy('date')->map(fn($r) => round((float)$r->percent, 2))->toArray();
            
            for ($i = $days - 1; $i >= 0; $i--) {
                $d = now()->subDays($i)->format('Y-m-d');
                $result[] = ['date' => $d, 'percent' => $map[$d] ?? 0.0];
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
}
