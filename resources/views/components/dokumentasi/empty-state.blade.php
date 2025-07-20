<div class="flex flex-col items-center justify-center py-16 px-6 rounded-lg bg-white shadow-sm">
    <div class="w-24 h-24 bg-whitesmoke rounded-full flex items-center justify-center mb-6">
        <svg class="w-12 h-12 text-darkslategray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
    </div>
    <h3 class="text-lg font-semibold text-darkslategray-200 mb-2">Belum Ada Dokumentasi</h3>
    <p class="text-gray-500 text-center mb-6 max-w-md">
        Anda belum memiliki dokumentasi untuk lahan ini. Mulai dokumentasikan aktivitas dan perkembangan lahan Anda.
    </p>
    <a href="{{ route('lahan.dokumentasi.create', $lahan) }}" 
        class="inline-flex rounded-md bg-darkslategray overflow-hidden flex flex-row items-center justify-center py-3 px-4 gap-2 !text-white text-sm no-underline hover:bg-slategray-200 font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Tambah Dokumentasi
    </a>
</div>