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
        $rencana = $this->getDataForPDF($lahan, $tahun, 'rencana');
        $realisasi = $this->getDataForPDF($lahan, $tahun, 'rekapitulasi');

        $rencanaData = $this->mapDataBiaya($rencana);
        $realisasiData = $this->mapDataBiaya($realisasi);

        $html = view('reports.rekapitulasi-biaya-pdf', [
            'lahan' => $lahan,
            'tahun' => $tahun,
            'rencana' => $rencanaData,
            'realisasi' => $realisasiData,
        ])->render();

        $pdf = PDF::loadHTML($html)->setPaper('a4', 'portrait');
        return $pdf->download("Rekap Biaya Reklamasi - {$lahan->nama_lahan} - {$tahun}.pdf");
    }

    public function getDataForPDF(Lahan $lahan, string $tahun, string $tipe) : ?BiayaReklamasi
    {
        return BiayaReklamasi::with('detailBiayaReklamasi')
            ->where('lahan_id', $lahan->lahan_id)
            ->where('tipe', $tipe)
            ->where('tahun', $tahun)
            ->first();
    }

    private function mapDataBiaya(?BiayaReklamasi $biayaReklamasi): array    {
        // Return empty structure if no data
        if (!$biayaReklamasi) {
            return $this->getDefaultFieldStructure();
        }

        // Start with empty structure
        $data = $this->getDefaultFieldStructure();

        $data['subtotal_1'] = (float) $biayaReklamasi->subtotal_1;
        $data['subtotal_2'] = (float) $biayaReklamasi->subtotal_2;

        // Map detail biaya reklamasi to data array
        if ($biayaReklamasi->detailBiayaReklamasi) {
            foreach ($biayaReklamasi->detailBiayaReklamasi as $detail) {
                $field = $detail->kegiatan;
                
                if ($field && array_key_exists($field, $data)) {
                    $data[$field] = (float) $detail->biaya;
                }
            }
        }

        return $data;
    }

    private function getDefaultFieldStructure()
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
            'subtotal_1' => 0,
            'subtotal_2' => 0,
        ];
    }
}
