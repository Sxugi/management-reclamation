<?php

namespace App\Services;

use App\Models\DataGudang;
use Illuminate\Http\Request;
use App\Models\Lahan;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DataGudangService
{
    protected static array $allowedSorts = [
        'tanggal_masuk',
        'jenis_barang',
        'nama_barang',
        'jumlah_barang',
        'lokasi_penyimpanan',
        'status_barang'
    ];

    public static function getFilteredData(Request $request, Lahan $lahan)
    {
        $query = DataGudang::where('lahan_id', $lahan->lahan_id);

        if ($request->filled('startDate') && $request->filled('endDate')) {
            if ($request->startDate > $request->endDate) {
                [$request->startDate, $request->endDate] = [$request->endDate, $request->startDate];
            }
            $query->whereBetween('tanggal_masuk', [$request->startDate, $request->endDate]);
        } elseif ($request->filled('startDate')) {
            $query->whereDate('tanggal_masuk', '>=', $request->startDate);
        } elseif ($request->filled('endDate')) {
            $query->whereDate('tanggal_masuk', '<=', $request->endDate);
        }
        if ($request->filled('jenisBarang')) {
            $query->where('jenis_barang', $request->jenisBarang);
        }
        if ($request->filled('statusBarang')) {
            $query->where('status_barang', $request->statusBarang);
        }

        $sort = $request->get('tableSortColumn');
        $direction = $request->get('tableSortDirection');

        if ($sort && in_array($sort, self::$allowedSorts) && in_array($direction, ['asc', 'desc'])) {
            $query->orderBy($sort, $direction);
        } else {
            $query->latest();
        }

        return $query->paginate(8)->appends($request->query());
    }

    /**
     * Export Data Gudang to Excel
     */
    public static function exportExcel(Lahan $lahan)
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Calibri');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(11);

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Gudang');

        // Fetch Data Gudang
        $dataGudang = DataGudang::where('lahan_id', $lahan->lahan_id)
            ->orderBy('tanggal_masuk', 'desc')
            ->get();

        // Setup Column Headers
        $headers = ['No', 'Tanggal Masuk', 'Jenis Barang', 'Nama Barang', 'Jumlah', 'Lokasi Penyimpanan', 'Status'];
        $endCol = 'G'; 

        // --- TITLE (Row 1) ---
        $lahanName = strtoupper($lahan->nama_lahan);
        $sheet->setCellValue('A1', "DATA INVENTARIS GUDANG - {$lahanName}");
        $sheet->mergeCells("A1:{$endCol}1");
        
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension('1')->setRowHeight(30);

        // --- HEADER TABLE (Row 2) ---
        $sheet->fromArray($headers, null, 'A2');
        
        $sheet->getStyle("A2:{$endCol}2")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '44546A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);
        $sheet->getRowDimension('2')->setRowHeight(25);

        // --- EMPTY STATE ---
        if ($dataGudang->isEmpty()) {
            $sheet->mergeCells("A3:{$endCol}5"); 
            $sheet->setCellValue('A3', "BELUM ADA DATA GUDANG");
            
            $sheet->getStyle('A3')->applyFromArray([
                'font' => ['italic' => true, 'color' => ['rgb' => '777777'], 'size' => 12],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']],
                'borders' => ['outline' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]]
            ]);
            
            foreach (range('A', $endCol) as $col) $sheet->getColumnDimension($col)->setWidth(15);
            $sheet->getColumnDimension('D')->setWidth(35);

            return self::outputStream($spreadsheet, $lahan);
        }

        // --- DATA CONTENT (Row 3 onwards) ---
        $row = 3;
        $no = 1;

        foreach ($dataGudang as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item->tanggal_masuk ? \Carbon\Carbon::parse($item->tanggal_masuk)->format('d-m-Y') : '-');
            $sheet->setCellValue('C' . $row, $item->jenis_barang);
            $sheet->setCellValue('D' . $row, $item->nama_barang);
            $sheet->setCellValue('E' . $row, $item->jumlah_barang);
            $sheet->setCellValue('F' . $row, $item->lokasi_penyimpanan);
            $sheet->setCellValue('G' . $row, ucfirst($item->status_barang));

            // Styling Baris
            $sheet->getStyle("A{$row}:{$endCol}{$row}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                'alignment' => ['vertical' => Alignment::VERTICAL_TOP]
            ]);
            
            // Alignment
            $sheet->getStyle("A{$row}:B{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("E{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("C{$row}:D{$row}")->getAlignment()->setIndent(1);
            $sheet->getStyle("F{$row}")->getAlignment()->setIndent(1);

            $row++;
        }

        // Auto Width
        foreach (range('A', $endCol) as $col) $sheet->getColumnDimension($col)->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(false); $sheet->getColumnDimension('D')->setWidth(35);
        $sheet->getColumnDimension('F')->setAutoSize(false); $sheet->getColumnDimension('F')->setWidth(25);

        return self::outputStream($spreadsheet, $lahan);
    }

    private static function outputStream($spreadsheet, $lahan)
    {
        $fileName = 'Data_Gudang_' . str_replace(' ', '_', $lahan->nama_lahan) . '_' . date('Ymd_His') . '.xlsx';
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
