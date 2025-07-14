<?php 

namespace App\Services;

use App\Models\DataReklamasi;
use App\Models\BiayaReklamasi;
use App\Models\Lahan;
use PDF;

class RekapitulasiBiayaService
{
    public function generate(Lahan $lahan, int $tahun)
    {
        $rencana = BiayaReklamasi::where('lahan_id', $lahan->lahan_id)
            ->where('type', 'rencana')
            ->where('tahun', $tahun)
            ->first();

        $realisasi = BiayaReklamasi::where('lahan_id', $lahan->lahan_id)
            ->where('type', 'rekapitulasi')
            ->where('tahun', $tahun)
            ->first();

        $html = view('reports.rekapitulasi-biaya-pdf', [
            'lahan' => $lahan,
            'tahun' => $tahun,
            'rencana' => $rencana,
            'realisasi' => $realisasi,
        ])->render();

        $pdf = PDF::loadHTML($html)->setPaper('a4', 'portrait');
        return $pdf->download("Rekap Biaya Reklamasi - {$lahan->nama_lahan} - {$tahun}.pdf");
    }
}
