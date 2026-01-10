<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DashboardController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display the dashboard for a specific lahan.
     */
    public function dashboard(Lahan $lahan)
    {
        // Use policy instead of manual check
        $this->authorize('view', $lahan);

        return view('detail-lahan.dashboard', compact('lahan'));
    }

    /**
     * Consolidated API endpoint for dashboard data
     * Handles: stats, progress-per-blok, map-data, progress-summary
     */
    public function getDashboardData(Request $request, Lahan $lahan)
    {
        try {
            // Use policy authorization
            $this->authorize('view', $lahan);

            $type = $request->get('type');
            
            switch ($type) {
                case 'stats':
                    $data = DashboardService::getDashboardStats($lahan->lahan_id);
                    break;
                
                case 'progress-per-blok':
                    $data = DashboardService::getProgressPerBlok($lahan->lahan_id);
                    break;
                
                case 'map-data':
                    Log::info('getMapData called for lahan: ' . $lahan->lahan_id);
                    $data = DashboardService::getMapData($lahan->lahan_id);
                    break;
                
                case 'progress-summary':
                    $data = DashboardService::getProgressSummary($lahan->lahan_id);
                    break;
                
                default:
                    return response()->json(['error' => 'Invalid type parameter'], 400);
            }

            return response()->json($data);

        } catch (\Exception $e) {
            Log::error("Dashboard getDashboardData error for type {$type}: " . $e->getMessage());
            
            // Return appropriate error response based on type
            if ($type === 'map-data') {
                return response()->json([
                    'type' => 'FeatureCollection',
                    'features' => [],
                    'error' => 'Failed to load map data'
                ], 500);
            }
            
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Consolidated API endpoint for historical data
     * Handles: historical-progress, block-historical
     */
    public function getHistoricalData(Request $request, Lahan $lahan)
    {
        try {
            // Use policy authorization
            $this->authorize('view', $lahan);

            $type = $request->get('type');
            $period = $request->get('period', '30days');

            switch ($type) {
                case 'progress':
                    $groupBy = $request->get('group_by', 'daily');
                    $data = DashboardService::getHistoricalProgress($lahan->lahan_id, $period, $groupBy);
                    break;
                
                case 'block':
                    $plotId = $request->get('plot_id');
                    if (!$plotId) {
                        return response()->json(['error' => 'plot_id required'], 400);
                    }
                    $data = DashboardService::getBlockHistorical($plotId, $period);
                    break;
                
                default:
                    return response()->json(['error' => 'Invalid type parameter'], 400);
            }

            return response()->json($data);

        } catch (\Exception $e) {
            Log::error("Dashboard getHistoricalData error for type {$type}: " . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Consolidated API endpoint for indicator data
     * Handles: all-indicators, indicator-progress, specific-indicator-progress, enhanced-indicator-progress, enhanced-blocks-for-indicator
     */
    public function getIndicatorData(Request $request, Lahan $lahan)
    {
        try {
            // Use policy authorization
            $this->authorize('view', $lahan);

            $type = $request->get('type');

            switch ($type) {
                case 'all':
                    $data = DashboardService::getAllIndicators();
                    break;
                
                case 'progress':
                    $data = DashboardService::getIndicatorProgress($lahan->lahan_id);
                    break;
                
                case 'specific':
                    $indicatorId = $request->get('indicator_id');
                    if (!$indicatorId) {
                        return response()->json(['error' => 'indicator_id required'], 400);
                    }
                    $period = $request->get('period', '30days');
                    $data = DashboardService::getSpecificIndicatorProgress($lahan->lahan_id, $indicatorId, $period);
                    
                    if (isset($data['error'])) {
                        return response()->json($data, 404);
                    }
                    break;
                
                case 'enhanced':
                    $indicatorId = $request->get('indicator_id');
                    if (!$indicatorId) {
                        return response()->json(['error' => 'indicator_id required'], 400);
                    }
                    $period = $request->get('period', '30days');
                    $plotId = $request->get('plot_id');
                    $weightMethod = $request->get('weight_method', 'target_weighted');
                    
                    $data = DashboardService::getEnhancedIndicatorProgress(
                        $lahan->lahan_id, 
                        (int)$indicatorId, 
                        $period, 
                        $plotId ? (int)$plotId : null,
                        $weightMethod
                    );
                    break;
                
                case 'enhanced-blocks':
                    $indicatorId = $request->get('indicator_id');
                    if (!$indicatorId) {
                        return response()->json(['error' => 'indicator_id required'], 400);
                    }
                    $data = DashboardService::getEnhancedBlocksForIndicator($lahan->lahan_id, (int)$indicatorId);
                    break;
                
                default:
                    return response()->json(['error' => 'Invalid type parameter'], 400);
            }

            return response()->json($data);

        } catch (\Exception $e) {
            Log::error("Dashboard getIndicatorData error for type {$type}: " . $e->getMessage());
            
            // Special handling for enhanced indicator errors
            if ($type === 'enhanced') {
                return response()->json([
                    'error' => 'Terjadi kesalahan saat memuat data indikator',
                    'data' => [],
                    'blocks' => []
                ], 500);
            }
            
            // Enhanced blocks returns empty array on error
            if ($type === 'enhanced-blocks') {
                return response()->json([], 500);
            }
            
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}