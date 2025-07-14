<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="id" lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rencana Reklamasi Tahap Operasi</title>
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
    <p class="s1" style="padding-top: 50pt; padding-left: 164pt; padding-right: 34pt; text-indent: -104pt; line-height: 150%; text-align: left;">
        Matrik 2.1 Rencana Reklamasi Tahap Operasi Produksi Periode Tahun {{ $tahunRange }}
    </p>

    <table style="border-collapse:collapse;margin:6pt auto" cellspacing="0" class="page-break">
        <tbody>
            <!-- Header Tabel -->
            <tr style="height:20pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" rowspan="2">
                    <p class="s2" style="padding-left: 11pt;text-indent: 0pt;line-height: 12pt;text-align: left;">NO.</p>
                </td>
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" rowspan="2">
                    <p class="s2" style="text-indent: 0pt;line-height: 12pt;text-align: center;">URAIAN</p>
                </td>
                <td style="width:224pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" colspan="{{ count($tahuns) }}">
                    <p class="s2" style="text-indent: 0pt;line-height: 12pt;text-align: center;">TAHUN</p>
                </td>
            </tr>
            <tr style="height:20pt">
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 5pt;text-indent: 0pt;line-height: 14pt;text-align: center;">{{ $tahun }}</p>
                </td>
                @endforeach
            </tr>

            <!-- Lahan yang dibuka section -->
            <tr style="height:20pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="text-indent: 0pt;text-align: center;">1.</p>
                </td>
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Lahan yang dibuka (ha)</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
                @endforeach
            </tr>
            
            <tr style="height:21pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" rowspan="11">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 5pt;text-indent: 0pt;line-height: 12pt;text-align: left;">a. area Penambangan</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['area_penambangan'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>
            
            <tr style="height:21pt">
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 6pt;text-indent: 0pt;line-height: 12pt;text-align: left;">b. area di luar Penambangan:</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
                @endforeach
            </tr>
            
            <tr style="height:28pt">
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">1) timbunan tanah zona pengakaran</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['timbuman_tanah_pengakaran'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>
            
            <tr style="height:31pt">
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">2) timbunan batuan samping<br/>dan/atau tanah/batuan penutup</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['timbuman_batuan_samping'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>
            
            <tr style="height:17pt">
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">3) timbunan komoditas tambang</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['timbuman_komoditas_tambang'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>
            
            <tr style="height:27pt">
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">4) jalan tambang dan/atau jalan<br/>angkut</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['jalan_tambang'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>

            <tr style="height:17pt">
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">5) kolam sedimen</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['kolam_sedimen'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>

            <tr style="height:40pt">
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">6) instalasi dan fasilitas<br/>pengolahan dan/atau<br/>pemurnian</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['fasilitas_pengolahan'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>

            <tr style="height:27pt">
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">7) kantor dan perumahan (<i>camp</i><br/>atau <i>flying camp</i>)</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['kantor_perumahan'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>

            <tr style="height:20pt">
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">8) bengkel</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['bengkel'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>

            <tr style="height:20pt">
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">9) fasilitas penunjang lainnya</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['fasilitas_penunjang'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>

            <!-- Penambangan Section -->
            <tr style="height:19pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 7pt;text-indent: 0pt;line-height: 12pt;text-align: center;">2.</p>
                </td>
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 5pt;text-indent: 0pt;line-height: 12pt;text-align: left;">Penambangan</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
                @endforeach
            </tr>
            
            <tr style="height:20pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" rowspan="3">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 6pt;text-indent: 0pt;line-height: 12pt;text-align: left;">a. lahan selesai ditambang (ha)</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['lahan_selesai_ditambang'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>

            <tr style="height:21pt">
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 6pt;text-indent: 0pt;line-height: 12pt;text-align: left;">b. lahan/<i>front </i>aktif ditambang (ha)</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['lahan_aktif_ditambang'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>

            <tr style="height:42pt">
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 6pt;text-indent: 0pt;line-height: 12pt;text-align: left;">c. volume batuan samping dan/atau<br/>tanah/batuan penutup yang digali (BCM<br/>atau m<sup>3</sup>)</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['volume_batuan_samping'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>

            <!-- Penimbunan Section -->
            <tr style="height:20pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 7pt;text-indent: 0pt;line-height: 12pt;text-align: center;">3.</p>
                </td>
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 5pt;text-indent: 0pt;line-height: 12pt;text-align: left;">Penimbunan</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
                @endforeach
            </tr>

            <tr style="height:20pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" rowspan="4">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 6pt;text-indent: 0pt;line-height: 12pt;text-align: left;">a. di bekas tambang (ha)</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['penimbunan_bekas_tambang'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>

            <tr style="height:21pt">
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 6pt;text-indent: 0pt;line-height: 12pt;text-align: left;">b. di luar bekas tambang (ha)</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['penimbunan_diluar_bekas_tambang'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>

            <tr style="height:28pt">
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 6pt;text-indent: 0pt;line-height: 12pt;text-align: left;">c. volume yang ditimbun di<br/>bekas tambang (m<sup>3</sup>)</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['volume_bekas_tambang'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>

            <tr style="height:31pt">
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 6pt;text-indent: 0pt;line-height: 114%;text-align: left;">d. volume yang ditimbun di luar<br/>bekas tambang (m<sup>3</sup>)</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['volume_diluar_bekas_tambang'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>
        </tbody>
    </table>

    <table style="border-collapse:collapse;margin:6pt auto;padding-top: 50pt;" cellspacing="0">
        <tbody>
            <!-- Header Tabel -->
            <tr style="height:20pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" rowspan="2">
                    <p class="s2" style="padding-left: 11pt;text-indent: 0pt;line-height: 12pt;text-align: left;">NO.</p>
                </td>
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" rowspan="2">
                    <p class="s2" style="text-indent: 0pt;line-height: 12pt;text-align: center;">URAIAN</p>
                </td>
                <td style="width:224pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" colspan="{{ count($tahuns) }}">
                    <p class="s2" style="text-indent: 0pt;line-height: 12pt;text-align: center;">TAHUN</p>
                </td>
            </tr>
            <tr style="height:20pt">
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 5pt;text-indent: 0pt;line-height: 14pt;text-align: center;">{{ $tahun }}</p>
                </td>
                @endforeach
            </tr>
            
            <!-- Reklamasi Section -->
            <tr style="height:14pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 7pt;text-indent: 0pt;line-height: 12pt;text-align: center;">4.</p>
                </td>
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 5pt;text-indent: 0pt;line-height: 12pt;text-align: left;">Reklamasi</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
                @endforeach
            </tr>

            <tr style="height:17pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-right-style:solid;border-right-width:1pt;border-bottom-style:solid;border-bottom-width:1pt" rowspan="10">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 6pt;text-indent: 0pt;line-height: 12pt;text-align: left;">a. penatagunaan lahan:</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
                @endforeach
            </tr>

            <tr style="height:17pt">
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">1) penataan lahan (ha)</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['penataan_tanah'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>

            <tr style="height:27pt">
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">2) penebaran  tanah  zona<br/>pengakaran (ha)</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['penebaran_tanah_pengakaran'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>

            <tr style="height:26pt">
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">3) pengendalian  erosi  dan<br/>sedimentasi</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['pengendalian_erosi'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>

            <tr style="height:17pt">
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 6pt;text-indent: 0pt;line-height: 12pt;text-align: left;">b. Revegetasi (ha):</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
                @endforeach
            </tr>

            <tr style="height:14pt">
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">1) analisis kualitas tanah</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['kualitas_tanah'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>

            <tr style="height:15pt">
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">2) pemupukan (ha)</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['pemupukan'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>

            <tr style="height:32pt">
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;text-align: left;">3) pengadaan bibit (batang<br/>dan/atau kg)</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['pengadaan_bibit'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>

            <tr style="height:17pt">
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">4) penanaman (batang)</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['penanaman'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>

            <tr style="height:14pt">
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 28pt;text-indent: 0pt;line-height: 12pt;text-align: left;">5) pemeliharaan tanaman (ha)</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['pemeliharaan_tanaman'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>

            <!-- Pencegahan dan Penanggulangan Air Asam Tambang Section -->
            <tr style="height:26pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="text-indent: 0pt;line-height: 12pt;text-align: center;">5.</p>
                </td>
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 5pt;text-indent: 0pt;line-height: 12pt;text-align: left;">Pencegahan dan penanggulangan<br/>air asam tambang</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['pencegahan_air_asam'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>

            <!-- Pekerjaan Sipil Section -->
            <tr style="height:40pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="text-indent: 0pt;line-height: 12pt;text-align: center;">6.</p>
                </td>
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 5pt;text-indent: 0pt;line-height: 12pt;text-align: left;">Pekerjaan sipil sesuai peruntukan<br/>lahan Pascatambang atau program<br/>Reklamasi bentuk lain (satuan luas)</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['pekerjaan_sipil'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>

            <!-- Rencana Pemanfaatan Lubang Bekas Tambang Section -->
            <tr style="height:27pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" rowspan="5">
                    <p class="s2" style="text-indent: 0pt;line-height: 12pt;text-align: center;">7.</p>
                </td>
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 5pt;text-indent: 0pt;line-height: 12pt;text-align: left;">Rencana pemanfaatan lubang<br/>bekastambang (<i>void</i>):</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p style="text-indent: 0pt;text-align: left;"><br></p>
                </td>
                @endforeach
            </tr>

            <tr style="height:13pt">
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 6pt;text-indent: 0pt;line-height: 12pt;text-align: left;">a. stabilisasi lereng</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['stabilisasi_lereng'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>

            <tr style="height:31pt">
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 6pt;text-indent: 0pt;line-height: 12pt;text-align: left;">b. pengamanan lubang bekas<br/>tambang (void) dari kecelakaan</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['pengamanan_lubang'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>

            <tr style="height:59pt">
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 6pt;text-indent: 0pt;line-height: 12pt;text-align: left;">c. pemulihan dan pemantauan<br/>kualitas air dan serta pengelolaan air<br/>dalam lubang bekas tambang (void)<br/>sesuai dengan peruntukannya</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['pemulihan_kualitas_air'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>

            <tr style="height:31pt">
                <td style="width:212pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 6pt;text-indent: 0pt;line-height: 12pt;text-align: left;">d. pemeliharaan lubang bekas<br/>tambang (void)</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($dataPerTahun[$tahun]['pemeliharaan_lubang'] ?? 0, 2) }}</p>
                </td>
                @endforeach
            </tr>
        </tbody>
    </table>
</body>
</html>