<div class="flex flex-col items-center justify-center py-16 px-6 rounded-lg bg-white shadow-sm self-stretch">
    <div class="w-24 h-24 bg-amber-50 rounded-full flex items-center justify-center mb-6">
        <svg class="w-12 h-12 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
    </div>
    <h3 class="text-lg font-semibold text-darkslategray mb-2">Tidak Ada Data Anggaran Ditemukan</h3>
    <p class="text-gray-500 text-center mb-2 max-w-md">
        Tidak ada data anggaran reklamasi yang cocok dengan filter aktif.
    </p>
    <div class="bg-amber-50 px-4 py-2 rounded-lg mb-6">
        <p class="text-amber-700 font-medium text-sm">
            @php
                $parts = [];
                if(request('startYear') && request('endYear')) {
                    $parts[] = 'Tahun: ' . e(request('startYear')) . ' - ' . e(request('endYear'));
                } elseif(request('startYear')) {
                    $parts[] = 'Mulai Tahun: ' . e(request('startYear'));
                } elseif(request('endYear')) {
                    $parts[] = 'Sampai Tahun: ' . e(request('endYear'));
                }
                if(request('startMonth') && request('endMonth')) {
                    $bulanList = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                    $startBulan = $bulanList[(int)request('startMonth')-1] ?? request('startMonth');
                    $endBulan = $bulanList[(int)request('endMonth')-1] ?? request('endMonth');
                    $parts[] = 'Bulan: ' . $startBulan . ' - ' . $endBulan;
                } elseif(request('startMonth')) {
                    $bulanList = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                    $parts[] = 'Mulai Bulan: ' . ($bulanList[(int)request('startMonth')-1] ?? request('startMonth'));
                } elseif(request('endMonth')) {
                    $bulanList = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                    $parts[] = 'Sampai Bulan: ' . ($bulanList[(int)request('endMonth')-1] ?? request('endMonth'));
                }
                if(request('minNominal')) {
                    $parts[] = 'Nominal Min: Rp. ' . number_format(request('minNominal'), 0, ',', '.');
                }
                if(request('maxNominal')) {
                    $parts[] = 'Nominal Max: Rp. ' . number_format(request('maxNominal'), 0, ',', '.');
                }
                if(request('minTotal')) {
                    $parts[] = 'Total Min: Rp. ' . number_format(request('minTotal'), 0, ',', '.');
                }
                if(request('kategori_anggaran')) {
                    $parts[] = 'Kategori: ' . e(request('kategori_anggaran'));
                }
                if(request('startQuarter') && request('endQuarter')) {
                    $parts[] = 'Quarter: ' . e(request('startQuarter')) . ' - ' . e(request('endQuarter'));
                } elseif(request('startQuarter')) {
                    $parts[] = 'Mulai Quarter: ' . e(request('startQuarter'));
                } elseif(request('endQuarter')) {
                    $parts[] = 'Sampai Quarter: ' . e(request('endQuarter'));
                }
                echo implode(' | ', $parts);
            @endphp
        </p>
    </div>
    <x-main.primary-button onclick="clearAnggaranFilters()"
        class="inline-flex items-center gap-2 px-6 py-3 font-medium rounded-lg hover:bg-gray-200 text-sm transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
        Hapus Filter
    </x-main.primary-button>
</div>