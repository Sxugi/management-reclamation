<?php

namespace App\Services;

use App\Models\Lahan;
use App\Models\KriteriaKeberhasilan;
use PDF;

class KriteriaKeberhasilanService
{
    /**
     * Validasi kelengkapan data berdasarkan kategori secara general.
     * @param Lahan $lahan
     * @return array
     */
    public function validatePDFGeneration(Lahan $lahan): array
    {
        $errors = [];
        $kriteria = KriteriaKeberhasilan::where('lahan_id', $lahan->lahan_id)
            ->with('detailKriteriaKeberhasilan')
            ->first();

        if (!$kriteria) {
            $errors[] = 'Tidak ada data kriteria keberhasilan.';
            return $errors;
        }

        // List kategori
        $kategoriList = [
            'penatagunaan' => 'Penatagunaan Lahan',
            'revegetasi' => 'Revegetasi',
            'penyelesaian' => 'Penyelesaian Akhir',
        ];

        $kategoriBelumLengkap = [];
        foreach ($kategoriList as $kategoriKey => $kategoriLabel) {
            // Ambil semua detail pada kategori ini
            $details = $kriteria->detailKriteriaKeberhasilan->where('kategori', $kategoriKey);
            // Jika tidak ada satupun detail pada kategori ini, berarti belum dibuat
            if ($details->isEmpty()) {
                $kategoriBelumLengkap[] = $kategoriLabel;
                continue;
            }

            // Validasi jika ada salah satu field penting yang kosong/null
            $cek = $details->filter(function($item) {
                return (
                    is_null($item->rencana) ||
                    is_null($item->realisasi) ||
                    is_null($item->hasil_evaluasi)
                );
            });

            if ($cek->count() > 0) {
                $kategoriBelumLengkap[] = $kategoriLabel;
            }
        }

        if (count($kategoriBelumLengkap) === count($kategoriList)) {
            $errors[] = 'Tidak ada data kriteria keberhasilan.';
        } elseif (!empty($kategoriBelumLengkap)) {
            $errors[] = 'Data kategori berikut belum lengkap: ' . implode(', ', $kategoriBelumLengkap) . '.';
        }

        return $errors;
    }

    /**
     * Generate dan download PDF Kriteria Keberhasilan.
     * @param Lahan $lahan
     * @return mixed
     */
    public function generate(Lahan $lahan)
    {
        $kriteria = KriteriaKeberhasilan::where('lahan_id', $lahan->lahan_id)
            ->with('detailKriteriaKeberhasilan')
            ->firstOrFail();

        $detail = [];
        foreach ($kriteria->detailKriteriaKeberhasilan as $item) {
            $detail[$item->kategori][$item->indikator] = $item;
        }

        $html = view('reports.kriteria-keberhasilan-pdf', [
            'lahan' => $lahan,
            'kriteria' => $kriteria,
            'detail' => $detail,
        ])->render();

        $pdf = PDF::loadHTML($html)->setPaper('a4', 'portrait');
        return $pdf->download("Kriteria Keberhasilan - {$lahan->nama_lahan}.pdf");
    }
}