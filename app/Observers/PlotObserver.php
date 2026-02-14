<?php

namespace App\Observers;

use App\Models\Plot;
use App\Models\Lahan;
use Illuminate\Support\Facades\Log;

class PlotObserver
{
    /**
     * Handle the Plot "created" event.
     */
    public function created(Plot $plot): void
    {
        $this->updateLuasLahan($plot->lahan_id);
    }

    /**
     * Handle the Plot "updated" event.
     */
    public function updated(Plot $plot): void
    {
        // Update luas lahan for the current lahan_id
        $this->updateLuasLahan($plot->lahan_id);
        
        // If lahan_id has changed, update the original lahan as well
        if ($plot->isDirty('lahan_id')) {
            $originalLahanId = $plot->getOriginal('lahan_id');
            if ($originalLahanId) {
                $this->updateLuasLahan($originalLahanId);
            }
        }
    }

    /**
     * Handle the Plot "deleted" event.
     */
    public function deleted(Plot $plot): void
    {
        $this->updateLuasLahan($plot->lahan_id);
    }

    /**
     * Update luas lahan dengan smart baseline logic
     * 
     * Business Rules:
     * 1. luas_lahan_original = immutable baseline (never changes after create)
     * 2. effective_baseline = MAX(original, current) - respects user manual edits
     * 3. If total_plot > baseline → expand luas_lahan to total_plot
     * 4. If total_plot <= baseline → restore luas_lahan to baseline
     * 
     * @param int|null $lahanId
     * @return void
     */
    private function updateLuasLahan(?int $lahanId): void
    {
        if (!$lahanId) {
            return;
        }

        $lahan = Lahan::find($lahanId);
        
        // Guard: Check if lahan exists
        if (!$lahan) {
            return;
        }

        // Initialize original if null (backward compatibility for existing data)
        if (is_null($lahan->luas_lahan_original)) {
            $lahan->luas_lahan_original = $lahan->luas_lahan;
        }

        $currentLuas = $lahan->luas_lahan;
        $luasOriginal = $lahan->luas_lahan_original;
        $totalPlot = (float) $lahan->plots()->sum('luas_area');

        // Smart baseline: use the larger value to respect user manual edits
        $effectiveBaseline = max($luasOriginal, $currentLuas);

        // Calculate new luas based on business rules
        $newLuas = $this->calculateNewLuasLahan($totalPlot, $effectiveBaseline);

        // Only update if there's a change
        if ($newLuas != $currentLuas) {
            $lahan->luas_lahan = $newLuas;
            
            // Use saveQuietly() to prevent infinite loops
            $lahan->saveQuietly();

            // Log for audit trail
            $this->logLuasLahanChange(
                $lahan, 
                $currentLuas, 
                $newLuas, 
                $totalPlot,
                $effectiveBaseline
            );
        }
    }

    /**
     * Calculate new luas lahan based on business rules
     * 
     * @param float $totalPlot Total luas area dari semua plot
     * @param float $baseline Effective baseline (max of original and current)
     * @return float New luas lahan value
     */
    private function calculateNewLuasLahan(float $totalPlot, float $baseline): float
    {
        // Rule 1: If total plot exceeds baseline, expand to accommodate all plots
        if ($totalPlot > $baseline) {
            return $totalPlot;
        }
        
        // Rule 2: If total plot is within baseline, restore to baseline
        return $baseline;
    }

    /**
     * Log luas lahan changes for audit trail
     * 
     * @param Lahan $lahan
     * @param float $oldLuas
     * @param float $newLuas
     * @param float $totalPlot
     * @param float $baseline
     * @return void
     */
    private function logLuasLahanChange(
        Lahan $lahan, 
        float $oldLuas, 
        float $newLuas, 
        float $totalPlot,
        float $baseline
    ): void {
        $action = $newLuas > $oldLuas ? 'expanded' : 'restored_to_baseline';
        
        Log::info('Luas lahan auto-adjusted by PlotObserver', [
            'lahan_id' => $lahan->lahan_id,
            'nama_lahan' => $lahan->nama_lahan,
            'luas_lahan_original' => $lahan->luas_lahan_original,
            'luas_lahan_before' => $oldLuas,
            'luas_lahan_after' => $newLuas,
            'total_luas_plot' => $totalPlot,
            'effective_baseline' => $baseline,
            'action' => $action,
            'timestamp' => now()->toDateTimeString(),
        ]);
    }
}