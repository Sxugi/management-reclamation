<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Lahan;
use Illuminate\Support\Collection;

class RencanaReklamasiService
{
    public function generate(Lahan $lahan, Collection $data, string $template, string $filename)
    {
        $tahunRange = '';
        $dataPerTahun = [];

        if ($data->count() > 0) {
            $tahunAwal = $lahan->tahun_awal;
            $tahunAkhir = $lahan->tahun_akhir;
            $tahunRange = "$tahunAwal s.d $tahunAkhir";
            foreach ($data as $item) {
                $dataPerTahun[$item->tahun] = $item;
            }
        }

        $html = view($template, [
            'lahan' => $lahan,
            'tahunRange' => $tahunRange,
            'dataPerTahun' => $dataPerTahun,
            'tahuns' => array_keys($dataPerTahun),
        ])->render();

        return Pdf::loadHTML($html)
            ->setPaper('a4', 'portrait')
            ->download($filename . ' - ' . $lahan->nama_lahan . '.pdf');
    }
}