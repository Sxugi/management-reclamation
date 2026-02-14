<div id="trendModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center hidden z-100">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-gainsboro bg-gradient-to-r from-blue-600 to-cyan-600 px-6 py-4 rounded-t-2xl">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="flex items-center self-stretch leading-7 font-semibold text-lg text-white font-outfit" id="trendModalTitle">Analisis Trend Monitoring</h3>
                    <p class="text-sm text-white/80 font-outfit" id="trendModalSubtitle">Loading...</p>
                </div>
            </div>
            <button onclick="window.closeTrendModal()" class="w-10 h-10 rounded-xl bg-white shadow-sm hover:bg-gray-500 flex items-center justify-center text-slategray hover:text-white transition-all duration-200 cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="p-6 font-outfit overflow-y-auto max-h-[calc(90vh-200px)]" id="trendModalBody">
            {{-- Loading State --}}
            <div id="trendLoading" class="flex flex-col items-center justify-center py-12">
                <svg class="animate-spin h-12 w-12 text-blue-600 mb-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-gray-600">Memuat data trend...</p>
            </div>

            {{-- Content will be injected here --}}
            <div id="trendContent" class="hidden"></div>

            {{-- Error State --}}
            <div id="trendError" class="hidden flex flex-col items-center justify-center py-12">
                <svg class="w-16 h-16 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-gray-600 mb-2">Gagal memuat data trend</p>
                <p class="text-sm text-gray-500 mb-4" id="errorMessage"></p>
                <button type="button" onclick="retryLoadTrend()" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Coba Lagi
                </button>
            </div>
        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-end p-6 border-t border-gainsboro bg-gray-50 rounded-b-2xl">
            <button onclick="window.closeTrendModal()" class="bg-red-500 !text-white text-sm py-3 px-6 rounded-lg font-medium font-outfit hover:bg-red-600 transition-colors border-none cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>