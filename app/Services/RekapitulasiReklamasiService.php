<?php 

namespace App\Services;

use App\Models\DataReklamasi;
use App\Models\Lahan;
use PDF;

class RekapitulasiReklamasiService
{
    public function generate(Lahan $lahan, int $tahun)
    {
        $dataKumulatif = [];

        $dataTahunan = DataReklamasi::where('lahan_id', $lahan->lahan_id)
            ->where('type', 'rekapitulasi')
            ->where('tahun', $tahun)
            ->first();

        $anyRow = DataReklamasi::where('lahan_id', $lahan->lahan_id)
            ->where('type', 'rekapitulasi')
            ->first();

        if ($anyRow) {
            $ignore = ['id', 'lahan_id', 'type', 'tahun', 'created_at', 'updated_at']; // kolom yang tidak usah di-sum
            $columns = array_diff(array_keys($anyRow->getAttributes()), $ignore);

            foreach ($columns as $field) {
                $dataKumulatif[$field] = (float) DataReklamasi::where('lahan_id', $lahan->lahan_id)
                    ->where('type', 'rekapitulasi')
                    ->where('tahun', '<=', $tahun)
                    ->sum($field);
            }
        }

        $html = view('reports.rekapitulasi-reklamasi-pdf', [
            'lahan' => $lahan,
            'tahun' => $tahun,
            'dataTahunan' => $dataTahunan,
            'dataKumulatif' => $dataKumulatif,
        ])->render();

        $pdf = PDF::loadHTML($html)->setPaper('a4', 'portrait');
        return $pdf->download("Rekapitulasi Pelaksanaan Reklamasi - {$lahan->nama_lahan} - {$tahun}.pdf");
    }
}

