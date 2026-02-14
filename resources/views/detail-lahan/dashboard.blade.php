<x-main-layout>
  	<x-slot name="header">
    	<div class="flex flex-row items-center justify-between gap-4">
			<h2 class="text-xl font-bold text-darkslategray font-outfit">Dashboard Reklamasi</h2>
			<div class="self-stretch flex flex-row items-center justify-start gap-1.5 text-left text-sm text-slategray font-outfit">
				<a type="button" href="{{ route('lahan.index') }}" class="relative leading-5 text-darkslategray no-underline visited:text-darkslategray">List Lahan</a>
				<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M5.83333 12.6667L10 8.5L5.83333 4.33333" stroke="#667085" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
				<div class="relative leading-5 text-darkslategray-200 font-medium">Dashboard</div>
			</div>
		</div>
  	</x-slot>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div data-turbo-temporary class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div data-turbo-temporary class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

	<div class="py-6">
		<div class="self-stretch mx-auto">
			<div class="bg-almostgray overflow-hidden shadow-md rounded-lg sm:rounded-lg">
                <div class="p-6 bg-white rounded-lg font-outfit">
                    <div class="self-stretch flex flex-row items-center justify-start gap-1 mb-6">
                        <div class="flex-1 flex flex-col items-start justify-start text-lg text-gray">
                            <div class="self-stretch relative leading-7 font-semibold text-lg text-darkslategray">Area Lahan Reklamasi</div>
							<div class="self-stretch relative text-sm leading-5 text-slategray">Progres untuk setiap blok lahan reklamasi.</div>
						</div>
					</div>
					<div class="rounded-2xl overflow-hidden flex flex-col items-center">
						<div class="w-full overflow-hidden shrink-0 flex flex-col items-start justify-between relative" style="height: 400px;">
							<div id="maplibre-map" class="w-full h-full rounded-lg"></div>
							<div class="absolute bottom-10 right-2 bg-white/90 backdrop-blur-md p-1.5 rounded-lg shadow-lg border border-gray-200 z-10 text-sm max-w-3xs font-outfit">
								<h4 class="font-bold text-gray-700 mb-2 border-b pb-1 text-[10px] uppercase tracking-wider text-center">Status Progres</h4>
								
								<div class="flex items-center mb-1.5">
									<div class="w-4 h-4 mr-2 bg-[#ef4444]/50 border-2 border-[#ef4444] rounded-[3px]"></div>
									<span class="text-gray-600 text-xs">0% - 49%</span>
								</div>

								<div class="flex items-center mb-1.5">
									<div class="w-4 h-4 mr-2 bg-[#f59e0b]/50 border-2 border-[#f59e0b] rounded-[3px]"></div>
									<span class="text-gray-600 text-xs">50% - 74%</span>
								</div>

								<div class="flex items-center mb-1.5">
									<div class="w-4 h-4 mr-2 bg-[#3b82f6]/50 border-2 border-[#3b82f6] rounded-[3px]"></div>
									<span class="text-gray-600 text-xs">75% - 99%</span>
								</div>

								<div class="flex items-center mb-2">
									<div class="w-4 h-4 mr-2 bg-[#22c55e]/50 border-2 border-[#22c55e] rounded-[3px]"></div>
									<span class="text-gray-600 text-xs font-medium">Selesai (100%)</span>
								</div>

								<div class="border-t my-1.5"></div>

								<div class="flex items-center">
									<div class="w-4 h-4 mr-2 bg-[#d946ef] border-2 border-[#d946ef] shadow-sm rounded-[3px]"></div>
									<span class="text-gray-800 text-xs font-bold">Plot Terpilih</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="flex items-center justify-center py-4 px-6 gap-8 text-center text-sm text-slategray font-outfit overflow-hidden">
					<div id="progress-summary" class="w-full flex items-center justify-center gap-6 flex-wrap">
						<div class="flex justify-center items-center space-x-2">
							<svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
								<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
								<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
							</svg>
							<span class="text-gray-600 text-sm">Memuat data...</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Right sidebar with stats and progress blocks -->
    <div class="xl:col-span-1 flex flex-col gap-6">
        <!-- General Information Card -->
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <div class="relative leading-7 font-semibold text-lg text-darkslategray mb-4">General Information</div>
            
            <div class="mb-6">
                <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span>Progress Overview</span>
                </h4>
                <div class="grid grid-cols-2 gap-3">
                    <div class="rounded-2xl bg-white border-gainsboro border p-4">
                        <div class="text-sm text-gray-600">Jumlah Blok</div>
                        <div id="jumlah-blok" class="text-2xl font-semibold text-darkslategray mt-2">-</div>
                    </div>

                    <div class="rounded-2xl bg-white border-gainsboro border p-4">
                        <div class="text-sm text-gray-600">Total Luas Area</div>
                        <div id="total-luas-area" class="text-2xl font-semibold text-darkslategray mt-2">- ha</div>
                    </div>

                    <div class="rounded-2xl bg-white border-gainsboro border p-4">
                        <div class="flex items-center justify-between mb-1">
                            <div class="text-sm text-gray-600">Progres Hari Ini</div>
                            <span id="activity-badge" class="hidden flex items-center text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700">
                                <span class="inline-block w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse mr-1"></span>
                            </span>
                        </div>
                        <div id="progres-hari-ini" class="text-2xl font-semibold text-darkslategray mt-2">+0%</div>
                        <div id="progres-message" class="text-xs text-gray-500 mt-1">-</div>
                        <div id="progres-detail" class="text-xs text-gray-400 mt-1 hidden">
                            Total: <span id="today-total">-</span>%
                        </div>
                    </div>

                    <div class="rounded-2xl bg-white border-gainsboro border p-4">
                        <div class="text-sm text-gray-600">Aktivitas Terakhir</div>
                        <div id="aktivitas-terakhir" class="text-2xl font-semibold text-darkslategray mt-2">-</div>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                    <span>Revegetasi & Penanaman</span>
                </h4>
                <div class="grid grid-cols-2 gap-3">
                    <div class="rounded-2xl bg-white border-gainsboro border p-4">
                        <div class="text-sm text-gray-600">Bibit Ditanam</div>
                        <div id="total-bibit" class="text-2xl font-semibold text-darkslategray mt-2">- btg</div>
                    </div>

                     <div class="rounded-2xl bg-white border-gainsboro border p-4">
                        <div class="text-sm text-gray-600">Benih Cover Crops</div>
                        <div id="total-benih-cover" class="text-2xl font-semibold text-darkslategray mt-2">- kg</div>
                    </div>

                    <div class="rounded-2xl bg-white border-gainsboro border p-4">
                        <div class="text-sm text-gray-600">Area Bervegetasi</div>
                        <div id="area-bervegetasi" class="text-2xl font-semibold text-darkslategray mt-2">- ha</div>
                    </div>

                    <div class="rounded-2xl bg-white border-gainsboro border p-4">
                        <div class="text-sm text-gray-600 truncate" title="Persentase Area Bervegetasi">% Area Bervegetasi</div>
                        <div id="persentase-area-bervegetasi" class="text-2xl font-semibold text-darkslategray mt-2">-%</div>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span>Input & Perbaikan Tanah</span>
                </h4>
                <div class="grid grid-cols-2 gap-3">
                    <div class="rounded-2xl bg-white border-gainsboro border p-4">
                        <div class="text-sm text-gray-600 truncate" title="Total Kompos/Organik Terpakai">Total Kompos/Organik Terpakai</div>
                        <div id="total-kompos" class="text-2xl font-semibold text-darkslategray mt-2">- kg</div>
                    </div>

                    <div class="rounded-2xl bg-white border-gainsboro border p-4">
                        <div class="text-sm text-gray-600 truncate" title="Total Pupuk Anorganik Terpakai">Total Pupuk Anorganik Terpakai</div>
                        <div id="total-pupuk" class="text-2xl font-semibold text-darkslategray mt-2">- kg</div>
                    </div>
                </div>
            </div>

            <div>
                <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Pemeliharaan & Monitoring</span>
                </h4>
                <div class="grid grid-cols-2 gap-3">
                    <div class="rounded-2xl bg-white border-gainsboro border p-4">
                        <div class="text-sm text-gray-600 truncate" title="Total Aktivitas Pemeliharaan">Total Aktivitas Pemeliharaan</div>
                        <div id="total-aktivitas" class="text-2xl font-semibold text-darkslategray mt-2">- kali</div>
                    </div>

                    <div class="rounded-2xl bg-white border-gainsboro border p-4">
                        <div class="text-sm text-gray-600 truncate" title="Total Tanaman Disulam">Total Tanaman Disulam</div>
                        <div id="total-tanaman-disulam" class="text-2xl font-semibold text-darkslategray mt-2">- btg</div>
                    </div>

                    <div class="rounded-2xl bg-white border-gainsboro border p-4">
                        <div class="text-sm text-gray-600 truncate" title="Tinggi Rata-rata">Tinggi Rata-rata</div>
                        <div id="tinggi-rata" class="text-2xl font-semibold text-darkslategray mt-2">- cm</div>
                    </div>

                    <div class="rounded-2xl bg-white border-gainsboro border p-4">
                        <div class="text-sm text-gray-600">Survival Rate</div>
                        <div id="survival-rate" class="text-2xl font-semibold text-darkslategray mt-2">-%</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progres per Blok Card -->
        <div class="bg-white rounded-2xl shadow-sm p-5 flex-1">
            <div class="flex items-center justify-between mb-4">
                <div class="relative leading-7 font-semibold text-lg text-darkslategray">Progres Blok Lahan</div>
                <div class="flex items-center gap-2">
                    <div class="flex gap-1 min-w-[80px] justify-center">
                        <div id="progress-dots" class="flex gap-1 transition-transform duration-200"></div>
                    </div>
                </div>
            </div>
            <div id="progress-block-content" class="min-h-[120px]"></div>
        </div>
    </div>

    <!-- Bottom: Chart -->
	<div class="py-6">
        <div class="bg-white rounded-2xl shadow-sm">
            
            <div class="flex flex-row justify-between p-6 border-b border-gainsboro rounded-t-2xl font-outfit">
                <div class="self-stretch flex flex-row items-center justify-start gap-1">
                    <div class="flex-1 flex flex-col items-start justify-start text-lg text-gray">
                        <div class="self-stretch relative leading-7 font-semibold text-lg text-darkslategray" id="chart-title">Grafik Progres Keseluruhan</div>
                        <div class="self-stretch relative text-sm leading-5 text-slategray">Tren progres / indikator / per-blok</div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <x-dashboard.chart-filter />
                        
                        <!-- Hidden main controls for backward compatibility -->
                        <div class="hidden">
                            <select id="chart-view">
                                <option value="overall" selected>Overall</option>
                                <option value="indicator">Indikator</option>
                                <option value="planted">Sebaran Pohon</option>
                            </select>

                            <div id="individual-blocks-toggle">
                                <input type="checkbox" id="show-individual-blocks">
                            </div>

                            <select id="indicator-selector">
                                <option value="">Pilih Indikator</option>
                            </select>

                            <select id="block-selector">
                                <option value="all">Pilih Blok</option>
                            </select>

                            <!-- Period selector (for Overall & Indicator) -->
                            <select id="chart-period">
                                <option value="7days">7 Hari</option>
                                <option value="30days" selected>30 Hari</option>
                                <option value="90days">90 Hari</option>
                                <option value="1year">1 Tahun</option>
                            </select>

                            <!-- Tree category selector (for Planted Trees) -->
                            <select id="tree-category">
                                <option value="all">Semua Kategori</option>
                                <option value="pionir">Pohon Pionir</option>
                                <option value="lokal">Pohon Lokal</option>
                                <option value="mpts">MPTS</option>
                                <option value="cover_crop">Cover Crops</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Chart Canvas - Full width -->
                <div class="grid grid-cols-1 lg:grid-cols-3 min-h-[400px]">
            
                    <div id="chart-container" class="lg:col-span-3 p-6 transition-all duration-500 ease-in-out">
                        <div style="position: relative; height: 100%; min-height: 350px; width: 100%;">
                            <canvas id="main-chart"></canvas>
                        </div>
                    </div>

                    <div id="planted-details-container" class="hidden lg:col-span-1 bg-gray-50/50 border border-0 border-l border-gainsboro flex-col h-full overflow-hidden transition-all duration-300">
                        <div class="p-4 border-b border-gainsboro bg-gray-50 flex items-center justify-between">
                            <h4 class="font-bold text-gray-700 text-sm">Rincian Kategori</h4>
                            <span class="text-[10px] text-gray-400 uppercase tracking-wider">Scroll untuk detail</span>
                        </div>
                        <div id="planted-details" class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-3" style="max-height: 400px;">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-main-layout>

<script>
	console.log('Dashboard blade template loaded');
	window.dashboardConfig = {
		lahanId: {{ $lahan->lahan_id }},
		baseUrl: '{{ url("/lahan/{$lahan->lahan_id}/dashboard") }}',
	};
	console.log('Dashboard config set:', window.dashboardConfig);

    function togglePlantedCategory(category) {
        const detail = document.getElementById(`${category}-detail`);
        const icon = document.getElementById(`${category}-icon`);
        
        if (!detail || !icon) return;
        
        if (detail.classList.contains('hidden')) {
            detail.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
        } else {
            detail.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    }
</script>