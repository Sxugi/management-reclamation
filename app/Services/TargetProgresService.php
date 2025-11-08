<?php

namespace App\Services;

use App\Models\Plot;
use App\Models\TargetProgresReklamasi;
use App\Models\IndikatorProgresReklamasi;
use App\Models\ActivityLog;
use App\Models\ProgresReklamasi;
use App\Models\PlotProgres;
use App\Models\ProgresSnapshot;
use App\Services\ProgresReklamasiService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TargetProgresService
{
    /**
     * Get all active indicators for target selection.
     */
    public function getTargetIndicators(): array
    {
        return IndikatorProgresReklamasi::active()->get()->keyBy('nama')->toArray();
    }

    /**
     * Get existing targets for a plot.
     */
    public function getExistingTargets(Plot $plot): array
    {
        return $plot->target()
            ->with('indikator')
            ->get()
            ->mapWithKeys(fn($target) => [$target->indikator->nama => $target->value])
            ->toArray();
    }

    /**
     * Sync targets for a plot.
     * Will create/update selected targets, and delete unselected targets if allowed.
     */
    public function syncTargets(Plot $plot, array $selectedKeys, array $values): array
    {
        $hadExisting = $plot->target()->exists();

        try {
            DB::transaction(function () use ($plot, $selectedKeys, $values) {
                $this->processTargets($plot, $selectedKeys, $values);
            });

            return ['had_existing' => $hadExisting];
        } catch (\Exception $e) {
            Log::error('Failed to sync targets', [
                'plot_id' => $plot->plot_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Process selected targets: create/update and remove unselected.
     */
    protected function processTargets(Plot $plot, array $selectedKeys, array $values): void
    {
        $indikators = IndikatorProgresReklamasi::whereIn('nama', $selectedKeys)->get()->keyBy('nama');
        $selectedIds = [];
        $hasTargetChanges = false;

        foreach ($selectedKeys as $key) {
            if (!isset($indikators[$key])) {
                Log::warning("Indicator not found for key: {$key}");
                continue;
            }

            $indicator = $indikators[$key];
            $selectedIds[] = $indicator->indikator_id;
            
            $targetChanged = $this->updateOrCreateTarget($plot, $indicator, $values[$key]);
            if ($targetChanged) {
                $hasTargetChanges = true;
            }
        }

        // Remove targets that are not selected anymore
        $deletedCount = $this->removeUnselectedTargets($plot, $selectedIds);
        if ($deletedCount > 0) {
            $hasTargetChanges = true;
        }

        // Only recalculate if there were actual target changes
        if ($hasTargetChanges) {
            $this->recalculateProgressAfterTargetChange($plot);
        }
    }

    /**
     * Create or update a target for a plot and indicator.
     * Also, add activity log and update plot progress snapshot.
     */
    protected function updateOrCreateTarget(Plot $plot, IndikatorProgresReklamasi $indicator, $value): bool
    {
        $target = TargetProgresReklamasi::where([
            'plot_id' => $plot->plot_id,
            'indikator_id' => $indicator->indikator_id,
        ])->first();

        $hasChanged = false;

        if (!$target) {
            // Create new target
            $target = TargetProgresReklamasi::create([
                'plot_id' => $plot->plot_id,
                'indikator_id' => $indicator->indikator_id,
                'value' => (float) $value,
            ]);
            $description = $this->generateTargetDescription('created', $indicator);
            ActivityLog::createLog(
                $plot->plot_id, 
                'created', 
                'target', 
                $target->target_id, 
                $description
            );

            $hasChanged = true;
        } else {
            // Only update & log if value changes
            if ($target->value != (float) $value) {
                $target->value = (float) $value;
                $target->save();

                $description = $this->generateTargetDescription('updated', $indicator);
                ActivityLog::createLog(
                    $plot->plot_id, 
                    'updated', 
                    'target', 
                    $target->target_id, 
                    $description
                );

                $hasChanged = true;
            }
        }
        return $hasChanged;
    }

    /**
     * Remove targets that are not selected anymore.
     * Prevent deletion if progres exists.
     */
    protected function removeUnselectedTargets(Plot $plot, array $selectedIds): int
    {
        // Get targets to delete (not in selected indicators)
        $query = TargetProgresReklamasi::where('plot_id', $plot->plot_id);

        if (!empty($selectedIds)) {
            $query->whereNotIn('indikator_id', $selectedIds);
        }

        $targetsToDelete = $query->with('indikator')->get();

        // Log and delete targets
        foreach ($targetsToDelete as $target) {
            $description = $this->generateTargetDescription('deleted', $target->indikator);
            
            ActivityLog::createLog(
                $plot->plot_id,
                'deleted',
                'target',
                $target->target_id,
                $description
            );
        }

        $deletedCount = $targetsToDelete->count();
        if ($deletedCount > 0) {
            TargetProgresReklamasi::whereIn('target_id', $targetsToDelete->pluck('target_id'))->delete();
        }

        return $deletedCount;
    }

    /**
     * Recalculate progress after target changes without creating new snapshots
     * Only updates current progress and recalculates existing snapshots
     */
    protected function recalculateProgressAfterTargetChange(Plot $plot): void
    {
        // Update current overall progress
        $currentPercent = ProgresReklamasiService::calculateOverallProgress($plot);
        PlotProgres::updateOrCreate(
            ['plot_id' => $plot->plot_id],
            ['percent' => $currentPercent]
        );

        // Recalculate ALL existing snapshots with new targets
        $existingSnapshots = ProgresSnapshot::where('plot_id', $plot->plot_id)
            ->orderBy('date', 'asc')
            ->get();

        foreach ($existingSnapshots as $snapshot) {
            $recalculatedPercent = ProgresReklamasiService::calculateProgressAtDate($plot, $snapshot->date);
            $snapshot->update(['percent' => $recalculatedPercent]);
        }
    }

    /**
     * Check if there is any progres for given plot and indicator.
     */
    public static function hasProgres(int $plotId, int $indikatorId): bool
    {
        return ProgresReklamasi::where('plot_id', $plotId)
            ->where('indikator_id', $indikatorId)
            ->exists();
    }

    /**
     * Generate description for activity log based on action and indicator.
     */
    public static function generateTargetDescription($action, $indicator)
    {
        $label = $indicator->label ?? $indicator->nama ?? 'Unknown Indicator';

        $actions = [
            'created' => "Menambahkan Target untuk Indikator {$label}",
            'updated' => "Mengubah Target untuk Indikator {$label}",
            'deleted' => "Menghapus Target untuk Indikator {$label}",
        ];

        return $actions[$action] ?? "Aktivitas Target tidak diketahui";
    }
}