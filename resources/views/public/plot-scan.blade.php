<x-public-layout>
    <x-slot name="title">Detail - {{ $plot->nama_plot }}</x-slot>

    <div class="bg-white rounded-2xl overflow-hidden shadow-md border border-gainsboro flex flex-col p-6 mb-6">
        <div class="flex flex-col gap-1">
            <span class="text-xs font-bold text-blue-600 uppercase tracking-widest">{{ $lahan->nama_lahan }}</span>
            <h1 class="text-3xl font-bold text-darkslategray">{{ $plot->nama_plot }}</h1>
            <div class="flex items-center gap-2 mt-2 text-gray-500 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span>{{ $handover->lokasi ?? 'Lokasi belum diset' }}</span>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <x-plot.metrics 
            :plot="$plot" 
            :activityLogs="$activityLogs" 
            :progres="$plot->progres" 
            :progressPercent="$progressPercent" 
            :progresDelta="$progresDelta"
        />

        <div class="space-y-4">
            <div class="flex items-center justify-between px-1">
                <h3 class="font-bold text-lg text-darkslategray">Progress Summary</h3>
                <span class="text-xs text-gray-500">Tap kartu untuk detail</span>
            </div>
            
            @forelse($technicalSummary as $kategori => $data)
                <div x-data="{ open: false }" class="bg-white border border-gainsboro rounded-xl shadow-sm transition-all duration-200">
                    
                    <button @click="open = !open" class="w-full flex items-center justify-between p-5 text-left focus:outline-none hover:bg-gray-50/50">
                        <div>
                            <h4 class="font-bold text-darkslategray text-sm uppercase tracking-wide">{{ $kategori }}</h4>
                            <p class="text-xs text-gray-400 mt-1">
                                @if($data['total_luas'] > 0)
                                    Total Area: <span class="font-medium text-gray-600">{{ number_format($data['total_luas'], 2) }} Ha</span>
                                @else
                                    Data Monitoring / Non-Fisik
                                @endif
                            </p>
                        </div>

                        <div class="flex items-center gap-4">
                            @if($data['total_items'] > 0)
                                <div class="flex flex-row text-right">
                                    <span class="block text-xl font-bold text-blue-600">
                                        {{ number_format($data['total_items']) }}
                                    </span>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase">{{ $data['unit_utama'] }}</span>
                                </div>
                            @endif
                            
                            <svg class="w-5 h-5 text-gray-300 transform transition-transform duration-200" 
                                 :class="{'rotate-180': open}" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>

                    <div x-show="open" x-collapse style="display: none;" class="border-t border-gainsboro bg-gray-50/50">
                        <div class="p-5 space-y-6">
                            @foreach($data['activities'] as $activityName => $actDetail)
                                <div class="relative pl-4 border-l-2 border-blue-200">
                                    
                                    <div class="flex justify-between items-start mb-3">
                                        <h5 class="font-bold text-sm text-gray-800">{{ $activityName }}</h5>
                                        @if($actDetail['luas'] > 0)
                                            <span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded font-medium">
                                                {{ number_format($actDetail['luas'], 2) }} Ha
                                            </span>
                                        @endif
                                    </div>

                                    @if(!empty($actDetail['items_detail']))
                                        <div class="space-y-3">
                                            @foreach($actDetail['items_detail'] as $itemName => $detail)
                                                <div class="bg-white rounded-lg border border-gainsboro p-3 shadow-sm">
                                                    <div class="flex justify-between items-center">
                                                        <span class="font-semibold text-gray-700 text-xs sm:text-sm">{{ $itemName }}</span>
                                                        <span class="font-bold text-darkslategray text-xs sm:text-sm">
                                                            {{ number_format($detail['qty']) }} 
                                                            <span class="text-[10px] font-normal text-gray-400">{{ $detail['unit'] }}</span>
                                                        </span>
                                                    </div>

                                                    <div class="flex flex-wrap gap-2 text-[10px]">
                                                        @if(!empty($detail['metodes']))
                                                            <span class="inline-flex items-center gap-1 bg-almostgray px-2 py-1 rounded text-gray-600 border border-gray-200 mt-2">
                                                                <span class="font-medium text-gray-400">Metode:</span>
                                                                {{ implode(', ', $detail['metodes']) }}
                                                            </span>
                                                        @endif

                                                        @if($detail['dosis_count'] > 0)
                                                            @php $avgDosis = $detail['dosis_sum'] / $detail['dosis_count']; @endphp
                                                            <span class="inline-flex items-center gap-1 bg-yellow-50 px-2 py-1 rounded text-yellow-700 border border-yellow-100 mt-2">
                                                                <span class="font-medium text-yellow-600/70">Avg Dosis:</span>
                                                                {{ number_format($avgDosis, 2) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if(!empty($actDetail['monitoring_stats']))
                                        <div class="grid grid-cols-1 gap-3 mt-2">
                                            @foreach($actDetail['monitoring_stats'] as $pohonLabel => $stats)
                                                <div class="bg-blue-50 rounded-lg border border-blue-100 p-3">
                                                    
                                                    <div class="flex justify-between items-center mb-3 border-b border-blue-200 pb-2">
                                                        <span class="font-bold text-blue-800 text-xs uppercase tracking-wide">{{ $pohonLabel }}</span>
                                                        <span class="text-[10px] text-blue-600 bg-white px-2 py-0.5 rounded-full border border-blue-100">
                                                            Update: {{ $stats['tanggal'] }}
                                                        </span>
                                                    </div>

                                                    <div class="grid grid-cols-1 gap-2 text-[10px]">
                                                        
                                                        @if($stats['sr_count'] > 0)
                                                            <div class="bg-white px-2 py-2 rounded shadow-sm border border-blue-50 flex flex-col justify-center">
                                                                <span class="text-gray-400 block mb-1">Survival Rate</span>
                                                                <div class="flex items-baseline gap-1">
                                                                    <span class="font-bold text-blue-600 text-sm">
                                                                        {{ number_format($stats['sr_sum'] / $stats['sr_count'], 1) }}%
                                                                    </span>
                                                                </div>
                                                                <span class="text-gray-400 text-[9px] block mt-1 pt-1 border-t border-gray-50">
                                                                    (H:{{ $stats['hidup'] }} / M:{{ $stats['mati'] }})
                                                                </span>
                                                            </div>
                                                        @elseif($stats['h_count'] > 0)
                                                            <div class="bg-white px-2 py-2 rounded shadow-sm border border-green-50 flex flex-col justify-center">
                                                                <span class="text-gray-400 block mb-1">Tinggi Rata-rata</span>
                                                                <span class="font-bold text-green-600 text-sm">
                                                                    {{ number_format($stats['h_sum'] / $stats['h_count'], 0) }} cm
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                    <p class="text-gray-500 text-sm">Belum ada data rekapitulasi pekerjaan.</p>
                </div>
            @endforelse
        </div>

        <div class="bg-white overflow-hidden shadow-md rounded-lg sm:rounded-lg border border-gainsboro">
            <div class="p-6 font-outfit">
                <div class="self-stretch flex flex-row items-center justify-start gap-1 mb-6">
                    <div class="flex-1 flex flex-col items-start justify-start gap-1 text-lg text-gray">
                        <div class="self-stretch relative leading-7 font-semibold">Area Lahan Reklamasi</div>
                        <div class="self-stretch relative text-sm leading-5 text-slategray">Peta sebaran dokumentasi dan area.</div>
                    </div>
                </div>
                <x-plot.map-controls :plot="$plot" :lahan="$lahan" :photoMarkersData="$photoMarkersData" :enableDraw="false"/>
            </div>
        </div>
    </div>
</x-public-layout>