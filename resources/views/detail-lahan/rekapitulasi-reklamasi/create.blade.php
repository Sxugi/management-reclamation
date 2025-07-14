<x-main-layout>
    <x-slot name="header">
        <div class="flex flex-row items-center justify-between gap-4">
            <h2 class="text-xl font-bold text-darkslategray font-outfit">Rekapitulasi Pelaksanaan Reklamasi</h2>
            <div class="self-stretch flex flex-row items-center justify-start gap-1.5 text-left text-sm text-slategray font-outfit">
                <a type="button" href="{{ route('lahan.index') }}" class="relative leading-5 text-darkslategray no-underline visited:text-darkslategray">List Lahan</a>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.83333 12.6667L10 8.5L5.83333 4.33333" stroke="#667085" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                <div class="relative leading-5 text-darkslategray-200 font-medium">Rekapitulasi Pelaksanaan Reklamasi</div>
            </div>
        </div>
    </x-slot>

    @php
        $tahun_aktif = request('tahun', $lahan->tahun_awal);
    @endphp

    <div class="self-stretch flex flex-row items-center justify-between text-sm font-outfit">
        <x-rekapitulasi-reklamasi.tab-tahun :lahan="$lahan" :rekapitulasi_reklamasi="$rekapitulasi_reklamasi" :tahun_aktif="$tahun_aktif" />
        <x-rekapitulasi-reklamasi.action-button :lahan="$lahan" :rekapitulasi_reklamasi="$rekapitulasi_reklamasi" :tahun_aktif="$tahun_aktif" />
    </div>

    <x-rekapitulasi-reklamasi.form :lahan="$lahan" :rekapitulasi_reklamasi="$rekapitulasi_reklamasi" :tahun_aktif="$tahun_aktif" :readonly="false" />
</x-main-layout>