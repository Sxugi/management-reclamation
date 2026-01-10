<div class="mb-6 bg-white rounded-2xl overflow-hidden shadow-md flex flex-col">
    @php
        $list = $target instanceof \Illuminate\Support\Collection
            ? $target
            : collect($target ? [$target] : []);
    @endphp    
    <div class="px-6 py-4 border-[0px] border-b-[1px] border-gainsboro border-solid flex justify-between items-center">
        <div class="font-bold text-base font-outfit">
            Target Reklamasi Blok {{ $plot->nama_plot }}
        </div>
        @if($list->isNotEmpty())
            <button
                type="button"
                class="cursor-pointer rounded-lg bg-darkslategray overflow-hidden flex flex-row items-center justify-center py-3 px-4 gap-2 !text-white no-underline hover:bg-slategray-200 disabled:opacity-50 disabled:cursor-not-allowed"
                x-on:click="$dispatch('open-modal','form-target-reklamasi')"
                @disabled(!auth()->user()->can('manage', [\App\Models\TargetProgresReklamasi::class, $plot]))>
                <span class="relative leading-5 font-medium">Edit</span>
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.83301 5.83333H4.99967C4.55765 5.83333 4.13372 6.00892 3.82116 6.32148C3.5086 6.63404 3.33301 7.05797 3.33301 7.49999V15C3.33301 15.442 3.5086 15.8659 3.82116 16.1785C4.13372 16.4911 4.55765 16.6667 4.99967 16.6667H12.4997C12.9417 16.6667 13.3656 16.4911 13.6782 16.1785C13.9907 15.8659 14.1663 15.442 14.1663 15V14.1667" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M13.3333 4.16666L15.8333 6.66666M16.9875 5.4875C17.3157 5.15929 17.5001 4.71415 17.5001 4.25C17.5001 3.78585 17.3157 3.3407 16.9875 3.0125C16.6593 2.68429 16.2142 2.49991 15.75 2.49991C15.2858 2.49991 14.8407 2.68429 14.5125 3.0125L7.5 10V12.5H10L16.9875 5.4875Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        @endif
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-5 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-10 gap-3 p-4 pt-0">
        @forelse($list as $t)
        <div class="flex flex-col items-center">
            <div class="text-xs font-medium text-gray-700 text-center leading-tight h-8 flex items-center justify-center px-1">
                {{ $t->indikator->formatted_nama ?? '-' }}
            </div>
            <div class="w-full bg-white border border-gainsboro rounded-lg shadow flex items-center justify-center px-3 py-3 text-sm text-gray-900 h-12">
                <div class="text-center flex flex-row gap-2">
                    <div class="font-semibold">{{ number_format($t->value ?? 0, 2) }}</div>
                    @if($t->indikator->satuan)
                        <div class="text-xs text-gray-500">{{ $t->indikator->satuan }}</div>
                    @endif
                </div>
            </div>
        </div>  
        @empty
            <div class="col-span-full">
                <div class="rounded-xl p-8 flex flex-col items-center justify-center text-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-whitesmoke">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-gray-800 font-outfit">Belum ada target untuk plot lahan reklamasi</div>
                        <p class="text-xs text-gray-500 max-w-sm font-outfit">
                            Tambahkan target reklamasi agar progres harian dapat dihitung.
                        </p>
                    </div>
                    <x-main.primary-button
                        type="button"
                        class="mt-1 inline-flex items-center gap-2 px-4 py-2.5 text-white text-xs font-medium rounded-lg"
                        x-on:click="$dispatch('open-modal', 'form-target-reklamasi')"
                        :disabled="! auth()->user()->can('manage', [\App\Models\TargetProgresReklamasi::class, $plot])">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Target
                    </x-main.primary-button>
                </div>
            </div>
        @endforelse
        </div>
    </div>
</div>

@php
    $existingData = $list->mapWithKeys(function($t) {
        return [$t->indikator->nama => $t->value];
    })->toArray();
    
    $isEdit = !empty($existingData);
@endphp

<x-plot.target.modal name="form-target-reklamasi" title="Target Reklamasi" :plot="$plot">
    <x-plot.target.form-fields 
        :action="route('plot.target.store', $plot->plot_id)"
        method="POST"
        :data="$existingData"
        :isEdit="$isEdit"
    />
</x-plot.target.modal>