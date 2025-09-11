<?php

namespace App\Services;

use App\Models\AnggaranReklamasi;
use Illuminate\Support\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Lahan; 
use Exception;

class AnggaranReklamasiService
{
    // Constants for better readability
    private const QUARTER_COMPLETE_THRESHOLD = 3;
    private const MAX_YEARS_PER_QUARTER = 2;
    private const Q1_BLOCK_INCOMPLETE_SAME_YEAR_ONLY = false;
    private const Q1_ALLOW_FORCE_NEW = false;

    private const QUARTER_OFFSETS = [
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
                ->selectRaw("
                    tahun, 
                    bulan, 
                    quarter, 
                    quarter_label,
                    SUM(nominal) as nominal,
                    STRING_AGG(kategori_anggaran, ', ') as kategori_anggaran,
                    COUNT(*) as jumlah_kategori,
                    json_agg(
                        json_build_object(
                            'id', anggaran_reklamasi_id,
                            'kategori', kategori_anggaran,
                            'nominal', nominal,
                            'tahun', tahun,
                            'bulan', bulan,
                            'quarter', quarter,
                            'created_at', created_at,
                            'updated_at', updated_at
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
            $query->where('kategori_anggaran', $request->kategori_anggaran);
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

    public function isDuplicate($quarter, $tahun, $bulan, $lahanId, $jenisAnggaran, $kategoriAnggaran, $excludeId = null)
    {
        $query = AnggaranReklamasi::where('quarter', $quarter)
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->where('lahan_id', $lahanId)
            ->where('jenis_anggaran', $jenisAnggaran)
            ->where('kategori_anggaran', $kategoriAnggaran);

        if ($excludeId) {
            $query->where('anggaran_reklamasi_id', '!=', $excludeId);
        }

        return $query->exists();
    }

    public function generateQuarterLabel(
        $quarter,
        $tahun,
        $bulan,
        $lahanId,
        $jenisAnggaran,
        $kategoriAnggaran,
        $excludeId = null
    ) {
        try {
            $context = $this->prepareGenerationContext(
                $quarter, $tahun, $bulan, $lahanId, $jenisAnggaran, $kategoriAnggaran, $excludeId
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

    private function prepareGenerationContext($quarter, $tahun, $bulan, $lahanId, $jenisAnggaran, $kategoriAnggaran, $excludeId)
    {
        // Get quarter records for SAME category (for direct sequence)
        $quarterRecordsCategory = AnggaranReklamasi::where('quarter', $quarter)
            ->where('lahan_id', $lahanId)
            ->where('jenis_anggaran', $jenisAnggaran)
            ->where('kategori_anggaran', $kategoriAnggaran)
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

        $relevantYears = $relevantSequence->pluck('tahun')
            ->map(fn($y) => (int)$y)
            ->push((int)$tahun)
            ->unique()
            ->sort()
            ->values();

        $allYears = $relevantYears;

        return [
            'quarter' => $quarter,
            'tahun' => (int)$tahun,
            'bulan' => (int)$bulan,
            'lahanId' => $lahanId,
            'jenisAnggaran' => $jenisAnggaran,
            'kategoriAnggaran' => $kategoriAnggaran,
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
            $globalCycles = array_filter($globalCycles, fn($c) => $c['quarter'] === $quarter);
            
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
                        \Log::info('STEP 3B: ✅ ADOPTED GLOBAL CYCLE', [
                            'quarter' => $context['quarter'],
                            'tahun' => $context['tahun'],
                            'kategori' => $context['kategoriAnggaran'],
                            'cycle' => $covering
                        ]);
                    }
                }
            }
        }

        return $cycles;
    }

    // Determine if global cycle should be adopted
    private function shouldAdoptGlobalCycle($context, $globalCycle): bool
    {
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
            ->where('kategori_anggaran', $context['kategoriAnggaran'])
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
            $candidate = ['start' => $context['yearsSet']->first(), 'end' => $context['yearsSet']->last()];
            $exists = collect($quarterCycles)->first(
                fn($c) => $c['start'] === $candidate['start'] && $c['end'] === $candidate['end']
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
    private function updateAllCategoriesForCrossYearQuarter($quarter, $lahanId, $jenisAnggaran, $finalLabel, $quarterRecordsAll)
    {
        // Get all records for this quarter across all categories
        $allQuarterRecords = AnggaranReklamasi::where('quarter', $quarter)
            ->where('lahan_id', $lahanId)
            ->where('jenis_anggaran', $jenisAnggaran)
            ->get();

        foreach ($allQuarterRecords as $record) {
            if ($record->quarter_label !== $finalLabel) {
                $oldLabel = $record->quarter_label;
                $record->quarter_label = $finalLabel;
                $record->save();
            }
        }
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
                ->where('kategori_anggaran', $kategoriAnggaran)
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
            $existingCategory = $existingQ1InYear->first()->kategori_anggaran;
                   
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
            if ($g->count() >= self::QUARTER_COMPLETE_THRESHOLD) return false;
            $hasCurrentYear = $g->contains(fn($r) => $r->tahun == $tahun);
            if (self::Q1_BLOCK_INCOMPLETE_SAME_YEAR_ONLY) {
                return $hasCurrentYear;
            }
            return true;
        });

        if ($incompleteBlocking->isNotEmpty() && (self::Q1_ALLOW_FORCE_NEW === false)) {
            $blockLabels = $incompleteBlocking->map(function ($g, $label) {
                $months = $g->sortBy(fn($r) => $r->tahun * 12 + $r->bulan)
                            ->map(fn($r) => self::MONTH_NAMES[$r->bulan] . ' ' . $r->tahun)
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
        $globalQ1Context = $this->findGlobalCompleteQ1Context($lahanId, $jenisAnggaran, $tahun);
        
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
        $q1Context = $this->findRelevantQ1ForYear($tahun, $allQuartersGlobal); // Use global data

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

        $q1EndMonth = $q1Months->last()->bulan;
        $q1EndYear = $q1Months->last()->tahun;

        $quarterStartMonth = $q1EndMonth + 1 + self::QUARTER_OFFSETS[$quarter];
        $quarterStartYear = $q1EndYear;

        while ($quarterStartMonth > 12) {
            $quarterStartYear++;
            $quarterStartMonth -= 12;
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
            return $item->tahun == $tahun && $item->bulan == $bulan && $item->kategori_anggaran == $kategoriAnggaran;
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
        $minYear = intval(min($allIdx) / 12);
        $maxYear = intval(max($allIdx) / 12);

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
            $query->where('kategori_anggaran', $kategoriAnggaran);
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

    private function prioritizeCycles(array $cycles): array
    {
        usort($cycles, function ($a, $b) {
            $spanA = $a['end'] - $a['start'];
            $spanB = $b['end'] - $b['start'];
            if ($spanA === $spanB) return $a['end'] <=> $b['end'];
            return $spanA <=> $spanB;
        });
        return $cycles[0];
    }

    private function decideLabelWithPolicyA(
        string $quarter,
        Collection $yearsSet,
        array $cycle,
        Collection $quarterRecords
    ): string {
        if ($yearsSet->count() > 1) {
            return sprintf('%s-%d/%d', $quarter, $cycle['start'], $cycle['end']);
        }
        $year = (int)$yearsSet->first();
        if ($year === (int)$cycle['start']) {
            return sprintf('%s-%d/%d', $quarter, $cycle['start'], $cycle['end']);
        }
        if ($year === (int)$cycle['end']) {
            if ($quarter === 'Q1') {
                return $quarter . '-' . $year;
            }
            return sprintf('%s-%d/%d', $quarter, $cycle['start'], $cycle['end']);
        }
        return $quarter . '-' . $year;
    }

    private function propagateCyclePolicyA(array $cycle, $lahanId, $jenisAnggaran, $kategoriAnggaran): void
    {
        $start = $cycle['start'];
        $end = $cycle['end'];

        $all = AnggaranReklamasi::where('lahan_id', $lahanId)
            ->where('jenis_anggaran', $jenisAnggaran)
            ->where('kategori_anggaran', $kategoriAnggaran)
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
            ->where('kategori_anggaran', $kategoriAnggaran)
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
            ->where('kategori_anggaran', $kategoriAnggaran)
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

    private function findGlobalCompleteQ1Context(int $lahanId, string $jenisAnggaran, int $targetYear): ?array
    {
        // Get ALL Q1s across all categories
        $q1All = AnggaranReklamasi::where('lahan_id', $lahanId)
            ->where('jenis_anggaran', $jenisAnggaran)
            ->where('quarter', 'Q1')
            ->whereNotNull('quarter_label')
            ->get();

        if ($q1All->isEmpty()) return null;

        // Group by quarter_label and find complete ones (3 months)
        $groups = $q1All->groupBy('quarter_label')
            ->filter(fn($g) => $g->count() >= self::QUARTER_COMPLETE_THRESHOLD);

        if ($groups->isEmpty()) return null;

        // First, try to find Q1 that covers the target year
        $candidates = [];
        foreach ($groups as $label => $g) {
            if (preg_match('/^Q1-(\d{4})(?:\/(\d{4}))?$/', $label, $m)) {
                $start = (int)$m[1];
                $end = isset($m[2]) ? (int)$m[2] : $start;
                if ($targetYear >= $start && $targetYear <= $end) {
                    $candidates[] = [
                        'label' => $label,
                        'data' => $g,
                        'start' => $start,
                        'end' => $end,
                        'priority' => 1 // Covers target year
                    ];
                }
            }
        }

        // If no Q1 covers target year, use the most recent complete Q1
        if (empty($candidates)) {
            foreach ($groups as $label => $g) {
                if (preg_match('/^Q1-(\d{4})(?:\/(\d{4}))?$/', $label, $m)) {
                    $start = (int)$m[1];
                    $end = isset($m[2]) ? (int)$m[2] : $start;
                    $candidates[] = [
                        'label' => $label,
                        'data' => $g,
                        'start' => $start,
                        'end' => $end,
                        'priority' => 2 // Fallback
                    ];
                }
            }
        }

        if (empty($candidates)) return null;

        // Sort by priority first, then by most recent
        usort($candidates, function($a, $b) {
            if ($a['priority'] !== $b['priority']) {
                return $a['priority'] <=> $b['priority'];
            }
            return [$b['end'], $b['start']] <=> [$a['end'], $a['start']];
        });

        $pick = $candidates[0];
        return [
            'status' => 'complete',
            'data' => $pick['data'],
            'label' => $pick['data']->unique('bulan')->map(fn($i) => self::MONTH_NAMES[$i->bulan] . ' ' . $i->tahun)->join(', '),
            'quarter_label' => $pick['label']
        ];
    }

    private function findRelevantQ1ForYear($targetYear, $allQuarters)
    {
        if (!($allQuarters instanceof Collection)) {
            $allQuarters = collect($allQuarters);
        }

        // Get ALL Q1 data (should be global now)
        $allQ1Data = $allQuarters->where('quarter', 'Q1');
        if ($allQ1Data->isEmpty()) {
            return ['status' => 'not_found'];
        }

        // Look for Q1 in the target year
        $q1InTargetYear = $allQ1Data->where('tahun', $targetYear);
        if ($q1InTargetYear->isNotEmpty()) {
            $label = $q1InTargetYear->first()->quarter_label;
            $full = $allQ1Data->where('quarter_label', $label);
            $status = $full->count() >= self::QUARTER_COMPLETE_THRESHOLD ? 'complete' : 'incomplete';
            $lbl = $full->unique('bulan')->map(fn($i) => self::MONTH_NAMES[$i->bulan] . " {$i->tahun}")->join(', ');
            return ['status' => $status, 'data' => $full, 'label' => $lbl, 'quarter_label' => $label];
        }

        // Look for complete Q1 groups
        $groups = $allQ1Data->groupBy('quarter_label');
        $completeGroups = $groups->filter(fn($grp) => $grp->count() >= self::QUARTER_COMPLETE_THRESHOLD);
        
        if ($completeGroups->isNotEmpty()) {
            // Find the most relevant complete Q1 (prefer recent ones)
            $bestGroup = null;
            $bestLabel = null;
            $bestEnd = 0;
            
            foreach ($completeGroups as $label => $grp) {
                if (preg_match('/^Q1-(\d{4})(?:\/(\d{4}))?$/', $label, $m)) {
                    $end = isset($m[2]) ? (int)$m[2] : (int)$m[1];
                    if ($end > $bestEnd) {
                        $bestEnd = $end;
                        $bestGroup = $grp;
                        $bestLabel = $label;
                    }
                }
            }
            
            if ($bestGroup) {
                $lbl = $bestGroup->map(fn($i) => self::MONTH_NAMES[$i->bulan] . " {$i->tahun}")->join(', ');
                return ['status' => 'complete', 'data' => $bestGroup, 'label' => $lbl, 'quarter_label' => $bestLabel];
            }
        }
        
        return ['status' => 'not_found'];
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

    public static function getFilteredData(Request $request, Lahan $lahan, string $jenis = null)
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
            $query->where('kategori_anggaran', $request->kategori_anggaran);
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
            ->selectRaw("
                tahun,
                bulan,
                quarter,
                quarter_label,
                SUM(nominal) AS nominal,
                STRING_AGG(kategori_anggaran, ', ') AS kategori_anggaran,
                COUNT(*) AS jumlah_kategori,
                json_agg(
                    json_build_object(
                        'id', anggaran_reklamasi_id,
                        'kategori', kategori_anggaran,
                        'nominal', nominal,
                        'tahun', tahun,
                        'bulan', bulan,
                        'quarter', quarter,
                        'created_at', created_at,
                        'updated_at', updated_at
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
        return AnggaranReklamasi::where('lahan_id', $lahan_id)
            ->distinct()
            ->pluck('kategori_anggaran')
            ->toArray();
    }
}