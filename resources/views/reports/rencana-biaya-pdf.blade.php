<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="id" lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rencana Biaya Reklamasi</title>
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
        .s4 { 
            color: black; 
            font-family: 'Bookman Old Style', serif; 
            text-decoration: none; 
            font-size: 10pt; 
            vertical-align: 2pt; 
        }
        .p, p { 
            color: black; 
            font-family: 'Bookman Old Style', serif; 
            text-decoration: none; 
            font-size: 9pt; 
            margin:0pt; 
        }
        .s5 { 
            color: black; 
            font-family: 'Bookman Old Style', serif; 
            text-decoration: none; 
            font-size: 9pt; 
            vertical-align: 2pt; 
        }
        table, tbody {
            vertical-align: top; 
            overflow: visible; 
        }
    </style>
</head>
<body>
    <p class="s1" style="padding-top: 3pt; padding-left: 164pt; text-indent: -104pt; line-height: 150%; text-align: left;">
        Matrik 2.2 Rencana Biaya Reklamasi Tahap Operasi Produksi Periode Tahun {{ $tahunRange }}
    </p>

    <table style="border-collapse:collapse;margin:6pt auto" cellspacing="0">
        <tbody>
            <!-- Header Tabel -->
            <tr style="height:14pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" rowspan="2">
                    <p class="s2" style="padding-left: 8pt;text-indent: 0pt;line-height: 12pt;text-align: left;">NO.</p>
                </td>
                <td style="width:194pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" rowspan="2">
                    <p class="s2" style="padding-left: 51pt;text-indent: 0pt;line-height: 12pt;text-align: left;">DESKRIPSI BIAYA</p>
                </td>
                <td style="width:232pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" colspan="{{ count($tahuns) }}">
                    <p class="s2" style="text-indent: 0pt;line-height: 12pt;text-align: center;">TAHUN</p>
                </td>
            </tr>
            <tr style="height:14pt">
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 5pt;text-indent: 0pt;line-height: 12pt;text-align: center;">{{ $tahun }}</p>
                </td>
                @endforeach
            </tr>
            
            <!-- Biaya Langsung Section -->
            <tr style="height:14pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="text-indent: 0pt;text-align: center;">1.</p>
                </td>
                <td style="width:194pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Biaya langsung (Rp/US$)</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p style="text-indent: 0pt;text-align: left;"><br/></p>
                </td>
                @endforeach
            </tr>
            
            <tr style="height:27pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" rowspan="12">
                    <p style="text-indent: 0pt;text-align: left;"><br/></p>
                </td>
                <td style="width:194pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 5pt;text-indent: 0pt;line-height: 12pt;text-align: left;">a. Biaya penatagunaan lahan,</p>
                    <p class="s2" style="padding-top: 1pt;padding-left: 23pt;text-indent: 0pt;text-align: left;">terdiri atas biaya :</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p style="text-indent: 0pt;text-align: left;"><br/></p>
                </td>
                @endforeach
            </tr>
            
            <tr style="height:14pt">
                <td style="width:194pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 26pt;text-indent: 0pt;line-height: 12pt;text-align: left;">1) Penataan permukaan tanah</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($biayaPerTahun[$tahun]['biaya_langsung']['penataan_permukaan_tanah'] ?? 0, 0) }}</p>
                </td>
                @endforeach
            </tr>
            
            <tr style="height:27pt">
                <td style="width:194pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 26pt;text-indent: 0pt;line-height: 12pt;text-align: left;">2) Penebaran tanah zona</p>
                    <p class="s2" style="padding-top: 1pt;padding-left: 44pt;text-indent: 0pt;text-align: left;">pengakaran</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($biayaPerTahun[$tahun]['biaya_langsung']['penebaran_tanah_zona_pengakaran'] ?? 0, 0) }}</p>
                </td>
                @endforeach
            </tr>
            
            <tr style="height:27pt">
                <td style="width:194pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 26pt;text-indent: 0pt;line-height: 12pt;text-align: left;">3) Pengendalian erosi dan</p>
                    <p class="s2" style="padding-top: 1pt;padding-left: 44pt;text-indent: 0pt;text-align: left;">sedimentasi</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($biayaPerTahun[$tahun]['biaya_langsung']['pengendalian_erosi'] ?? 0, 0) }}</p>
                </td>
                @endforeach
            </tr>
            
            <tr style="height:27pt">
                <td style="width:194pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 5pt;text-indent: 0pt;line-height: 12pt;text-align: left;">b. Biaya revegetasi, terdiri atas</p>
                    <p class="s2" style="padding-top: 1pt;padding-left: 23pt;text-indent: 0pt;text-align: left;">biaya :</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p style="text-indent: 0pt;text-align: left;"><br/></p>
                </td>
                @endforeach
            </tr>
            
            <tr style="height:14pt">
                <td style="width:194pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 26pt;text-indent: 0pt;text-align: left;">1) analisis kualitas tanah</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($biayaPerTahun[$tahun]['biaya_langsung']['analisis_kualitas_tanah'] ?? 0, 0) }}</p>
                </td>
                @endforeach
            </tr>

            <tr style="height:14pt">
                <td style="width:194pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 26pt;text-indent: 0pt;line-height: 12pt;text-align: left;">2) pemupukan</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($biayaPerTahun[$tahun]['biaya_langsung']['pemupukan'] ?? 0, 0) }}</p>
                </td>
                @endforeach
            </tr>

            <tr style="height:14pt">
                <td style="width:194pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 26pt;text-indent: 0pt;line-height: 12pt;text-align: left;">3) pengadaan bibit</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($biayaPerTahun[$tahun]['biaya_langsung']['pengadaan_bibit'] ?? 0, 0) }}</p>
                </td>
                @endforeach
            </tr>

            <tr style="height:14pt">
                <td style="width:194pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 26pt;text-indent: 0pt;text-align: left;">4) penanaman</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($biayaPerTahun[$tahun]['biaya_langsung']['penanaman'] ?? 0, 0) }}</p>
                </td>
                @endforeach
            </tr>

            <tr style="height:14pt">
                <td style="width:194pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 26pt;text-indent: 0pt;line-height: 12pt;text-align: left;">5) pemeliharaan tanaman</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($biayaPerTahun[$tahun]['biaya_langsung']['pemeliharaan_tanaman'] ?? 0, 0) }}</p>
                </td>
                @endforeach
            </tr>

            <tr style="height:41pt">
                <td style="width:194pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 23pt;text-indent: -18pt;line-height: 113%;text-align: left;">c. biaya pencegahan dan penanggulangan air asam</p>
                    <p class="s2" style="padding-left: 23pt;text-indent: 0pt;text-align: left;">tambang (apabila ada)</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($biayaPerTahun[$tahun]['biaya_langsung']['biaya_pencegahan'] ?? 0, 0) }}</p>
                </td>
                @endforeach
            </tr>
            
            <!-- SUBTOTAL 1 -->
            <tr style="height:14pt">
                <td style="width:194pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 4pt;text-indent: 0pt;line-height: 12pt;text-align: left;"><strong>SUBTOTAL 1 (Rp/US$)</strong></p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;"><strong>{{ number_format($biayaPerTahun[$tahun]['biaya_langsung']['subtotal_1'] ?? 0, 0) }}</strong></p>
                </td>
                @endforeach
            </tr>
            
            <!-- Biaya Tidak Langsung -->
            <tr style="height:14pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="text-indent: 0pt;line-height: 12pt;text-align: center;">2.</p>
                </td>
                <td style="width:194pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 4pt;text-indent: 0pt;line-height: 12pt;text-align: left;">Biaya tidak langsung (Rp/US$)</p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p style="text-indent: 0pt;text-align: left;"><br/></p>
                </td>
                @endforeach
            </tr>
            
            <tr style="height:30pt">
                <td style="width:36pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" rowspan="5">
                    <p style="text-indent: 0pt;text-align: left;"><br/></p>
                </td>
                <td style="width:194pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 17pt;text-indent: 0pt;line-height: 12pt;text-align: left;">a. biaya mobilisasi dan</p>
                    <p class="s2" style="padding-top: 1pt;padding-left: 40pt;text-indent: 0pt;text-align: left;">demobilisasi alat<span class="s4">**1)</span></p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($biayaPerTahun[$tahun]['biaya_tidak_langsung']['biaya_mobilisasi'] ?? 0, 0) }}</p>
                </td>
                @endforeach
            </tr>
            
            <tr style="height:30pt">
                <td style="width:194pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 17pt;text-indent: 0pt;line-height: 12pt;text-align: left;">b. biaya perencanaan</p>
                    <p class="s2" style="padding-top: 1pt;padding-left: 40pt;text-indent: 0pt;text-align: left;">Reklamasi**<span class="s4">2)</span></p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($biayaPerTahun[$tahun]['biaya_tidak_langsung']['biaya_perencanaan'] ?? 0, 0) }}</p>
                </td>
                @endforeach
            </tr>
            
            <tr style="height:71pt">
                <td style="width:194pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 40pt;padding-right: 13pt;text-indent: -22pt;line-height: 114%;text-align: left;">c. biaya administrasi dan keuntungan pihak ketiga sebagai pelaksana Reklamasi tahap</p>
                    <p class="s2" style="padding-left: 40pt;text-indent: 0pt;text-align: left;">Eksplorasi<span class="s4">**3)</span></p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($biayaPerTahun[$tahun]['biaya_tidak_langsung']['biaya_admin'] ?? 0, 0) }}</p>
                </td>
                @endforeach
            </tr>
            
            <tr style="height:16pt">
                <td style="width:194pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 17pt;text-indent: 0pt;line-height: 14pt;text-align: left;">d. biaya supervisi<span class="s4">**4)</span></p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;">{{ number_format($biayaPerTahun[$tahun]['biaya_tidak_langsung']['biaya_supervisi'] ?? 0, 0) }}</p>
                </td>
                @endforeach
            </tr>
            
            <tr style="height:14pt">
                <td style="width:194pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-left: 5pt;text-indent: 0pt;line-height: 12pt;text-align: left;"><strong>SUBTOTAL 2 (Rp/US$)</strong></p>
                </td>
                @foreach($tahuns as $tahun)
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;"><strong>{{ number_format($biayaPerTahun[$tahun]['biaya_tidak_langsung']['subtotal_2'] ?? 0, 0) }}</strong></p>
                </td>
                @endforeach
            </tr>
            
            <tr style="height:14pt">
                <td style="width:230pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" colspan="2">
                    <p class="s2" style="padding-left: 41pt;text-indent: 0pt;line-height: 12pt;text-align: left;"><strong>TOTAL (Rp/US$)</strong></p>
                </td>
                @foreach($tahuns as $tahun)
                @php
                    $total = ($biayaPerTahun[$tahun]['biaya_langsung']['subtotal_1'] ?? 0) + 
                            ($biayaPerTahun[$tahun]['biaya_tidak_langsung']['subtotal_2'] ?? 0);
                @endphp
                <td style="width:43pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
                    <p class="s2" style="padding-right: 5pt;text-align: center;"><strong>{{ number_format($total, 0) }}</strong></p>
                </td>
                @endforeach
            </tr>
        </tbody>
    </table>
    
    <p style="padding-top: 3pt;padding-left: 7pt;text-indent: 0pt;text-align: left;">Keterangan :</p>
    <p class="s5" style="padding-top: 1pt;padding-left: 7pt;text-indent: 0pt;text-align: left;">
        **1) <span class="p">besarnya 2,5% dari biaya langsung atau berdasarkan perhitungan</span>
    </p>
    <p class="s5" style="padding-top: 1pt;padding-left: 7pt;text-indent: 0pt;text-align: left;">
        **2)<span class="p">besarnya 2% - 10% dari biaya langsung (<i>grafik Englemen's Heavy Construction Cost File</i>)</span>
    </p>
    <p class="s5" style="padding-top: 1pt;padding-left: 7pt;text-indent: 0pt;text-align: left;">
        **3)<span class="p">besarnya 3% - 14% dari biaya langsung (<i>grafik Englemen's Heavy Construction Cost File</i>)</span>
    </p>
    <p class="s5" style="padding-top: 1pt;padding-left: 7pt;text-indent: 0pt;text-align: left;">
        **4) <span class="p">besarnya 2% - 7% dari biaya langsung (<i>grafik Englemen's Heavy Construction Cost File</i>)</span>
    </p>
</body>
</html>