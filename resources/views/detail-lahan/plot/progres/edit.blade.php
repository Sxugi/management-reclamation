<x-main-layout>
    <x-slot name="header">
        <div class="flex flex-row items-center justify-between gap-4">
            <h2 class="text-xl font-bold text-darkslategray font-outfit">Menambah Progres Reklamasi</h2>
            <div class="self-stretch flex flex-row items-center justify-start gap-1.5 text-left text-sm text-slategray font-outfit">
                <a type="button" href="{{ route('lahan.plot.index', $plot->lahan_id) }}" class="relative leading-5 text-darkslategray no-underline visited:text-darkslategray">Plot Lahan</a>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.83333 12.6667L10 8.5L5.83333 4.33333" stroke="#667085" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                <a type="button" href="{{ route('plot.show', $plot->plot_id) }}" class="relative leading-5 text-darkslategray no-underline visited:text-darkslategray">Detail Lahan {{ $plot->nama_plot }}</a>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.83333 12.6667L10 8.5L5.83333 4.33333" stroke="#667085" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                <div class="relative leading-5 text-darkslategray-200 font-medium">Mengedit Progres Reklamasi</div>
            </div>
        </div>
    </x-slot>
    
    <div class="self-stretch mx-auto">
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
    
        <x-plot.progres.form-progres-wrapper
            :action="route('plot.progres.update', [$plot, $progres])"
            method="PUT"
            :data="$data"
            :is-edit="true"
            :kategori="$kategori"
            :aktivitas="$aktivitas"
            :jenisAktivitas="$jenisAktivitas"
            :existingFiles="$existingFiles"
            :masterPohon="$masterPohon"
        />
    </div>
</x-main-layout>