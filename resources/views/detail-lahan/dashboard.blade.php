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

	<!-- Bottom: Chart + Progress per Blok + General Information -->
	<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
		<!-- Chart area with Progress per Blok - takes 2 columns -->
		<div class="xl:col-span-2 bg-white rounded-2xl shadow-sm">
			<!-- Chart area - Full width -->
			<div class="self-stretch mx-auto">
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

							<select id="chart-period">
								<option value="7days">7 Hari</option>
								<option value="30days" selected>30 Hari</option>
								<option value="90days">90 Hari</option>
								<option value="1year">1 Tahun</option>
							</select>
						</div>
					</div>
				</div>
				<!-- Chart Canvas - Full width -->
				<div class="p-6">
					<div style="position: relative; height: 340px;">
						<canvas id="main-chart"></canvas>
					</div>
				</div>
			</div>
		</div>

		<!-- Right sidebar with stats and progress blocks -->
		<div class="xl:col-span-1 flex flex-col gap-6">
			<!-- General Information Card -->
			<div class="bg-white rounded-2xl shadow-sm p-5">
				<div class="relative leading-7 font-semibold text-lg text-darkslategray mb-4">General Information</div>
					<div class="grid grid-cols-2 gap-4">
						<div class="rounded-2xl bg-white border-gainsboro border p-4">
							<div class="text-sm text-gray-600">Blok Lahan</div>
							<div id="jumlah-blok" class="text-2xl font-semibold text-darkslategray mt-2">-</div>
						</div>

						<div class="rounded-2xl bg-white border-gainsboro border p-4">
							<div class="text-sm text-gray-600">Progres Hari Ini</div>
							<div id="progres-hari-ini" class="text-2xl font-semibold text-darkslategray mt-2">-%</div>
						</div>

						<div class="rounded-2xl bg-white border-gainsboro border p-4">
							<div class="text-sm text-gray-600">Total Progres</div>
							<div id="total-progres" class="text-2xl font-semibold text-darkslategray mt-2">-%</div>
						</div>

						<div class="rounded-2xl bg-white border-gainsboro border p-4">
							<div class="text-sm text-gray-600">Blok Selesai</div>
							<div id="blok-selesai" class="text-2xl font-semibold text-darkslategray mt-2">-</div>
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
</script>