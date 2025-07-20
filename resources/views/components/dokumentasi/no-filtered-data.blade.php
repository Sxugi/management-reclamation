<div class="flex flex-col items-center justify-center py-16 px-6 rounded-lg bg-white shadow-sm">
    <div class="w-24 h-24 bg-amber-50 rounded-full flex items-center justify-center mb-6">
        <svg class="w-12 h-12 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
    </div>
    <h3 class="text-lg font-semibold text-darkslategray-200 mb-2">Tidak Ada Dokumentasi Ditemukan</h3>
    <p class="text-gray-500 text-center mb-2 max-w-md">
        Tidak ada dokumentasi yang ditemukan untuk periode:
    </p>
    <div class="bg-amber-50 px-4 py-2 rounded-lg mb-6">
        <p class="text-amber-700 font-medium text-sm">
            @if(request('start_date') && request('end_date'))
                {{ \Carbon\Carbon::parse(request('start_date'))->format('d M Y') }} - {{ \Carbon\Carbon::parse(request('end_date'))->format('d M Y') }}
            @elseif(request('start_date'))
                Mulai dari {{ \Carbon\Carbon::parse(request('start_date'))->format('d M Y') }}
            @elseif(request('end_date'))
                Sampai {{ \Carbon\Carbon::parse(request('end_date'))->format('d M Y') }}
            @else
                Filter aktif
            @endif
        </p>
    </div>
    <div class="flex flex-col sm:flex-row gap-3">
        <x-main.primary-button onclick="clearDateFilter()" 
                class="inline-flex items-center gap-2 px-6 py-3 font-medium rounded-lg hover:bg-gray-200 text-sm transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            Hapus Filter
        </x-main.primary-button>
        <a href="{{ route('lahan.dokumentasi.create', $lahan) }}" 
           class="inline-flex rounded-lg bg-darkslategray overflow-hidden flex flex-row items-center justify-center py-3 px-4 gap-2 !text-white text-sm no-underline hover:bg-slategray-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Buat Dokumentasi Baru
        </a>
    </div>
</div>