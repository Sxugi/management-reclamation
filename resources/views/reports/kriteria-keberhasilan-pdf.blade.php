<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="id" lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Kriteria Keberhasilan Reklamasi Tahap Operasi Produksi</title>
    <style>
        @font-face {
            font-family: 'Bookman Old Style';
            src: url('{{ storage_path('fonts/Bookman Old Style.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'Bookman Old Style';
            src: url('{{ storage_path('fonts/Bookman Old Style Bold.ttf') }}') format('truetype');
            font-weight: bold;
            font-style: normal;
        }
        * {
            margin: 0; 
            padding: 0; 
            text-indent: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Bookman Old Style', serif;
            font-size: 10pt;
            color: black;
            margin-left: 70pt;
            margin-right: 70pt;
        }
        table {
            border-collapse: collapse;
            width: calc(100% - 40pt);
            margin: 20pt auto;
        }
        td {
            border: 1pt solid black;
            padding: 4pt;
            vertical-align: top;
            font-size: 9pt;
            line-height: 1.2;
        }
        .header {
            text-align: center;
            font-weight: bold;
        }
        .center {
            text-align: center;
        }
        .small-text {
            font-size: 8pt;
        }
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
            margin-bottom: 15pt;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <p class="s1" style="padding-top: 50pt; padding-left: 164pt; padding-right: 34pt; text-indent: -104pt; line-height: 150%; text-align: center;">
        Matrik 16. Kriteria Keberhasilan Reklamasi Tahap Operasi Produksi
    </p>

    <table style="border-collapse:collapse;margin:6pt auto" cellspacing="0" class="page-break">
        <tbody>
        <tr>
            <td class="header center" style="width: 8%;">No.</td>
            <td class="header center" style="width: 12%;">Kegiatan Reklamasi</td>
            <td class="header center" style="width: 12%;">Obyek Kegiatan</td>
            <td class="header center" style="width: 18%;">Parameter</td>
            <td class="header center" style="width: 10%;">Rencana</td>
            <td class="header center" style="width: 10%;">Realisasi/ Hasil Penilaian</td>
            <td class="header center" style="width: 20%;">Standar Keberhasilan</td>
            <td class="header center" style="width: 10%;">Hasil Evaluasi</td>
        </tr>

        <!-- Penatagunaan Lahan -->
        <tr>
            <td class="center" rowspan="8">1.</td>
            <td rowspan="8">Penataan kriteria</td>
            <td rowspan="2">Penataan kriteria</td>
            <td>a. luas area yang ditata</td>
            <td class="center">{{ $detail['penatagunaan']['luas_ditata']->rencana ?? '...' }}<br/>(ha)</td>
            <td class="center">{{ $detail['penatagunaan']['luas_ditata']->realisasi ?? '...' }}<br/>(ha)</td>
            <td>{{ $detail['penatagunaan']['luas_ditata']->standar_keberhasilan ?? 'Sesuai dengan rencana' }}</td>
            <td class="center">{{ $detail['penatagunaan']['luas_ditata']->hasil_evaluasi ?? '' }}</td>
        </tr>
        <tr>
            <td>b. stabilitas timbunan</td>
            <td class="center">{{ $detail['penatagunaan']['stabilitas_ditata']->rencana ?? '...' }}</td>
            <td class="center">{{ $detail['penatagunaan']['stabilitas_ditata']->realisasi ?? '...' }}</td>
            <td>{{ $detail['penatagunaan']['stabilitas_ditata']->standar_keberhasilan ?? 'Tidak ada longsoran' }}</td>
            <td class="center">{{ $detail['penatagunaan']['stabilitas_ditata']->hasil_evaluasi ?? '' }}</td>
        </tr>
        <tr>
            <td rowspan="2">Penimbunan kembali kriteria bekas tambang</td>
            <td>a. luas area yang ditimbun</td>
            <td class="center">{{ $detail['penatagunaan']['luas_ditimbun']->rencana ?? '...' }}<br/>(ha)</td>
            <td class="center">{{ $detail['penatagunaan']['luas_ditimbun']->realisasi ?? '...' }}<br/>(ha)</td>
            <td>{{ $detail['penatagunaan']['luas_ditimbun']->standar_keberhasilan ?? 'Sesuai atau melebihi rencana' }}</td>
            <td class="center">{{ $detail['penatagunaan']['luas_ditimbun']->hasil_evaluasi ?? '' }}</td>
        </tr>
        <tr>
            <td>b. stabilitas timbunan</td>
            <td class="center">{{ $detail['penatagunaan']['stabilitas_ditimbun']->rencana ?? '...' }}</td>
            <td class="center">{{ $detail['penatagunaan']['stabilitas_ditimbun']->realisasi ?? '...' }}</td>
            <td>{{ $detail['penatagunaan']['stabilitas_ditimbun']->standar_keberhasilan ?? 'Tidak ada longsoran' }}</td>
            <td class="center">{{ $detail['penatagunaan']['stabilitas_ditimbun']->hasil_evaluasi ?? '' }}</td>
        </tr>
        <tr>
            <td rowspan="2">Penebaran tanah zona pengakaran</td>
            <td>a. luas area yang ditebar</td>
            <td class="center">{{ $detail['penatagunaan']['luas_ditebar']->rencana ?? '...' }}<br/>(ha)</td>
            <td class="center">{{ $detail['penatagunaan']['luas_ditebar']->realisasi ?? '...' }}<br/>(ha)</td>
            <td class="small-text" style="padding-left: 15px">
                <li> Baik (lebih dari 75% dari luas keseluruhan areal bekas tambang)</li>
                <li>Sedang (50%-75% dari luas keseluruhan areal bekas tambang)</li>
            </td>
            <td class="center">{{ $detail['penatagunaan']['luas_ditebar']->hasil_evaluasi ?? '' }}</td>
        </tr>
        <tr>
            <td>b. pH tanah</td>
            <td class="center">{{ $detail['penatagunaan']['ph_tanah']->rencana ?? '...' }}</td>
            <td class="center">{{ $detail['penatagunaan']['ph_tanah']->realisasi ?? '...' }}</td>
            <td class="small-text" style="padding-left: 15px">
                <li> Baik (5 - 6)</li>
                <li>Sedang (4,5 - <5)</li>
            </td>
            <td class="center">{{ $detail['penatagunaan']['ph_tanah']->hasil_evaluasi ?? '' }}</td>
        </tr>
        <tr>
            <td rowspan="2">Pengendalian erosi dan sedimentasi</td>
            <td>a. saluran drainase</td>
            <td class="center">{{ $detail['penatagunaan']['saluran_drainase']->rencana ?? '...' }}</td>
            <td class="center">{{ $detail['penatagunaan']['saluran_drainase']->realisasi ?? '...' }}</td>
            <td>{{ $detail['penatagunaan']['saluran_drainase']->standar_keberhasilan ?? 'Tidak terjadi erosi dan sedimentasi aktif pada kriteria yang sudah ditata' }}</td>
            <td class="center">{{ $detail['penatagunaan']['saluran_drainase']->hasil_evaluasi ?? '' }}</td>
        </tr>
        <tr>
            <td>b. bangunan pengendali erosi</td>
            <td class="center">{{ $detail['penatagunaan']['pengendalian_erosi']->rencana ?? '...' }}</td>
            <td class="center">{{ $detail['penatagunaan']['pengendalian_erosi']->realisasi ?? '...' }}</td>
            <td>{{ $detail['penatagunaan']['pengendalian_erosi']->standar_keberhasilan ?? 'Tidak terjadi alur-alur erosi' }}</td>
            <td class="center">{{ $detail['penatagunaan']['pengendalian_erosi']->hasil_evaluasi ?? '' }}</td>
        </tr>
        </tbody>
    </table>

    <table style="border-collapse:collapse;margin:6pt auto;padding-top: 50pt;" cellspacing="0">
        <tbody>
        <tr>
            <td class="header center" style="width: 8%;">No.</td>
            <td class="header center" style="width: 12%;">Kegiatan Reklamasi</td>
            <td class="header center" style="width: 12%;">Obyek Kegiatan</td>
            <td class="header center" style="width: 18%;">Parameter</td>
            <td class="header center" style="width: 10%;">Rencana</td>
            <td class="header center" style="width: 10%;">Realisasi/ Hasil Penilaian</td>
            <td class="header center" style="width: 20%;">Standar Keberhasilan</td>
            <td class="header center" style="width: 10%;">Hasil Evaluasi</td>
        </tr>
        
        <!-- Revegetasi -->
        <tr>
            <td class="center" rowspan="5">2.</td>
            <td rowspan="5">Revegetasi</td>
            <td rowspan="2">Penanaman</td>
            <td>a. luas area penanaman<br/>
                <span class="small-text">1. tanaman penutup (cover crop)<br/>
                2. tanaman cepat tumbuh<br/>
                3. tanaman lokal</span>
            </td>
            <td class="center">
                1. {{ $detail['revegetasi']['luas_penutup']->rencana ?? '...' }}<br>
                2. {{ $detail['revegetasi']['luas_cepat_tumbuh']->rencana ?? '...' }}<br>
                3. {{ $detail['revegetasi']['luas_lokal']->rencana ?? '...' }}<br>(ha)
            </td>
            <td class="center">
                1. {{ $detail['revegetasi']['luas_penutup']->realisasi ?? '...' }}<br>
                2. {{ $detail['revegetasi']['luas_cepat_tumbuh']->realisasi ?? '...' }}<br>
                3. {{ $detail['revegetasi']['luas_lokal']->realisasi ?? '...' }}<br>(ha)
            </td>
            <td>
                Sesuai dengan rencana
            </td>
            <td class="center">
                1. {{ $detail['revegetasi']['luas_penutup']->hasil_evaluasi ?? '' }}<br>
                2. {{ $detail['revegetasi']['luas_cepat_tumbuh']->hasil_evaluasi ?? '' }}<br>
                3. {{ $detail['revegetasi']['luas_lokal']->hasil_evaluasi ?? '' }}
            </td>
        </tr>
        <tr>
            <td>
                b. pertumbuhan tanaman
                <br>
                <span class="small-text">
                    1. tanaman penutup (cover crop)<br>
                    2. tanaman cepat tumbuh<br>
                    3. tanaman lokal
                </span>
            </td>
            <td class="center">
                1. {{ $detail['revegetasi']['pertumbuhan_penutup']->rencana ?? '...' }}<br>
                2. {{ $detail['revegetasi']['pertumbuhan_cepat_tumbuh']->rencana ?? '...' }}<br>
                3. {{ $detail['revegetasi']['pertumbuhan_lokal']->rencana ?? '...' }}<br>(ha)
            </td>
            <td class="center">
                1. {{ $detail['revegetasi']['pertumbuhan_penutup']->realisasi ?? '...' }}<br>
                2. {{ $detail['revegetasi']['pertumbuhan_cepat_tumbuh']->realisasi ?? '...' }}<br>
                3. {{ $detail['revegetasi']['pertumbuhan_lokal']->realisasi ?? '...' }}<br>(ha)
            </td>
            <td class="small-text" style="padding-left: 15px">
                <li> Baik (rasio tumbuh > 80%)</li>
                <li>Sedang (rasio tumbuh 60-80%)</li>
            </td>
            <td class="center">
                1. {{ $detail['revegetasi']['pertumbuhan_penutup']->hasil_evaluasi ?? '' }}<br>
                2. {{ $detail['revegetasi']['pertumbuhan_cepat_tumbuh']->hasil_evaluasi ?? '' }}<br>
                3. {{ $detail['revegetasi']['pertumbuhan_lokal']->hasil_evaluasi ?? '' }}
            </td>
        </tr>
        <tr>
            <td rowspan="3">Pengelolaan material pembangkit air asam tambang</td>
            <td>a. pengelolaan material</td>
            <td class="center">{{ $detail['revegetasi']['pengelolaan_material']->rencana ?? '...' }}</td>
            <td class="center">{{ $detail['revegetasi']['pengelolaan_material']->realisasi ?? '...' }}</td>
            <td>{{ $detail['revegetasi']['pengelolaan_material']->standar_keberhasilan ?? 'Sesuai dengan rencana' }}</td>
            <td class="center">{{ $detail['revegetasi']['pengelolaan_material']->hasil_evaluasi ?? '' }}</td>
        </tr>
        <tr>
            <td>b. bangunan pengendali erosi</td>
            <td class="center">{{ $detail['revegetasi']['bangunan_erosi']->rencana ?? '...' }}</td>
            <td class="center">{{ $detail['revegetasi']['bangunan_erosi']->realisasi ?? '...' }}</td>
            <td>{{ $detail['revegetasi']['bangunan_erosi']->standar_keberhasilan ?? 'Tidak terjadi alur-alur erosi' }}</td>
            <td class="center">{{ $detail['revegetasi']['bangunan_erosi']->hasil_evaluasi ?? '' }}</td>
        </tr>
        <tr>
            <td>c. kolam pengendap sedimen</td>
            <td class="center">{{ $detail['revegetasi']['kolam_sedimen']->rencana ?? '...' }}</td>
            <td class="center">{{ $detail['revegetasi']['kolam_sedimen']->realisasi ?? '...' }}</td>
            <td>{{ $detail['revegetasi']['kolam_sedimen']->standar_keberhasilan ?? 'Kualitas air keluaran memenuhi ketentuan Baku Mutu Lingkungan' }}</td>
            <td class="center">{{ $detail['revegetasi']['kolam_sedimen']->hasil_evaluasi ?? '' }}</td>
        </tr>

        <!-- Penyelesaian Akhir -->
        <tr>
            <td class="center" rowspan="4">3.</td>
            <td rowspan="4">Penyelesaian Akhir</td>
            <td>Penutupan tajuk</td>
            <td></td>
            <td class="center">{{ $detail['penyelesaian']['penutupan_tajuk']->rencana ?? '...' }}</td>
            <td class="center">{{ $detail['penyelesaian']['penutupan_tajuk']->realisasi ?? '...' }}</td>
            <td>{{ $detail['penyelesaian']['penutupan_tajuk']->standar_keberhasilan ?? '>80%' }}</td>
            <td class="center">{{ $detail['penyelesaian']['penutupan_tajuk']->hasil_evaluasi ?? '' }}</td>
        </tr>
        <tr>
            <td rowspan="3">Pemeliharaan</td>
            <td>a. pemupukan</td>
            <td class="center">{{ $detail['penyelesaian']['pemupukan']->rencana ?? '...' }}</td>
            <td class="center">{{ $detail['penyelesaian']['pemupukan']->realisasi ?? '...' }}</td>
            <td>{{ $detail['penyelesaian']['pemupukan']->standar_keberhasilan ?? 'Sesuai dengan dosis yang dianjurkan' }}</td>
            <td class="center">{{ $detail['penyelesaian']['pemupukan']->hasil_evaluasi ?? '' }}</td>
        </tr>
        <tr>
            <td>b. pengendalian gulma, hama, dan penyakit</td>
            <td class="center">{{ $detail['penyelesaian']['pengendalian_hama']->rencana ?? '...' }}</td>
            <td class="center">{{ $detail['penyelesaian']['pengendalian_hama']->realisasi ?? '...' }}</td>
            <td>{{ $detail['penyelesaian']['pengendalian_hama']->standar_keberhasilan ?? 'Pengendalian berdasarkan hasil analisis' }}</td>
            <td class="center">{{ $detail['penyelesaian']['pengendalian_hama']->hasil_evaluasi ?? '' }}</td>
        </tr>
        <tr>
            <td>c. penyulaman</td>
            <td class="center">{{ $detail['penyelesaian']['penyulaman']->rencana ?? '...' }}</td>
            <td class="center">{{ $detail['penyelesaian']['penyulaman']->realisasi ?? '...' }}</td>
            <td>{{ $detail['penyelesaian']['penyulaman']->standar_keberhasilan ?? 'Sesuai dengan jumlah tanaman yang mati' }}</td>
            <td class="center">{{ $detail['penyelesaian']['penyulaman']->hasil_evaluasi ?? '' }}</td>
        </tr>
        </tbody>
    </table>
</body>
</html>