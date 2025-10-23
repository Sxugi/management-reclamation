<x-main-layout>
    <x-slot name="header">
        <div class="flex flex-row items-center justify-between gap-4">
            <h2 class="text-xl font-bold text-darkslategray font-outfit">Detail Lahan {{ $plot->nama_plot }}</h2>
            <div class="self-stretch flex flex-row items-center justify-start gap-1.5 text-left text-sm text-slategray font-outfit">
                <a type="button" href="{{ route('lahan.index') }}" class="relative leading-5 text-darkslategray no-underline visited:text-darkslategray">List Lahan</a>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.83333 12.6667L10 8.5L5.83333 4.33333" stroke="#667085" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                <a type="button" href="{{ route('lahan.plot.index', $lahan->lahan_id) }}" class="relative leading-5 text-darkslategray no-underline visited:text-darkslategray">Plot Lahan</a>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.83333 12.6667L10 8.5L5.83333 4.33333" stroke="#667085" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                <div class="relative leading-5 text-darkslategray-200 font-medium">Detail Lahan {{ $plot->nama_plot }}</div>
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
        @if($errors->has('target'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ $errors->first('target') }}
            </div>
        @endif

        <x-plot.metrics :plot="$plot" :activityLogs="$activityLogs" :progres="$progres" :progressPercent="$progressPercent" :progresDelta="$progresDelta"/>
        <x-plot.target.container :plot="$plot" :target="$target"/>
        <x-plot.progres.list :plot="$plot" :lahan="$lahan" :progres="$progres" :kategori="$kategori" :hasFilter="$hasFilter" />
        <div class="bg-white overflow-hidden shadow-md rounded-lg sm:rounded-lg">
            <div class="p-6">
                <div class="self-stretch flex flex-row items-center justify-start gap-1 mb-6">
                    <div class="flex-1 flex flex-col items-start justify-start gap-1 text-lg text-gray">
                        <div class="self-stretch relative leading-7 font-semibold">Area Lahan Reklamasi</div>
                        <div class="self-stretch relative text-sm leading-5 text-slategray">Plot lahan menjadi beberapa bagian.</div>
                    </div>
                    <a href=" {{ route('plot.edit', [$lahan->lahan_id, $plot->plot_id]) }} " class="rounded-lg bg-darkslategray overflow-hidden flex flex-row items-center justify-center py-3 px-4 gap-2 !text-white no-underline hover:bg-slategray-200">
                        <span class="relative leading-5 font-medium">Edit</span>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.83301 5.83333H4.99967C4.55765 5.83333 4.13372 6.00892 3.82116 6.32148C3.5086 6.63404 3.33301 7.05797 3.33301 7.49999V15C3.33301 15.442 3.5086 15.8659 3.82116 16.1785C4.13372 16.4911 4.55765 16.6667 4.99967 16.6667H12.4997C12.9417 16.6667 13.3656 16.4911 13.6782 16.1785C13.9907 15.8659 14.1663 15.442 14.1663 15V14.1667" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M13.3333 4.16666L15.8333 6.66666M16.9875 5.4875C17.3157 5.15929 17.5001 4.71415 17.5001 4.25C17.5001 3.78585 17.3157 3.3407 16.9875 3.0125C16.6593 2.68429 16.2142 2.49991 15.75 2.49991C15.2858 2.49991 14.8407 2.68429 14.5125 3.0125L7.5 10V12.5H10L16.9875 5.4875Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
                <x-plot.map-controls :plot="$plot" :lahan="$lahan" :enableDraw="false"/>
            </div>
        </div>
    </div>
</x-main-layout>