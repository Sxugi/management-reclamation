@props(['
    lahan', 
    'dokumentasi'
])

<div class="w-full max-w-6xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <x-dokumentasi.open-modal :dokumentasi="$dokumentasi" />

        <div class="flex flex-col justify-between space-y-6">
            <div class="space-y-4">
                <div class="text-lg font-bold text-darkslategray-200">Deskripsi</div>
                @if($dokumentasi->deskripsi)
                    <div class="prose prose-sm max-w-none">
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $dokumentasi->deskripsi }}</p>
                    </div>
                @else
                    <p class="text-gray-500 italic">Tidak ada deskripsi.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4">
    <a href="{{ route('lahan.dokumentasi.edit', [$lahan, $dokumentasi]) }}" 
        class="rounded-lg bg-darkslategray overflow-hidden flex flex-row items-center justify-center py-3 px-4 gap-2 !text-white no-underline hover:bg-slategray-200">
        <span class="relative text-leading-5 font-medium">Edit</span>
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M5.83301 5.83333H4.99967C4.55765 5.83333 4.13372 6.00892 3.82116 6.32148C3.5086 6.63404 3.33301 7.05797 3.33301 7.49999V15C3.33301 15.442 3.5086 15.8659 3.82116 16.1785C4.13372 16.4911 4.55765 16.6667 4.99967 16.6667H12.4997C12.9417 16.6667 13.3656 16.4911 13.6782 16.1785C13.9907 15.8659 14.1663 15.442 14.1663 15V14.1667" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M13.3333 4.16666L15.8333 6.66666M16.9875 5.4875C17.3157 5.15929 17.5001 4.71415 17.5001 4.25C17.5001 3.78585 17.3157 3.3407 16.9875 3.0125C16.6593 2.68429 16.2142 2.49991 15.75 2.49991C15.2858 2.49991 14.8407 2.68429 14.5125 3.0125L7.5 10V12.5H10L16.9875 5.4875Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </a>
    
    <x-main.primary-button x-data="" 
            x-on:click.prevent="$dispatch('open-modal', 'confirm-dokumentasi-deletion-{{ $dokumentasi->dokumentasi_id }}'); open = false"
            class="py-3 px-4 gap-2 font-medium">
        <span class="relative text-leading-5 font-medium">Delete</span>
        <svg width="18" height="18" viewBox="0 0 15 18" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4.52679 1.0877L4.28571 1.5625H1.07143C0.478795 1.5625 0 2.0373 0 2.625C0 3.2127 0.478795 3.6875 1.07143 3.6875H13.9286C14.5212 3.6875 15 3.2127 15 2.625C15 2.0373 14.5212 1.5625 13.9286 1.5625H10.7143L10.4732 1.0877C10.2924 0.725781 9.92076 0.5 9.51562 0.5H5.48438C5.07924 0.5 4.70759 0.725781 4.52679 1.0877ZM13.9286 4.75H1.07143L1.78125 16.0059C1.83482 16.8459 2.53795 17.5 3.38504 17.5H11.615C12.4621 17.5 13.1652 16.8459 13.2187 16.0059L13.9286 4.75Z" fill="white"/>
        </svg>
    </x-main.primary-button>
</div>

<x-main.modal name="confirm-dokumentasi-deletion-{{ $dokumentasi->dokumentasi_id }}" focusable>
    <form method="POST" action="{{ route('lahan.dokumentasi.destroy', [$lahan, $dokumentasi]) }}" class="p-6">
        @csrf
        @method('DELETE')

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Apakah Anda yakin ingin menghapus dokumentasi ini?') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Setelah dokumentasi ini dihapus, semua data terkait akan hilang secara permanen. Tindakan ini tidak dapat dibatalkan.') }}
        </p>

        <div class="mt-6 flex justify-end font-outfit">
            <x-main.secondary-button @click="$dispatch('close')">
                {{ __('Batal') }}
            </x-main.secondary-button>
            <x-main.primary-button class="ml-3">
                {{ __('Hapus') }}
            </x-main.primary-button>
        </div>
    </form>
</x-main.modal>