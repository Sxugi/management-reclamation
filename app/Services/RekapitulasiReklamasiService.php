<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DataReklamasi;
use App\Models\Lahan;

class RekapitulasiReklamasiService
{
    public function generate(Lahan $lahan, int $tahun)
    {
        $dataTahunanModel = DataReklamasi::with('detailReklamasi')
            ->where('lahan_id', $lahan->lahan_id)
            ->where('tipe', 'rekapitulasi')
            ->where('tahun', $tahun)
            ->first();

        $dataTahunan = $this->mapDataTahunan($dataTahunanModel);

        $dataKumulatif = $this->getKumulatifData($lahan, $tahun);

        $html = view('reports.rekapitulasi-reklamasi-pdf', [
            'lahan' => $lahan,
            'tahun' => $tahun,
            'dataTahunan' => $dataTahunan,
            'dataKumulatif' => $dataKumulatif,
        ])->render();

        return Pdf::loadHTML($html)
            ->setPaper('a4', 'portrait')
            ->download("Rekapitulasi Pelaksanaan Reklamasi - {$lahan->nama_lahan} - {$tahun}.pdf");
    }

    private function mapDataTahunan($dataTahunanModel): array
    {
        $fields = $this->getDefaultFieldStructure();
        if (!$dataTahunanModel) {
            return $fields;
        }

        foreach ($dataTahunanModel->detailReklamasi as $detail) {
            $field = $detail->kegiatan;
            if (array_key_exists($field, $fields)) {
                $fields[$field] = (float) $detail->volume;
            }
        }

        return $fields;
    }

    private function getKumulatifData(Lahan $lahan, int $tahun): array
    {
        $defaultFields = $this->getDefaultFieldStructure();
        $dataKumulatif = $defaultFields;

        $allData = DataReklamasi::with('detailReklamasi')
            ->where('lahan_id', $lahan->lahan_id)
            ->where('tipe', 'rekapitulasi')
            ->where('tahun', '<=', $tahun)
            ->get();

        foreach ($allData as $item) {
            foreach ($item->detailReklamasi as $detail) {
                $field = $detail->kegiatan;
                if (array_key_exists($field, $dataKumulatif)) {
                    $dataKumulatif[$field] += (float) $detail->volume;
                }
            }
        }

        return $dataKumulatif;
    }

    private function getDefaultFieldStructure(): array
    {
        return [
            'area_penambangan' => 0,
            'timbunan_tanah_pengakaran' => 0,
            'timbunan_batuan_samping' => 0,
            'timbunan_komoditas_tambang' => 0,
            'timbunan_limbah_fasilitas' => 0,
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