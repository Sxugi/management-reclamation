<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Lahan;
use App\Models\DataReklamasi;

class RencanaReklamasiService
{
    public function generate(Lahan $lahan)
    {
        $data = $this->getDataForPDF($lahan);

        if (!$data) {
            return null;
        }

        $html = view('reports.rencana-reklamasi-pdf', $data)->render();

        return Pdf::loadHTML($html)
            ->setPaper('a4', 'portrait')
            ->download('Rencana Reklamasi - ' . $lahan->nama_lahan . '.pdf');
    }

    public function getDataForPDF(Lahan $lahan): ?array
    {
        $data = DataReklamasi::with('detailDataReklamasi')
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
            }

            foreach ($item->detailDataReklamasi as $detail) {
                $field = $detail->kegiatan;

                if (array_key_exists($field, $dataPerTahun[$tahun])) {
                    $dataPerTahun[$tahun][$field] = (float) $detail->volume;
                }
            }
        }

        return $dataPerTahun;
    }

    private function getDefaultFieldStructure(): array
    {
        return [
            'area_penambangan' => 0,
            'timbunan_tanah_pengakaran' => 0,
            'timbunan_batuan_samping' => 0,
            'timbunan_komoditas_tambang' => 0,
            'jalan_tambang' => 0,
            'kolam_sedimen' => 0,
            'fasilitas_pengolahan' => 0,
            'kantor_perumahan' => 0,
            'bengkel' => 0,
            'fasilitas_penunjang' => 0,
            'lahan_selesai_ditambang' => 0,
            'lahan_aktif_ditambang' => 0,
            'volume_batuan_samping' => 0,
            'penimbunan_bekas_tambang' => 0,
            'penimbunan_diluar_bekas_tambang' => 0,
            'volume_bekas_tambang' => 0,
            'volume_diluar_bekas_tambang' => 0,
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
        ];
    }
}
