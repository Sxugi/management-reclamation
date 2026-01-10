<?php

namespace App\Services;

use App\Models\Pohon;
use App\Models\JenisPohon;
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
        $query = Pohon::with('jenis')->where('lahan_id', $lahan->lahan_id);

        if ($request->filled('pohonType')) {
            $query->where('jenis_pohon_id', $request->pohonType);
        }

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
                $q->where('jumlah', '>=', $request->minQuantity);
            }
        };

        $query->whereHas('dataPohon', function($q) use ($relationFilter) {
            $relationFilter($q);
        });

        // Eager load filtered relation
        $query->whereHas('dataPohon', function($q) use ($relationFilter) {
            $relationFilter($q);
        });

        $sort = $request->get('tableSortColumn');
        $direction = $request->get('tableSortDirection');

        if ($sort && in_array($sort, self::$allowedSorts) && in_array($direction, ['asc', 'desc'])) {
            if ($sort === 'jenis_pohon') {
                $query->join('jenis_pohon', 'pohon.jenis_pohon_id', '=', 'jenis_pohon.jenis_pohon_id')
                  ->orderBy('jenis_pohon.nama_pohon', $direction)
                  ->select('pohon.*');
            } elseif ($sort === 'total') {
                $query->withCount(['dataPohon as total' => function($q) use ($relationFilter) {
                    $relationFilter($q);
                    $q->select(\DB::raw('COALESCE(SUM(jumlah), 0)'));
                }])->orderBy('total', $direction);
            } else {
                $query->with(['dataPohon' => function($q) use ($sort, $direction) {
                    $q->orderBy($sort, $direction);
                }]);
            }
        }

        $query->with('jenis');

        return $query->paginate(8)->appends($request->query());
    }

    public static function mapDataPohonByTahun($pohonCollection, $sortDirection = 'asc')
    {
        // If paginator passed, transform its collection externally; caller typically passes getCollection()
        foreach ($pohonCollection as $pohon) {
            // Ensure relation exists
            $dataPohon = $pohon->dataPohon ?? collect();

            // sort relation by tahun
            $sortedDataPohon = $sortDirection === 'desc'
                ? $dataPohon->sortByDesc('tahun')
                : $dataPohon->sortBy('tahun');

            $tahunMap = [];
            foreach ($sortedDataPohon as $dp) {
                $tahunMap[(int)$dp->tahun] = $dp;
            }
            $pohon->dataPohonByTahun = $tahunMap;

            // Compute SUM total jumlah across all loaded dataPohon for this jenis pohon
            $pohon->SUM = (int) $dataPohon->sum(function ($r) {
                return (int) ($r->jumlah ?? 0);
            });
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

        // Fetch Data Pohon with related DataPohon
        $dataPohonRaw = Pohon::with(['jenis', 'dataPohon' => function($q) {
                $q->orderBy('tahun', 'asc');
            }])
            ->where('lahan_id', $lahan->lahan_id)
            ->join('jenis_pohon', 'pohon.jenis_pohon_id', '=', 'jenis_pohon.jenis_pohon_id')
            ->orderBy('jenis_pohon.nama_pohon', 'asc')
            ->select('pohon.*')
            ->get();

        // Determine Unique Years
        $allYears = [];
        foreach ($dataPohonRaw as $pohon) {
            foreach ($pohon->dataPohon as $dp) {
                $allYears[] = $dp->tahun;
            }
        }
        $uniqueYears = array_unique($allYears);
        sort($uniqueYears);

        // Logic Empty State
        $isEmptyData = empty($uniqueYears);
        if ($isEmptyData) {
            // If empty, use current year as dummy header to maintain table structure
            $uniqueYears = [date('Y')]; 
        }

        // --- CALCULATE COLUMN POSITIONS ---
        $yearStartColIndex = 2; // Column B
        $totalYears = count($uniqueYears);
        $totalColIndex = $yearStartColIndex + $totalYears; 
        $totalColString = Coordinate::stringFromColumnIndex($totalColIndex);
        $lastYearColString = Coordinate::stringFromColumnIndex($totalColIndex - 1);

        // --- TITLE (Row 1) ---
        $lahanName = strtoupper($lahan->nama_lahan);
        $sheet->setCellValue('A1', "DATA INVENTARISASI POHON - {$lahanName}");
        $sheet->mergeCells("A1:{$totalColString}1");
        
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension('1')->setRowHeight(30);

        // --- HEADER TABLE (Row 2 & 3) ---
        // Labels
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
            // If empty data, show '-' instead of year
            $sheet->setCellValue($colString . '3', $isEmptyData ? '-' : $year);
            $colIndex++;
        }

        // Styling Headers
        $headerRange = "A2:{$totalColString}3";
        $sheet->getStyle($headerRange)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'font' => ['bold' => true]
        ]);

        $sheet->getStyle("B2:{$lastYearColString}2")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('E7E6E6');
        $sheet->getStyle("B3:{$lastYearColString}3")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('92D050'); 

        // --- EMPTY STATE ---
        if ($isEmptyData) {
            $sheet->mergeCells("A4:{$totalColString}6");
            $sheet->setCellValue('A4', "BELUM ADA DATA POHON");
            
            $sheet->getStyle('A4')->applyFromArray([
                'font' => ['italic' => true, 'color' => ['rgb' => '777777'], 'size' => 12],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']],
                'borders' => ['outline' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]]
            ]);
            
            // Width Adjust
            $sheet->getColumnDimension('A')->setWidth(25);
            $sheet->getColumnDimension('B')->setWidth(15);
            
            return $this->outputStream($spreadsheet, $lahan);
        }

        // --- DATA CONTENT (Row 4 onwards) ---
        $row = 4;
        $grandTotal = 0;

        foreach ($dataPohonRaw as $pohon) {
            $namaPohon = $pohon->jenis ? $pohon->jenis->nama_pohon : '-';

            $sheet->setCellValue('A' . $row, $namaPohon);

            $pohonByYear = [];
            foreach ($pohon->dataPohon as $dp) {
                $pohonByYear[$dp->tahun] = $dp->jumlah;
            }

            $colIndex = 2;
            $rowTotal = 0;

            foreach ($uniqueYears as $year) {
                $colString = Coordinate::stringFromColumnIndex($colIndex);
                if (isset($pohonByYear[$year])) {
                    $val = $pohonByYear[$year];
                    $sheet->setCellValue($colString . $row, $val);
                    $rowTotal += $val;
                } else {
                    $sheet->setCellValue($colString . $row, ''); 
                }
                $colIndex++;
            }

            $sheet->setCellValue($totalColString . $row, $rowTotal);
            $grandTotal += $rowTotal;

            // Styling Baris
            $sheet->getStyle("A{$row}:{$totalColString}{$row}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);
            
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setIndent(1);

            $row++;
        }

        // --- FOOTER (TOTAL) ---
        $footerRow = $row;
        $sheet->mergeCells("A{$footerRow}:{$lastYearColString}{$footerRow}");
        $sheet->setCellValue("A{$footerRow}", "Total Keseluruhan Tanaman");
        $sheet->setCellValue($totalColString . $footerRow, $grandTotal);

        $footerRange = "A{$footerRow}:{$totalColString}{$footerRow}";
        $sheet->getStyle($footerRange)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
        ]);

        $sheet->getStyle($totalColString . $footerRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('548235');
        $sheet->getStyle($totalColString . $footerRow)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE));

        // Auto Width
        foreach (range('A', $totalColString) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet->getColumnDimension('A')->setAutoSize(false); $sheet->getColumnDimension('A')->setWidth(25);

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
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
