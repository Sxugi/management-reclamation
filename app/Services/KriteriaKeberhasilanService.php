<?php

namespace App\Services;

use App\Models\Lahan;
use App\Models\KriteriaKeberhasilan;
use PDF;

class KriteriaKeberhasilanService {

    public function validatePDFGeneration(Lahan $lahan): array
    {
        $errors = [];

        $kriteria = KriteriaKeberhasilan::where('lahan_id', $lahan->lahan_id)->first();

        if (!$kriteria) {
            $errors[] = 'Data kriteria keberhasilan belum tersedia.';
            return $errors;
        }

        if (
            is_null($kriteria->rencana_luas_ditata) &&
            is_null($kriteria->realisasi_luas_ditata) &&
            is_null($kriteria->evaluasi_luas_ditata)
        ) {
            $errors[] = 'Data Penatagunaan Lahan belum terisi.';
        }

        if (
            is_null($kriteria->rencana_luas_penanaman) &&
            is_null($kriteria->realisasi_luas_penanaman) &&
            is_null($kriteria->evaluasi_luas_penanaman)
        ) {
            $errors[] = 'Data Revegetasi belum terisi.';
        }

        if (
            is_null($kriteria->rencana_penutupan_tajuk) &&
            is_null($kriteria->realisasi_penutupan_tajuk) &&
            is_null($kriteria->evaluasi_penutupan_tajuk)
        ) {
            $errors[] = 'Data Penyelesaian Akhir belum terisi.';
        }

        return $errors;
    }

    public function downloadPDF(Lahan $lahan)
    {
        $kriteria = KriteriaKeberhasilan::where('lahan_id', $lahan->lahan_id)->firstOrFail();

        $html = view('reports.kriteria-keberhasilan-pdf', [
            'lahan' => $lahan,
            'kriteria' => $kriteria
        ])->render();

        $pdf = PDF::loadHTML($html)->setPaper('a4', 'portrait');
        return $pdf->download("Kriteria Keberhasilan - {$lahan->nama_lahan}.pdf");
    }
}