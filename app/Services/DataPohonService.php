<?php

namespace App\Services;

use App\Models\Pohon;
use App\Models\JenisPohon;
use App\Models\DataPohonRealisasi;
use App\Models\DataPohonManual;
use Illuminate\Http\Request;
use App\Models\Lahan; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DataPohonService
{
    protected static array $allowedSorts = [
        'jenis_pohon',
        'tahun',
        'jumlah',
        'total'
    ];

    public static function getFilteredData(Request $request, Lahan $lahan)
    {
        // Load relations WITHOUT filter first
        $query = Pohon::with([
                'jenisPohon'
            ])
            ->where('lahan_id', $lahan->lahan_id);

        // Filter by jenis pohon
        if ($request->filled('pohonType')) {
            $query->where('jenis_pohon_id', $request->pohonType);
        }

        // Build filter closure
        $relationFilter = function ($q) use ($request) {
            if ($request->filled('year')) {
                $q->where('tahun', $request->year);
            } else {    
                $start = $request->startYear;
                $end   = $request->endYear;
                if ($request->filled('startYear') && $request->filled('endYear')) {
                    if ($start > $end) {
                        [$start, $end] = [$end, $start];
                    }
                    $q->whereBetween('tahun', [$start, $end]);
                } elseif ($request->filled('startYear')) {
                    $q->where('tahun', '>=', $start);
                } elseif ($request->filled('endYear')) {
                    $q->where('tahun', '<=', $end);
                }
            }

            if ($request->filled('minQuantity')) {
                $q->where('jumlah_batang', '>=', $request->minQuantity);
            }
        };

        // Only show pohon that has matching data
        $query->where(function($q) use ($relationFilter) {
            $q->whereHas('dataRealisasi', $relationFilter)
              ->orWhereHas('dataManual', $relationFilter);
        });

        // Eager load WITH filter applied
        $query->with([
            'dataRealisasi' => $relationFilter,
            'dataRealisasi.plot',
            'dataManual' => $relationFilter
        ]);

        // Sorting
        $sort = $request->get('tableSortColumn');
        $direction = $request->get('tableSortDirection');

        if ($sort && in_array($sort, self::$allowedSorts) && in_array($direction, ['asc', 'desc'])) {
            if ($sort === 'jenis_pohon') {
                $query->join('jenis_pohon', 'pohon.jenis_pohon_id', '=', 'jenis_pohon.jenis_pohon_id')
                  ->orderBy('jenis_pohon.nama_pohon', $direction)
                  ->select('pohon.*');
            } 
            elseif ($sort === 'total') {
                $query->leftJoin('data_pohon_realisasi', 'pohon.pohon_id', '=', 'data_pohon_realisasi.pohon_id')
                    ->leftJoin('data_pohon_manual', 'pohon.pohon_id', '=', 'data_pohon_manual.pohon_id')
                    ->select('pohon.*', \DB::raw('SUM(COALESCE(data_pohon_realisasi.jumlah_batang, 0)) + SUM(COALESCE(data_pohon_manual.jumlah_batang, 0)) as total_trees'))
                    ->groupBy('pohon.pohon_id')
                    ->orderBy('total_trees', $direction);
            }
            elseif ($sort === 'tahun') {
                $query->selectRaw('pohon.*, 
                        MAX(COALESCE(dpr.tahun, dpm.tahun)) as latest_tahun')
                    ->leftJoin('data_pohon_realisasi as dpr', 'pohon.pohon_id', '=', 'dpr.pohon_id')
                    ->leftJoin('data_pohon_manual as dpm', 'pohon.pohon_id', '=', 'dpm.pohon_id')
                    ->groupBy('pohon.pohon_id')
                    ->orderBy('latest_tahun', $direction);
            }
        }

        return $query->paginate(8)->appends($request->query());
    }

    public static function mapDataPohonByTahun($pohonCollection, $sortDirection = 'asc')
    {
        foreach ($pohonCollection as $pohon) {
            // Combine both realisasi and manual
            $allData = collect();

            // Process realisasi
            foreach ($pohon->dataRealisasi as $realisasi) {
                $allData->push((object)[
                    'tahun' => $realisasi->tahun,
                    'tipe' => 'realisasi',
                    'plot_name' => $realisasi->plot?->nama_plot,
                    'realisasi_progres' => $realisasi->jumlah_batang,
                    'stok_manual' => 0,
                    'total' => $realisasi->jumlah_batang,
                    'model' => $realisasi,
                ]);
            }

            // Process manual
            foreach ($pohon->dataManual as $manual) {
                $allData->push((object)[
                    'tahun' => $manual->tahun,
                    'tipe' => 'manual',
                    'plot_name' => null,
                    'realisasi_progres' => 0,
                    'stok_manual' => $manual->jumlah_batang,
                    'total' => $manual->jumlah_batang,
                    'model' => $manual,
                ]);
            }

            // Group by tahun
            $groupedByYear = $allData->groupBy('tahun')->map(function ($items) {
                $first = $items->first();
                $totalVal = $items->sum('total');
                
                $first->total_display = $totalVal; 
                return $first;
            });

            // Sort by tahun
            $sortedGrouped = $sortDirection === 'desc'
                ? $groupedByYear->sortByDesc('tahun')
                : $groupedByYear->sortBy('tahun');

            $pohon->dataPohonByTahun = $sortedGrouped->all();

            // Calculate SUM (only from filtered data)
            $pohon->SUM = $allData->sum('total');
        }

        return $pohonCollection;
    }

    public static function getTahunList($pohonCollection, $sort = 'tahun', $direction = 'asc')
    {
        $tahunList = collect($pohonCollection)
            ->flatMap(function ($data) {
                return array_keys($data->dataPohonByTahun ?? []);
            })
            ->unique()
            ->toArray();

        if ($sort === 'tahun' && in_array($direction, ['asc', 'desc'])) {
            usort($tahunList, function ($a, $b) use ($direction) {
                return $direction === 'asc' ? $a <=> $b : $b <=> $a;
            });
        } else {
            sort($tahunList);
        }

        return $tahunList;
    }

    public static function hasFilter(Request $request)
    {
        return $request->filled('year') ||
               $request->filled('pohonType') ||
               $request->filled('minQuantity') ||
               $request->filled('startYear') ||
               $request->filled('endYear') ||
               $request->filled('total');
    }

    public static function getJenisPohonList($lahan_id)
    {
        return JenisPohon::orderBy('nama_pohon')->get();
    }

    /**
     * Export Data Pohon to Excel
     */
    public function exportExcel(Lahan $lahan)
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Calibri');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(11);

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Pohon');

        // Fetch with new relations
        $dataPohonRaw = Pohon::with([
                'jenisPohon',
                'dataRealisasi',
                'dataManual'
            ])
            ->where('lahan_id', $lahan->lahan_id)
            ->join('jenis_pohon', 'pohon.jenis_pohon_id', '=', 'jenis_pohon.jenis_pohon_id')
            ->orderBy('jenis_pohon.nama_pohon', 'asc')
            ->select('pohon.*')
            ->get();

        // Use separate array instead of model property
        $mappedDataByPohon = [];
        $allYears = [];
        
        foreach ($dataPohonRaw as $pohon) {
            $pohonId = $pohon->pohon_id;
            $mappedDataByPohon[$pohonId] = [];
            
            // Process realisasi
            foreach ($pohon->dataRealisasi as $dr) {
                $allYears[] = $dr->tahun;
                if (!isset($mappedDataByPohon[$pohonId][$dr->tahun])) {
                    $mappedDataByPohon[$pohonId][$dr->tahun] = 0;
                }
                $mappedDataByPohon[$pohonId][$dr->tahun] += $dr->jumlah_batang;
            }

            // Process manual
            foreach ($pohon->dataManual as $dm) {
                $allYears[] = $dm->tahun;
                if (!isset($mappedDataByPohon[$pohonId][$dm->tahun])) {
                    $mappedDataByPohon[$pohonId][$dm->tahun] = 0;
                }
                $mappedDataByPohon[$pohonId][$dm->tahun] += $dm->jumlah_batang;
            }
        }

        $uniqueYears = array_unique($allYears);
        sort($uniqueYears);

        // Empty state
        $isEmptyData = empty($uniqueYears);
        if ($isEmptyData) {
            $uniqueYears = [date('Y')]; 
        }

        // Column positions
        $yearStartColIndex = 2;
        $totalYears = count($uniqueYears);
        $totalColIndex = $yearStartColIndex + $totalYears; 
        $totalColString = Coordinate::stringFromColumnIndex($totalColIndex);
        $lastYearColString = Coordinate::stringFromColumnIndex($totalColIndex - 1);

        // TITLE
        $lahanName = strtoupper($lahan->nama_lahan);
        $sheet->setCellValue('A1', "DATA INVENTARISASI POHON - {$lahanName}");
        $sheet->mergeCells("A1:{$totalColString}1");
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        // HEADERS
        $sheet->setCellValue('A2', 'Jenis Pohon');
        $sheet->mergeCells('A2:A3');
        $sheet->setCellValue('B2', 'Tahun');
        $sheet->mergeCells("B2:{$lastYearColString}2");
        $sheet->setCellValue($totalColString . '2', 'Total');
        $sheet->mergeCells("{$totalColString}2:{$totalColString}3");

        // Sub Headers - Years
        $colIndex = $yearStartColIndex;
        foreach ($uniqueYears as $year) {
            $colString = Coordinate::stringFromColumnIndex($colIndex);
            $sheet->setCellValue($colString . '3', $isEmptyData ? '-' : $year);
            $colIndex++;
        }

        // Styling
        $sheet->getStyle("A2:{$totalColString}3")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'font' => ['bold' => true]
        ]);
        $sheet->getStyle("B3:{$lastYearColString}3")->getFill()
            ->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('92D050');

        // Empty state
        if ($isEmptyData) {
            $sheet->setCellValue('A4', "BELUM ADA DATA POHON");
            $sheet->mergeCells("A4:{$totalColString}4");
            return $this->outputStream($spreadsheet, $lahan);
        }

        // DATA CONTENT
        $row = 4;
        $grandTotal = 0;

        foreach ($dataPohonRaw as $pohon) {
            $pohonId = $pohon->pohon_id;
            $namaPohon = $pohon->jenisPohon->nama_pohon ?? '-';
            $sheet->setCellValue('A' . $row, $namaPohon);

            $colIndex = 2;
            $rowTotal = 0;

            foreach ($uniqueYears as $year) {
                $colString = Coordinate::stringFromColumnIndex($colIndex);
                // Use separate array instead of model property
                $val = $mappedDataByPohon[$pohonId][$year] ?? 0;
                
                if ($val > 0) {
                    $sheet->setCellValue($colString . $row, $val);
                    $rowTotal += $val;
                } else {
                    $sheet->setCellValue($colString . $row, ''); 
                }
                $colIndex++;
            }

            $sheet->setCellValue($totalColString . $row, $rowTotal);
            $grandTotal += $rowTotal;
            $row++;
        }

        // FOOTER
        $sheet->setCellValue("A{$row}", "Total Keseluruhan");
        $sheet->mergeCells("A{$row}:{$lastYearColString}{$row}");
        $sheet->setCellValue($totalColString . $row, $grandTotal);
        
        $sheet->getStyle("A4:{$totalColString}{$row}")
            ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("A{$row}:{$totalColString}{$row}")->getFont()->setBold(true);

        foreach (range('A', $totalColString) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return $this->outputStream($spreadsheet, $lahan);
    }

    private function outputStream($spreadsheet, $lahan)
    {
        $fileName = 'Data_Pohon_' . str_replace(' ', '_', $lahan->nama_lahan) . '_' . date('Ymd_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        
        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment;filename="' . $fileName . '"',
        ]);
    }
}