<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row sm:flex-row sm:items-center sm:justify-between items-center justify-between gap-4 sm:gap-0 w-full">
            <a type="button" href="{{ route('lahan.index') }}" class="inline-flex items-center rounded-md bg-darkslategray px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slategray-200 border-none font-outfit transition no-underline">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                <span class="text-white">Back</span>
            </a>
            <h2 class="text-xl sm:text-2xl font-bold text-darkslategray-300 font-outfit">Edit Data Lahan</h2>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Map Column -->
                <x-lahan.map :longitude="$point->getX() ?? null" :latitude="$point->getY() ?? null" />

                <!-- Form Column -->
                <x-lahan.form :lahan="$lahan" />
            </div>
        </div>
    </div>
</x-app-layout>