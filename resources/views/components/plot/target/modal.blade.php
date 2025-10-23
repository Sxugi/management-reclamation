@props(['name', 'title' => 'Form', 'plot' => null])

<div 
    x-data="{ open: false }"
    x-on:open-modal.window="if ($event.detail === '{{ $name }}') open = true"
    x-on:close-modal.window="if ($event.detail === '{{ $name }}') open = false"
    x-on:keyup.escape.window="$dispatch('close-modal', '{{ $name }}')"
    x-show="open"
    class="fixed inset-0 flex items-center justify-center bg-black/50 z-50"
    style="display: none"
    @click.self="$dispatch('close-modal', '{{ $name }}')"
>
    <div 
        class="bg-white rounded-2xl shadow-xl w-full max-w-3xl mx-4 max-h-[90vh] overflow-y-auto relative"
        @click.stop=""
        tabindex="0"
    >
        <div class="flex items-center justify-between p-6 border-b border-gainsboro bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-2xl">
            <div class="font-bold text-lg text-darkslategray font-outfit flex items-center gap-2">
                <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-green-200">
                    <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    </svg>
                </div>
                <div class="flex flex-col">
                    {{ $title }} {{ $plot?->nama_plot ?? '' }}
                    <div class="!font-medium text-xs text-slategray font-outfit">
                        Tambahkan atau ubah target indikator reklamasi pada blok ini.
                    </div>
                </div>
            </div>
            <button 
                type="button" 
                @click="$dispatch('close-modal', '{{ $name }}')"
                class="w-10 h-10 rounded-xl bg-white shadow-sm border border-gainsboro hover:bg-gray-500 flex items-center justify-center text-slategray hover:text-white transition-all duration-200"
                aria-label="Tutup modal"
            >
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="p-6 font-outfit">
            {{ $slot }}
        </div>
    </div>
</div>