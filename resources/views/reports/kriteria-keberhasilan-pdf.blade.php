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

        /* Reset */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Bookman Old Style', serif;
            font-size: 9pt;
            color: #000;
            margin-left: 50pt;
            margin-right: 50pt;
            line-height: 1.12;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin: 16pt auto;
            table-layout: fixed;
            -webkit-hyphens: auto;
               -moz-hyphens: auto;
                    hyphens: auto;
        }

        thead { display: table-header-group; } /* ensure header repeats across pages */
        tfoot { display: table-footer-group; }

        /* Cells */
        th, td {
            border: 1pt solid #000;
            padding: 4pt 6pt;
            vertical-align: top;
            font-size: 8.5pt;
            line-height: 1.08;
            white-space: normal;
            overflow-wrap: break-word;
            word-break: break-word;
            -ms-word-break: normal;
            -webkit-hyphens: auto;
               -moz-hyphens: auto;
                    hyphens: auto;
        }

        th.header {
            background: #fff;
            font-weight: bold;
            text-align: center;
            font-size: 9pt;
            padding: 6pt 4pt;
        }

        .header { text-align:center; font-weight:bold; }
        .center { text-align:center; }
        .small-text { font-size:8pt; line-height:1.0; }
        .title { text-align:center; font-weight:bold; font-size:12pt; margin-bottom:12pt; }

        /* Allow rows to break across pages when necessary */
        tr { page-break-inside: auto; }
        td, th { page-break-inside: avoid; /* avoid splitting the cell itself where possible */ }

        /* Column widths - adjust to give more room to evaluation column */
        .col-no { width: 6%; min-width: 30px; max-width: 60px; }
        .col-kegiatan { width: 14%; }
        .col-obyek { width: 12%; }
        .col-param { width: 18%; }
        .col-rencana { width: 10%; }
        .col-realisasi { width: 10%; }
        .col-standar { width: 14%; }
        .col-evaluasi { width: 16%; } /* expanded to give more space for long text */

        /* Lists inside cells */
        ul.inline-list { margin: 0; padding-left: 14px; list-style: disc; }

        /* Tight padding variant */
        .tight { padding: 2pt 4pt; }

        /* Make header row not break across pages */
        thead tr { page-break-inside: avoid; page-break-after: auto; }

        /* Small visual tweak for long text - reduce word spacing a bit */
        .small-paragraph { font-size: 8.4pt; line-height: 1.05; }
    </style>
</head>
<body>
    <p style="padding-top: 44pt; text-align: center; font-weight:bold;">
        Matrik 16. Kriteria Keberhasilan Reklamasi Tahap Operasi Produksi
    </p>

    <table cellspacing="0" style="margin-top:12pt;">
        <thead>
        <tr>
            <th class="header col-no">No.</th>
            <th class="header col-kegiatan">Kegiatan Reklamasi</th>
            <th class="header col-obyek">Obyek Kegiatan</th>
            <th class="header col-param">Parameter</th>
            <th class="header col-rencana">Rencana</th>
            <th class="header col-realisasi">Realisasi/ Hasil Penilaian</th>
            <th class="header col-standar">Standar Keberhasilan</th>
            <th class="header col-evaluasi">Hasil Evaluasi</th>
        </tr>
        </thead>
        <tbody>
        <!-- Penatagunaan Lahan -->
        <tr>
            <td class="center col-no" rowspan="8">1.</td>
            <td class="col-kegiatan" rowspan="8">Penataan kriteria</td>
            <td class="col-obyek" rowspan="2">Penataan kriteria</td>
            <td class="col-param">a. luas area yang ditata</td>
            <td class="center col-rencana">{{ $detail['penatagunaan']['luas_ditata']->rencana ?? '...' }}<br/>(ha)</td>
            <td class="center col-realisasi">{{ $detail['penatagunaan']['luas_ditata']->realisasi ?? '...' }}<br/>(ha)</td>
            <td class="col-standar">{{ $detail['penatagunaan']['luas_ditata']->standar_keberhasilan ?? 'Sesuai dengan rencana' }}</td>
            <td class="col-evaluasi small-paragraph">{{ $detail['penatagunaan']['luas_ditata']->hasil_evaluasi ?? '' }}</td>
        </tr>
        <tr>
            <td class="col-param">b. stabilitas timbunan</td>
            <td class="center col-rencana">{{ $detail['penatagunaan']['stabilitas_ditata']->rencana ?? '...' }}</td>
            <td class="center col-realisasi">{{ $detail['penatagunaan']['stabilitas_ditata']->realisasi ?? '...' }}</td>
            <td class="col-standar">{{ $detail['penatagunaan']['stabilitas_ditata']->standar_keberhasilan ?? 'Tidak ada longsoran' }}</td>
            <td class="col-evaluasi small-paragraph">{{ $detail['penatagunaan']['stabilitas_ditata']->hasil_evaluasi ?? '' }}</td>
        </tr>

        <tr>
            <td class="col-obyek" rowspan="2">Penimbunan kembali kriteria bekas tambang</td>
            <td class="col-param">a. luas area yang ditimbun</td>
            <td class="center col-rencana">{{ $detail['penatagunaan']['luas_ditimbun']->rencana ?? '...' }}<br/>(ha)</td>
            <td class="center col-realisasi">{{ $detail['penatagunaan']['luas_ditimbun']->realisasi ?? '...' }}<br/>(ha)</td>
            <td class="col-standar">{{ $detail['penatagunaan']['luas_ditimbun']->standar_keberhasilan ?? 'Sesuai atau melebihi rencana' }}</td>
            <td class="col-evaluasi small-paragraph">{{ $detail['penatagunaan']['luas_ditimbun']->hasil_evaluasi ?? '' }}</td>
        </tr>
        <tr>
            <td class="col-param">b. stabilitas timbunan</td>
            <td class="center col-rencana">{{ $detail['penatagunaan']['stabilitas_ditimbun']->rencana ?? '...' }}</td>
            <td class="center col-realisasi">{{ $detail['penatagunaan']['stabilitas_ditimbun']->realisasi ?? '...' }}</td>
            <td class="col-standar">{{ $detail['penatagunaan']['stabilitas_ditimbun']->standar_keberhasilan ?? 'Tidak ada longsoran' }}</td>
            <td class="col-evaluasi small-paragraph">{{ $detail['penatagunaan']['stabilitas_ditimbun']->hasil_evaluasi ?? '' }}</td>
        </tr>

        <tr>
            <td class="col-obyek" rowspan="2">Penebaran tanah zona pengakaran</td>
            <td class="col-param">a. luas area yang ditebar</td>
            <td class="center col-rencana">{{ $detail['penatagunaan']['luas_ditebar']->rencana ?? '...' }}<br/>(ha)</td>
            <td class="center col-realisasi">{{ $detail['penatagunaan']['luas_ditebar']->realisasi ?? '...' }}<br/>(ha)</td>
            <td class="col-standar small-text">
                <ul class="inline-list">
                    <li>Baik (lebih dari 75% dari luas keseluruhan areal bekas tambang)</li>
                    <li>Sedang (50% - 75% dari luas keseluruhan areal bekas tambang)</li>
                </ul>
            </td>
            <td class="col-evaluasi small-paragraph">{{ $detail['penatagunaan']['luas_ditebar']->hasil_evaluasi ?? '' }}</td>
        </tr>
        <tr>
            <td class="col-param">b. pH tanah</td>
            <td class="center col-rencana">{{ $detail['penatagunaan']['ph_tanah']->rencana ?? '...' }}</td>
            <td class="center col-realisasi">{{ $detail['penatagunaan']['ph_tanah']->realisasi ?? '...' }}</td>
            <td class="col-standar small-text">
                <ul class="inline-list">
                    <li>Baik (5 - 6)</li>
                    <li>Sedang (4.5 - &lt;5)</li>
                </ul>
            </td>
            <td class="col-evaluasi small-paragraph">{{ $detail['penatagunaan']['ph_tanah']->hasil_evaluasi ?? '' }}</td>
        </tr>

        <tr>
            <td class="col-obyek" rowspan="2">Pengendalian erosi dan sedimentasi</td>
            <td class="col-param">a. saluran drainase</td>
            <td class="center col-rencana">{{ $detail['penatagunaan']['saluran_drainase']->rencana ?? '...' }}</td>
            <td class="center col-realisasi">{{ $detail['penatagunaan']['saluran_drainase']->realisasi ?? '...' }}</td>
            <td class="col-standar">{{ $detail['penatagunaan']['saluran_drainase']->standar_keberhasilan ?? 'Tidak terjadi erosi dan sedimentasi aktif pada kriteria yang sudah ditata' }}</td>
            <td class="col-evaluasi small-paragraph">{{ $detail['penatagunaan']['saluran_drainase']->hasil_evaluasi ?? '' }}</td>
        </tr>
        <tr>
            <td class="col-param">b. bangunan pengendali erosi</td>
            <td class="center col-rencana">{{ $detail['penatagunaan']['pengendalian_erosi']->rencana ?? '...' }}</td>
            <td class="center col-realisasi">{{ $detail['penatagunaan']['pengendalian_erosi']->realisasi ?? '...' }}</td>
            <td class="col-standar">{{ $detail['penatagunaan']['pengendalian_erosi']->standar_keberhasilan ?? 'Tidak terjadi alur-alur erosi' }}</td>
            <td class="col-evaluasi small-paragraph">{{ $detail['penatagunaan']['pengendalian_erosi']->hasil_evaluasi ?? '' }}</td>
        </tr>
        </tbody>
    </table>

    <table cellspacing="0" style="margin-top:10pt; page-break-before: always;">
        <thead>
        <tr>
            <th class="header col-no">No.</th>
            <th class="header col-kegiatan">Kegiatan Reklamasi</th>
            <th class="header col-obyek">Obyek Kegiatan</th>
            <th class="header col-param">Parameter</th>
            <th class="header col-rencana">Rencana</th>
            <th class="header col-realisasi">Realisasi/ Hasil Penilaian</th>
            <th class="header col-standar">Standar Keberhasilan</th>
            <th class="header col-evaluasi">Hasil Evaluasi</th>
        </tr>
        </thead>
        <tbody>
        
        <!-- Revegetasi -->
        <tr>
            <td class="center col-no" rowspan="5">2.</td>
            <td class="col-kegiatan" rowspan="5">Revegetasi</td>
            <td class="col-obyek" rowspan="2">Penanaman</td>
            <td class="col-param">
                a. luas area penanaman<br/>
                <span class="small-text">
                    1. tanaman penutup (cover crop)<br/>
                    2. tanaman cepat tumbuh<br/>
                    3. tanaman lokal
                </span>
            </td>
            <td class="center col-rencana">
                1. {{ $detail['revegetasi']['luas_penutup']->rencana ?? '...' }}<br>
                2. {{ $detail['revegetasi']['luas_cepat_tumbuh']->rencana ?? '...' }}<br>
                3. {{ $detail['revegetasi']['luas_lokal']->rencana ?? '...' }}<br>(ha)
            </td>
            <td class="center col-realisasi">
                1. {{ $detail['revegetasi']['luas_penutup']->realisasi ?? '...' }}<br>
                2. {{ $detail['revegetasi']['luas_cepat_tumbuh']->realisasi ?? '...' }}<br>
                3. {{ $detail['revegetasi']['luas_lokal']->realisasi ?? '...' }}<br>(ha)
            </td>
            <td class="col-standar">Sesuai dengan rencana</td>
            <td class="col-evaluasi small-paragraph">
                1. {{ $detail['revegetasi']['luas_penutup']->hasil_evaluasi ?? '' }}<br>
                2. {{ $detail['revegetasi']['luas_cepat_tumbuh']->hasil_evaluasi ?? '' }}<br>
                3. {{ $detail['revegetasi']['luas_lokal']->hasil_evaluasi ?? '' }}
            </td>
        </tr>
        <tr>
            <td class="col-param">
                b. pertumbuhan tanaman<br>
                <span class="small-text">
                    1. tanaman penutup (cover crop)<br>
                    2. tanaman cepat tumbuh<br>
                    3. tanaman lokal
                </span>
            </td>
            <td class="center col-rencana">
                1. {{ $detail['revegetasi']['pertumbuhan_penutup']->rencana ?? '...' }}<br>
                2. {{ $detail['revegetasi']['pertumbuhan_cepat_tumbuh']->rencana ?? '...' }}<br>
                3. {{ $detail['revegetasi']['pertumbuhan_lokal']->rencana ?? '...' }}
            </td>
            <td class="center col-realisasi">
                1. {{ $detail['revegetasi']['pertumbuhan_penutup']->realisasi ?? '...' }}<br>
                2. {{ $detail['revegetasi']['pertumbuhan_cepat_tumbuh']->realisasi ?? '...' }}<br>
                3. {{ $detail['revegetasi']['pertumbuhan_lokal']->realisasi ?? '...' }}
            </td>
            <td class="col-standar small-text">
                <ul class="inline-list">
                    <li>Baik (rasio tumbuh &gt; 80%)</li>
                    <li>Sedang (rasio tumbuh 60-80%)</li>
                </ul>
            </td>
            <td class="center col-evaluasi">
                1. {{ $detail['revegetasi']['pertumbuhan_penutup']->hasil_evaluasi ?? '' }}<br>
                2. {{ $detail['revegetasi']['pertumbuhan_cepat_tumbuh']->hasil_evaluasi ?? '' }}<br>
                3. {{ $detail['revegetasi']['pertumbuhan_lokal']->hasil_evaluasi ?? '' }}
            </td>
        </tr>
        <tr>
            <td class="col-obyek" rowspan="3">Pengelolaan material pembangkit air asam tambang</td>
            <td class="col-param">a. pengelolaan material</td>
            <td class="center col-rencana">{{ $detail['revegetasi']['pengelolaan_material']->rencana ?? '...' }}</td>
            <td class="center col-realisasi">{{ $detail['revegetasi']['pengelolaan_material']->realisasi ?? '...' }}</td>
            <td class="col-standar">{{ $detail['revegetasi']['pengelolaan_material']->standar_keberhasilan ?? 'Sesuai dengan rencana' }}</td>
            <td class="center col-evaluasi">{{ $detail['revegetasi']['pengelolaan_material']->hasil_evaluasi ?? '' }}</td>
        </tr>
        <tr>
            <td class="col-param">b. bangunan pengendali erosi</td>
            <td class="center col-rencana">{{ $detail['revegetasi']['bangunan_erosi']->rencana ?? '...' }}</td>
            <td class="center col-realisasi">{{ $detail['revegetasi']['bangunan_erosi']->realisasi ?? '...' }}</td>
            <td class="col-standar">{{ $detail['revegetasi']['bangunan_erosi']->standar_keberhasilan ?? 'Tidak terjadi alur-alur erosi' }}</td>
            <td class="center col-evaluasi">{{ $detail['revegetasi']['bangunan_erosi']->hasil_evaluasi ?? '' }}</td>
        </tr>
        <tr>
            <td class="col-param">c. kolam pengendap sedimen</td>
            <td class="center col-rencana">{{ $detail['revegetasi']['kolam_sedimen']->rencana ?? '...' }}</td>
            <td class="center col-realisasi">{{ $detail['revegetasi']['kolam_sedimen']->realisasi ?? '...' }}</td>
            <td class="col-standar">{{ $detail['revegetasi']['kolam_sedimen']->standar_keberhasilan ?? 'Kualitas air keluaran memenuhi ketentuan Baku Mutu Lingkungan' }}</td>
            <td class="center col-evaluasi">{{ $detail['revegetasi']['kolam_sedimen']->hasil_evaluasi ?? '' }}</td>
        </tr>
        </tbody>
        </table>
    
    <table cellspacing="0" style="margin-top:10pt; page-break-before: always;">
        <thead>
        <tr>
            <th class="header col-no">No.</th>
            <th class="header col-kegiatan">Kegiatan Reklamasi</th>
            <th class="header col-obyek">Obyek Kegiatan</th>
            <th class="header col-param">Parameter</th>
            <th class="header col-rencana">Rencana</th>
            <th class="header col-realisasi">Realisasi/ Hasil Penilaian</th>
            <th class="header col-standar">Standar Keberhasilan</th>
            <th class="header col-evaluasi">Hasil Evaluasi</th>
        </tr>
        </thead>
        <tbody>
        <!-- Penyelesaian Akhir -->
        <tr>
            <td class="center col-no" rowspan="4">3.</td>
            <td class="col-kegiatan" rowspan="4">Penyelesaian Akhir</td>
            <td class="col-obyek">Penutupan tajuk</td>
            <td class="col-param"></td>
            <td class="center col-rencana">{{ $detail['penyelesaian']['penutupan_tajuk']->rencana ?? '...' }}<br/>(ha)</td>
            <td class="center col-realisasi">{{ $detail['penyelesaian']['penutupan_tajuk']->realisasi ?? '...' }}<br/>(ha)</td>
            <td class="col-standar">{{ $detail['penyelesaian']['penutupan_tajuk']->standar_keberhasilan ?? '>80%' }}</td>
            <td class="center col-evaluasi">{{ $detail['penyelesaian']['penutupan_tajuk']->hasil_evaluasi ?? '' }}</td>
        </tr>
        <tr>
            <td class="col-obyek" rowspan="3">Pemeliharaan</td>
            <td class="col-param">a. pemupukan</td>
            <td class="center col-rencana">{{ $detail['penyelesaian']['pemupukan']->rencana ?? '...' }}</td>
            <td class="center col-realisasi">{{ $detail['penyelesaian']['pemupukan']->realisasi ?? '...' }}</td>
            <td class="col-standar">{{ $detail['penyelesaian']['pemupukan']->standar_keberhasilan ?? 'Sesuai dengan dosis yang dianjurkan' }}</td>
            <td class="center col-evaluasi">{{ $detail['penyelesaian']['pemupukan']->hasil_evaluasi ?? '' }}</td>
        </tr>
        <tr>
            <td class="col-param">b. pengendalian gulma, hama, dan penyakit</td>
            <td class="center col-rencana">{{ $detail['penyelesaian']['pengendalian_hama']->rencana ?? '...' }}</td>
            <td class="center col-realisasi">{{ $detail['penyelesaian']['pengendalian_hama']->realisasi ?? '...' }}</td>
            <td class="col-standar">{{ $detail['penyelesaian']['pengendalian_hama']->standar_keberhasilan ?? 'Pengendalian berdasarkan hasil analisis' }}</td>
            <td class="center col-evaluasi">{{ $detail['penyelesaian']['pengendalian_hama']->hasil_evaluasi ?? '' }}</td>
        </tr>
        <tr>
            <td class="col-param">c. penyulaman</td>
            <td class="center col-rencana">{{ $detail['penyelesaian']['penyulaman']->rencana ?? '...' }}</td>
            <td class="center col-realisasi">{{ $detail['penyelesaian']['penyulaman']->realisasi ?? '...' }}</td>
            <td class="col-standar">{{ $detail['penyelesaian']['penyulaman']->standar_keberhasilan ?? 'Sesuai dengan jumlah tanaman yang mati' }}</td>
            <td class="center col-evaluasi">{{ $detail['penyelesaian']['penyulaman']->hasil_evaluasi ?? '' }}</td>
        </tr>
        </tbody>
    </table>
</body>
</html>