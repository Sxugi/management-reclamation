<?php

namespace App\Services;

use App\Models\Lahan;
use App\Models\JenisPohon;
use App\Models\DataPohonRealisasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MonitoringService
{
    /**
     * Get all monitoring records for a given jenis pohon across all plots in lahan
     */
    public static function getAllMonitoringByPlot(Lahan $lahan, int $jenisPohonId): array
    {
        try {
            $sql = "
                SELECT 
                    pr.progres_id,
                    pr.plot_id,
                    p.nama_plot,
                    p.luas_area,
                    pr.tanggal,
                    pr.catatan,
                    MAX(CASE WHEN fd.field_key = 'luas_area_sampling' THEN fv.field_value END) as luas_sampling,
                    MAX(CASE WHEN fd.field_key = 'jumlah_bibit_hidup' THEN fv.field_value END) as hidup,
                    MAX(CASE WHEN fd.field_key = 'jumlah_bibit_mati' THEN fv.field_value END) as mati,
                    MAX(CASE WHEN fd.field_key = 'umur_tanaman_bulan' THEN fv.field_value END) as umur_bulan,
                    MAX(CASE WHEN fd.field_key = 'penyebab_kematian' THEN fv.field_value END) as penyebab_kematian,
                    MAX(CASE WHEN fd.field_key = 'kondisi_kesehatan_tanaman' THEN fv.field_value END) as kondisi_kesehatan
                FROM progres pr
                JOIN plot p ON pr.plot_id = p.plot_id
                JOIN jenis_aktivitas ja ON pr.jenis_aktivitas_id = ja.jenis_aktivitas_id
                JOIN progres_field_values fv ON pr.progres_id = fv.progres_id
                JOIN field_definitions fd ON fv.field_definition_id = fd.field_definition_id
                WHERE p.lahan_id = ?
                    AND ja.field = 'monitoring_survival_rate'
                    AND EXISTS (
                        SELECT 1 FROM progres_field_values fv2
                        JOIN field_definitions fd2 ON fv2.field_definition_id = fd2.field_definition_id
                        WHERE fv2.progres_id = pr.progres_id
                            AND fd2.field_key = 'jenis_pohon_id'
                            AND fv2.field_value = ?
                    )
                GROUP BY pr.progres_id, pr.plot_id, p.nama_plot, p.luas_area, pr.tanggal, pr.catatan
                ORDER BY pr.plot_id ASC, pr.tanggal DESC, pr.progres_id DESC
            ";

            // Get results from database
            $results = DB::select($sql, [$lahan->lahan_id, $jenisPohonId]);

            // Get total planted per plot
            $totalBatangPerPlot = DataPohonRealisasi::join('pohon', 'data_pohon_realisasi.pohon_id', '=', 'pohon.pohon_id')
                ->join('plot', 'data_pohon_realisasi.plot_id', '=', 'plot.plot_id')
                ->where('plot.lahan_id', $lahan->lahan_id)
                ->where('pohon.jenis_pohon_id', $jenisPohonId)
                ->select('data_pohon_realisasi.plot_id', DB::raw('SUM(data_pohon_realisasi.jumlah_batang) as total_batang'))
                ->groupBy('data_pohon_realisasi.plot_id')
                ->pluck('total_batang', 'plot_id')
                ->toArray();

            // Format results
            $formatted = [];
            foreach ($results as $row) {
                $totalPlanted = (int)($totalBatangPerPlot[$row->plot_id] ?? 0);
                $hidup = (int)$row->hidup;
                $mati = (int)$row->mati;
                $totalSurveyed = $hidup + $mati;

                $formatted[] = [
                    'progres_id' => $row->progres_id,
                    'plot_id' => $row->plot_id,
                    'plot_name' => $row->nama_plot,
                    'luas_area' => $row->luas_area,
                    'luas_area_formatted' => number_format($row->luas_area, 2),
                    'luas_sampling' => $row->luas_sampling,
                    'luas_sampling_formatted' => $row->luas_sampling ? number_format($row->luas_sampling, 2) : null,
                    'tanggal_monitoring' => \Carbon\Carbon::parse($row->tanggal)->format('d M Y'),
                    'tanggal_raw' => $row->tanggal,
                    'total_planted' => $totalPlanted,
                    'hidup' => $hidup,
                    'mati' => $mati,
                    'total_surveyed' => $totalSurveyed,
                    
                    // Calculate survival and mortality rates based on surveyed trees
                    'survival_rate' => $totalSurveyed > 0 
                        ? round(($hidup / $totalSurveyed) * 100, 2)  // Use totalSurveyed, not totalPlanted
                        : 0,
                    
                    'mortality_rate' => $totalSurveyed > 0 
                        ? round(($mati / $totalSurveyed) * 100, 2) 
                        : 0,
                    
                    'is_full_census' => $totalSurveyed >= $totalPlanted,
                    'is_sampling' => $totalSurveyed < $totalPlanted,
                    'sampling_percentage' => $totalPlanted > 0 
                        ? round(($totalSurveyed / $totalPlanted) * 100, 2) 
                        : 0,
                    
                    'umur_bulan' => (int)($row->umur_bulan ?? 0),
                    'penyebab_kematian' => $row->penyebab_kematian,
                    'kondisi_kesehatan' => $row->kondisi_kesehatan,
                    'catatan' => $row->catatan,
                ];
            }

            // Group by plot
            $groupedByPlot = [];
            foreach ($formatted as $item) {
                $plotId = $item['plot_id'];
                if (!isset($groupedByPlot[$plotId])) {
                    $groupedByPlot[$plotId] = [
                        'plot_id' => $item['plot_id'],
                        'plot_name' => $item['plot_name'],
                        'luas_area' => $item['luas_area_formatted'],
                        'total_planted' => $item['total_planted'],
                        'monitoring_count' => 0,
                        'monitoring_records' => []
                    ];
                }
                $groupedByPlot[$plotId]['monitoring_records'][] = $item;
                $groupedByPlot[$plotId]['monitoring_count']++;
            }

            return $groupedByPlot;

        } catch (\Exception $e) {
            Log::error('Error getting all monitoring by plot', [
                'lahan_id' => $lahan->lahan_id,
                'jenis_pohon_id' => $jenisPohonId,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Get latest monitoring data for jenis_pohon across all plots in lahan
     */
    public static function getLatestMonitoringByPlot(Lahan $lahan, int $jenisPohonId): array
    {
        $sql = "
            WITH LatestMonitoring AS (
                SELECT 
                    p.plot_id,
                    MAX(pr.tanggal) as latest_date
                FROM progres pr
                JOIN jenis_aktivitas ja ON pr.jenis_aktivitas_id = ja.jenis_aktivitas_id
                JOIN plot p ON pr.plot_id = p.plot_id
                JOIN progres_field_values fv ON pr.progres_id = fv.progres_id
                JOIN field_definitions fd ON fv.field_definition_id = fd.field_definition_id
                WHERE p.lahan_id = ?
                    AND ja.field = 'monitoring_survival_rate'
                    AND fd.field_key = 'jenis_pohon_id'
                    AND fv.field_value = ?
                GROUP BY p.plot_id
            )
            SELECT 
                pr.plot_id,
                p.nama_plot,
                p.luas_area,
                pr.tanggal,
                MAX(CASE WHEN fd.field_key = 'jumlah_bibit_hidup' THEN fv.field_value END) as hidup,
                MAX(CASE WHEN fd.field_key = 'jumlah_bibit_mati' THEN fv.field_value END) as mati,
                MAX(CASE WHEN fd.field_key = 'umur_tanaman_bulan' THEN fv.field_value END) as umur_bulan,
                MAX(CASE WHEN fd.field_key = 'penyebab_kematian' THEN fv.field_value END) as penyebab_kematian
            FROM progres pr
            JOIN LatestMonitoring lm ON pr.plot_id = lm.plot_id AND pr.tanggal = lm.latest_date
            JOIN plot p ON pr.plot_id = p.plot_id
            JOIN jenis_aktivitas ja ON pr.jenis_aktivitas_id = ja.jenis_aktivitas_id
            JOIN progres_field_values fv ON pr.progres_id = fv.progres_id
            JOIN field_definitions fd ON fv.field_definition_id = fd.field_definition_id
            WHERE ja.field = 'monitoring_survival_rate'
            GROUP BY pr.plot_id, p.nama_plot, p.luas_area, pr.tanggal
            ORDER BY pr.tanggal DESC
        ";

        $results = DB::select($sql, [$lahan->lahan_id, $jenisPohonId]);

        // Format results
        $formatted = [];
        foreach ($results as $row) {
            $formatted[$row->plot_id] = [
                'plot_id' => $row->plot_id,
                'plot_name' => $row->nama_plot,
                'luas_area' => number_format($row->luas_area, 2),
                'hidup' => (int)$row->hidup,
                'mati' => (int)$row->mati,
                'umur_bulan' => (int)($row->umur_bulan ?? 0),
                'tanggal_monitoring' => \Carbon\Carbon::parse($row->tanggal)->format('d M Y'),
                'penyebab_kematian' => $row->penyebab_kematian,
            ];
        }

        return $formatted;
    }

    /** 
     * Get monitoring trend analysis for a given jenis pohon across all plots in lahan
     */
    public static function getMonitoringTrend(Lahan $lahan, int $jenisPohonId): array
    {
        try {
            $allData = self::getAllMonitoringByPlot($lahan, $jenisPohonId);
            
            if (empty($allData)) {
                return [
                    'has_data' => false,
                    'plots' => [],
                    'overall_stats' => null
                ];
            }

            $trendData = [];
            $allRecords = [];

            foreach ($allData as $plotData) {
                $records = $plotData['monitoring_records'];
                
                if (empty($records)) continue;

                // Records in DESC order (newest first)
                $newestRecord = $records[0];
                $oldestRecord = end($records);

                $latestSR = $newestRecord['survival_rate'];
                $oldestSR = $oldestRecord['survival_rate'];

                try {
                    $oldestDate = \Carbon\Carbon::parse($oldestRecord['tanggal_raw']);
                    $newestDate = \Carbon\Carbon::parse($newestRecord['tanggal_raw']);
                    
                    $periodMonths = $oldestDate->diffInMonths($newestDate);
                    $periodDays = $oldestDate->diffInDays($newestDate);
                    
                    // If same month, show as fraction
                    if ($periodMonths == 0 && $periodDays > 0) {
                        $periodMonths = round($periodDays / 30, 1);
                    }
                    
                } catch (\Exception $e) {
                    Log::warning('Error calculating period', [
                        'plot_id' => $plotData['plot_id'],
                        'error' => $e->getMessage()
                    ]);
                    $periodMonths = 0;
                    $periodDays = 0;
                }
                
                $trendData[] = [
                    'plot_id' => $plotData['plot_id'],
                    'plot_name' => $plotData['plot_name'],
                    'total_planted' => $plotData['total_planted'],
                    'monitoring_count' => count($records),
                    'latest_sr' => $records[0]['survival_rate'],
                    'oldest_sr' => end($records)['survival_rate'],
                    'sr_change' => $records[0]['survival_rate'] - end($records)['survival_rate'],
                    'trend' => self::calculateTrend($records),
                    'status' => self::getSRStatus($records[0]['survival_rate']),
                    'period_months' => $periodMonths,
                    'period_days' => $periodDays,
                    'records' => $records
                ];

                $allRecords = array_merge($allRecords, $records);
            }

            // Calculate overall statistics
            $overallStats = self::calculateOverallStats($allRecords, $allData);

            return [
                'has_data' => true,
                'jenis_pohon_id' => $jenisPohonId,
                'plots' => $trendData,
                'overall_stats' => $overallStats
            ];

        } catch (\Exception $e) {
            Log::error('Error getting monitoring trend', [
                'lahan_id' => $lahan->lahan_id,
                'jenis_pohon_id' => $jenisPohonId,
                'error' => $e->getMessage()
            ]);
            
            return [
                'has_data' => false,
                'plots' => [],
                'overall_stats' => null,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Calculate trend direction
     */
    private static function calculateTrend(array $records): string
    {
        if (count($records) < 2) {
            return 'insufficient_data';
        }

        $latest = $records[0];
        $oldest = end($records);
        
        $change = $latest['survival_rate'] - $oldest['survival_rate'];

        if ($change > 2) return 'improving';
        if ($change < -10) return 'declining_critical';
        if ($change < -5) return 'declining_significant';
        if ($change < -2) return 'declining_slight';
        
        return 'stable';
    }

    /**
     * Get SR status classification
     */
    private static function getSRStatus(float $sr): array
    {
        if ($sr >= 90) return ['label' => 'Sangat Baik', 'color' => 'green', 'icon' => '🌟'];
        if ($sr >= 80) return ['label' => 'Baik', 'color' => 'blue', 'icon' => '✅'];
        if ($sr >= 70) return ['label' => 'Cukup', 'color' => 'yellow', 'icon' => '⚠️'];
        if ($sr >= 60) return ['label' => 'Kurang', 'color' => 'orange', 'icon' => '⚡'];
        return ['label' => 'Buruk', 'color' => 'red', 'icon' => '❌'];
    }

    /**
     * Calculate overall statistics
     */
    private static function calculateOverallStats(array $allRecords, array $plotData): array
    {
        if (empty($allRecords)) {
            return [
                'total_monitoring' => 0,
                'total_plots' => 0,
                'total_planted' => 0,
                'average_sr' => 0,
                'best_sr' => 0,
                'worst_sr' => 0,
                'total_surveyed' => 0,
                'total_hidup' => 0,
                'total_mati' => 0,
            ];
        }

        $srValues = array_column($allRecords, 'survival_rate');
        $totalPlanted = array_sum(array_column($plotData, 'total_planted'));
        $totalSurveyed = array_sum(array_column($allRecords, 'total_surveyed'));
        $totalHidup = array_sum(array_column($allRecords, 'hidup'));
        $totalMati = array_sum(array_column($allRecords, 'mati'));

        return [
            'total_monitoring' => count($allRecords),
            'total_plots' => count($plotData),
            'total_planted' => $totalPlanted,
            'average_sr' => round(array_sum($srValues) / count($srValues), 2),
            'best_sr' => max($srValues),
            'worst_sr' => min($srValues),
            'total_surveyed' => $totalSurveyed,
            'total_hidup' => $totalHidup,
            'total_mati' => $totalMati,
        ];
    }

    /**
     * Get monitoring data with jenis pohon info (for modal payload)
     */
    public static function getMonitoringDataForModal(Lahan $lahan, int $jenisPohonId): array
    {
        $jenisPohon = JenisPohon::find($jenisPohonId);
        if (!$jenisPohon) {
            return [];
        }

        $monitoringByPlot = self::getLatestMonitoringByPlot($lahan, $jenisPohonId);

        // Get total planted trees per plot for the given jenis pohon
        $totalBatangPerPlot = DataPohonRealisasi::join('pohon', 'data_pohon_realisasi.pohon_id', '=', 'pohon.pohon_id')
            ->join('plot', 'data_pohon_realisasi.plot_id', '=', 'plot.plot_id')  // PERUBAHAN DI SINI
            ->where('plot.lahan_id', $lahan->lahan_id)
            ->where('pohon.jenis_pohon_id', $jenisPohonId)
            ->select('data_pohon_realisasi.plot_id', DB::raw('SUM(data_pohon_realisasi.jumlah_batang) as total_batang'))
            ->groupBy('data_pohon_realisasi.plot_id')
            ->pluck('total_batang', 'plot_id')
            ->toArray();

        // Add jenis pohon info and total batang per plot to each item
        return array_map(function($item) use ($jenisPohon, $totalBatangPerPlot) {
            $item['jenis_pohon_nama'] = $jenisPohon->nama_pohon;
            $item['jenis_pohon_kategori'] = $jenisPohon->kategori;
            $item['realisasi_progres'] = (int)($totalBatangPerPlot[$item['plot_id']] ?? 0);
            return $item;
        }, array_values($monitoringByPlot));
    }

    /**
     * Cached versions
     */
    public static function getCachedMonitoringTrend(Lahan $lahan, int $jenisPohonId, int $ttl = 300): array
    {
        $cacheKey = "monitoring_trend:{$lahan->lahan_id}:{$jenisPohonId}";
        
        return Cache::remember($cacheKey, $ttl, function() use ($lahan, $jenisPohonId) {
            return self::getMonitoringTrend($lahan, $jenisPohonId);
        });
    }

    public static function clearCache(Lahan $lahan, int $jenisPohonId): void
    {
        $cacheKeys = [
            "monitoring_data:{$lahan->lahan_id}:{$jenisPohonId}",
            "monitoring_trend:{$lahan->lahan_id}:{$jenisPohonId}"
        ];
        
        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }
}