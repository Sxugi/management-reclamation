@props(['lahan'])

<div class="relative" x-data="{ open: false }">
    <!-- Dropdown Toggle Button -->
    <button 
        @click="open = !open" 
        class="flex border-none fill-none bg-transparent hover:bg-gray-200 rounded-lg p-2 items-center"
    >
        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M15 16.875C15.7459 16.875 16.4613 16.5787 16.9887 16.0512C17.5162 15.5238 17.8125 14.8084 17.8125 14.0625C17.8125 13.3166 17.5162 12.6012 16.9887 12.0738C16.4613 11.5463 15.7459 11.25 15 11.25C14.2541 11.25 13.5387 11.5463 13.0113 12.0738C12.4838 12.6012 12.1875 13.3166 12.1875 14.0625C12.1875 14.8084 12.4838 15.5238 13.0113 16.0512C13.5387 16.5787 14.2541 16.875 15 16.875ZM2.8125 16.875C3.55842 16.875 4.27379 16.5787 4.80124 16.0512C5.32868 15.5238 5.625 14.8084 5.625 14.0625C5.625 13.3166 5.32868 12.6012 4.80124 12.0738C4.27379 11.5463 3.55842 11.25 2.8125 11.25C2.06658 11.25 1.35121 11.5463 0.823762 12.0738C0.296316 12.6012 0 13.3166 0 14.0625C0 14.8084 0.296316 15.5238 0.823762 16.0512C1.35121 16.5787 2.06658 16.875 2.8125 16.875ZM27.1875 16.875C27.9334 16.875 28.6488 16.5787 29.1762 16.0512C29.7037 15.5238 30 14.8084 30 14.0625C30 13.3166 29.7037 12.6012 29.1762 12.0738C28.6488 11.5463 27.9334 11.25 27.1875 11.25C26.4416 11.25 25.7262 11.5463 25.1988 12.0738C24.6713 12.6012 24.375 13.3166 24.375 14.0625C24.375 14.8084 24.6713 15.5238 25.1988 16.0512C25.7262 16.5787 26.4416 16.875 27.1875 16.875Z" fill="white"/>
        </svg>
    </button>

    <!-- Dropdown Menu with Transitions -->
    <div 
        x-show="open" 
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute z-10 mt-1 rounded-lg top-8 bg-slategray-200 ring-black/5 focus:outline-hidden"
    >
        <div class="py-1">
            <!-- Edit Option -->
            <a 
                href="{{ route('lahan.edit', $lahan->lahan_id) }}" 
                class="no-underline text-left block px-3 py-2 text-sm text-white hover:bg-gray-200 font-outfit border-none bg-slategray-200 rounded-lg"
            >
                Edit
            </a>
            
            <!-- Delete Option (Opens Modal) -->
            <button 
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'confirm-lahan-deletion-{{ $lahan->lahan_id }}'); open = false"
                class="no-underline text-left block px-3 py-2 text-sm text-white hover:bg-gray-200 font-outfit border-none bg-slategray-200 rounded-lg"
            >
                Hapus
            </button>
        </div>
    </div>
    
    <!-- Deletion Confirmation Modal -->
    <x-main.modal name="confirm-lahan-deletion-{{ $lahan->lahan_id }}" focusable>
        <form method="POST" action="{{ route('lahan.destroy', $lahan->lahan_id) }}" class="p-6">
            @csrf
            @method('DELETE')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Apakah Anda yakin ingin menghapus lahan ini?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Setelah lahan ini dihapus, semua data terkait akan hilang secara permanen. Tindakan ini tidak dapat dibatalkan.') }}
            </p>

            <div class="mt-6 flex justify-end font-outfit">
                <x-main.secondary-button x-on:click="$dispatch('close')">
                    {{ __('Batal') }}
                </x-main.secondary-button>

                <x-main.danger-button class="ml-3">
                    {{ __('Hapus Lahan') }}
                </x-main.danger-button>
            </div>
        </form>
    </x-main.modal>
</div>