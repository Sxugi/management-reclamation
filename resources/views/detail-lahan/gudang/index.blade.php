<x-main-layout>
    <x-slot name="header">
        <div class="flex flex-row items-center justify-between gap-4">
            <h2 class="text-xl font-bold text-darkslategray font-outfit">Gudang</h2>
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
    </div>
</x-main-layout>