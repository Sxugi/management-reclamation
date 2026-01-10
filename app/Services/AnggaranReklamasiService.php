<?php

namespace App\Services;

use App\Models\AnggaranReklamasi;
use App\Models\KategoriAnggaran;
use Illuminate\Support\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Lahan; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Exception;

class AnggaranReklamasiService
{
    // Constants for better readability
    private const QUARTER_COMPLETE_THRESHOLD = 3;
    private const MAX_YEARS_PER_QUARTER = 2;
    private const Q1_BLOCK_INCOMPLETE_SAME_YEAR_ONLY = false;
    private const Q1_ALLOW_FORCE_NEW = false;

    private const QUARTER_OFFSETS = [
        'Q1' => -3,
        'Q2' => 0,
        'Q3' => 3,
        'Q4' => 6
    ];

    private const MONTH_NAMES = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    public function getByJenis($lahanId, $jenis, $perPage = 12)
    {
        try {
            $query = AnggaranReklamasi::where('lahan_id', $lahanId)
                ->where('jenis_anggaran', $jenis)
                ->join('kategori_anggaran', 'anggaran_reklamasi.kategori_anggaran_id', '=', 'kategori_anggaran.kategori_anggaran_id')
                ->selectRaw("
                    tahun, 
                    bulan, 
                    quarter, 
                    quarter_label,
                    SUM(nominal) as nominal,
                    STRING_AGG(kategori_anggaran.nama_kategori, ', ') as list_kategori,
                    COUNT(*) as jumlah_kategori,
                    json_agg(
                        json_build_object(
                            'id', anggaran_reklamasi_id,
                            'kategori_id', anggaran_reklamasi.kategori_anggaran_id,
                            'kategori', kategori_anggaran.nama_kategori,
                            'nominal', nominal,
                            'tahun', tahun,
                            'bulan', bulan,
                            'quarter', quarter,
                            'created_at', anggaran_reklamasi.created_at, 
                            'updated_at', anggaran_reklamasi.updated_at
                        )
                    ) as items_json
                ")
                ->groupBy('tahun', 'bulan', 'quarter', 'quarter_label')
                ->orderBy('tahun', 'asc')
                ->orderBy('bulan', 'asc');

            return $query->paginate($perPage);
        } catch (QueryException $e) {
            \Log::error('Failed to fetch budget data by type', [
                'error' => $e->getMessage(),
                'lahan_id' => $lahanId,
                'jenis' => $jenis
            ]);
            throw new Exception('Gagal mengambil data anggaran. Silakan coba lagi.');
        }
    }

    public function generateRowspanMap($collection, ?string $sortColumn = null)
    {
        $items = method_exists($collection, 'items')
            ? collect($collection->items())
            : collect($collection ?? []);

        $isQuarterSort = $sortColumn === 'quarter';
        $isNominalSort = $sortColumn === 'nominal';

        $mapYear = [];
        $mapQuarter = [];

        foreach ($items as $item) {
            $mapYear[$item->tahun] = ($mapYear[$item->tahun] ?? 0) + 1;
            $ql = $item->quarter_label ?? $item->quarter;
            $mapQuarter[$ql] = ($mapQuarter[$ql] ?? 0) + 1;
        }

        $yearSegments = [];
        $quarterSegments = []; 
        if ($isQuarterSort) {
            $prevYear = null;
            $prevQuarter = null;
            $yearAnchor = null;
            $quarterAnchor = null;

            foreach ($items->values() as $idx => $row) {
                $qLabel = $row->quarter_label ?? $row->quarter;

                // Year contiguous grouping
                if ($row->tahun !== $prevYear) {
                    $yearAnchor = $idx;
                    $yearSegments[$yearAnchor] = 1;
                    $prevYear = $row->tahun;
                } else {
                    $yearSegments[$yearAnchor]++;
                }

                // Quarter contiguous grouping (independent of year)
                if ($qLabel !== $prevQuarter) {
                    $quarterAnchor = $idx;
                    $quarterSegments[$quarterAnchor] = 1;
                    $prevQuarter = $qLabel;
                } else {
                    $quarterSegments[$quarterAnchor]++;
                }
            }
        }

        return [
            'mapYear'    => $mapYear,
            'mapQuarter' => $mapQuarter,

            'segments' => [
                'year'    => $yearSegments,
                'quarter' => $quarterSegments,
            ],

            'flags' => [
                'isQuarterSort'      => $isQuarterSort,
                'isNominalSort'      => $isNominalSort,
                'hideTotal'          => $isNominalSort,
                'useBasicRowspan'    => !$isNominalSort,
                'useContiguousQuarter' => $isQuarterSort,
            ],
        ];
    }

    public function getQuarterTotals(Request $request, Lahan $lahan, string $jenisAnggaran)
    {
        $query = AnggaranReklamasi::where('lahan_id', $lahan->lahan_id)
            ->where('jenis_anggaran', $jenisAnggaran)
            ->whereNotNull('quarter_label');

        if ($request->filled('startYear')) {
            $query->where('tahun', '>=', $request->startYear);
        }
        if ($request->filled('endYear')) {
            $query->where('tahun', '<=', $request->endYear);
        }
        if ($request->filled('startMonth')) {
            $query->where('bulan', '>=', $request->startMonth);
        }
        if ($request->filled('endMonth')) {
            $query->where('bulan', '<=', $request->endMonth);
        }
        if ($request->filled('kategori_anggaran')) {
            $query->where('kategori_anggaran_id', $request->kategori_anggaran);
        }
        if ($request->filled('quarter')) {
            $query->where('quarter', $request->quarter);
        }
        if ($request->filled('startQuarter') && $request->filled('endQuarter')) {
            $quarters = ['Q1', 'Q2', 'Q3', 'Q4'];
            $startIdx = array_search($request->startQuarter, $quarters);
            $endIdx = array_search($request->endQuarter, $quarters);
            if ($startIdx !== false && $endIdx !== false) {
                if ($startIdx > $endIdx) {
                    [$startIdx, $endIdx] = [$endIdx, $startIdx];
                }
                $quarterRange = array_slice($quarters, $startIdx, $endIdx - $startIdx + 1);
                $query->whereIn('quarter', $quarterRange);
            }
        }
        if ($request->filled('minNominal')) {
            $query->where('nominal', '>=', $request->minNominal);
        }
        if ($request->filled('maxNominal')) {
            $query->where('nominal', '<=', $request->maxNominal);
        }

        return $query
            ->groupBy('quarter_label')
            ->selectRaw('quarter_label, SUM(nominal) as total, COUNT(*) as month_count')
            ->orderBy('quarter_label')
            ->get();
    }

    public function isDuplicate($quarter, $tahun, $bulan, $lahanId, $jenisAnggaran, $kategoriAnggaranId, $excludeId = null)
    {
        $query = AnggaranReklamasi::query()
            ->where('anggaran_reklamasi.lahan_id', $lahanId)
            ->where('anggaran_reklamasi.quarter', $quarter)
            ->where('anggaran_reklamasi.tahun', $tahun)
            ->where('anggaran_reklamasi.bulan', $bulan) 
            ->where('anggaran_reklamasi.jenis_anggaran', $jenisAnggaran)
            ->where('anggaran_reklamasi.kategori_anggaran_id', $kategoriAnggaranId);

        if ($excludeId) {
            $query->where('anggaran_reklamasi.anggaran_reklamasi_id', '!=', $excludeId);
        }

        return $query->exists();
    }

    public function generateQuarterLabel(
        $quarter,
        $tahun,
        $bulan,
        $lahanId,
        $jenisAnggaran,
        $kategoriId,
        $excludeId = null
    ) {
        try {
            $context = $this->prepareGenerationContext(
                $quarter, $tahun, $bulan, $lahanId, $jenisAnggaran, $kategoriId, $excludeId
            );

            $cycles = $this->determineCycles($context);
            $selectedCycle = $this->selectApplicableCycle($context, $cycles);
            $finalLabel = $this->generateFinalLabel($context, $selectedCycle);

            $this->applyLabelAndPropagate($context, $selectedCycle, $finalLabel);
            return $finalLabel;
        } catch (Exception $e) {
            \Log::error('Failed to generate quarter label', [
                'error' => $e->getMessage(),
                'quarter' => $quarter,
                'tahun' => $tahun
            ]);
            throw new Exception('Gagal membuat label quarter. Silakan coba lagi.');
        }
    }

    private function prepareGenerationContext($quarter, $tahun, $bulan, $lahanId, $jenisAnggaran, $kategoriId, $excludeId)
    {
        // Get quarter records for SAME category (for direct sequence)
        $quarterRecordsCategory = AnggaranReklamasi::where('quarter', $quarter)
            ->where('lahan_id', $lahanId)
            ->where('jenis_anggaran', $jenisAnggaran)
            ->where('kategori_anggaran_id', $kategoriId) // Pastikan kolom ini benar (biasanya _id)
            ->when($excludeId, fn($q) => $q->where('anggaran_reklamasi_id', '!=', $excludeId))
            ->orderBy('tahun')->orderBy('bulan')->get();

        // Get quarter records for ALL categories (for cross-year detection)
        $quarterRecordsAll = AnggaranReklamasi::where('quarter', $quarter)
            ->where('lahan_id', $lahanId)
            ->where('jenis_anggaran', $jenisAnggaran)
            ->when($excludeId, fn($q) => $q->where('anggaran_reklamasi_id', '!=', $excludeId))
            ->orderBy('tahun')->orderBy('bulan')->get();

        $relevantSequence = $this->pickRelevantQuarterSequence(
            $quarterRecordsCategory,
            (int)$tahun,
            (int)$bulan
        );

        // Include the new input in the sequence to check cross-year
        $allRecordsWithInput = $relevantSequence->push((object)[
            'tahun' => (int)$tahun,
            'bulan' => (int)$bulan,
            'quarter' => $quarter,
            'quarter_label' => null,
            'kategori_anggaran_id' => $kategoriId,
            'anggaran_reklamasi_id' => null 
        ]);

        $relevantYears = $allRecordsWithInput->pluck('tahun')
            ->map(fn($y) => (int)$y)
            ->unique()
            ->sort()
            ->values();

        // Also check globally for this quarter
        $globalYears = $quarterRecordsAll->pluck('tahun')
            ->push((int)$tahun)
            ->map(fn($y) => (int)$y)
            ->unique()
            ->sort()
            ->values();

        $allYears = $relevantYears->count() > 1 ? $relevantYears : $globalYears;

        return [
            'quarter' => $quarter,
            'tahun' => (int)$tahun,
            'bulan' => (int)$bulan,
            'lahanId' => $lahanId,
            'jenisAnggaran' => $jenisAnggaran,
            'kategoriAnggaran' => $kategoriId, // [FIX] Gunakan $kategoriId, bukan $kategoriAnggaran
            'quarterRecords' => $relevantSequence,
            'quarterRecordsAll' => $quarterRecordsAll,
            'yearsSet' => $allYears,
            'isMultiYear' => $allYears->count() > 1,
            'existingLabelExample' => $relevantSequence->first()?->quarter_label
        ];
    }

    private function determineCycles($context)
    {
        // Get existing cycles for this category
        $cycles = $this->getExistingCrossYearRanges(
            $context['lahanId'], 
            $context['jenisAnggaran'], 
            $context['kategoriAnggaran']
        );

        $yearInt = $context['tahun'];
        $quarter = $context['quarter'];
        
        $hasLocalCover = collect($cycles)->contains(
            fn($c) => $c['quarter'] === $quarter && $yearInt >= $c['start'] && $yearInt <= $c['end']
        );

        // Check for global cycle override
        if ($hasLocalCover) {
            $globalCyclesAll = $this->getExistingCrossYearRanges(
                $context['lahanId'], 
                $context['jenisAnggaran'], 
                null // ALL categories
            );
            $globalCycles = array_filter($globalCyclesAll, fn($c) => $c['quarter'] === $quarter);
            
            $newStartCycle = collect($globalCycles)->first(fn($c) => $c['start'] === $yearInt);
            
            if ($newStartCycle) {
                $localHasSameStart = collect($cycles)->contains(fn($c) => $c['start'] === $yearInt);
                
                if (!$localHasSameStart) {
                    $hasLocalCover = false;
                }
            }
        }

        // Add quarter-specific cycle adoption logic
        if (!$hasLocalCover) {  
            $globalCycles = $this->getExistingCrossYearRanges(
                $context['lahanId'], 
                $context['jenisAnggaran'], 
                null
            );
            $globalCycles = array_filter($globalCycles, fn($c) => $c['quarter'] === $quarter);
            
            if (!empty($globalCycles)) {
                $covering = collect($globalCycles)->first(
                    fn($c) => $yearInt >= $c['start'] && $yearInt <= $c['end']
                );
                
                if ($covering) {
                    $shouldAdopt = $this->shouldAdoptGlobalCycle($context, $covering);
                    
                    $alreadyExists = collect($cycles)->contains(
                        fn($c) => $c['start'] === $covering['start'] && $c['end'] === $covering['end']
                    );
                    
                    if ($shouldAdopt && !$alreadyExists) {
                        $cycles[] = $covering;
                    }
                }
            }
        }

        return $cycles;
    }

    // Determine if global cycle should be adopted
    private function shouldAdoptGlobalCycle($context, $globalCycle): bool
    {
        // Basic validation
        if (!isset($globalCycle['start']) || !isset($globalCycle['end']) || !isset($globalCycle['quarter'])) {
            return false;
        }

        $quarter = $context['quarter'];
        $yearInt = $context['tahun'];
        $isMultiYear = $context['isMultiYear'];
        
        // If current input is already multi-year, adopt the cycle
        if ($isMultiYear) {
            return true;
        }
        
        // UNIVERSAL LOGIC: Find which sequence this input would logically join
        $existingSameQuarterCategory = AnggaranReklamasi::where('quarter', $quarter)
            ->where('lahan_id', $context['lahanId'])
            ->where('jenis_anggaran', $context['jenisAnggaran'])
            ->where('kategori_anggaran_id', $context['kategoriAnggaran'])
            ->get();
            
        if ($existingSameQuarterCategory->isEmpty()) {
            return false; // No existing data, don't adopt any cycle
        }
        
        // Find which quarter sequence this input would logically join
        $relevantSequence = $this->findRelevantSequenceForAdoption($existingSameQuarterCategory, $yearInt, $quarter);
        
        // Check if this input would create cross-year with the RELEVANT sequence only
        $allYearsWithInput = $relevantSequence->pluck('tahun')
            ->push($yearInt)
            ->unique()
            ->sort()
            ->values();
            
        $wouldCreateCrossYear = $allYearsWithInput->count() > 1;
        
        if (!$wouldCreateCrossYear) {
            return false;
        }
        
        // Only adopt if the global cycle exactly matches the relevant sequence span
        if ($wouldCreateCrossYear) {
            $minYear = $allYearsWithInput->first();
            $maxYear = $allYearsWithInput->last();
            
            $exactMatch = ($globalCycle['start'] == $minYear && $globalCycle['end'] == $maxYear);
            
            return $exactMatch;
        }

        return false;
    }

    private function findRelevantSequenceForAdoption($existingQuarterRecords, $inputYear, $quarter)
    {
        if ($existingQuarterRecords->isEmpty()) {
            return collect();
        }
        
        // Find records in the SAME YEAR as input
        $sameYearRecords = $existingQuarterRecords->where('tahun', $inputYear);
        
        if ($sameYearRecords->isNotEmpty()) {
            // Input should join the same year sequence
            return $sameYearRecords;
        }
        
        // Find records that could logically connect to input year
        // Group by quarter_label to identify existing sequences
        $sequences = $existingQuarterRecords->groupBy('quarter_label');
        
        foreach ($sequences as $label => $records) {
            $sequenceYears = $records->pluck('tahun')->unique()->sort()->values();
            $minYear = $sequenceYears->min();
            $maxYear = $sequenceYears->max();
            
            // Check if input year would logically extend this sequence
            $isAdjacentYear = ($inputYear == $minYear - 1) || ($inputYear == $maxYear + 1);
            
            if ($isAdjacentYear) {  
                return $records;
            }
        }
        
        // No logical connection found - return empty (new sequence)
        return collect();
    }

    private function selectApplicableCycle($context, $cycles)
    {
        $quarter = $context['quarter'];
        $quarterCycles = array_filter($cycles, fn($c) => $c['quarter'] === $quarter);

        if ($context['isMultiYear']) {
            $candidate = [
                'quarter' => $quarter,
                'start' => $context['yearsSet']->first(), 
                'end' => $context['yearsSet']->last()
            ];
            
            $exists = collect($quarterCycles)->first(
                fn($c) => $c['start'] === $candidate['start'] && $c['end'] === $candidate['end']
                && $c['quarter'] === $quarter
            );
            
            if (!$exists) {
                $cycles[] = $candidate;
            }
            
            return $exists ?: $candidate;
        }

        return $this->chooseApplicableCycleForSingleYearQuarter(
            $context['quarter'],
            $context['yearsSet']->first(),
            $cycles,
            $context['quarterRecords']
        );
    }

    private function generateFinalLabel($context, $selectedCycle)
    {
        if ($selectedCycle) {
            $finalLabel = $this->decideLabelWithPolicyA(
                $context['quarter'],
                $context['yearsSet'],
                $selectedCycle,
                $context['quarterRecords']
            );
        } else {
            $finalLabel = $context['quarter'] . '-' . $context['yearsSet']->first();
        }

        // Preserve existing cross-year labels if different cycle
        if ($context['existingLabelExample'] && 
            $this->isDifferentCycleCrossYear($context['existingLabelExample'], $finalLabel)) {
            $finalLabel = $context['existingLabelExample'];
        }

        return $finalLabel;
    }

    private function applyLabelAndPropagate($context, $selectedCycle, $finalLabel)
    {
        // Apply to current category sequence
        $this->applyLabelToQuarterRecordsByIds(
            $context['quarterRecords']->pluck('anggaran_reklamasi_id')->all(),
            $finalLabel
        );

        // If this is cross-year, update ALL categories for this quarter
        if ($context['isMultiYear'] && str_contains($finalLabel, '/')) {
            $this->updateAllCategoriesForCrossYearQuarter(
                $context['quarter'],
                $context['lahanId'],
                $context['jenisAnggaran'],
                $finalLabel,
                $context['yearsSet']->toArray()
            );
        }

        if ($selectedCycle) {
            $cycles = $this->getExistingCrossYearRanges(
                $context['lahanId'], 
                $context['jenisAnggaran'], 
                $context['kategoriAnggaran']
            );

            $isNewCycle = !collect($cycles)->contains(
                fn($c) => $c['start'] === $selectedCycle['start'] && $c['end'] === $selectedCycle['end']
            );

            if ($isNewCycle) {
                $previousCycle = $this->findPreviousCycleSharingBoundary($selectedCycle, $cycles);
                $this->propagateCyclePolicyA($selectedCycle, $context['lahanId'], $context['jenisAnggaran'], $context['kategoriAnggaran']);
                $this->handleRollingUpgradeQ1($selectedCycle, $previousCycle, $context['lahanId'], $context['jenisAnggaran'], $context['kategoriAnggaran']);
            }
        }
    }

    // Update all categories for a cross-year quarter
    private function updateAllCategoriesForCrossYearQuarter($quarter, $lahanId, $jenisAnggaran, $finalLabel, array $yearsSet)
    {
        DB::transaction(function () use ($quarter, $lahanId, $jenisAnggaran, $finalLabel, $yearsSet) {
            // Scope to cycle years only
            $yearsSet = array_values(array_unique(array_map('intval', $yearsSet)));
            sort($yearsSet);
            $startYear = $yearsSet[0];
            $endYear   = $yearsSet[count($yearsSet) - 1];

            // Fetch only rows within the cycle's years
            $allQuarterRecords = AnggaranReklamasi::where('quarter', $quarter)
                ->where('lahan_id', $lahanId)
                ->where('jenis_anggaran', $jenisAnggaran)
                ->whereBetween('tahun', [$startYear, $endYear])
                ->get();

            if ($allQuarterRecords->isEmpty()) return;

            // Determine the 3 contiguous unique months that actually form the quarter
            $uniqueIdx = $allQuarterRecords
                ->map(fn($r) => $r->tahun * 12 + $r->bulan)
                ->unique()
                ->sort()
                ->values();

            // Find all contiguous windows of length 3
            $windows = [];
            for ($i = 0; $i <= $uniqueIdx->count() - 3; $i++) {
                $a = $uniqueIdx[$i]; $b = $uniqueIdx[$i+1]; $c = $uniqueIdx[$i+2];
                if ($b === $a + 1 && $c === $b + 1) {
                    $windows[] = [$a, $b, $c];
                }
            }

            // Prefer a window that spans both start and end years if cross-year
            $targetIdx = null;
            if (!empty($windows)) {
                foreach ($windows as $win) {
                    $minYear = intdiv(min($win) - 1, 12);
                    $maxYear = intdiv(max($win) - 1, 12);
                    if ($startYear !== $endYear) {
                        if ($minYear === $startYear && $maxYear === $endYear) {
                            $targetIdx = $win;
                            break;
                        }
                    }
                }
                // Fallback: use the last found contiguous window
                if (!$targetIdx) {
                    $targetIdx = end($windows);
                }
            } else {
                // If we have less than 3 unique months (still forming), update what we have
                $targetIdx = $uniqueIdx->all();
            }

            $idxSet = is_array($targetIdx) ? $targetIdx : (array)$targetIdx;

            foreach ($allQuarterRecords as $record) {
                $idx = $record->tahun * 12 + $record->bulan;

                // Update only within the chosen window; never override a different cross-year cycle
                if (in_array($idx, $idxSet, true)
                    && !$this->isDifferentCycleCrossYear($record->quarter_label, $finalLabel)
                    && $record->quarter_label !== $finalLabel) {
                    $oldLabel = $record->quarter_label;
                    $record->quarter_label = $finalLabel;
                    $record->save();
                    \Log::info('Cross-year label propagated (scoped)', [
                        'id' => $record->anggaran_reklamasi_id,
                        'from' => $oldLabel,
                        'to'   => $finalLabel,
                        'tahun' => $record->tahun,
                        'bulan' => $record->bulan,
                    ]);
                }
            }
        });
    }

    public function regenerateQuarterLabelAfterDeletion(
        string $quarter,
        int $lahanId,
        string $jenisAnggaran,
        ?string $oldLabel
    ): void
    {
        if (!$oldLabel) {
            return;
        }

        DB::transaction(function () use ($quarter, $lahanId, $jenisAnggaran, $oldLabel) {
            $remainingRecords = AnggaranReklamasi::where('quarter', $quarter)
                ->where('lahan_id', $lahanId)
                ->where('jenis_anggaran', $jenisAnggaran)
                ->where('quarter_label', $oldLabel)
                ->orderBy('tahun')
                ->orderBy('bulan')
                ->get();

            if ($remainingRecords->isEmpty()) {
                \Log::info('No remaining records after deletion', [
                    'quarter' => $quarter,
                    'old_label' => $oldLabel
                ]);
                return;
            }

            $years = $remainingRecords->pluck('tahun')->unique()->sort()->values();

            // Simple: If multi-year, try to find matching cycle, else fallback to simple format
            $cycle = $years->count() > 1 
                ? ['quarter' => $quarter, 'start' => $years->first(), 'end' => $years->last()]
                : null;

            // decideLabelWithPolicyA handles all logic
            $newLabel = $this->decideLabelWithPolicyA($quarter, $years, $cycle ?? [], $remainingRecords);

            if ($newLabel !== $oldLabel) {
                $remainingRecords->each(function($record) use ($newLabel) {
                    $record->update(['quarter_label' => $newLabel]);
                });

                \Log::info('Quarter label recalculated', [
                    'from' => $oldLabel,
                    'to' => $newLabel,
                    'records' => $remainingRecords->count()
                ]);
            }
        });
    }

    public function validateQuarterSequence(
        $quarter,
        $tahun,
        $bulan,
        $lahanId,
        $jenisAnggaran,
        $kategoriAnggaran,
        $excludeId = null
    ) {
        try {
            $inputLabel = self::MONTH_NAMES[$bulan] . " $tahun";

            // Get all quarters for this lahan and jenis (not per kategori for Q1 logic)
            $allQuartersGlobal = AnggaranReklamasi::where('lahan_id', $lahanId)
                ->where('jenis_anggaran', $jenisAnggaran)
                ->when($excludeId, fn($q) => $q->where('anggaran_reklamasi_id', '!=', $excludeId))
                ->orderBy('tahun')->orderBy('bulan')
                ->get();

            // Get quarters for this specific kategori
            $allQuartersLocal = $allQuartersGlobal
                ->where('kategori_anggaran_id', $kategoriAnggaran)
                ->values();

            // Check for duplicates
            $conflict = $allQuartersLocal->first(fn($r) => $r->tahun == $tahun && $r->bulan == $bulan && $r->quarter == $quarter);
            if ($conflict) {
                return [
                    'valid' => false,
                    'message' => "Bulan $inputLabel sudah digunakan untuk {$conflict->quarter}. Silakan pilih bulan lain."
                ];
            }

            // Check if Q1 exists globally (across all categories)
            $hasQ1Global = $allQuartersGlobal->where('quarter', 'Q1')->where('tahun', $tahun)->isNotEmpty();

            if (!$hasQ1Global && $quarter !== 'Q1') {
                return [
                    'valid' => false,
                    'message' => "Anda harus membuat Quarter Q1 terlebih dahulu sebelum membuat $quarter pada tahun $tahun."
                ];
            }

            if ($quarter === 'Q1') {
                return $this->validateQ1Logic(
                    $quarter,
                    (int)$tahun,
                    (int)$bulan,
                    $allQuartersLocal,
                    $inputLabel,
                    $lahanId,
                    $jenisAnggaran,
                    $kategoriAnggaran,
                    $allQuartersGlobal
                );
            }

            return $this->validateOtherQuartersLogic(
                $quarter,
                (int)$tahun,
                (int)$bulan,
                $allQuartersLocal,
                $inputLabel,
                $lahanId,
                $jenisAnggaran,
                $allQuartersGlobal,
                $kategoriAnggaran
            );
        } catch (Exception $e) {
            \Log::error('Validation error', ['error' => $e->getMessage()]);
            return [
                'valid' => false,
                'message' => 'Terjadi kesalahan saat validasi. Silakan coba lagi.'
            ];
        }
    }

    private function validateQ1Logic(
        $quarter,
        $tahun,
        $bulan,
        $allQuartersLocal,
        $inputLabel,
        $lahanId,
        $jenisAnggaran,
        $kategoriAnggaran,
        $allQuartersGlobal
    ) {
        // Get ALL Q1s across all categories for global validation
        $allQ1Global = $allQuartersGlobal->where('quarter', 'Q1');
        
        // Check if there's ANY Q1 in this year from ANY category
        $existingQ1InYear = $allQ1Global->where('tahun', $tahun);
        if ($existingQ1InYear->isNotEmpty()) {    
            // There is at least one Q1 in this year from some category
            $firstQ1 = $existingQ1InYear->first();     
            if (!$firstQ1) {
                return [
                    'valid' => false,
                    'message' => 'Data Q1 tidak konsisten. Silakan hubungi administrator.'
                ];
            }

            // Get all Q1 in this year (from all categories) and treat as one sequence
            $allQ1InYear = $allQ1Global->where('tahun', $tahun);

            // Check unique months instead of total records
            $uniqueMonths = $allQ1InYear->pluck('bulan')->unique();
            
            // Check if Q1 is already complete (3 UNIQUE months across all categories)
            if ($uniqueMonths->count() >= self::QUARTER_COMPLETE_THRESHOLD) {
                if ($uniqueMonths->contains($bulan)) {
                    return ['valid' => true, 'message' => 'Valid - Menambah kategori pada bulan yang sudah ada'];
                } else {
                    $existingLabels = $uniqueMonths
                        ->sort()
                        ->map(fn($month) => self::MONTH_NAMES[$month] . " {$tahun}")
                        ->join(', ');
                    
                    return [
                        'valid' => false,
                        'message' => "Q1 sudah lengkap dengan 3 bulan: $existingLabels. Tidak dapat menambah bulan baru."
                    ];
                }
            }

            $uniqueMonthRecords = $uniqueMonths->map(function($month) use ($allQ1InYear, $tahun) {
                return $allQ1InYear->where('bulan', $month)->first();
            });
            
            // Validate sequence within the global Q1 (treat all categories as one)
            return $this->validateSequenceWithinQuarter($uniqueMonthRecords, $bulan, $tahun, $quarter, $inputLabel, $kategoriAnggaran);
        }

        // Check for incomplete Q1 groups globally
        $incompleteGroups = $allQ1Global
            ->groupBy('quarter_label')
            ->filter(fn($g) => $g->pluck('bulan')->unique()->count() < self::QUARTER_COMPLETE_THRESHOLD);

        if ($incompleteGroups->isNotEmpty()) {
            foreach ($incompleteGroups as $label => $group) {
                // Get unique month records in this group
                $uniqueMonthRecords = $group->pluck('bulan')->unique()->sort()
                    ->map(fn($m) => $group->firstWhere('bulan', $m))
                    ->values();

                // Check if input is the next contiguous month after the last month in the group
                $last = $uniqueMonthRecords->sortBy(fn($r) => $r->tahun * 12 + $r->bulan)->last();
                if ($last) {
                    $lastIdx  = $last->tahun * 12 + $last->bulan;
                    $inputIdx = $tahun * 12 + $bulan;
                    $isContiguousNext = ($inputIdx === $lastIdx + 1);

                    if ($isContiguousNext) {
                        // Validate sequence within this incomplete Q1 group
                        $check = $this->validateSequenceWithinQuarter(
                            $uniqueMonthRecords,
                            $bulan,
                            $tahun,
                            $quarter,
                            $inputLabel,
                            $kategoriAnggaran
                        );
                        if ($check['valid']) {
                            $labelShow = $label ?: '(tanpa label)';
                            return ['valid' => true, 'message' => "Valid - melengkapi Q1 {$labelShow}"];
                        }
                    }
                }
            }
        }

        // No Q1 in this year, check if this category can start a new Q1
        $allQ1 = $allQuartersGlobal->where('quarter', 'Q1');

        if ($allQ1->isEmpty()) {
            // Completely new Q1 for this category - allowed
            return ['valid' => true, 'message' => 'Valid - First Q1'];
        }

        // Category has Q1 elsewhere, validate new sequence start
        return $this->validateNewQ1Start($allQ1, $tahun, $bulan, $quarter, $inputLabel, $allQuartersGlobal, $lahanId, $jenisAnggaran, $kategoriAnggaran);
    }

    private function validateNewQ1Start($allQ1, $tahun, $bulan, $quarter, $inputLabel, $allQuartersGlobal, $lahanId, $jenisAnggaran, $kategoriAnggaran)
    {
        // Check for incomplete Q1 blocking
        $q1Groups = $allQ1->groupBy('quarter_label');
        $incompleteBlocking = $q1Groups->filter(function ($g) use ($tahun) {
            $uniqueMonths = $g->pluck('bulan')->unique();
            
            if ($uniqueMonths->count() >= self::QUARTER_COMPLETE_THRESHOLD) {
                return false;
            }
            $hasCurrentYear = $g->contains(fn($r) => $r->tahun == $tahun);
            if (self::Q1_BLOCK_INCOMPLETE_SAME_YEAR_ONLY) {
                return $hasCurrentYear;
            }
            return true;
        });

        if ($incompleteBlocking->isNotEmpty() && (self::Q1_ALLOW_FORCE_NEW === false)) {
            $blockLabels = $incompleteBlocking->map(function ($g, $label) {
                $months = $g->pluck('bulan')->unique()
                    ->sort()
                    ->map(fn($m) => self::MONTH_NAMES[$m] . ' ' . $g->where('bulan', $m)->first()->tahun)
                    ->join(', ');
                $labelShow = $label ?? '(tanpa label)';
                return "{$labelShow} [{$months}]";
            })->values()->join(' | ');

            return [
                'valid' => false,
                'message' => "Masih ada Q1 yang belum lengkap: {$blockLabels}. Lengkapi terlebih dahulu sebelum membuat Q1 baru."
            ];
        }

        // Validate new Q1 sequence rules
        $quartersByYear = $allQuartersGlobal->groupBy('tahun')->map(fn($yd) => $yd->groupBy('quarter'));

        $extra = $this->validateNewQ1SequenceStart(
            $quarter,
            $tahun,
            $bulan,
            $quartersByYear,
            $lahanId,
            $jenisAnggaran,
            $kategoriAnggaran
        );
        if (!$extra['valid']) return $extra;

        return ['valid' => true, 'message' => 'Valid - Memulai Q1 baru untuk kategori ini'];
    }

    private function validateOtherQuartersLogic(
        $quarter,
        $tahun,
        $bulan,
        $allQuartersLocal,
        $inputLabel,
        $lahanId,
        $jenisAnggaran,
        $allQuartersGlobal,
        $kategoriAnggaran
    ) {
        // Always use global Q1 context first, not category-specific
        $globalQ1Context = $this->findGlobalCompleteQ1Context($lahanId, $jenisAnggaran, $tahun, $bulan, $quarter);
        
        if ($globalQ1Context) {
            // Use global Q1 as reference
            return $this->validateBasedOnQ1($quarter, $tahun, $bulan, $globalQ1Context, $allQuartersGlobal, $inputLabel, $kategoriAnggaran);
        }

        // If no global Q1 found, try to find pattern from existing year data in current category
        $existingInYear = $allQuartersLocal->where('tahun', $tahun)
            ->where('quarter_label', '!=', '')->first();

        if ($existingInYear) {
            if (preg_match('/Q\d-(\d{4})(?:\/(\d{4}))?/', $existingInYear->quarter_label, $m)) {
                $startYear = (int)$m[1];
                $endYear = !empty($m[2]) ? (int)$m[2] : $startYear;
                $expectedQ1Label = $endYear > $startYear
                    ? "Q1-{$startYear}/{$endYear}"
                    : "Q1-{$startYear}";
                
                // Look for this Q1 label globally, not just in current category
                $matchingQ1Global = $allQuartersGlobal->where('quarter', 'Q1')
                    ->where('quarter_label', $expectedQ1Label);
                
                if ($matchingQ1Global->isNotEmpty() && $matchingQ1Global->count() >= self::QUARTER_COMPLETE_THRESHOLD) {
                    $q1ForContext = [
                        'status' => 'complete',
                        'data' => $matchingQ1Global,
                        'quarter_label' => $expectedQ1Label
                    ];
                    return $this->validateBasedOnQ1($quarter, $tahun, $bulan, $q1ForContext, $allQuartersGlobal, $inputLabel, $kategoriAnggaran);
                }
            }
        }

        // Find relevant Q1 globally, not per category
        $q1Context = $this->findRelevantQ1ForYear($tahun, $bulan, $quarter, $allQuartersGlobal);

        if (in_array($q1Context['status'], ['not_found', 'incomplete'])) {
            if ($q1Context['status'] === 'incomplete') {
                return [
                    'valid' => false,
                    'message' => "Q1 belum lengkap. Silakan lengkapi Q1 terlebih dahulu sebelum membuat $quarter."
                ];
            }
            return [
                'valid' => false,
                'message' => "Belum ada Q1 yang lengkap sebagai referensi. Silakan buat dan lengkapi Q1 terlebih dahulu."
            ];
        }
        return $this->validateBasedOnQ1($quarter, $tahun, $bulan, $q1Context, $allQuartersGlobal, $inputLabel, $kategoriAnggaran);
    }

    private function validateBasedOnQ1($quarter, $tahun, $bulan, $q1ForThisContext, $allQuartersGlobal, $inputLabel, $kategoriAnggaran)
    {
        $q1Data = $q1ForThisContext['data'];
        $q1Months = $q1Data->sortBy(fn($item) => $item->tahun * 12 + $item->bulan);

        if ($q1Months->isEmpty()) {
            return [
                'valid' => false,
                'message' => 'Data Q1 tidak valid. Silakan periksa kembali.'
            ];
        }

        $q1EndMonth = $q1Months->last()->bulan;
        $q1EndYear = $q1Months->last()->tahun;

        if (!isset(self::QUARTER_OFFSETS[$quarter])) {
            return [
                'valid' => false,
                'message' => 'Quarter tidak valid.'
            ];
        }

        $quarterStartMonth = $q1EndMonth + 1 + self::QUARTER_OFFSETS[$quarter];
        $quarterStartYear = $q1EndYear;

        $maxIterations = 24; // Max 2 years
        $iterations = 0;
        while ($quarterStartMonth > 12 && $iterations < $maxIterations) {
            $quarterStartYear++;
            $quarterStartMonth -= 12;
            $iterations++;
        }

        while ($quarterStartMonth < 1 && $iterations < $maxIterations) {
            $quarterStartYear--;
            $quarterStartMonth += 12;
            $iterations++;
        }

        $validMonths = [];
        for ($i = 0; $i < 3; $i++) {
            $month = $quarterStartMonth + $i;
            $year = $quarterStartYear;
            if ($month > 12) {
                $year++;
                $month -= 12;
            }
            $validMonths[] = [
                'month' => $month,
                'year' => $year,
                'label' => self::MONTH_NAMES[$month] . " $year"
            ];
        }

        $isValid = collect($validMonths)->contains(
            fn($vm) => $vm['month'] == $bulan && $vm['year'] == $tahun
        );

        if (!$isValid) {
            $labels = collect($validMonths)->pluck('label')->join(', ');
            $q1Labels = $q1Months
                ->unique('bulan')
                ->map(fn($i) => self::MONTH_NAMES[$i->bulan] . " {$i->tahun}")
                ->join(', ');
            
            return [
                'valid' => false,
                'message' => "$quarter harus dimulai pada salah satu bulan berikut: $labels. (Berdasarkan Q1: $q1Labels)"
            ];
        }

        $existingInQuarter = $this->getExistingQuarterData($quarter, $tahun, $allQuartersGlobal);
        return $this->validateSequenceWithinQuarter($existingInQuarter, $bulan, $tahun, $quarter, $inputLabel, $kategoriAnggaran);
    }

    private function validateSequenceWithinQuarter($existingInQuarter, $bulan, $tahun, $quarter, $inputLabel, $kategoriAnggaran)
    {
        $uniqueMonths = $existingInQuarter->pluck('bulan')->unique();
        if ($uniqueMonths->count() >= self::QUARTER_COMPLETE_THRESHOLD && !$uniqueMonths->contains($bulan)) {
            $existingLabels = $uniqueMonths
                ->sort()
                ->map(fn($month) => self::MONTH_NAMES[$month] . " {$tahun}")
                ->join(', ');
            
            return [
                'valid' => false,
                'message' => "$quarter sudah lengkap dengan 3 bulan: $existingLabels. Tidak dapat menambah bulan baru."
            ];
        }

        if ($existingInQuarter->isEmpty()) {
            return ['valid' => true, 'message' => 'Valid'];
        }

        $alreadyExists = $existingInQuarter->first(function($item) use ($tahun, $bulan, $kategoriAnggaran) {
            return $item->tahun == $tahun && $item->bulan == $bulan && $item->kategori_anggaran_id == $kategoriAnggaran;
        });

        if ($alreadyExists) {
            return [
                'valid' => false,
                'message' => "Bulan $inputLabel untuk kategori $kategoriAnggaran sudah ada dalam $quarter."
            ];
        }

        $sorted = $existingInQuarter
            ->sortBy(fn($item) => $item->tahun * 100 + $item->bulan)
            ->values();

        $first = $sorted->first();
        $last = $sorted->last();

        $firstIdx = $first->tahun * 12 + $first->bulan;
        $lastIdx = $last->tahun * 12 + $last->bulan;
        $inputIdx = $tahun * 12 + $bulan;

        if (!$uniqueMonths->contains($bulan)) {
            $adjacent = $inputIdx == $firstIdx - 1 || $inputIdx == $lastIdx + 1;
            if (!$adjacent) {
                $suggestions = [];
                $leftIdx = $firstIdx - 1;
                if ($leftIdx >= 0) {
                    $leftYear = intdiv($leftIdx, 12);
                    $leftMonth = $leftIdx % 12;
                    if ($leftMonth == 0) { 
                        $leftMonth = 12; 
                        $leftYear -= 1; 
                    }
                    $suggestions[] = self::MONTH_NAMES[$leftMonth] . " $leftYear";
                }
                
                $rightIdx = $lastIdx + 1;
                $rightYear = intdiv($rightIdx, 12);
                $rightMonth = $rightIdx % 12;
                if ($rightMonth == 0) { 
                    $rightMonth = 12; 
                    $rightYear -= 1; 
                }
                $suggestions[] = self::MONTH_NAMES[$rightMonth] . " $rightYear";

                $existingLabels = $sorted
                    ->map(fn($i) => self::MONTH_NAMES[$i->bulan] . " {$i->tahun}")
                    ->join(', ');
                
                $suggestStr = $suggestions 
                    ? "Bulan yang dapat ditambahkan: " . implode(' atau ', array_unique($suggestions))
                    : "Tidak ada slot tersisa";

                return [
                    'valid' => false,
                    'message' => "Bulan dalam quarter harus berurutan. Sudah ada: $existingLabels. $suggestStr."
                ];
            }
        }

        // Check year span
        $allIdx = $existingInQuarter
            ->map(fn($item) => $item->tahun * 12 + $item->bulan)
            ->toArray();
        $allIdx[] = $inputIdx;

        $minIdx  = min($allIdx);
        $maxIdx  = max($allIdx);
        $minYear = intdiv($minIdx - 1, 12);
        $maxYear = intdiv($maxIdx - 1, 12);

        if (($maxYear - $minYear) > 1) {
            return [
                'valid' => false,
                'message' => "$quarter tidak boleh melewati lebih dari 2 tahun berturut-turut."
            ];
        }

        return ['valid' => true, 'message' => 'Valid'];
    }

    private function getExistingCrossYearRanges($lahanId, $jenisAnggaran, ?string $kategoriAnggaran = null): array
    {
        $query = AnggaranReklamasi::where('lahan_id', $lahanId)
            ->where('jenis_anggaran', $jenisAnggaran)
            ->whereNotNull('quarter_label');

        if ($kategoriAnggaran !== null) {
            $query->where('kategori_anggaran_id', $kategoriAnggaran);
        }

        $labels = $query->pluck('quarter_label')->unique();        

        $cycles = [];
        foreach ($labels as $label) {
            if (preg_match('/^(Q[1-4])-(\d{4})\/(\d{4})$/', trim($label), $m)) {
                $quarter = $m[1];
                $key = $quarter . '-' . $m[2] . '-' . $m[3];
                $cycle = [
                    'quarter' => $quarter,
                    'start' => (int)$m[2],
                    'end' => (int)$m[3]
                ];
                $cycles[$key] = $cycle;
            }
        }
        
        $result = array_values($cycles);
    
        return $result;
    }

    private function chooseApplicableCycleForSingleYearQuarter(
        string $quarter,
        int $year,
        array $cycles,
        Collection $quarterRecords
    ): ?array {
        $quarterCycles = array_filter($cycles, fn($c) => $c['quarter'] === $quarter);

        if (empty($quarterCycles)) return null;
        $candidatesStart = [];
        $candidatesEnd = [];
        foreach ($quarterCycles as $cycle) {
            if ($year === $cycle['start']) {
                $candidatesStart[] = $cycle;
            } elseif ($year === $cycle['end']) {
                if ($quarter === 'Q1') {
                    continue; // Policy A
                }
                $candidatesEnd[] = $cycle;
            }
        }
        if (!empty($candidatesStart)) {
            return $this->prioritizeCycles($candidatesStart);
        }
        if (!empty($candidatesEnd)) {
            return $this->prioritizeCycles($candidatesEnd);
        }
        return null;
    }

    private function decideLabelWithPolicyA(
        string $quarter,
        Collection $yearsSet,
        array $cycle,
        Collection $quarterRecords
    ): string {
        // Add quarter to cycle if not present
        if (!isset($cycle['quarter'])) {
            $cycle['quarter'] = $quarter;
        }

        // If multiple years, always use cross-year format
        if ($yearsSet->count() > 1) {
            return sprintf('%s-%d/%d', $quarter, $cycle['start'], $cycle['end']);
        }
        
        // Single year
        $year = (int)$yearsSet->first();
        
        // Check if this single year is part of a cross-year cycle
        if (isset($cycle['start']) && isset($cycle['end']) && $cycle['start'] !== $cycle['end']) {
            // This is part of a cross-year cycle
            if ($year === (int)$cycle['start']) {
                return sprintf('%s-%d/%d', $quarter, $cycle['start'], $cycle['end']);
            }
            if ($year === (int)$cycle['end']) {
                if ($quarter === 'Q1') {
                    // Policy A: Q1 at end year stays single
                    return $quarter . '-' . $year;
                }
                return sprintf('%s-%d/%d', $quarter, $cycle['start'], $cycle['end']);
            }
        }
        
        return $quarter . '-' . $year;
    }

    private function propagateCyclePolicyA(array $cycle, $lahanId, $jenisAnggaran, $kategoriAnggaran): void
    {
        $start = $cycle['start'];
        $end = $cycle['end'];

        $all = AnggaranReklamasi::where('lahan_id', $lahanId)
            ->where('jenis_anggaran', $jenisAnggaran)
            ->where('kategori_anggaran_id', $kategoriAnggaran)
            ->whereBetween('tahun', [$start, $end])
            ->get();

        $byQuarter = $all->groupBy('quarter');
        foreach (['Q1', 'Q2', 'Q3', 'Q4'] as $q) {
            if (!$byQuarter->has($q)) continue;
            $records = $byQuarter[$q];
            $yearsIn = $records->pluck('tahun')->unique();
            $hasStart = $yearsIn->contains($start);
            $hasEnd = $yearsIn->contains($end);
            if (!($hasStart || $hasEnd)) continue;
            if ($q === 'Q1' && !$hasStart && $hasEnd) {
                continue; // Policy A: end-year-only Q1 dibiarkan single-year
            }
            $newLabel = sprintf('%s-%d/%d', $q, $start, $end);
            foreach ($records as $r) {
                if ($this->isDifferentCycleCrossYear($r->quarter_label, $newLabel)) continue;
                if ($r->quarter_label !== $newLabel) {
                    $old = $r->quarter_label;
                    $r->quarter_label = $newLabel;
                    $r->save();
                    \Log::info('Cycle propagation applied', [
                        'quarter' => $q,
                        'id' => $r->anggaran_reklamasi_id,
                        'from' => $old,
                        'to' => $newLabel
                    ]);
                }
            }
        }
    }

    private function handleRollingUpgradeQ1(
        array $newCycle,
        ?array $previousCycle,
        $lahanId,
        $jenisAnggaran,
        $kategoriAnggaran
    ): void {
        if (!$previousCycle) return;
        if ($previousCycle['end'] !== $newCycle['start']) return;

        $startYear = $newCycle['start'];
        $labelBefore = 'Q1-' . $startYear;
        $labelAfter = sprintf('Q1-%d/%d', $newCycle['start'], $newCycle['end']);

        $targets = AnggaranReklamasi::where('quarter', 'Q1')
            ->where('lahan_id', $lahanId)
            ->where('jenis_anggaran', $jenisAnggaran)
            ->where('kategori_anggaran_id', $kategoriAnggaran)
            ->where('tahun', $startYear)
            ->where('quarter_label', $labelBefore)
            ->get();

        foreach ($targets as $r) {
            $r->quarter_label = $labelAfter;
            $r->save();
        }
    }

    private function findPreviousCycleSharingBoundary(array $current, array $allCycles): ?array
    {
        foreach ($allCycles as $c) {
            if ($c['end'] === $current['start']
                && !($c['start'] === $current['start'] && $c['end'] === $current['end'])) {
                return $c;
            }
        }
        return null;
    }

    private function isDifferentCycleCrossYear(?string $existing, string $candidate): bool
    {
        if (!$existing || !$candidate) return false;
        if (!str_contains($existing, '/') || !str_contains($candidate, '/')) return false;
        if (preg_match('/^Q\d-(\d{4})\/(\d{4})$/', $existing, $a)
            && preg_match('/^Q\d-(\d{4})\/(\d{4})$/', $candidate, $b)
        ) {
            return ($a[1] != $b[1]) || ($a[2] != $b[2]);
        }
        return false;
    }

    private function validateNewQ1SequenceStart(
        $quarter,
        $tahun,
        $bulan,
        $quartersByYear,
        $lahanId,
        $jenisAnggaran,
        $kategoriAnggaran
    ) {
        $existingQ1 = AnggaranReklamasi::where('quarter', 'Q1')
            ->where('lahan_id', $lahanId)
            ->where('jenis_anggaran', $jenisAnggaran)
            ->where('kategori_anggaran_id', $kategoriAnggaran)
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        if ($existingQ1->isEmpty()) {
            return ['valid' => true, 'message' => 'Valid - Q1 pertama'];
        }

        $getStartQ1 = function (int $y, bool $isPrev) use ($quartersByYear): ?int {
            if (!isset($quartersByYear[$y]['Q1'])) {
                return null;
            }
            
            if ($isPrev) {
                $q1 = collect($quartersByYear[$y]['Q1'])->sortBy('bulan')->first();
            } else {
                $q1 = collect($quartersByYear[$y]['Q1'])->sortBy('bulan')->last();
            }
            return $q1 ? (int)$q1->bulan : null;
        };

        $prevYear = $tahun - 1;
        $nextYear = $tahun + 1;

        $prevStart = $getStartQ1($prevYear, true);
        $nextStart = $getStartQ1($nextYear, false);

        if ($prevStart !== null && $bulan < $prevStart) {
            return [
                'valid' => false,
                'message' => "Q1 $tahun tidak boleh dimulai lebih awal dari Q1 tahun sebelumnya (" . self::MONTH_NAMES[$prevStart] . " $prevYear). Minimal mulai " . self::MONTH_NAMES[$prevStart] . " $tahun."
            ];
        }

        if ($nextStart !== null && $bulan > $nextStart) {
            return [
                'valid' => false,
                'message' => "Q1 $tahun tidak boleh dimulai lebih lambat dari Q1 tahun berikutnya (" . self::MONTH_NAMES[$nextStart] . " $nextYear). Maksimal mulai " . self::MONTH_NAMES[$nextStart] . " $tahun."
            ];
        }

        if (isset($quartersByYear[$prevYear]['Q4'])) {
            $q4PreviousYear = collect($quartersByYear[$prevYear]['Q4']);
            $isOverlapping = $q4PreviousYear->contains(fn($q4) => $q4->tahun == $tahun && $q4->bulan == $bulan);
            if ($isOverlapping) {
                $inputLabel = self::MONTH_NAMES[$bulan] . " $tahun";
                return [
                    'valid' => false,
                    'message' => "Q1 $tahun tidak boleh dimulai pada $inputLabel karena bertumpuk dengan Q4 tahun sebelumnya."
                ];
            }
        }

        return ['valid' => true, 'message' => 'Valid - Memulai Q1 baru'];
    }

    private function findGlobalCompleteQ1Context(int $lahanId, string $jenisAnggaran, int $targetYear, int $targetMonth, string $targetQuarter): ?array
    {
        // Get ALL Q1s across all categories
        $q1All = AnggaranReklamasi::where('lahan_id', $lahanId)
            ->where('jenis_anggaran', $jenisAnggaran)
            ->where('quarter', 'Q1')
            ->whereNotNull('quarter_label')
            ->get();

        if ($q1All->isEmpty()) return null;

        // Group by quarter_label and find complete ones (3 UNIQUE months)
        $groups = $q1All->groupBy('quarter_label')
            ->filter(fn($g) => $g->pluck('bulan')->unique()->count() >= self::QUARTER_COMPLETE_THRESHOLD);

        if ($groups->isEmpty()) return null;

        // Check each complete Q1 to see if it can produce the target
        $candidates = [];
        
        foreach ($groups as $label => $g) {
            if (!preg_match('/^Q1-(\d{4})(?:\/(\d{4}))?$/', $label, $m)) {
                continue;
            }
            
            // Get Q1's last month/year
            $sorted = $g->sortBy(fn($r) => $r->tahun * 12 + $r->bulan);
            $q1LastRecord = $sorted->last();
            $q1EndMonth = (int)$q1LastRecord->bulan;
            $q1EndYear = (int)$q1LastRecord->tahun;
            
            // Calculate what months this Q1 would produce for the target quarter
            if (!isset(self::QUARTER_OFFSETS[$targetQuarter])) {
                continue;
            }
            
            $quarterStartMonth = $q1EndMonth + 1 + self::QUARTER_OFFSETS[$targetQuarter];
            $quarterStartYear = $q1EndYear;
            
            // Normalize month
            $iterations = 0;
            $maxIterations = 24;
            while ($quarterStartMonth > 12 && $iterations < $maxIterations) {
                $quarterStartYear++;
                $quarterStartMonth -= 12;
                $iterations++;
            }
            
            while ($quarterStartMonth < 1 && $iterations < $maxIterations) {
                $quarterStartYear--;
                $quarterStartMonth += 12;
                $iterations++;
            }
            
            // Calculate the 3 valid months for this quarter based on this Q1
            $validMonths = [];
            for ($i = 0; $i < 3; $i++) {
                $month = $quarterStartMonth + $i;
                $year = $quarterStartYear;
                if ($month > 12) {
                    $year++;
                    $month -= 12;
                }
                $validMonths[] = ['month' => $month, 'year' => $year];
            }
            
            // Check if target month/year is in this Q1's valid range
            $canServe = collect($validMonths)->contains(
                fn($vm) => $vm['month'] == $targetMonth && $vm['year'] == $targetYear
            );
            
            if ($canServe) {
                $candidates[] = [
                    'label' => $label,
                    'data' => $g,
                    'q1EndYear' => $q1EndYear,
                    'priority' => 1 // Can serve this target
                ];
            }
        }
        
        // If no Q1 can serve, return null (will fallback to findRelevantQ1ForYear)
        if (empty($candidates)) {
            return null;
        }
        
        // Sort by most recent Q1
        usort($candidates, fn($a, $b) => $b['q1EndYear'] <=> $a['q1EndYear']);
        
        $pick = $candidates[0];
        
        return [
            'status' => 'complete',
            'data' => $pick['data'],
            'label' => $pick['data']->pluck('bulan')->unique()->sort()
                        ->map(fn($i) => self::MONTH_NAMES[$i] . ' ' . $pick['data']->firstWhere('bulan', $i)->tahun)->join(', '),
            'quarter_label' => $pick['label']
        ];
    }

    private function findRelevantQ1ForYear($targetYear, $targetMonth, $targetQuarter, $allQuarters)
    {
        if (!($allQuarters instanceof Collection)) {
            $allQuarters = collect($allQuarters);
        }

        $allQ1Data = $allQuarters->where('quarter', 'Q1');
        if ($allQ1Data->isEmpty()) {
            return ['status' => 'not_found'];
        }

        // Get all complete Q1 groups
        $groups = $allQ1Data->groupBy('quarter_label');
        $completeGroups = $groups->filter(fn($grp) => $grp->pluck('bulan')->unique()->count() >= self::QUARTER_COMPLETE_THRESHOLD);
        
        if ($completeGroups->isEmpty()) {
            // Check for incomplete Q1 in target year
            $q1InTargetYear = $allQ1Data->where('tahun', $targetYear);
            if ($q1InTargetYear->isNotEmpty()) {
                $label = $q1InTargetYear->first()->quarter_label;
                $full = $allQ1Data->where('quarter_label', $label);
                return ['status' => 'incomplete', 'data' => $full, 'label' => '', 'quarter_label' => $label];
            }
            return ['status' => 'not_found'];
        }

        // Check each Q1 to see if it can produce the target year/month for this quarter
        $candidates = [];
        
        foreach ($completeGroups as $label => $grp) {
            if (!preg_match('/^Q1-(\d{4})(?:\/(\d{4}))?$/', $label, $m)) {
                continue;
            }
            
            // Get Q1's last month
            $sorted = $grp->sortBy(fn($r) => $r->tahun * 12 + $r->bulan);
            $q1LastRecord = $sorted->last();
            $q1EndMonth = (int)$q1LastRecord->bulan;
            $q1EndYear = (int)$q1LastRecord->tahun;
            
            // Calculate what months this Q1 would produce for the target quarter
            if (!isset(self::QUARTER_OFFSETS[$targetQuarter])) {
                continue;
            }
            
            $quarterStartMonth = $q1EndMonth + 1 + self::QUARTER_OFFSETS[$targetQuarter];
            $quarterStartYear = $q1EndYear;
            
            // Normalize month
            $iterations = 0;
            $maxIterations = 24;
            while ($quarterStartMonth > 12 && $iterations < $maxIterations) {
                $quarterStartYear++;
                $quarterStartMonth -= 12;
                $iterations++;
            }
            
            while ($quarterStartMonth < 1 && $iterations < $maxIterations) {
                $quarterStartYear--;
                $quarterStartMonth += 12;
                $iterations++;
            }
            
            // Check if this Q1 can produce the target year/month
            $validMonths = [];
            for ($i = 0; $i < 3; $i++) {
                $month = $quarterStartMonth + $i;
                $year = $quarterStartYear;
                if ($month > 12) {
                    $year++;
                    $month -= 12;
                }
                $validMonths[] = ['month' => $month, 'year' => $year];
            }
            
            // Check if target month/year is in valid range
            $canServe = collect($validMonths)->contains(
                fn($vm) => $vm['month'] == $targetMonth && $vm['year'] == $targetYear
            );
            
            if ($canServe) {
                $candidates[] = [
                    'label' => $label,
                    'data' => $grp,
                    'q1EndYear' => $q1EndYear
                ];
            }
        }
        
        // If no Q1 can serve this combination, return not found
        if (empty($candidates)) {
            return ['status' => 'not_found'];
        }
        
        // Prefer the most recent Q1 if multiple can serve
        usort($candidates, fn($a, $b) => $b['q1EndYear'] <=> $a['q1EndYear']);
        
        $best = $candidates[0];
        $grp = $best['data'];
        
        $lbl = $grp->pluck('bulan')->unique()->sort()
            ->map(fn($m) => self::MONTH_NAMES[$m] . " " . $grp->firstWhere('bulan', $m)->tahun)
            ->join(', ');
        
        return [
            'status' => 'complete',
            'data' => $grp,
            'label' => $lbl,
            'quarter_label' => $best['label']
        ];
    }

    private function getExistingQuarterData($quarter, $targetYear, $allQuarters)
    {
        return $allQuarters->where('quarter', $quarter)
            ->filter(fn($item) => $item->tahun == $targetYear);
    }

    private function pickRelevantQuarterSequence(Collection $quarterRecords, int $tahunBaru, int $bulanBaru): Collection
    {
        if ($quarterRecords->isEmpty()) return collect();

        $sorted = $quarterRecords->sortBy(fn($r) => $r->tahun * 12 + $r->bulan)->values();
        $sequences = [];
        $current = collect([$sorted[0]]);
        for ($i = 1; $i < $sorted->count(); $i++) {
            $prev = $sorted[$i - 1];
            $curr = $sorted[$i];
            $prevIdx = $prev->tahun * 12 + $prev->bulan;
            $currIdx = $curr->tahun * 12 + $curr->bulan;
            if ($currIdx - $prevIdx === 1) {
                $current->push($curr);
            } else {
                $sequences[] = $current;
                $current = collect([$curr]);
            }
        }
        if ($current->isNotEmpty()) $sequences[] = $current;

        $inputIdx = $tahunBaru * 12 + $bulanBaru;
        foreach ($sequences as $seq) {
            $first = $seq->first();
            $last = $seq->last();
            $firstIdx = $first->tahun * 12 + $first->bulan;
            $lastIdx = $last->tahun * 12 + $last->bulan;
            if ($inputIdx === $firstIdx - 1 || $inputIdx === $lastIdx + 1) {
                return $seq;
            }
        }
        return collect();
    }

    private function applyLabelToQuarterRecordsByIds(array $ids, string $label): void
    {
        if (empty($ids)) return;
        DB::transaction(function () use ($ids, $label) {
            $records = AnggaranReklamasi::whereIn('anggaran_reklamasi_id', $ids)->get();
            foreach ($records as $r) {
                if ($r->quarter_label !== $label) {
                    $old = $r->quarter_label;
                    $r->quarter_label = $label;
                    $r->save();
                    \Log::info('Quarter label applied (targeted)', [
                        'id' => $r->anggaran_reklamasi_id,
                        'from' => $old,
                        'to' => $label
                    ]);
                }
            }
        });
    }

    protected static array $allowedSorts = [
        'tahun', 
        'bulan', 
        'nominal',
        'quarter', 
        'quarter_label',
        'quarter_total',
        'kategori_anggaran', 
        'jenis_anggaran', 
        'created_at'
    ];

    public static function getFilteredData(Request $request, Lahan $lahan, ?string $jenis = null)
    {
        $query = AnggaranReklamasi::query()->where('lahan_id', $lahan->lahan_id);

        // Filter by jenis_anggaran if provided
        if ($jenis) {
            $query->where('jenis_anggaran', $jenis);
        }

        // Year range filter
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        } else {
            if ($request->filled('startYear') && $request->filled('endYear')) {
                if ($request->startYear > $request->endYear) {
                    [$request->startYear, $request->endYear] = [$request->endYear, $request->startYear];
                }
                $query->whereBetween('tahun', [$request->startYear, $request->endYear]);
            } elseif ($request->filled('startYear')) {
                $query->where('tahun', '>=', $request->startYear);
            } elseif ($request->filled('endYear')) {
                $query->where('tahun', '<=', $request->endYear);
            }
        }

        // Month range filter  
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        } else {
            if ($request->filled('startMonth') && $request->filled('endMonth')) {
                if ($request->startMonth > $request->endMonth) {
                    [$request->startMonth, $request->endMonth] = [$request->endMonth, $request->startMonth];
                }
                $query->whereBetween('bulan', [$request->startMonth, $request->endMonth]);
            } elseif ($request->filled('startMonth')) {
                $query->where('bulan', '>=', $request->startMonth);
            } elseif ($request->filled('endMonth')) {
                $query->where('bulan', '<=', $request->endMonth);
            }
        }

        // Minimum nominal filter
        if ($request->filled('minNominal')) {
            $query->where('nominal', '>=', $request->minNominal);
        }

        // Selected category filter
        if ($request->filled('kategori_anggaran')) {
            $query->where('anggaran_reklamasi.kategori_anggaran_id', $request->kategori_anggaran);
        }

        // Quarter range filter
        if ($request->filled('quarter')) {
            $query->where('quarter', $request->quarter);
        } else {
            if ($request->filled('startQuarter') && $request->filled('endQuarter')) {
            $quarters = ['Q1', 'Q2', 'Q3', 'Q4'];
            $startIdx = array_search($request->startQuarter, $quarters);
            $endIdx = array_search($request->endQuarter, $quarters);
            
            if ($startIdx !== false && $endIdx !== false) {
                $quarterRange = array_slice($quarters, $startIdx, $endIdx - $startIdx + 1);
                $query->whereIn('quarter', $quarterRange);
            }
            } elseif ($request->filled('startQuarter')) {
                $quarters = ['Q1', 'Q2', 'Q3', 'Q4'];
                $startIdx = array_search($request->startQuarter, $quarters);
                if ($startIdx !== false) {
                    $quarterRange = array_slice($quarters, $startIdx);
                    $query->whereIn('quarter', $quarterRange);
                }
            } elseif ($request->filled('endQuarter')) {
                $quarters = ['Q1', 'Q2', 'Q3', 'Q4'];
                $endIdx = array_search($request->endQuarter, $quarters);
                if ($endIdx !== false) {
                    $quarterRange = array_slice($quarters, 0, $endIdx + 1);
                    $query->whereIn('quarter', $quarterRange);
                }
            }
        }

        // Nominal range filter
        if ($request->filled('minNominal') && $request->filled('maxNominal')) {
            if ($request->minNominal > $request->maxNominal) {
                [$request->minNominal, $request->maxNominal] = [$request->maxNominal, $request->minNominal];
            }
            $query->whereBetween('nominal', [$request->minNominal, $request->maxNominal]);
        } elseif ($request->filled('minNominal')) {
            $query->where('nominal', '>=', $request->minNominal);
        } elseif ($request->filled('maxNominal')) {
            $query->where('nominal', '<=', $request->maxNominal);
        }

        $groupedSub = $query->clone()
            ->join('kategori_anggaran', 'anggaran_reklamasi.kategori_anggaran_id', '=', 'kategori_anggaran.kategori_anggaran_id')
            ->selectRaw("
                tahun,
                bulan,
                quarter,
                quarter_label,
                SUM(nominal) AS nominal,
                STRING_AGG(kategori_anggaran.nama_kategori, ', ') AS kategori_anggaran_nama,
                COUNT(*) AS jumlah_kategori,
                json_agg(
                    json_build_object(
                        'id', anggaran_reklamasi_id,
                        'kategori', kategori_anggaran.nama_kategori,
                        'nominal', nominal,
                        'tahun', tahun,
                        'bulan', bulan,
                        'quarter', quarter,
                        'created_at', anggaran_reklamasi.created_at,
                        'updated_at', anggaran_reklamasi.updated_at
                    )
                ) AS items_json,
                SUM(SUM(nominal)) OVER (PARTITION BY quarter_label) AS quarter_total
            ")
            ->groupBy('tahun','bulan','quarter','quarter_label');

        $sub = DB::query()->fromSub($groupedSub, 'g');

        if ($request->filled('minTotal')) {
            $sub->where('quarter_total', '>=', (int)$request->minTotal);
        }

        // Sorting
        $sort = $request->get('tableSortColumn');
        $direction = $request->get('tableSortDirection', 'asc');

        if ($sort && in_array($sort, self::$allowedSorts) && in_array($direction, ['asc', 'desc'], true)) {
            switch($sort) {
                case 'bulan':
                    $sub->orderBy('tahun', $direction)
                        ->orderBy('bulan', $direction);
                    break;
                case 'tahun':
                    $sub->orderBy('tahun', $direction)
                        ->orderBy('bulan', $direction);
                    break;
                case 'quarter':
                    $order = "
                        CASE quarter
                            WHEN 'Q1' THEN 1
                            WHEN 'Q2' THEN 2
                            WHEN 'Q3' THEN 3
                            WHEN 'Q4' THEN 4
                            ELSE 5
                        END
                    ";
                    if ($direction === 'asc') {
                        $sub->orderByRaw("$order ASC")
                            ->orderBy('tahun','asc')
                            ->orderBy('bulan','asc');
                    } else {
                        $sub->orderByRaw("$order DESC")
                            ->orderBy('tahun','desc')
                            ->orderBy('bulan','desc');
                    }
                    break;
                case 'nominal':
                    $sub->orderBy('nominal', $direction);
                    break;
                default:
                    $sub->orderBy($sort, $direction);
                    break;
            }
        } else {
            $sub->orderBy('tahun', 'asc')
                ->orderBy('bulan', 'asc');
        }

        return $sub->paginate(12)->appends($request->query());
    }

    public function getKategoriAnggaranList($lahan_id)
    {
        return KategoriAnggaran::orderBy('nama_kategori')->get();
    }

    /**
     * Export Anggaran to Excel 
     */
    public function exportExcel(Lahan $lahan)
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Calibri');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(11);

        $spreadsheet->removeSheetByIndex(0);

        $types = ['actual', 'projection', 'forecast'];
        
        $quarterColors = [
            'Q1' => 'E2EFDA', 'Q2' => 'DDEBF7', 'Q3' => 'FFF2CC', 'Q4' => 'FCE4D6', 'DEFAULT' => 'FFFFFF'
        ];
        $totalColors = [
            'Q1' => 'C6E0B4', 'Q2' => 'BDD7EE', 'Q3' => 'FFE699', 'Q4' => 'F8CBAD', 'DEFAULT' => 'EAEAEA'
        ];
        
        foreach ($types as $index => $type) {
            $sheet = new Worksheet($spreadsheet, ucfirst($type));
            $spreadsheet->addSheet($sheet, $index);

            // TITLE
            $lahanName = strtoupper($lahan->nama_lahan);
            $typeName = strtoupper($type);
            $sheet->setCellValue('A1', "DATA ANGGARAN REKLAMASI - {$lahanName} ({$typeName})");
            $sheet->mergeCells('A1:F1');
            $sheet->getStyle('A1')->applyFromArray([
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ]);
            $sheet->getRowDimension('1')->setRowHeight(30);

            // HEADER
            $headers = ['Tahun', 'Quarter', 'Bulan', 'Rincian Penggunaan (Kategori)', 'Total Nominal (Bulan)', 'Total Quarter'];
            $sheet->fromArray($headers, null, 'A2');
            
            $headerStyle = [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '44546A']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ];
            $sheet->getStyle('A2:F2')->applyFromArray($headerStyle);
            $sheet->getRowDimension('2')->setRowHeight(25);

            // DATA FETCHING
            $rawData = AnggaranReklamasi::where('lahan_id', $lahan->lahan_id)
                ->where('jenis_anggaran', $type)
                ->join('kategori_anggaran', 'anggaran_reklamasi.kategori_anggaran_id', '=', 'kategori_anggaran.kategori_anggaran_id')
                ->selectRaw("
                    tahun, bulan, quarter, quarter_label,
                    SUM(nominal) as total_nominal_bulan,
                    json_agg(json_build_object('kategori', kategori_anggaran.nama_kategori, 'nominal', nominal)) as items_json
                ")
                ->groupBy('tahun', 'bulan', 'quarter', 'quarter_label')
                ->orderBy('tahun', 'asc')
                ->orderBy('bulan', 'asc')
                ->get();

            if ($rawData->isEmpty()) {
                $sheet->mergeCells('A3:F5'); 
                $sheet->setCellValue('A3', "BELUM ADA DATA ANGGARAN " . strtoupper($type));
                $sheet->getStyle('A3')->applyFromArray([
                    'font' => ['italic' => true, 'color' => ['rgb' => '777777'], 'size' => 12],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']],
                    'borders' => ['outline' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]]
                ]);
                foreach (range('A', 'F') as $col) $sheet->getColumnDimension($col)->setWidth(15);
                $sheet->getColumnDimension('D')->setWidth(40);
                continue; 
            }

            $groupedData = $rawData->groupBy('tahun')->map(fn($y) => $y->groupBy('quarter_label'));
            $row = 3; 

            foreach ($groupedData as $tahun => $quarters) {
                $yearStartRow = $row; 

                foreach ($quarters as $qLabel => $months) {
                    $quarterStartRow = $row;
                    $quarterTotalSum = 0;
                    
                    $qPrefix = substr($qLabel, 0, 2); 
                    $bgColor = $quarterColors[$qPrefix] ?? $quarterColors['DEFAULT'];
                    $totalColor = $totalColors[$qPrefix] ?? $totalColors['DEFAULT'];

                    foreach ($months as $data) {
                        $items = json_decode($data->items_json);
                        $detailLines = [];
                        foreach ($items as $item) {
                            $nom = number_format($item->nominal, 0, ',', '.');
                            $detailLines[] = "- {$item->kategori}: Rp {$nom}";
                        }
                        $detailString = implode("\n", $detailLines);
                        if (!empty($detailString)) $detailString .= "\n"; 

                        $sheet->setCellValue('A' . $row, $tahun);
                        $sheet->setCellValue('B' . $row, $qLabel); 
                        $sheet->setCellValue('C' . $row, self::MONTH_NAMES[$data->bulan]);
                        $sheet->setCellValue('D' . $row, $detailString);
                        $sheet->setCellValue('E' . $row, $data->total_nominal_bulan);

                        // STYLING
                        $sheet->getStyle('C' . $row)->applyFromArray(['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER], 'font' => ['bold' => true]]);
                        $sheet->getStyle('D' . $row)->applyFromArray(['alignment' => ['vertical' => Alignment::VERTICAL_TOP, 'wrapText' => true, 'indent' => 1]]);
                        
                        // FORMAT NOMINAL
                        $sheet->getStyle('E' . $row)->applyFromArray([
                            'numberFormat' => ['formatCode' => '"Rp " #,##0; "Rp " (#,##0); "Rp " -; @'],
                            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'horizontal' => Alignment::HORIZONTAL_CENTER],
                        ]);

                        $quarterTotalSum += $data->total_nominal_bulan;
                        $row++;
                    }

                    $quarterEndRow = $row - 1;

                    // COLORS
                    $sheet->getStyle("A{$quarterStartRow}:B{$quarterEndRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($bgColor);
                    $sheet->getStyle("F{$quarterStartRow}:F{$quarterEndRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($totalColor);
                        
                    // MERGE
                    if ($quarterEndRow > $quarterStartRow) $sheet->mergeCells("B{$quarterStartRow}:B{$quarterEndRow}");
                    
                    $sheet->setCellValue("F{$quarterStartRow}", $quarterTotalSum);
                    if ($quarterEndRow > $quarterStartRow) $sheet->mergeCells("F{$quarterStartRow}:F{$quarterEndRow}");

                    // STYLE TOTAL
                    $sheet->getStyle("F{$quarterStartRow}")->applyFromArray([
                        'numberFormat' => ['formatCode' => '"Rp " #,##0; "Rp " (#,##0); "Rp " -; @'],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                        'font' => ['bold' => true]
                    ]);
                }

                $yearEndRow = $row - 1;
                if ($yearEndRow > $yearStartRow) $sheet->mergeCells("A{$yearStartRow}:A{$yearEndRow}");
                
                $sheet->getStyle("A{$yearStartRow}:B{$yearEndRow}")->applyFromArray(['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER], 'font' => ['bold' => true]]);
            }

            $lastRow = $row - 1;
            if ($lastRow >= 3) $sheet->getStyle("A3:F{$lastRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            $sheet->getColumnDimension('A')->setWidth(10);
            $sheet->getColumnDimension('B')->setWidth(12);
            $sheet->getColumnDimension('C')->setWidth(15);
            $sheet->getColumnDimension('D')->setWidth(60);
            $sheet->getColumnDimension('E')->setWidth(25);
            $sheet->getColumnDimension('F')->setWidth(25);
        }

        $spreadsheet->setActiveSheetIndex(0);
        $fileName = 'Anggaran_Reklamasi_' . str_replace(' ', '_', $lahan->nama_lahan) . '_' . date('Ymd_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        
        return new StreamedResponse(function () use ($writer) { $writer->save('php://output'); }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment;filename="' . $fileName . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}