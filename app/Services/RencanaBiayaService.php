<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Lahan;
use App\Models\BiayaReklamasi;

class RencanaBiayaService
{
    public function generate(Lahan $lahan)
    {
        $data = $this->getDataForPDF($lahan);

        if (!$data) {
            return null;
        }

        $html = view('reports.rencana-biaya-pdf', $data)->render();

        return Pdf::loadHTML($html)
            ->setPaper('a4', 'portrait')
            ->download('Rencana Biaya - ' . $lahan->nama_lahan . '.pdf');
    }

    public function getDataForPDF(Lahan $lahan): ?array
    {
        $data = BiayaReklamasi::with('detailBiayaReklamasi')
            ->where('lahan_id', $lahan->lahan_id)
            ->where('tipe', 'rencana')
            ->orderBy('tahun')
            ->get();

        if ($data->isEmpty()) {
            return null;
        }

        $dataPerTahun = $this->mapDataPerTahun($data);

        return [
            'lahan' => $lahan,
            'tahunRange' => "{$lahan->tahun_awal} s.d {$lahan->tahun_akhir}",
            'dataPerTahun' => $dataPerTahun,
            'tahuns' => array_keys($dataPerTahun),
        ];
    }

    private function mapDataPerTahun($data): array
    {
        $dataPerTahun = [];

        foreach ($data as $item) {
            $tahun = $item->tahun;

            if (!isset($dataPerTahun[$tahun])) {
                $dataPerTahun[$tahun] = $this->getDefaultFieldStructure();

                $dataPerTahun[$tahun]['subtotal_1'] = (float) $item->subtotal_1;
                $dataPerTahun[$tahun]['subtotal_2'] = (float) $item->subtotal_2;
            }

            foreach ($item->detailBiayaReklamasi as $detail) {
                $field = $detail->kegiatan;

                if (array_key_exists($field, $dataPerTahun[$tahun])) {
                    $dataPerTahun[$tahun][$field] = (float) $detail->biaya;
                }
            }
        }

        return $dataPerTahun;
    }

    private function getDefaultFieldStructure(): array
    {
        return [
            'penataan_tanah' => 0,
            'penebaran_tanah_pengakaran' => 0,
            'pengendalian_erosi' => 0,
            'kualitas_tanah' => 0,
            'pemupukan' => 0,
            'pengadaan_bibit' => 0,
            'penanaman' => 0,
            'pemeliharaan_tanaman' => 0,
            'pencegahan_air_asam' => 0,
            'pekerjaan_sipil' => 0,
            'stabilisasi_lereng' => 0,
            'pengamanan_lubang' => 0,
            'pemulihan_kualitas_air' => 0,
            'pemeliharaan_lubang' => 0,
            'mobilisasi_demobilisasi_alat' => 0,
            'perencanaan_reklamasi' => 0,
            'administrasi_pihak_ketiga' => 0,
            'supervisi' => 0,
        ];
    }
}