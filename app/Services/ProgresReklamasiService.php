<?php

namespace App\Services;

use App\Models\Plot;
use App\Models\Pohon;
use App\Models\JenisPohon;
use App\Models\DataPohonRealisasi;
use App\Models\ProgresReklamasi;
use App\Models\PlotProgres;
use App\Models\ProgresSnapshot;
use App\Models\FieldDefinition;
use App\Models\ProgresFieldValue;
use App\Models\ProgresDokumentasi;
use App\Models\IndikatorProgresReklamasi;
use App\Models\TargetProgresReklamasi;
use App\Models\KategoriAktivitas;
use App\Models\JenisAktivitas;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Clickbar\Magellan\Data\Geometries\Point;

class ProgresReklamasiService
{
    protected static array $allowedSorts = [
        'tanggal',
        'kategori',
        'jenis_aktivitas_id',
    ];

    /**
     * Get activity category options for form dropdowns
     */
    public static function getKategoriAktivitasOptions(): array
    {
        $kategori = KategoriAktivitas::all();
        return $kategori->pluck('label', 'kategori_id')->toArray();
    }

    /**
     * Get activity type options for form dropdowns
     */
    public static function getJenisAktivitasOptions(): array
    {
        $jenisAktivitas = JenisAktivitas::all();
        return $jenisAktivitas->pluck('label', 'jenis_aktivitas_id')->toArray();
    }

    /**
     * Get existing 'jenis_pohon_id' field value for a given progress record
     */
    private function getExistingJenisPohonId(int $progressId): ?int
    {
        $val = DB::table('progres_field_values as pfv')
            ->join('field_definitions as fd', 'pfv.field_definition_id', '=', 'fd.field_definition_id')
            ->where('pfv.progres_id', $progressId)
            ->where('fd.field_key', 'jenis_pohon_id')
            ->value('pfv.field_value');
            
        return $val ? (int)$val : null;
    }

    /**
     * Get filtered and paginated progress data with multiple filter options
     * Supports date range, category, and documentation filters
     */
    public static function getFilteredData(Request $request, Plot $plot)
    {
        $query = ProgresReklamasi::with([
            'jenisAktivitas.kategoriAktivitas',
            'dokumentasi',
            'fieldValues.fieldDefinition',
        ])
        ->where('plot_id', $plot->plot_id);

        // Apply date range filtering with flexible options
        if ($request->filled('date')) {
            $query->whereDate('tanggal', $request->date);
        } else
        if ($request->filled('startDate') && $request->filled('endDate')) {
            if ($request->startDate > $request->endDate) {
                [$request->startDate, $request->endDate] = [$request->endDate, $request->startDate];
            }
            $query->whereBetween('tanggal', [$request->startDate, $request->endDate]);
        } elseif ($request->filled('startDate')) {
            $query->whereDate('tanggal', '>=', $request->startDate);
        } elseif ($request->filled('endDate')) {
            $query->whereDate('tanggal', '<=', $request->endDate);
        }

        // Filter by activity category
        if ($request->filled('category')) {
            $label = $request->category;
            $kategoriId = KategoriAktivitas::where('label', $label)->value('kategori_id');
            if ($kategoriId) {
                $query->whereHas('jenisAktivitas', function($q) use ($kategoriId) {
                    $q->where('kategori_id', $kategoriId);
                });
            }
        }

        if ($request->filled('activity')) {
            $label = $request->activity;
            $jenisAktivitasId = JenisAktivitas::where('label', $label)->value('jenis_aktivitas_id');
            if ($jenisAktivitasId) {
                $query->whereHas('jenisAktivitas', function($q) use ($jenisAktivitasId) {
                    $q->where('jenis_aktivitas_id', $jenisAktivitasId);
                });
            }
        }

        // Filter by documentation presence
        if ($request->filled('hasDokumentasi')) {
            if ($request->hasDokumentasi === 'true') {
                $query->whereHas('dokumentasi');
            } elseif ($request->hasDokumentasi === 'false') {
                $query->whereDoesntHave('dokumentasi');
            }
        }

        // Apply sorting with join for category sorting
        $sort = $request->get('tableSortColumn');
        $direction = $request->get('tableSortDirection', 'desc');

        if ($sort && in_array($sort, self::$allowedSorts) && in_array($direction, ['asc', 'desc'])) {
            if ($sort === 'kategori') {
                $query->join('jenis_aktivitas', 'progres.jenis_aktivitas_id', '=', 'jenis_aktivitas.jenis_aktivitas_id')
                      ->join('kategori_aktivitas', 'jenis_aktivitas.kategori_id', '=', 'kategori_aktivitas.kategori_id')
                      ->orderBy('kategori_aktivitas.label', $direction)
                      ->select('progres.*');
            } else {
                $query->orderBy($sort, $direction);
            }
        } else {
            $query->latest('tanggal');
        }

        return $query->paginate(5)->appends($request->query());
    }

    /**
     * Check if any filters are applied to the request
     */
    public static function hasFilter(Request $request): bool
    {
        return $request->filled('date') ||
               $request->filled('startDate') ||
               $request->filled('endDate') ||
               $request->filled('category') ||
               $request->filled('activity') ||
               $request->filled('hasDokumentasi');
    }

    /**
     * Calculate progress summary for all indicators with configurable aggregation types
     * Supports sum, max, and count aggregation methods
     */
    public static function calculateProgressSummary(Plot $plot)
    {
        $indicatorConfig = config('indicators.targets');
        $indicatorKeys = array_keys($indicatorConfig);

        // Initialize summary structure with config-based aggregation types
        $summary = [];
        foreach ($indicatorKeys as $key) {
            $summary[$key] = [
                'label' => $indicatorConfig[$key]['label'],
                'satuan' => $indicatorConfig[$key]['satuan'],
                'description' => $indicatorConfig[$key]['description'] ?? null,
                'total' => 0,
                'records_count' => 0,
                'summary_type' => $indicatorConfig[$key]['summary_type'] ?? 'sum',
                '_activity_breakdown' => []
            ];
        }

        // Process all progress records for the plot
        $progressList = ProgresReklamasi::with(['fieldValues.fieldDefinition'])
            ->where('plot_id', $plot->plot_id)
            ->whereNotNull('indikator_id')
            ->get();

        // Aggregate values based on configured aggregation type
        foreach ($progressList as $progress) {
            $activityId = $progress->jenis_aktivitas_id;

            foreach ($progress->fieldValues as $fieldValue) {
                $fieldDef = $fieldValue->fieldDefinition;
                if (!$fieldDef || !$fieldDef->indicator_key) continue;

                $key = $fieldDef->indicator_key;
                if (!isset($summary[$key])) continue;

                $value = (float) $fieldValue->field_value;
                $summaryType = $summary[$key]['summary_type'];

                // Apply appropriate aggregation method
                if ($summaryType === 'sum') {
                    if (!isset($summary[$key]['_activity_breakdown'][$activityId])) {
                        $summary[$key]['_activity_breakdown'][$activityId] = 0;
                    }
                    $summary[$key]['_activity_breakdown'][$activityId] += $value;

                } elseif ($summaryType === 'count') {
                    $summary[$key]['total'] = ($summary[$key]['total'] ?? 0) + 1;
                }

                $summary[$key]['records_count']++;
            }
        }

        // Ensure all indicators have numeric values
        foreach ($summary as $key => &$item) {
            if ($item['summary_type'] === 'sum') {
                $contributors = $item['_activity_breakdown'];
                $activeActivityCount = count($contributors);

                if ($activeActivityCount > 0) {
                    $grandTotal = array_sum($contributors);
                    
                    $item['total'] = $grandTotal / $activeActivityCount;
                } else {
                    $item['total'] = 0;
                }
            }

            unset($item['_activity_breakdown']);

            if ($item['total'] === null) {
                $item['total'] = 0;
            }
        }

        return $summary;
    }

    /**
     * Get cached mapping of indicator IDs to their keys for performance
     */
    public static function getIndikatorIdToKeyMap()
    {
        return cache()->remember('indikator_id_to_key_map', 3600, function () {
            return IndikatorProgresReklamasi::where('is_active', true)
                ->pluck('nama', 'indikator_id')
                ->toArray();
        });
    }

    /**
     * Calculate overall progress percentage based on targets vs actual values
     * Returns weighted average of all indicator progress percentages
     */
    public static function calculateOverallProgress(Plot $plot)
    {
        $indikatorIdToKey = self::getIndikatorIdToKeyMap();
        $targets = TargetProgresReklamasi::where('plot_id', $plot->plot_id)->get();
        $summary = self::calculateProgressSummary($plot);
        $progressList = [];

        // Calculate progress percentage for each target indicator
        foreach ($targets as $target) {
            $indikatorId = $target->indikator_id;
            $targetValue = $target->value;

            $key = $indikatorIdToKey[$indikatorId] ?? null;
            if (!$key) continue;

            $actual = $summary[$key]['total'] ?? 0;

            if ($targetValue > 0) {
                // Cap progress at 100% per indicator
                $progressPercent = min($actual / $targetValue, 1);
                $progressList[] = $progressPercent;
            }
        }

        // Return weighted average as percentage
        $overallProgress = count($progressList) > 0
            ? round(array_sum($progressList) / count($progressList) * 100, 2)
            : 0;

        return $overallProgress;
    }

    /**
     * Update plot progress and recalculate timeline snapshots
     * Maintains historical accuracy by updating current date and all future snapshots
     */
    public static function updatePlotProgress(Plot $plot, $date = null): void
    {
        $targetDate = $date ?? now()->format('Y-m-d');

        // Update current overall progress (always reflects latest data)
        $currentPercent = self::calculateOverallProgress($plot);
        PlotProgres::updateOrCreate(
            ['plot_id' => $plot->plot_id],
            ['percent' => $currentPercent]
        );

        // Create/update snapshot for the specific date being modified
        $progressAtTargetDate = self::calculateProgressAtDate($plot, $targetDate);
        ProgresSnapshot::updateOrCreate([
            'plot_id' => $plot->plot_id,
            'date' => $targetDate,
        ], [
            'percent' => $progressAtTargetDate,
        ]);

        // Recalculate all future snapshots to maintain timeline consistency
        self::recalculateSnapshotsAfterDate($plot, $targetDate);
        try {
            $deleted = self::cleanupOrphanedSnapshots($plot);
        } catch (\Exception $e) {
            Log::error('Error during cleanup of orphaned snapshots', [
                'plot_id' => $plot->plot_id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update only current progress without creating snapshots
     * Used when targets change but no actual progress activity occurs
     */
    public static function updateCurrentProgressOnly(Plot $plot): void
    {
        $currentPercent = self::calculateOverallProgress($plot);
        
        PlotProgres::updateOrCreate(
            ['plot_id' => $plot->plot_id],
            ['percent' => $currentPercent]
        );
    }

    /**
     * Get progress delta between latest and previous snapshots
     * Used for showing progress trends and changes
     */
    public static function getProgresDelta($plot): array
    {
        if (!$plot || !$plot->plot_id) {
            return [
                'latestPercent' => 0,
                'previousPercent' => 0, 
                'delta' => 0,
                'isFirstData' => true,
            ];
        }

        // Get the two most recent snapshots for comparison
        $snapshots = ProgresSnapshot::where('plot_id', $plot->plot_id)
            ->orderBy('date', 'desc')
            ->limit(2)
            ->get(['percent', 'date']);

        $latestPercent = $snapshots->first()?->percent ?? 0;
        $previousPercent = $snapshots->count() > 1 ? $snapshots->get(1)->percent : 0;

        return [
            'latestPercent' => $latestPercent,
            'previousPercent' => $previousPercent,
            'delta' => round($latestPercent - $previousPercent, 2),
            'isFirstData' => $snapshots->count() < 2,
        ];
    }

    /**
     * Recalculate progress snapshots for all dates after the specified date
     * Ensures timeline consistency when historical data is modified
     */
    public static function recalculateSnapshotsAfterDate(Plot $plot, $afterDate): void
    {
        // Get all existing snapshots after the target date
        $snapshotsToUpdate = ProgresSnapshot::where('plot_id', $plot->plot_id)
            ->where('date', '>', $afterDate)
            ->orderBy('date', 'asc')
            ->get();

        // Recalculate each snapshot based on data available up to that date
        foreach ($snapshotsToUpdate as $snapshot) {
            $oldPercent = $snapshot->percent;
            $newPercent = self::calculateProgressAtDate($plot, $snapshot->date);
            $snapshot->update(['percent' => $newPercent]);
        }
    }

    /**
     * Generate human-readable description for activity logging
     */
    public static function generateProgresDescription($action, $progres)
    {
        try {
            if (!$progres->relationLoaded('indikator')) {
                $progres->load('indikator');
            }
            if (!$progres->relationLoaded('jenisAktivitas')) {
                $progres->load('jenisAktivitas');
            }

            $indikator = $progres->indikator?->label ?? $progres->indikator?->nama ?? 'Indikator Tidak Diketahui';
            $jenisAktivitas = $progres->jenisAktivitas?->label ?? $progres->jenisAktivitas?->nama ?? 'Aktivitas Tidak Diketahui';

            $actions = [
                'created' => "Menambahkan Progres untuk Kategori {$indikator} dengan Aktivitas {$jenisAktivitas}",
                'updated' => "Mengubah Progres untuk Kategori {$indikator} dengan Aktivitas {$jenisAktivitas}",
                'deleted' => "Menghapus Progres untuk Kategori {$indikator} dengan Aktivitas {$jenisAktivitas}",
            ];

            return $actions[$action] ?? "Aktivitas Progres tidak diketahui";
        } catch (\Exception $e) {
            Log::error('Error generating progres description', [
                'action' => $action,
                'progres_id' => $progres->progres_id ?? 'unknown',
                'error' => $e->getMessage()
            ]);
            
            return "Aktivitas progres {$action}";
        }
    }

    /**
     * Calculate progress percentage at a specific date
     * Only considers data up to and including the specified date
     */
    public static function calculateProgressAtDate(Plot $plot, $date)
    {
        try {
            if (!$plot || !$plot->plot_id) {
                Log::error('Invalid plot provided to calculateProgressAtDate');
                return 0;
            }

            $indikatorIdToKey = self::getIndikatorIdToKeyMap();
            $targets = TargetProgresReklamasi::where('plot_id', $plot->plot_id)->get();
            $summary = self::calculateProgressSummaryAtDate($plot, $date);
            $progressList = [];

            // Calculate progress for each target indicator
            foreach ($targets as $target) {
                $indikatorId = $target->indikator_id;
                $targetValue = $target->value;
                $key = $indikatorIdToKey[$indikatorId] ?? null;
                
                if (!$key) {
                    Log::warning('Key not found for indicator', ['indikator_id' => $indikatorId]);
                    continue;
                }

                $actual = $summary[$key]['total'] ?? 0;
                
                if ($targetValue > 0) {
                    $progressPercent = min($actual / $targetValue, 1);
                    $progressList[] = $progressPercent;
                }
            }

            $result = count($progressList) > 0
                ? round(array_sum($progressList) / count($progressList) * 100, 2)
                : 0;

            return $result;
        } catch (\Exception $e) {
            Log::error('Error calculating progress at date', [
                'plot_id' => $plot->plot_id ?? 'unknown',
                'date' => $date,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Calculate progress summary up to a specific date only
     * Used for accurate historical progress calculations
     */
    public static function calculateProgressSummaryAtDate(Plot $plot, $date)
    {
        $indicatorConfig = config('indicators.targets');
        $indicatorKeys = array_keys($indicatorConfig);

        // Initialize summary structure
        $summary = [];
        foreach ($indicatorKeys as $key) {
            $summary[$key] = [
                'label' => $indicatorConfig[$key]['label'],
                'satuan' => $indicatorConfig[$key]['satuan'],
                'description' => $indicatorConfig[$key]['description'] ?? null,
                'total' => null,
                'records_count' => 0,
                'summary_type' => $indicatorConfig[$key]['summary_type'] ?? 'sum',
                '_activity_breakdown' => []
            ];
        }

        // Only include progress data up to the specified date
        $progressList = ProgresReklamasi::with(['fieldValues.fieldDefinition'])
            ->where('plot_id', $plot->plot_id)
            ->where('tanggal', '<=', $date)
            ->whereNotNull('indikator_id')
            ->get();

        // Aggregate values using configured aggregation methods
        foreach ($progressList as $progress) {
            $activityId = $progress->jenis_aktivitas_id;

            foreach ($progress->fieldValues as $fieldValue) {
                $fieldDef = $fieldValue->fieldDefinition;
                if (!$fieldDef || !$fieldDef->indicator_key) continue;

                $key = $fieldDef->indicator_key;
                if (!isset($summary[$key])) continue;

                $value = (float) $fieldValue->field_value;
                $summaryType = $summary[$key]['summary_type'];

                if ($summaryType === 'sum') {
                    if (!isset($summary[$key]['_activity_breakdown'][$activityId])) {
                        $summary[$key]['_activity_breakdown'][$activityId] = 0;
                    }
                    $summary[$key]['_activity_breakdown'][$activityId] += $value;

                } elseif ($summaryType === 'count') {
                    $summary[$key]['total'] = ($summary[$key]['total'] ?? 0) + 1;
                }

                $summary[$key]['records_count']++;
            }
        }

        // Ensure all indicators have numeric values
        foreach ($summary as $key => &$item) {
            if ($item['summary_type'] === 'sum') {
                $contributors = $item['_activity_breakdown']; 
                $activeActivityCount = count($contributors);

                if ($activeActivityCount > 0) {
                    $grandTotal = array_sum($contributors);
                    $item['total'] = $grandTotal / $activeActivityCount;
                } else {
                    $item['total'] = 0;
                }
            }

            unset($item['_activity_breakdown']);

            if ($item['total'] === null) {
                $item['total'] = 0;
            }
        }

        return $summary;
    }

    /**
     * Clean up orphaned snapshots that have no corresponding progress data
     * Maintenance method for data integrity
     */
    public static function cleanupOrphanedSnapshots(Plot $plot): int
    {
        // Get all dates that have actual progress data
        $validDates = ProgresReklamasi::where('plot_id', $plot->plot_id)
            ->distinct()
            ->pluck('tanggal')
            ->toArray();

        // Remove snapshots for dates without progress data
        $deletedCount = ProgresSnapshot::where('plot_id', $plot->plot_id)
            ->whereNotIn('date', $validDates)
            ->delete();

        return $deletedCount;
    }

    /**
     * Create new progress record with dynamic fields and documentation
     * Handles transaction safety and automatic progress updates
     */
    public function create(Plot $plot, array $requestData): ProgresReklamasi
    {
        return DB::transaction(function() use ($plot, $requestData) {
            try {
                // Extract primary value and indicator from dynamic fields
                $mainValueData = $this->extractMainValueData($requestData['jenis_aktivitas_id'], $requestData);
                
                // Create main progress record
                $progressData = [
                    'plot_id'           => $plot->plot_id,
                    'indikator_id'      => $mainValueData['indikator_id'],
                    'jenis_aktivitas_id'=> $requestData['jenis_aktivitas_id'],
                    'tanggal'           => $requestData['tanggal'],
                    'value'             => $mainValueData['value'],
                    'catatan'           => $requestData['catatan'] ?? null,
                ];

                $progress = ProgresReklamasi::create($progressData);

                // Save all dynamic field values
                $this->saveDynamicFieldValues($progress->progres_id, $requestData['jenis_aktivitas_id'], $requestData);

                // Handle file uploads and storage
                $this->saveDocumentationFiles($progress->progres_id, $requestData);
                
                // Create audit trail entry
                $description = self::generateProgresDescription('created', $progress);
                ActivityLog::createLog(
                    $plot->plot_id, 
                    'created', 
                    'progres', 
                    $progress->progres_id, 
                    $description
                );

                // Update progress calculations and snapshots
                self::updatePlotProgress($plot, $requestData['tanggal']);

                if (isset($requestData['jenis_pohon_id'])) {
                    // Clear monitoring cache
                    \App\Services\MonitoringService::clearCache(
                        $plot->lahan, 
                        (int)$requestData['jenis_pohon_id']
                    );
                }

                if (isset($requestData['jenis_pohon_id'])) {
                    $year = \Carbon\Carbon::parse($requestData['tanggal'])->year;
                    $this->syncInventoryPohon($plot->plot_id, (int)$requestData['jenis_pohon_id'], $year);
                }

                return $progress;
            } catch (\Exception $e) {
                Log::error("Failed to create progress record", [
                    'plot_id' => $plot->plot_id,
                    'error' => $e->getMessage()
                ]);
                throw $e;
            }
        });
    }

    /**
     * Update existing progress record with category change handling
     * Manages field cleanup when activity type changes
     */
    public function update(ProgresReklamasi $progress, array $requestData): ProgresReklamasi
    {
        $oldActivityId = $progress->jenis_aktivitas_id;
        $newActivityId = $requestData['jenis_aktivitas_id'];
        $isCategoryChanged = $oldActivityId != $newActivityId;
        $oldPohonId = $this->getExistingJenisPohonId($progress->progres_id);
        $oldYear = \Carbon\Carbon::parse($progress->tanggal)->year;

        return DB::transaction(function() use ($progress, $requestData, $oldActivityId, $newActivityId, $isCategoryChanged, $oldPohonId, $oldYear) {
            try {
                // Clean up incompatible field values if category changed
                if ($isCategoryChanged) {
                    $this->cleanupOldFieldValues($progress->progres_id, $oldActivityId, $newActivityId);
                }

                // Extract new primary value data
                $mainValueData = $this->extractMainValueData($requestData['jenis_aktivitas_id'], $requestData);
                
                // Update main progress record
                $updateData = [
                    'indikator_id'      => $mainValueData['indikator_id'],
                    'jenis_aktivitas_id'=> $requestData['jenis_aktivitas_id'],
                    'tanggal'           => $requestData['tanggal'],
                    'value'             => $mainValueData['value'],
                    'catatan'           => $requestData['catatan'] ?? null,
                ];

                $progress->update($updateData);

                // Update dynamic field values for new category
                $this->updateDynamicFieldValues($progress->progres_id, $requestData['jenis_aktivitas_id'], $requestData);

                // Handle file removal and addition
                $this->handleRemovedDocumentationFiles($progress->progres_id, $requestData);
                $this->saveDocumentationFiles($progress->progres_id, $requestData);

                // Create audit trail entry
                $description = self::generateProgresDescription('updated', $progress);
                ActivityLog::createLog(
                    $progress->plot_id,
                    'updated',
                    'progres',
                    $progress->progres_id,
                    $description
                );

                // Recalculate progress and snapshots
                self::updatePlotProgress($progress->plot, $requestData['tanggal']);

                if (isset($requestData['jenis_pohon_id'])) {
                    // Clear monitoring cache
                    \App\Services\MonitoringService::clearCache(
                        $progress->plot->lahan, 
                        (int)$requestData['jenis_pohon_id']
                    );
                }

                $newPohonId = isset($requestData['jenis_pohon_id']) ? (int)$requestData['jenis_pohon_id'] : null;
                $newYear = \Carbon\Carbon::parse($requestData['tanggal'])->year;

                if ($newPohonId) {
                    $this->syncInventoryPohon($progress->plot_id, $newPohonId, $newYear);
                }

                if ($oldPohonId && ($oldPohonId !== $newPohonId || $oldYear !== $newYear)) {
                    $this->syncInventoryPohon($progress->plot_id, $oldPohonId, $oldYear);
                }

                return $progress;
            } catch (\Exception $e) {
                Log::error("Failed to update progress record", [
                    'progress_id' => $progress->progres_id,
                    'error' => $e->getMessage()
                ]);
                throw $e;
            }
        });
    }

    /**
     * Delete progress record and all related data
     * Handles file cleanup and progress recalculation
     */
    public function delete(ProgresReklamasi $progress): void
    {
        $plotId = $progress->plot_id;
        $pohonId = $this->getExistingJenisPohonId($progress->progres_id);
        $year = \Carbon\Carbon::parse($progress->tanggal)->year;

        DB::transaction(function() use ($progress, $plotId, $pohonId, $year) {
            try {
                // Remove files from storage and database records
                foreach ($progress->dokumentasi as $documentation) {
                    if ($documentation->image_path && Storage::disk('public')->exists($documentation->image_path)) {
                        Storage::disk('public')->delete($documentation->image_path);
                    }
                    $documentation->delete();
                }

                // Clean up related field values
                $progress->fieldValues()->delete();

                // Remove main progress record
                $progress->delete();

                // Create audit trail entry
                $description = self::generateProgresDescription('deleted', $progress);
                ActivityLog::createLog(
                    $progress->plot_id, 
                    'deleted', 
                    'progres', 
                    $progress->progres_id, 
                    $description
                );

                if ($pohonId) {
                    $this->syncInventoryPohon($plotId, $pohonId, $year);
                }

                // Recalculate progress after deletion
                self::updatePlotProgress($progress->plot, $progress->tanggal);

                if (isset($requestData['jenis_pohon_id'])) {
                    // Clear monitoring cache
                    \App\Services\MonitoringService::clearCache(
                        $progress->plot->lahan, 
                        (int)$requestData['jenis_pohon_id']
                    );
                }
            } catch (\Exception $e) {
                Log::error("Failed to delete progress record", [
                    'progress_id' => $progressId,
                    'error' => $e->getMessage()
                ]);
                throw $e;
            }
        });
    }

    /**
     * Extract main value and indicator ID from dynamic request data
     * Supports database-driven field discovery with fallbacks
     */
    private function extractMainValueData(int $activityId, array $requestData): array
    {
        try {
            // Get activity type
            $jenisAktivitas = JenisAktivitas::find($activityId);
            
            // Check if this is a monitoring activity (NO indicator needed)
            if ($jenisAktivitas && $this->isMonitoringActivity($jenisAktivitas->field)) {
                // Monitoring activities don't have indicator_id
                return [
                    'value' => $this->extractMonitoringValue($jenisAktivitas->field, $requestData),
                    'indikator_id' => null, // ← NULL for monitoring
                    'source' => 'monitoring_activity'
                ];
            }
            
            // Primary method: find field with indicator mapping
            $primaryField = FieldDefinition::where('jenis_aktivitas_id', $activityId)
                ->whereNotNull('indicator_key')
                ->first();

            if ($primaryField && array_key_exists($primaryField->field_key, $requestData)) {
                $fieldValue = $requestData[$primaryField->field_key];
                
                if (is_numeric($fieldValue) && (float) $fieldValue >= 0) {
                    $indicatorId = $this->findIndicatorIdByName($primaryField->indicator_key);
                    
                    return [
                        'value' => (float) $fieldValue,
                        'indikator_id' => $indicatorId,
                        'source' => 'database_driven'
                    ];
                }
            }

            // Fallback: use first valid numeric value
            $excludedKeys = ['jenis_aktivitas_id', 'tanggal', 'catatan', 'indikator_id', 'dokumentasi', '_token'];
            
            foreach ($requestData as $key => $value) {
                if (in_array($key, $excludedKeys)) continue;
                
                if (is_numeric($value) && (float) $value > 0) {
                    return [
                        'value' => (float) $value,
                        'indikator_id' => null,
                        'source' => 'first_numeric'
                    ];
                }
            }

            // Final fallback for empty forms
            return ['value' => 0, 'indikator_id' => null, 'source' => 'zero_fallback'];

        } catch (\Exception $e) {
            Log::error("Error extracting main value data", [
                'activity_id' => $activityId,
                'error' => $e->getMessage()
            ]);
            return ['value' => 0, 'indikator_id' => null, 'source' => 'error_fallback'];
        }
    }

    /**
     * Check if activity is monitoring type (doesn't require indicator)
     */
    private function isMonitoringActivity(string $activityField): bool
    {
        $monitoringActivities = [
            'monitoring_survival_rate',
            'monitoring_pertumbuhan',
            'monitoring_kualitas_air',
            'monitoring_biodiversitas',
        ];
        
        return in_array($activityField, $monitoringActivities);
    }

    /**
     * Extract representative value for monitoring activities
     * Since monitoring doesn't have targets, we use a representative metric
     */
    private function extractMonitoringValue(string $activityField, array $requestData): float
    {
        switch ($activityField) {
            case 'monitoring_survival_rate':
                // Calculate survival rate as value
                $hidup = (int)($requestData['jumlah_bibit_hidup'] ?? 0);
                $mati = (int)($requestData['jumlah_bibit_mati'] ?? 0);
                $total = $hidup + $mati;
                
                return $total > 0 ? round(($hidup / $total) * 100, 2) : 0;
                
            case 'monitoring_pertumbuhan':
                // Use average height as representative value
                return (float)($requestData['tinggi_tanaman_rata'] ?? 0);
                
            case 'monitoring_kualitas_air':
                // Use pH or main parameter
                return (float)($requestData['ph'] ?? 0);
                
            default:
                // Default: find first numeric value
                foreach ($requestData as $key => $value) {
                    if (is_numeric($value) && (float)$value > 0) {
                        return (float)$value;
                    }
                }
                return 0;
        }
    }

    /**
     * Find indicator ID by matching name with indicators table
     */
    private function findIndicatorIdByName(?string $indicatorName): ?int
    {
        if (!$indicatorName) return null;
        
        try {
            $indicator = IndikatorProgresReklamasi::where('nama', $indicatorName)->first();
            return $indicator ? $indicator->indikator_id : null;
        } catch (\Exception $e) {
            Log::error("Error finding indicator ID", [
                'indicator_name' => $indicatorName,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Save dynamic field values for activity-specific fields
     * Skips empty values to maintain clean data
     */
    private function saveDynamicFieldValues(int $progressId, int $activityId, array $requestData): void
    {
        try {
            $fieldDefinitions = FieldDefinition::where('jenis_aktivitas_id', $activityId)->get();

            foreach ($fieldDefinitions as $fieldDef) {
                if (!array_key_exists($fieldDef->field_key, $requestData)) continue;

                $fieldValue = $requestData[$fieldDef->field_key];
                
                // Only save meaningful values
                if ($fieldValue !== null && $fieldValue !== '' && $fieldValue !== '0') {
                    ProgresFieldValue::create([
                        'progres_id' => $progressId,
                        'field_definition_id' => $fieldDef->field_definition_id,
                        'field_value' => $fieldValue,
                    ]);
                }
            }

        } catch (\Exception $e) {
            Log::error("Error saving dynamic field values", [
                'progress_id' => $progressId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update dynamic field values with cleanup of empty values
     */
    private function updateDynamicFieldValues(int $progressId, int $activityId, array $requestData): void
    {
        try {
            $fieldDefinitions = FieldDefinition::where('jenis_aktivitas_id', $activityId)->get();
            $processedCount = 0;
            
            foreach ($fieldDefinitions as $fieldDef) {
                if (!array_key_exists($fieldDef->field_key, $requestData)) continue;

                $fieldValue = $requestData[$fieldDef->field_key];
                $whereConditions = [
                    'progres_id' => $progressId,
                    'field_definition_id' => $fieldDef->field_definition_id,
                ];
                
                if ($fieldValue !== null && $fieldValue !== '' && $fieldValue !== '0') {
                    // Create or update with new value
                    ProgresFieldValue::updateOrCreate($whereConditions, [
                        'field_value' => $fieldValue,
                    ]);
                } else {
                    // Remove empty values to keep database clean
                    ProgresFieldValue::where($whereConditions)->delete();
                }
                $processedCount++;
            }

        } catch (\Exception $e) {
            Log::error("Error updating dynamic field values", [
                'progress_id' => $progressId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Save uploaded documentation files to storage and database
     * Handles multiple file uploads with error recovery
     */
    private function saveDocumentationFiles(int $progressId, array $requestData): void
    {
        if (empty($requestData['dokumentasi'])) return;

        try {
            foreach ($requestData['dokumentasi'] as $uploadedFile) {
                if (!$uploadedFile || !$uploadedFile->isValid()) continue;

                try {
                    // Store file in public disk under organized folder
                    $filePath = $uploadedFile->store('progres_dokumentasi', 'public');

                    // Extract GPS coordinates if needed
                    $gps = $this->extractGpsCoordinates($uploadedFile->getRealPath());

                    $locationPoint = null;

                    if ($gps && isset($gps['lat']) && isset($gps['lng'])) {
                        $locationPoint = Point::make($gps['lng'], $gps['lat']);
                    }
                    
                    ProgresDokumentasi::create([
                        'progres_id' => $progressId,
                        'image_path' => $filePath,
                        'location'   => $locationPoint,
                    ]);

                } catch (\Exception $fileException) {
                    Log::error("Error saving individual file", [
                        'progress_id' => $progressId,
                        'file_name' => $uploadedFile->getClientOriginalName(),
                        'error' => $fileException->getMessage()
                    ]);
                }
            }

        } catch (\Exception $e) {
            Log::error("Error saving documentation files", [
                'progress_id' => $progressId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     *  Extract GPS coordinates from image EXIF data
     */
    private function extractGpsCoordinates($filePath)
    {
        try {
            // Cek apakah file adalah gambar dan fungsi exif tersedia
            if (!function_exists('exif_read_data')) return null;
            
            // Baca EXIF
            // '@' digunakan untuk suppress error jika format gambar tidak support EXIF
            $exif = @exif_read_data($filePath);

            if (!$exif || !isset($exif['GPSLatitude'], $exif['GPSLongitude'], $exif['GPSLatitudeRef'], $exif['GPSLongitudeRef'])) {
                return null;
            }

            // Konversi DMS (Degrees Minutes Seconds) ke Decimal
            $lat = $this->gpsToDecimal($exif['GPSLatitude'], $exif['GPSLatitudeRef']);
            $lng = $this->gpsToDecimal($exif['GPSLongitude'], $exif['GPSLongitudeRef']);

            return ['lat' => $lat, 'lng' => $lng];

        } catch (\Exception $e) {
            return null; // Abaikan error, lanjut simpan file tanpa koordinat
        }
    }

    /**
     * Convert GPS coordinates from DMS format to Decimal format
     */
    private function gpsToDecimal($coordinate, $hemisphere)
    {
        for ($i = 0; $i < 3; $i++) {
            $part = explode('/', $coordinate[$i]);
            if (count($part) == 1) {
                $coordinate[$i] = $part[0];
            } else if (count($part) == 2) {
                $coordinate[$i] = floatval($part[0]) / floatval($part[1]);
            } else {
                $coordinate[$i] = 0;
            }
        }

        $decimal = $coordinate[0] + ($coordinate[1] / 60) + ($coordinate[2] / 3600);

        // Jika S (South) atau W (West), buat negatif
        if ($hemisphere == 'S' || $hemisphere == 'W') {
            $decimal = $decimal * -1;
        }

        return $decimal;
    }

    /**
     * Handle removal of documentation files based on frontend indices
     * Parses JSON data and removes files from both storage and database
     */
    private function handleRemovedDocumentationFiles(int $progressId, array $requestData): void
    {
        if (empty($requestData['removed_files'])) return;

        try {
            // Parse removal data from frontend
            $removedFilesData = is_string($requestData['removed_files']) 
                ? json_decode($requestData['removed_files'], true) 
                : $requestData['removed_files'];

            if (!is_array($removedFilesData) || empty($removedFilesData['dokumentasi'])) return;

            $removedIndices = $removedFilesData['dokumentasi'];
            
            // Get existing files in consistent order
            $existingDocuments = ProgresDokumentasi::where('progres_id', $progressId)
                ->orderBy('progres_dokumentasi_id')
                ->get();

            // Remove files by index
            foreach ($removedIndices as $index) {
                if (!isset($existingDocuments[$index])) continue;

                $document = $existingDocuments[$index];
                
                // Remove physical file
                if ($document->image_path && Storage::disk('public')->exists($document->image_path)) {
                    Storage::disk('public')->delete($document->image_path);
                }

                // Remove database record
                $document->delete();
            }
        } catch (\Exception $e) {
            Log::error("Error handling removed documentation files", [
                'progress_id' => $progressId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Clean up field values when activity category changes
     * Prevents orphaned field values and maintains data integrity
     */
    private function cleanupOldFieldValues(int $progressId, int $oldActivityId, int $newActivityId): void
    {
        try {
            // Remove values from old category fields
            $oldFieldIds = FieldDefinition::where('jenis_aktivitas_id', $oldActivityId)
                ->pluck('field_definition_id')
                ->toArray();

            $deletedByOldCategory = 0;
            if (!empty($oldFieldIds)) {
                $deletedByOldCategory = ProgresFieldValue::where('progres_id', $progressId)
                    ->whereIn('field_definition_id', $oldFieldIds)
                    ->delete();
            }

            // Clean up any remaining orphaned values
            $newFieldIds = FieldDefinition::where('jenis_aktivitas_id', $newActivityId)
                ->pluck('field_definition_id')
                ->toArray();

            $deletedOrphaned = 0;
            if (!empty($newFieldIds)) {
                $deletedOrphaned = ProgresFieldValue::where('progres_id', $progressId)
                    ->whereNotIn('field_definition_id', $newFieldIds)
                    ->delete();
            } else {
                // Remove all field values if new category has no fields
                $deletedOrphaned = ProgresFieldValue::where('progres_id', $progressId)->delete();
            }

            $totalDeleted = $deletedByOldCategory + $deletedOrphaned;
        } catch (\Exception $e) {
            Log::error("Error cleaning up old field values", [
                'progress_id' => $progressId,
                'error' => $e->getMessage()
            ]);
            throw new \Exception("Failed to cleanup old field values: " . $e->getMessage());
        }
    }

    /**
     * Synchronize inventory data for specific tree type and year
     * Calculates total planted trees from daily reports and updates inventory
     */
    public function syncInventoryPohon(int $plotId, int $jenisPohonId, $year): void
    {
        // Config whitelist
        $allowedActivities = [
            'penanaman_pionir',
            'penanaman_lokal',
            'penanaman_mpts',
            'penyulaman'
        ];

        $allowedFields = [
            'jumlah_bibit', 
            'jumlah_tanaman'
        ];

        // Query total dari EAV
        $total = DB::table('progres')
            ->join('jenis_aktivitas', 'progres.jenis_aktivitas_id', '=', 'jenis_aktivitas.jenis_aktivitas_id')
            ->join('progres_field_values as fv_pohon', 'progres.progres_id', '=', 'fv_pohon.progres_id')
            ->join('field_definitions as fd_pohon', 'fv_pohon.field_definition_id', '=', 'fd_pohon.field_definition_id')
            ->join('progres_field_values as fv_jumlah', 'progres.progres_id', '=', 'fv_jumlah.progres_id')
            ->join('field_definitions as fd_jumlah', 'fv_jumlah.field_definition_id', '=', 'fd_jumlah.field_definition_id')
            ->where('progres.plot_id', $plotId)
            ->whereYear('progres.tanggal', $year)
            ->whereIn('jenis_aktivitas.field', $allowedActivities)
            ->where('fd_pohon.field_key', 'jenis_pohon_id')
            ->where('fv_pohon.field_value', $jenisPohonId)
            ->whereIn('fd_jumlah.field_key', $allowedFields)
            ->sum(DB::raw('CAST(fv_jumlah.field_value AS DECIMAL)'));
        
        $plot = DB::table('plot')->where('plot_id', $plotId)->first();
        if (!$plot) return;

        $pohon = Pohon::firstOrCreate([
            'lahan_id' => $plot->lahan_id,
            'jenis_pohon_id' => $jenisPohonId
        ]);

        if ($total > 0) {
            // Update realisasi
            DataPohonRealisasi::updateOrCreate(
                [
                    'pohon_id' => $pohon->pohon_id,
                    'plot_id'  => $plotId,
                    'tahun'    => $year
                ],
                [
                    'jumlah_batang' => $total
                ]
            );
        } else {
            // Delete if zero
            DataPohonRealisasi::where([
                'pohon_id' => $pohon->pohon_id,
                'plot_id'  => $plotId,
                'tahun'    => $year
            ])->delete();
        }
    }

    /**
     * Export progres reklamasi to Excel
     */
    public function exportExcel(Plot $plot)
    {
        $plot->load('lahan');

        $pohonMap = JenisPohon::pluck('nama_pohon', 'jenis_pohon_id')->toArray();

        // Config Mapping Field
        $fieldMap = [];
        $rawCategories = config('indicators.categories', []); // Ensure default to empty array
        foreach ($rawCategories as $cat) {
            foreach ($cat['activities'] as $act) {
                foreach ($act['fields'] as $key => $conf) {
                    $fieldMap[$key] = [
                        'label' => $conf['label'] ?? ucwords(str_replace('_', ' ', $key)),
                        'satuan' => $conf['satuan'] ?? ''
                    ];
                }
            }
        }

        // Fetch Data Progres
        $dataProgres = ProgresReklamasi::with([
                'jenisAktivitas.kategoriAktivitas',
                'fieldValues.fieldDefinition',
                'dokumentasi'
            ])
            ->where('plot_id', $plot->plot_id)
            ->orderBy('tanggal', 'desc')
            ->get();

        // Create Spreadsheet
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Calibri');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(11);

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Progres ' . substr($plot->nama_plot, 0, 20));

        // --- TITLE (Row 1) ---
        $lahanName = $plot->lahan ? strtoupper($plot->lahan->nama_lahan) : 'LAHAN';
        $plotName = strtoupper($plot->nama_plot);
        $titleText = "DATA PROGRES REKLAMASI - {$lahanName} (BLOK {$plotName})";

        $sheet->setCellValue('A1', $titleText);
        $sheet->mergeCells('A1:G1');
        
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension('1')->setRowHeight(30);

        // --- HEADER (Row 2) ---
        $headers = ['No', 'Tanggal', 'Kategori', 'Aktivitas', 'Detail Data (Input)', 'Catatan', 'Dokumentasi (Link)'];
        $sheet->fromArray($headers, null, 'A2');

        $sheet->getStyle('A2:G2')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '44546A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);
        $sheet->getRowDimension('2')->setRowHeight(25);

        // --- EMPTY STATE ---
        if ($dataProgres->isEmpty()) {
            $sheet->mergeCells('A3:G5');
            $sheet->setCellValue('A3', "BELUM ADA DATA PROGRES");
            
            $sheet->getStyle('A3')->applyFromArray([
                'font' => ['italic' => true, 'color' => ['rgb' => '777777'], 'size' => 12],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']],
                'borders' => ['outline' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]]
            ]);
            
            // Widths
            foreach (range('A', 'D') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);
            $sheet->getColumnDimension('E')->setWidth(40);
            $sheet->getColumnDimension('F')->setWidth(20);
            $sheet->getColumnDimension('G')->setWidth(30);

            return $this->outputStream($spreadsheet, $plot->nama_plot);
        }

        // --- DATA CONTENT (Row 3 onwards) ---
        $row = 3;
        $no = 1;

        foreach ($dataProgres as $progres) {
            // Format Detail
            $detailString = [];
            foreach ($progres->fieldValues as $fv) {
                $fieldKey = $fv->fieldDefinition->field_key;
                $label = $fv->fieldDefinition->label ?? ($fieldMap[$fieldKey]['label'] ?? $fieldKey);
                $satuan = $fv->fieldDefinition->unit ?? ($fieldMap[$fieldKey]['satuan'] ?? '');
                $value = $fv->field_value;
                if ($fv->fieldDefinition->field_type === 'dynamic_select') {
                    // Map pohon ID to name
                    $value = $pohonMap[$value] ?? $value;
                } elseif ($fieldKey === 'metode_sampling') {
                    $value = ucwords(str_replace('_', ' ', $value));
                }
                $detailString[] = "- {$label}: {$value} {$satuan}";
            }
            $formattedDetails = implode("\n", $detailString);
            if (!empty($formattedDetails)) $formattedDetails .= "\n"; 

            // Format Dokumentasi
            $docLinks = [];
            if ($progres->dokumentasi->count() > 0) {
                foreach ($progres->dokumentasi as $index => $doc) {
                    $url = asset('storage/' . $doc->image_path); 
                    $docLinks[] = "Foto " . ($index + 1) . ": " . $url;
                }
            }
            $docString = implode("\n", $docLinks);
            if (!empty($docString)) $docString .= "\n";

            $catatan = $progres->catatan;
            if (!empty($catatan)) $catatan .= "\n";

            // Set Values
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $progres->tanggal ? $progres->tanggal->format('d-m-Y') : '-');
            $sheet->setCellValue('C' . $row, $progres->jenisAktivitas->kategoriAktivitas->label ?? '-');
            $sheet->setCellValue('D' . $row, $progres->jenisAktivitas->label ?? '-');
            $sheet->setCellValue('E' . $row, $formattedDetails);
            $sheet->setCellValue('F' . $row, $catatan ?? '-');
            $sheet->setCellValue('G' . $row, $docString);

            // Styling
            $sheet->getStyle('A' . $row . ':D' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_TOP);
            $sheet->getStyle('E' . $row . ':G' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_TOP)->setWrapText(true)->setIndent(1);

            $row++;
        }

        // Finishing Border
        $lastRow = $row - 1;
        if ($lastRow >= 3) {
            $sheet->getStyle('A3:G' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }
        
        // Widths
        foreach (range('A', 'D') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);
        $sheet->getColumnDimension('E')->setWidth(55); 
        $sheet->getColumnDimension('F')->setWidth(30);
        $sheet->getColumnDimension('G')->setWidth(50);

        return $this->outputStream($spreadsheet, $plot->nama_plot);
    }

    private function outputStream($spreadsheet, $plotName) {
        $fileName = 'Progres_Reklamasi_' . str_replace(' ', '_', $plotName) . '_' . date('Ymd_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        
        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment;filename="' . $fileName . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}