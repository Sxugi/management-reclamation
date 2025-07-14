<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="id" lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rekapitulasi Pelaksanaan Reklamasi Tahap Operasi</title>
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
        }
        .s1 { 
            color: black; 
            font-family: 'Bookman Old Style', serif; 
            font-style: normal; 
            font-size: 12pt; 
        }
        .s2 { 
            color: black; 
            font-family: 'Bookman Old Style', serif; 
            text-decoration: none; 
            font-size: 10pt; 
        }
        .p, p { 
            color: black; 
            font-family: 'Bookman Old Style', serif; 
            text-decoration: none; 
            font-size: 9pt; 
            margin:0pt; 
        }
        table, tbody {
            vertical-align: top; 
            overflow: visible; 
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <p class="s1" style="padding-top: 50pt; padding-left: 164pt; padding-right: 34pt; text-indent: -104pt; line-height: 150%; text-align: center;">
        Matrik 14 Rekapitulasi Pelaksanaan Reklamasi Tahap Operasi Produksi
    </p>

    <table style="border-collapse:collapse;margin:6pt auto" cellspacing="0" class="page-break">
        <tbody>
            <!-- Header Tabel -->
            <tr style="height:58pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 11pt;text-indent: 0pt;line-height: 12pt;text-align: left;">No.</p>
                </td>
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="text-indent: 0pt;line-height: 12pt;text-align: center;">Uraian</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="text-indent: 0pt;line-height: 12pt;text-align: center;">Tahun {{ $tahun }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="text-indent: 0pt;line-height: 12pt;text-align: center;">Kumulatif s.d. Tahun<br/>{{ $tahun }}</p>
                </td>
            </tr>

            <!-- Lahan yang dibuka section -->
            <tr style="height:20pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="text-indent: 0pt;text-align: center;">1.</p>
                </td>
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Lahan yang dibuka (ha)</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
            </tr>
            
            <tr style="height:21pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" rowspan="12">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 5pt;text-indent: 0pt;line-height: 12pt;text-align: left;">a. area Penambangan</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['area_penambangan'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['area_penambangan'] ?? 0, 2) }}</p>
                </td>
            </tr>
            
            <tr style="height:21pt">
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 6pt;text-indent: 0pt;line-height: 12pt;text-align: left;">b. area di luar Penambangan:</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
            </tr>
            
            <tr style="height:28pt">
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">1) timbunan tanah zona pengakaran</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['timbuman_tanah_pengakaran'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['timbuman_tanah_pengakaran'] ?? 0, 2) }}</p>
                </td>
            </tr>
            
            <tr style="height:31pt">
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">2) timbunan batuan samping dan/atau tanah/batuan penutup</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['timbuman_batuan_samping'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['timbuman_batuan_samping'] ?? 0, 2) }}</p>
                </td>
            </tr>
            
            <tr style="height:17pt">
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">3) timbunan komoditas tambang</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['timbuman_komoditas_tambang'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['timbuman_komoditas_tambang'] ?? 0, 2) }}</p>
                </td>
            </tr>

            <tr style="height:17pt">
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">4) timbunan/penyimpanan limbah fasilitas penunjang</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['timbuman_limbah_fasilitas'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['timbuman_limbah_fasilitas'] ?? 0, 2) }}</p>
                </td>
            </tr>
            
            <tr style="height:27pt">
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">5) jalan tambang dan/atau jalan angkut</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['jalan_tambang'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['jalan_tambang'] ?? 0, 2) }}</p>
                </td>
            </tr>

            <tr style="height:17pt">
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">6) kolam sedimen</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['kolam_sedimen'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['kolam_sedimen'] ?? 0, 2) }}</p>
                </td>
            </tr>

            <tr style="height:40pt">
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">7) instalasi dan fasilitas pengolahan dan/atau pemurnian</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['fasilitas_pengolahan'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['fasilitas_pengolahan'] ?? 0, 2) }}</p>
                </td>
            </tr>

            <tr style="height:27pt">
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">8) kantor dan perumahan (<i>camp</i> atau <i>flying camp</i>)</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['kantor_perumahan'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['kantor_perumahan'] ?? 0, 2) }}</p>
                </td>
            </tr>

            <tr style="height:20pt">
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">9) bengkel</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['bengkel'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['bengkel'] ?? 0, 2) }}</p>
                </td>
            </tr>

            <tr style="height:20pt">
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">10) fasilitas penunjang lainnya</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['fasilitas_penunjang'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['fasilitas_penunjang'] ?? 0, 2) }}</p>
                </td>
            </tr>

            <!-- Penambangan Section -->
            <tr style="height:19pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 7pt;text-indent: 0pt;line-height: 12pt;text-align: center;">2.</p>
                </td>
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 5pt;text-indent: 0pt;line-height: 12pt;text-align: left;">Penambangan</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
            </tr>
            
            <tr style="height:20pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" rowspan="3">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 6pt;text-indent: 0pt;line-height: 12pt;text-align: left;">a. lahan selesai ditambang (ha)</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['lahan_selesai_ditambang'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['lahan_selesai_ditambang'] ?? 0, 2) }}</p>
                </td>
            </tr>

            <tr style="height:21pt">
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 6pt;text-indent: 0pt;line-height: 12pt;text-align: left;">b. lahan/<i>front </i>aktif ditambang (ha)</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['lahan_aktif_ditambang'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['lahan_aktif_ditambang'] ?? 0, 2) }}</p>
                </td>
            </tr>

            <tr style="height:42pt">
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 6pt;text-indent: 0pt;line-height: 12pt;text-align: left;">c. volume batuan samping dan/atau tanah/batuan penutup yang digali (BCM atau m<sup>3</sup>)</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['volume_batuan_samping'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['volume_batuan_samping'] ?? 0, 2) }}</p>
                </td>
            </tr>

            <!-- Penimbunan Section -->
            <tr style="height:20pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 7pt;text-indent: 0pt;line-height: 12pt;text-align: center;">3.</p>
                </td>
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 5pt;text-indent: 0pt;line-height: 12pt;text-align: left;">Penimbunan</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
            </tr>

            <tr style="height:20pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" rowspan="4">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 6pt;text-indent: 0pt;line-height: 12pt;text-align: left;">a. di bekas tambang (ha)</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['penimbunan_bekas_tambang'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['penimbunan_bekas_tambang'] ?? 0, 2) }}</p>
                </td>
            </tr>

            <tr style="height:21pt">
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 6pt;text-indent: 0pt;line-height: 12pt;text-align: left;">b. di luar bekas tambang (ha)</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['penimbunan_diluar_bekas_tambang'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['penimbunan_diluar_bekas_tambang'] ?? 0, 2) }}</p>
                </td>
            </tr>

            <tr style="height:28pt">
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 6pt;text-indent: 0pt;line-height: 12pt;text-align: left;">c. volume yang ditimbun di bekas tambang (m<sup>3</sup>)</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['volume_bekas_tambang'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['volume_bekas_tambang'] ?? 0, 2) }}</p>
                </td>
            </tr>

            <tr style="height:31pt">
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 6pt;text-indent: 0pt;line-height: 114%;text-align: left;">d. volume yang ditimbun di luar bekas tambang (m<sup>3</sup>)</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['volume_diluar_bekas_tambang'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['volume_diluar_bekas_tambang'] ?? 0, 2) }}</p>
                </td>
            </tr>
        </tbody>
    </table>

    <table style="border-collapse:collapse;margin:6pt auto;padding-top: 50pt;" cellspacing="0">
        <tbody>
            <!-- Header Tabel -->
            <tr style="height:48pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 11pt;text-indent: 0pt;line-height: 12pt;text-align: left;">No.</p>
                </td>
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="text-indent: 0pt;line-height: 12pt;text-align: center;">Uraian</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="text-indent: 0pt;line-height: 12pt;text-align: center;">Tahun {{ $tahun }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="text-indent: 0pt;line-height: 12pt;text-align: center;">Kumulatif s.d. Tahun {{ $tahun }}</p>
                </td>
            </tr>
            
            <!-- Reklamasi Section -->
            <tr style="height:14pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 7pt;text-indent: 0pt;line-height: 12pt;text-align: center;">4.</p>
                </td>
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 5pt;text-indent: 0pt;line-height: 12pt;text-align: left;">Reklamasi</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
            </tr>

            <tr style="height:17pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-right-style:solid;border-right-width:1pt;border-bottom-style:solid;border-bottom-width:1pt" rowspan="10">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 6pt;text-indent: 0pt;line-height: 12pt;text-align: left;">a. penatagunaan lahan:</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
            </tr>

            <tr style="height:17pt">
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">1) penataan lahan (ha)</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['penataan_tanah'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['penataan_tanah'] ?? 0, 2) }}</p>
                </td>
            </tr>

            <tr style="height:27pt">
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">2) penebaran  tanah  zona pengakaran (ha)</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['penebaran_tanah_pengakaran'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['penebaran_tanah_pengakaran'] ?? 0, 2) }}</p>
                </td>
            </tr>

            <tr style="height:26pt">
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">3) pengendalian  erosi  dan sedimentasi</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['pengendalian_erosi'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['pengendalian_erosi'] ?? 0, 2) }}</p>
                </td>
            </tr>

            <tr style="height:17pt">
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 6pt;text-indent: 0pt;line-height: 12pt;text-align: left;">b. Revegetasi (ha):</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
            </tr>

            <tr style="height:14pt">
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">1) analisis kualitas tanah</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['kualitas_tanah'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['kualitas_tanah'] ?? 0, 2) }}</p>
                </td>
            </tr>

            <tr style="height:15pt">
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">2) pemupukan (ha)</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['pemupukan'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['pemupukan'] ?? 0, 2) }}</p>
                </td>
            </tr>

            <tr style="height:32pt">
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;text-align: left;">3) pengadaan bibit (batang dan/atau kg)</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['pengadaan_bibit'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['pengadaan_bibit'] ?? 0, 2) }}</p>
                </td>
            </tr>

            <tr style="height:17pt">
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">4) penanaman (batang)</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['penanaman'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['penanaman'] ?? 0, 2) }}</p>
                </td>
            </tr>

            <tr style="height:14pt">
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">5) pemeliharaan tanaman (ha)</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['pemeliharaan_tanaman'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['pemeliharaan_tanaman'] ?? 0, 2) }}</p>
                </td>
            </tr>

            <!-- Pencegahan dan Penanggulangan Air Asam Tambang Section -->
            <tr style="height:26pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="text-indent: 0pt;line-height: 12pt;text-align: center;">5.</p>
                </td>
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 5pt;text-indent: 0pt;line-height: 12pt;text-align: left;">Pencegahan dan penanggulangan air asam tambang</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['pencegahan_air_asam'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['pencegahan_air_asam'] ?? 0, 2) }}</p>
                </td>
            </tr>

            <!-- Pekerjaan Sipil Section -->
            <tr style="height:40pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="text-indent: 0pt;line-height: 12pt;text-align: center;">6.</p>
                </td>
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 5pt;text-indent: 0pt;line-height: 12pt;text-align: left;">Pekerjaan sipil sesuai peruntukan lahan Pascatambang atau program Reklamasi bentuk lain (satuan luas)</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['pekerjaan_sipil'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['pekerjaan_sipil'] ?? 0, 2) }}</p>
                </td>
            </tr>

            <!-- Rencana Pemanfaatan Lubang Bekas Tambang Section -->
            <tr style="height:27pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" rowspan="5">
                    <p class="s2" style="text-indent: 0pt;line-height: 12pt;text-align: center;">7.</p>
                </td>
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 5pt;text-indent: 0pt;line-height: 12pt;text-align: left;">Rencana pemanfaatan lubang bekastambang (<i>void</i>):</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
            </tr>

            <tr style="height:13pt">
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 6pt;text-indent: 0pt;line-height: 12pt;text-align: left;">a. stabilisasi lereng</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['stabilisasi_lereng'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['stabilisasi_lereng'] ?? 0, 2) }}</p>
                </td>
            </tr>

            <tr style="height:31pt">
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 6pt;text-indent: 0pt;line-height: 12pt;text-align: left;">b. pengamanan lubang bekas tambang (void) dari kecelakaan</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['pengamanan_lubang'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['pengamanan_lubang'] ?? 0, 2) }}</p>
                </td>
            </tr>

            <tr style="height:59pt">
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 6pt;text-indent: 0pt;line-height: 12pt;text-align: left;">c. pemulihan dan pemantauan kualitas air dan serta pengelolaan air dalam lubang bekas tambang (void) sesuai dengan peruntukannya</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['pemulihan_kualitas_air'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['pemulihan_kualitas_air'] ?? 0, 2) }}</p>
                </td>
            </tr>

            <tr style="height:31pt">
                <td style="width:185pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 6pt;text-indent: 0pt;line-height: 12pt;text-align: left;">d. pemeliharaan lubang bekas tambang (void)</p>
                </td>
                <td style="width:56pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataTahunan['pemeliharaan_lubang'] ?? 0, 2) }}</p>
                </td>
                <td style="width:135pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataKumulatif['pemeliharaan_lubang'] ?? 0, 2) }}</p>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>