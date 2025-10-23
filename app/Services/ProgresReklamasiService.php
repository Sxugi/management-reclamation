<?php

namespace App\Services;

use App\Models\Plot;
use App\Models\ProgresReklamasi;
use App\Models\PlotProgres;
use App\Models\ProgresSnapshot;
use App\Models\FieldDefinition;
use App\Models\ProgresFieldValue;
use App\Models\ProgresDokumentasi;
use App\Models\IndikatorProgresReklamasi;
use App\Models\TargetProgresReklamasi;
use App\Models\KategoriAktivitas;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProgresReklamasiService
{
    protected static array $allowedSorts = [
        'tanggal',
        'kategori',
    ];

    public static function getKategoriAktivitasOptions(): array
    {
        $kategori = KategoriAktivitas::all();
        return $kategori->pluck('label', 'kategori_id')->toArray();
    }

    public static function getFilteredData(Request $request, Plot $plot)
    {
        $query = ProgresReklamasi::with([
            'jenisAktivitas.kategoriAktivitas',
            'dokumentasi',
            'fieldValues.fieldDefinition',
        ])
        ->where('plot_id', $plot->plot_id);

        // Date range filtering
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

        // Activity type filtering
        if ($request->filled('category')) {
            $label = $request->category;
            $kategoriId = KategoriAktivitas::where('label', $label)->value('kategori_id');
            if ($kategoriId) {
                $query->whereHas('jenisAktivitas', function($q) use ($kategoriId) {
                    $q->where('kategori_id', $kategoriId);
                });
            }
        }

        // Documentation filtering
        if ($request->filled('hasDokumentasi')) {
            if ($request->hasDokumentasi === 'true') {
                $query->whereHas('dokumentasi');
            } elseif ($request->hasDokumentasi === 'false') {
                $query->whereDoesntHave('dokumentasi');
            }
        }

        // Sorting
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

    public static function hasFilter(Request $request): bool
    {
        return $request->filled('date') ||
               $request->filled('startDate') ||
               $request->filled('endDate') ||
               $request->filled('category') ||
               $request->filled('hasDokumentasi');
    }

    public static function calculateProgressSummary(Plot $plot)
    {
        $indicatorConfig = config('indicators.targets');
        $indicatorKeys = array_keys($indicatorConfig);

        $summary = [];
        foreach ($indicatorKeys as $key) {
            $summary[$key] = [
                'label' => $indicatorConfig[$key]['label'],
                'satuan' => $indicatorConfig[$key]['satuan'],
                'description' => $indicatorConfig[$key]['description'] ?? null,
                'total' => null,
                'records_count' => 0,
                'summary_type' => $indicatorConfig[$key]['summary_type'] ?? 'sum',
            ];
        }

        $progressList = ProgresReklamasi::with(['fieldValues.fieldDefinition'])
            ->where('plot_id', $plot->plot_id)
            ->get();

        foreach ($progressList as $progress) {
            foreach ($progress->fieldValues as $fieldValue) {
                $fieldDef = $fieldValue->fieldDefinition;
                if (!$fieldDef || !$fieldDef->indicator_key) continue;

                $key = $fieldDef->indicator_key;
                if (!isset($summary[$key])) continue;

                $value = (float) $fieldValue->field_value;
                $summaryType = $summary[$key]['summary_type'];

                if ($summaryType === 'max') {
                    if ($summary[$key]['total'] === null || $value > $summary[$key]['total']) {
                        $summary[$key]['total'] = $value;
                    }
                } elseif ($summaryType === 'sum') {
                    $summary[$key]['total'] = ($summary[$key]['total'] ?? 0) + $value;
                } elseif ($summaryType === 'count') {
                    $summary[$key]['total'] = ($summary[$key]['total'] ?? 0) + 1;
                }

                $summary[$key]['records_count']++;
            }
        }

        foreach ($summary as $key => &$item) {
            if ($item['total'] === null) {
                $item['total'] = 0;
            }
        }

        return $summary;
    }

    public static function getIndikatorIdToKeyMap()
    {
        return IndikatorProgresReklamasi::pluck('nama', 'indikator_id')->toArray();
    }

    public static function calculateOverallProgress(Plot $plot)
    {
        $indikatorIdToKey = self::getIndikatorIdToKeyMap();
        $targets = TargetProgresReklamasi::where('plot_id', $plot->plot_id)->get();
        $summary = self::calculateProgressSummary($plot);
        $progressList = [];

        foreach ($targets as $target) {
            $indikatorId = $target->indikator_id;
            $targetValue = $target->value;

            $key = $indikatorIdToKey[$indikatorId] ?? null;
            if (!$key) continue;

            $actual = $summary[$key]['total'] ?? 0;

            if ($targetValue > 0) {
                $progressList[] = min($actual / $targetValue, 1);
            }
        }

        $overallProgress = count($progressList) > 0
            ? round(array_sum($progressList) / count($progressList) * 100, 2)
            : 0;

        return $overallProgress;
    }

    public static function updatePlotProgress(Plot $plot, $date = null): void
    {
        $percent = self::calculateOverallProgress($plot);

        PlotProgres::updateOrCreate(
            ['plot_id' => $plot->plot_id],
            ['percent' => $percent]
        );

        ProgresSnapshot::updateOrCreate(
            [
                'plot_id' => $plot->plot_id,
                'date'    => $date,
            ],
            [
                'percent' => $percent,
            ]
        );
    }

    public static function getProgresDelta($plot): array
    {
        $snapshots = ProgresSnapshot::where('plot_id', $plot->plot_id)
            ->orderBy('date', 'desc')
            ->limit(2)
            ->get();

        $latestPercent  = $snapshots->first() ? $snapshots->first()->percent : 0;
        $previousPercent  = $snapshots->count() > 1 ? $snapshots->get(1)->percent : 0;

        return [
            'latestPercent' => $latestPercent,
            'previousPercent' => $previousPercent,
            'delta' => $latestPercent - $previousPercent,
            'isFirstData' => $snapshots->count() < 2,
        ];
    }

    public static function generateProgresDescription($action, $progres)
    {
        $indikator = $progres->indikator->label ?? $progres->indikator->nama ?? 'Unknown Indicator';
        $jenisAktivitas = $progres->jenisAktivitas->label ?? 'Unknown Activity';

        $actions = [
            'created' => "Menambahkan Progres untuk Kategori {$indikator} dengan Aktivitas {$jenisAktivitas}",
            'updated' => "Mengubah Progres untuk Kategori {$indikator} dengan Aktivitas {$jenisAktivitas}",
            'deleted' => "Menghapus Progres untuk Kategori {$indikator} dengan Aktivitas {$jenisAktivitas}",
        ];

        return $actions[$action] ?? "Aktivitas Progres tidak diketahui";
    }

    /**
     * Create new progress record with dynamic fields and documentation
     */
    public function create(Plot $plot, array $requestData): ProgresReklamasi
    {
        return DB::transaction(function() use ($plot, $requestData) {
            try {
                // Get main value and indicator for the progress
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

                // Save dynamic field values
                $this->saveDynamicFieldValues($progress->progres_id, $requestData['jenis_aktivitas_id'], $requestData);

                // Save documentation files
                $this->saveDocumentationFiles($progress->progres_id, $requestData);
                
                // Log activity for audit trail
                $description = $this->generateProgresDescription('created', $progress);
                ActivityLog::createLog(
                    $plot->plot_id, 
                    'created', 
                    'progres', 
                    $progress->progres_id, 
                    $description
                );

                self::updatePlotProgress($plot, $requestData['tanggal']);

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
     */
    public function update(ProgresReklamasi $progress, array $requestData): ProgresReklamasi
    {
        $oldActivityId = $progress->jenis_aktivitas_id;
        $newActivityId = $requestData['jenis_aktivitas_id'];
        $isCategoryChanged = $oldActivityId != $newActivityId;

        return DB::transaction(function() use ($progress, $requestData, $oldActivityId, $newActivityId, $isCategoryChanged) {
            try {
                // Clean up old field values if category changed
                if ($isCategoryChanged) {
                    $this->cleanupOldFieldValues($progress->progres_id, $oldActivityId, $newActivityId);
                }

                // Get main value data for new category
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

                // Handle removed documentation files
                $this->handleRemovedDocumentationFiles($progress->progres_id, $requestData);

                // Save new documentation files
                $this->saveDocumentationFiles($progress->progres_id, $requestData);

                // Log activity for audit trail
                $description = $this->generateProgresDescription('updated', $progress);
                ActivityLog::createLog(
                    $plot->plot_id,
                    'updated',
                    'progres',
                    $progress->progres_id,
                    $description
                );

                self::updatePlotProgress($progress->plot, $requestData['tanggal']);

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
     */
    public function delete(ProgresReklamasi $progress): void
    {
        DB::transaction(function() use ($progress) {
            $plotId = $progress->plot_id;
            $progressId = $progress->progres_id;

            try {
                $deletedFilesCount = 0;

                // Delete documentation files from storage and database
                foreach ($progress->dokumentasi as $documentation) {
                    if ($documentation->image_path && Storage::disk('public')->exists($documentation->image_path)) {
                        Storage::disk('public')->delete($documentation->image_path);
                        $deletedFilesCount++;
                    }
                    $documentation->delete();
                }

                // Delete field values
                $progress->fieldValues()->delete();

                // Delete main progress record
                $progress->delete();

                // Log activity for audit trail
                $description = $this->generateProgresDescription('deleted', $progress);
                ActivityLog::createLog(
                    $plot->plot_id, 
                    'deleted', 
                    'progres', 
                    $progress->progres_id, 
                    $description
                );

                self::updatePlotProgress($progress->plot, $progress->tanggal);
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
     * Extract main value and indicator ID from request data
     */
    private function extractMainValueData(int $activityId, array $requestData): array
    {
        try {
            // Try database-driven approach: find field with indicator_key
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

            // Fallback: use first numeric value found
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

            // Final fallback: zero value
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
     * Find indicator ID by name from indicators table
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
     * Save dynamic field values for new progress record
     */
    private function saveDynamicFieldValues(int $progressId, int $activityId, array $requestData): void
    {
        try {
            $fieldDefinitions = FieldDefinition::where('jenis_aktivitas_id', $activityId)->get();
            $savedCount = 0;

            foreach ($fieldDefinitions as $fieldDef) {
                if (!array_key_exists($fieldDef->field_key, $requestData)) continue;

                $fieldValue = $requestData[$fieldDef->field_key];
                
                // Save only non-empty values
                if ($fieldValue !== null && $fieldValue !== '' && $fieldValue !== '0') {
                    ProgresFieldValue::create([
                        'progres_id' => $progressId,
                        'field_definition_id' => $fieldDef->field_definition_id,
                        'field_value' => $fieldValue,
                    ]);
                    $savedCount++;
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
     * Update dynamic field values for existing progress record
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
                    // Update or create field value
                    ProgresFieldValue::updateOrCreate($whereConditions, [
                        'field_value' => $fieldValue,
                    ]);
                } else {
                    // Delete empty field values
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
     * Save documentation files to storage and database
     */
    private function saveDocumentationFiles(int $progressId, array $requestData): void
    {
        if (empty($requestData['dokumentasi'])) return;

        try {
            $savedCount = 0;

            foreach ($requestData['dokumentasi'] as $uploadedFile) {
                if (!$uploadedFile || !$uploadedFile->isValid()) continue;

                try {
                    $filePath = $uploadedFile->store('progres_dokumentasi', 'public');
                    
                    ProgresDokumentasi::create([
                        'progres_id' => $progressId,
                        'image_path' => $filePath,
                    ]);
                    $savedCount++;

                } catch (\Exception $fileException) {
                    Log::error("Error saving individual file", [
                        'progress_id' => $progressId,
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
     * Handle removal of documentation files based on frontend data
     */
    private function handleRemovedDocumentationFiles(int $progressId, array $requestData): void
    {
        if (empty($requestData['removed_files'])) return;

        try {
            // Parse JSON data from frontend
            $removedFilesData = is_string($requestData['removed_files']) 
                ? json_decode($requestData['removed_files'], true) 
                : $requestData['removed_files'];

            if (!is_array($removedFilesData) || empty($removedFilesData['dokumentasi'])) return;

            $removedIndices = $removedFilesData['dokumentasi'];
            
            // Get existing documentation ordered by ID
            $existingDocuments = ProgresDokumentasi::where('progres_id', $progressId)
                ->orderBy('progres_dokumentasi_id')
                ->get();

            $deletedCount = 0;

            foreach ($removedIndices as $index) {
                if (!isset($existingDocuments[$index])) continue;

                $document = $existingDocuments[$index];
                
                // Delete file from storage
                if ($document->image_path && Storage::disk('public')->exists($document->image_path)) {
                    Storage::disk('public')->delete($document->image_path);
                }

                // Delete database record
                $document->delete();
                $deletedCount++;
            }
        } catch (\Exception $e) {
            Log::error("Error handling removed documentation files", [
                'progress_id' => $progressId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Clean up old field values when activity category changes
     */
    private function cleanupOldFieldValues(int $progressId, int $oldActivityId, int $newActivityId): void
    {
        try {
            // Get field IDs for old category
            $oldFieldIds = FieldDefinition::where('jenis_aktivitas_id', $oldActivityId)
                ->pluck('field_definition_id')
                ->toArray();

            $deletedByOldCategory = 0;
            if (!empty($oldFieldIds)) {
                $deletedByOldCategory = ProgresFieldValue::where('progres_id', $progressId)
                    ->whereIn('field_definition_id', $oldFieldIds)
                    ->delete();
            }

            // Get field IDs for new category
            $newFieldIds = FieldDefinition::where('jenis_aktivitas_id', $newActivityId)
                ->pluck('field_definition_id')
                ->toArray();

            // Clean up any orphaned field values that don't belong to new category
            $deletedOrphaned = 0;
            if (!empty($newFieldIds)) {
                $deletedOrphaned = ProgresFieldValue::where('progres_id', $progressId)
                    ->whereNotIn('field_definition_id', $newFieldIds)
                    ->delete();
            } else {
                // If no new fields, delete all field values
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
}