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
            <!-- Header Tabel -->
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

        <!-- Row 1: Penataan kriteria -->
        <tr>
            <td class="center" rowspan="8">1.</td>
            <td rowspan="8">Penataan kriteria</td>
            <td rowspan="2">Penataan kriteria</td>
            <td>a. luas area yang ditata</td>
            <td class="center">{{ $kriteria->rencana_luas_ditata ?? '...' }}<br/>(ha)</td>
            <td class="center">{{ $kriteria->realisasi_luas_ditata ?? '...' }}<br/>(ha)</td>
            <td>Sesuai dengan rencana</td>
            <td class="center">{{ $kriteria->evaluasi_luas_ditata ?? '' }}</td>
        </tr>

        <tr>
            <td>b. stabilitas timbunan</td>
            <td class="center">{{ $kriteria->rencana_stabilitas_ditata ?? '...' }}</td>
            <td class="center">{{ $kriteria->realisasi_stabilitas_ditata ?? '...' }}</td>
            <td>Tidak ada longsoran</td>
            <td class="center">{{ $kriteria->evaluasi_stabilitas_ditata ?? '' }}</td>
        </tr>

        <!-- Penimbunan Kembali -->
        <tr>
            <td rowspan="2">Penimbunan kembali kriteria bekas tambang</td>
            <td>a. luas area yang ditimbun</td>
            <td class="center">{{ $kriteria->rencana_luas_ditimbun ?? '...' }}<br/>(ha)</td>
            <td class="center">{{ $kriteria->realisasi_luas_ditimbun ?? '...' }}<br/>(ha)</td>
            <td>Sesuai atau melebihi rencana</td>
            <td class="center">{{ $kriteria->evaluasi_luas_ditimbun ?? '' }}</td>
        </tr>

        <tr>
            <td>b. stabilitas timbunan</td>
            <td class="center">{{ $kriteria->rencana_stabilitas_ditimbun ?? '...' }}</td>
            <td class="center">{{ $kriteria->realisasi_stabilitas_ditimbun ?? '...' }}</td>
            <td>Tidak ada longsoran</td>
            <td class="center">{{ $kriteria->evaluasi_stabilitas_ditimbun ?? '' }}</td>
        </tr>

        <!-- Penebaran Tanah -->
        <tr>
            <td rowspan="2">Penebaran tanah zona pengakaran</td>
            <td>a. luas area yang ditebar</td>
            <td class="center">{{ $kriteria->rencana_luas_ditebar ?? '...' }}<br/>(ha)</td>
            <td class="center">{{ $kriteria->realisasi_luas_ditebar ?? '...' }}<br/>(ha)</td>
            <td class="small-text" style="padding-left: 15px"><li> Baik (lebih dari 75% dari luas keseluruhan areal bekas tambang)</li> <li>Sedang (50%-75% dari luas keseluruhan areal bekas tambang)</li></td>
            <td class="center">{{ $kriteria->evaluasi_luas_ditebar ?? '' }}</td>
        </tr>

        <tr>
            <td>b. pH tanah</td>
            <td class="center">{{ $kriteria->rencana_ph_tanah ?? '...' }}</td>
            <td class="center">{{ $kriteria->realisasi_ph_tanah ?? '...' }}</td>
            <td class="small-text" style="padding-left: 15px"><li> Baik (5 - 6)</li> <li>Sedang (4,5 - <5)</li></td>
            <td class="center">{{ $kriteria->evaluasi_ph_tanah ?? '' }}</td>
        </tr>

        <!-- Pengendalian Erosi -->
        <tr>
            <td rowspan="2">Pengendalian erosi dan sedimentasi</td>
            <td>a. saluran drainase</td>
            <td class="center">{{ $kriteria->rencana_saluran_drainase ?? '...' }}</td>
            <td class="center">{{ $kriteria->realisasi_saluran_drainase ?? '...' }}</td>
            <td>Tidak terjadi erosi dan sedimentasi aktif pada kriteria yang sudah ditata</td>
            <td class="center">{{ $kriteria->evaluasi_saluran_drainase ?? '' }}</td>
        </tr>

        <tr>
            <td>b. bangunan pengendali erosi</td>
            <td class="center">{{ $kriteria->rencana_pengendalian_erosi ?? '...' }}</td>
            <td class="center">{{ $kriteria->realisasi_pengendalian_erosi ?? '...' }}</td>
            <td>Tidak terjadi alur-alur erosi</td>
            <td class="center">{{ $kriteria->evaluasi_pengendalian_erosi ?? '' }}</td>
        </tr>
        </tbody>
    </table>

    <table style="border-collapse:collapse;margin:6pt auto;padding-top: 50pt;" cellspacing="0">
        <tbody>
            <!-- Header Tabel -->
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
        
        <tr>
            <td class="center" rowspan="5">2.</td>
            <td rowspan="5">Revegetasi</td>
            <td rowspan="2">Penanaman</td>
            <td>a. luas area penanaman<br/>
                <span class="small-text">1. tanaman penutup (cover crop)<br/>
                2. tanaman cepat tumbuh<br/>
                3. tanaman lokal</span>
            </td>
            <td class="center">{{ $kriteria->rencana_luas_penanaman ?? '...' }}<br/>(ha)</td>
            <td class="center">{{ $kriteria->realisasi_luas_penanaman ?? '...' }}<br/>(ha)</td>
            <td>Sesuai dengan rencana</td>
            <td class="center">{{ $kriteria->evaluasi_luas_penanaman ?? '' }}</td>
        </tr>

        <tr>
            <td>b. pertumbuhan tanaman<br/>
                <span class="small-text">1. tanaman penutup (cover crop)<br/>
                2. tanaman cepat tumbuh<br/>
                3. tanaman lokal</span>
            </td>
            <td class="center">{{ $kriteria->rencana_pertumbuhan_tanaman ?? '...' }}<br/>(ha)</td>
            <td class="center">{{ $kriteria->realisasi_pertumbuhan_tanaman ?? '...' }}<br/>(ha)</td>
            <td class="small-text" style="padding-left: 15px"><li> Baik (rasio tumbuh > 80%)</li> <li>Sedang (rasio tumbuh 60-80%)</li></td>
            <td class="center">{{ $kriteria->evaluasi_pertumbuhan_tanaman ?? '' }}</td>
        </tr>

        <!-- Pengelolaan Material -->
        <tr>
            <td rowspan="3">Pengelolaan material pembangkit air asam tambang</td>
            <td>a. pengelolaan material</td>
            <td class="center">{{ $kriteria->rencana_pengelolaan_material ?? '...' }}</td>
            <td class="center">{{ $kriteria->realisasi_pengelolaan_material ?? '...' }}</td>
            <td>Sesuai dengan rencana</td>
            <td class="center">{{ $kriteria->evaluasi_pengelolaan_material ?? '' }}</td>
        </tr>

        <tr>
            <td>b. bangunan pengendali erosi</td>
            <td class="center">{{ $kriteria->rencana_bangunan_erosi ?? '...' }}</td>
            <td class="center">{{ $kriteria->realisasi_bangunan_erosi ?? '...' }}</td>
            <td>Tidak terjadi alur-alur erosi</td>
            <td class="center">{{ $kriteria->evaluasi_bangunan_erosi ?? '' }}</td>
        </tr>

        <tr>
            <td>c. kolam pengendap sedimen</td>
            <td class="center">{{ $kriteria->rencana_kolam_sedimen ?? '...' }}</td>
            <td class="center">{{ $kriteria->realisasi_kolam_sedimen ?? '...' }}</td>
            <td>Kualitas air keluaran memenuhi ketentuan Baku Mutu Lingkungan</td>
            <td class="center">{{ $kriteria->evaluasi_kolam_sedimen ?? '' }}</td>
        </tr>

        <!-- Row 3: Penyelesaian Akhir -->
        <tr>
            <td class="center" rowspan="4">3.</td>
            <td rowspan="4">Penyelesaian Akhir</td>
            <td>Penutupan tajuk</td>
            <td></td>
            <td class="center">{{ $kriteria->rencana_penutupan_tajuk ?? '...' }}</td>
            <td class="center">{{ $kriteria->realisasi_penutupan_tajuk ?? '...' }}</td>
            <td>>80%</td>
            <td class="center">{{ $kriteria->evaluasi_penutupan_tajuk ?? '' }}</td>
        </tr>

        <tr>
            <td rowspan="3">Pemeliharaan</td>
            <td>a. pemupukan</td>
            <td class="center">{{ $kriteria->rencana_pemupukan ?? '...' }}</td>
            <td class="center">{{ $kriteria->realisasi_pemupukan ?? '...' }}</td>
            <td>Sesuai dengan dosis yang dianjurkan</td>
            <td class="center">{{ $kriteria->evaluasi_pemupukan ?? '' }}</td>
        </tr>

        <tr>
            <td>b. pengendalian gulma, hama, dan penyakit</td>
            <td class="center">{{ $kriteria->rencana_pengendalian_hama ?? '...' }}</td>
            <td class="center">{{ $kriteria->realisasi_pengendalian_hama ?? '...' }}</td>
            <td>Pengendalian berdasarkan hasil analisis</td>
            <td class="center">{{ $kriteria->evaluasi_pengendalian_hama ?? '' }}</td>
        </tr>

        <tr>
            <td>c. penyulaman</td>
            <td class="center">{{ $kriteria->rencana_penyulaman ?? '...' }}</td>
            <td class="center">{{ $kriteria->realisasi_penyulaman ?? '...' }}</td>
            <td>Sesuai dengan jumlah tanaman yang mati</td>
            <td class="center">{{ $kriteria->evaluasi_penyulaman ?? '' }}</td>
        </tr>
        </tbody>
    </table>
</body>
</html>