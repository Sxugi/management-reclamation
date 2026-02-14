<x-main-layout>
    <x-slot name="header">
        <div class="flex flex-row items-center justify-between gap-4">
            <h2 class="text-xl font-bold text-darkslategray font-outfit">Gudang & Inventaris</h2>
            <div class="self-stretch flex flex-row items-center justify-start gap-1.5 text-left text-sm text-slategray font-outfit">
                <a type="button" href="{{ route('lahan.index') }}" class="relative leading-5 text-darkslategray no-underline visited:text-darkslategray">List Lahan</a>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.83333 12.6667L10 8.5L5.83333 4.33333" stroke="#667085" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                <div class="relative leading-5 text-darkslategray-200 font-medium">Gudang</div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="self-stretch mx-auto">
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

            <div class="mb-4">
                @php
                    $chunks = $rekapStok->chunk(4); 
                @endphp

                <div x-data="{ 
                        activeSlide: 0, 
                        totalSlides: {{ $chunks->count() }},
                        next() { 
                            if (this.activeSlide < this.totalSlides - 1) this.activeSlide++; 
                        },
                        prev() { 
                            if (this.activeSlide > 0) this.activeSlide--; 
                        }
                    }" 
                    class="bg-white rounded-lg shadow-sm p-6 border border-gainsboro relative group/slider">

                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-darkslategray font-outfit">Stok Saat Ini</h3>
                            <span class="text-xs text-gray-500 bg-gainsboro px-2 py-1 rounded mt-1 inline-block">
                                Update: {{ now()->format('H:i') }}
                            </span>
                        </div>

                        <div class="flex flex-row items-center gap-4">
                            <a href="{{ route('lahan.gudang.index', ['lahan' => $lahan->lahan_id]) }}" 
                            class="inline-flex items-center justify-center p-2 rounded-lg border border-gray-300 text-darkslategray hover:bg-gainsboro transition-colors">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.65 6.35A7.958 7.958 0 0012 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08A5.99 5.99 0 0112 18c-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z" fill="currentColor"/>
                                </svg>
                            </a>
                            @if($chunks->count() > 1)
                                <div class="flex items-center gap-2">
                                    <button @click="prev()" 
                                            :class="{ 'opacity-30 cursor-not-allowed': activeSlide === 0, 'hover:bg-gray-100 text-darkslategray': activeSlide > 0 }"
                                            class="w-8 h-8 rounded-full flex items-center justify-center border border-gainsboro transition-colors"
                                            :disabled="activeSlide === 0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                    </button>

                                    <div class="flex gap-1">
                                        <template x-for="i in totalSlides">
                                            <div class="h-1.5 rounded-full transition-all duration-300"
                                                :class="(activeSlide === i-1) ? 'bg-darkslategray w-4' : 'bg-gray-300 w-1.5'">
                                            </div>
                                        </template>
                                    </div>

                                    <button @click="next()" 
                                            :class="{ 'opacity-30 cursor-not-allowed': activeSlide === totalSlides - 1, 'hover:bg-gray-100 text-darkslategray': activeSlide < totalSlides - 1 }"
                                            class="w-8 h-8 rounded-full flex items-center justify-center border border-gainsboro transition-colors"
                                            :disabled="activeSlide === totalSlides - 1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($rekapStok->isEmpty())
                        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 text-blue-800 text-sm flex gap-3 items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Belum ada data barang.
                        </div>
                    @else
                        <div class="overflow-hidden relative">
                            <div class="flex transition-transform duration-500 ease-in-out"
                                :style="'transform: translateX(-' + (activeSlide * 100) + '%)'">
                                
                                @foreach($chunks as $chunk)
                                    <div class="w-full flex-shrink-0 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 px-0.5 pb-1">
                                        @foreach($chunk as $item)
                                            @php
                                                // Calculate percentage of remaining stock
                                                $divisor = $item->total_masuk > 0 ? $item->total_masuk : 1;
                                                
                                                // Percentage: (Remaining / Total Ever Entered) * 100
                                                $persentase = ($item->total_sisa / $divisor) * 100;
                                                
                                                // Determine Status based on 25% (As per request)
                                                $isEmpty = $item->total_sisa <= 0;
                                                $isLow = $persentase <= 25 && !$isEmpty; // Kurang dari 25%
                                                
                                                // Styling
                                                $textColor = $isEmpty ? 'text-red-600' : ($isLow ? 'text-orange-600' : 'text-blue-600');
                                                
                                                // Link Filter
                                                $filterLink = route('lahan.gudang.index', ['lahan' => $lahan->lahan_id, 'jenisBarang' => $item->jenis_barang, 'namaBarang' => $item->nama_barang]);
                                            @endphp

                                            <a href="{{ $filterLink }}" class="block no-underline bg-white rounded-xl p-4 border border-gainsboro hover:bg-gray-50 hover:border-blue-400 transition-all cursor-pointer h-full flex flex-col justify-between shadow-sm hover:shadow-md group">
                                                
                                                <div class="flex justify-between items-start mb-4">
                                                    <div class="flex-1 min-w-0 pr-2">
                                                        <h5 class="text-sm font-bold text-darkslategray mb-1 truncate group-hover:text-blue-700 transition-colors" title="{{ $item->nama_barang }}">
                                                            {{ $item->nama_barang }}
                                                        </h5>
                                                        <div class="flex items-center gap-2 text-xs text-gray-500">
                                                            <span>{{ $item->jenis_barang }}</span>
                                                        </div>
                                                    </div>
                                                    
                                                    @if($isEmpty)
                                                        <span class="text-[10px] font-bold text-red-600 bg-red-50 px-2 py-1 rounded-full border border-red-100">Habis</span>
                                                    @elseif($isLow)
                                                        <div class="flex flex-col items-end">
                                                            <span class="text-[10px] font-bold text-orange-600 bg-orange-50 px-2 py-1 rounded-full border border-orange-100">Menipis</span>
                                                            <span class="text-[9px] text-orange-400 mt-0.5">{{ number_format($persentase, 0) }}%</span>
                                                        </div>
                                                    @else
                                                        <span class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-1 rounded-full border border-green-100">Aman</span>
                                                    @endif
                                                </div>

                                                <div class="flex items-end justify-between border-t border-gray-50 pt-3 mt-auto">
                                                    <span class="text-xs text-gray-400 font-medium">Sisa Stok</span>
                                                    <div class="text-right">
                                                        <span class="text-2xl font-extrabold {{ $textColor }} leading-none tracking-tight">
                                                            {{ number_format($item->total_sisa) }}
                                                        </span>
                                                        <span class="text-xs font-bold text-gray-400 ml-0.5">{{ $item->satuan }}</span>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    @for($i = $chunk->count(); $i < 4; $i++)
                                        <div class="hidden md:block"></div>
                                    @endfor
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="bg-white shadow-md rounded-lg sm:rounded-lg">
            <div class="p-6">
                <div class="w-full flex flex-col items-start justify-start shadow-sm rounded-2xl">
                    <div class="rounded-t-2xl bg-white border-gainsboro border-solid border-[1px] border-b-0 box-border self-stretch flex flex-row items-center justify-between gap-1 py-4 px-6">
                        <div class="flex items-center self-stretch leading-7 font-semibold text-lg text-darkslategray font-outfit">
                            List Barang
                        </div>
                        <div class="flex flex-row items-center justify-between gap-2">
                            <x-gudang.filter-button :lahan="$lahan"/>
                            <x-gudang.action-button :lahan="$lahan"/>
                        </div>
                    </div>
                    <x-gudang.list :gudang="$gudang" :lahan="$lahan" :hasFilter="$hasFilter"/>
                </div>
            </div>
        </div>
    </div>
</x-main-layout>