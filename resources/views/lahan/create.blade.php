<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row sm:flex-row sm:items-center sm:justify-between items-center justify-between gap-4 sm:gap-0 w-full">
            <x-main.primary-button href="{{ route('lahan.index') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                <span>Back</span>
            </x-main.primary-button>
            <h2 class="text-xl sm:text-2xl font-bold text-darkslategray-300 font-outfit">Input Data Lahan</h2>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Map Column -->
                <x-lahan.map />

                <!-- Form Column -->
                <x-lahan.form />
            </div>
        </div>
    </div>
</x-app-layout>