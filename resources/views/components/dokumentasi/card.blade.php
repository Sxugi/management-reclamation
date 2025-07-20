@props([
    'lahan',
    'dokumentasi',
    'hasFilter' => false,
])

<div class="w-full font-outfit">
    @if($dokumentasi->count() > 0)
        <!-- Grid Layout for Cards -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 w-full font-outfit">
            @foreach($dokumentasi as $doc)
                <div class="w-full max-w-3xs mx-auto shadow-sm rounded-lg bg-white flex flex-col justify-between transition-transform hover:scale-105 duration-200">
                    <div class="flex flex-col items-start justify-start gap-2">
                        <div class="w-full h-[200px] overflow-hidden rounded-t-lg">
                            @if($doc->image_path)
                                <img class="w-full h-full object-cover" 
                                     alt="Dokumentasi {{ $doc->nama ?? 'Lahan' }}" 
                                     src="{{ Storage::url($doc->image_path) }}"
                                     onerror="this.src='{{ asset('images/default-placeholder.png') }}'">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="flex-1 flex items-center justify-center pt-4 px-4">
                            <div class="text-base font-bold leading-5 text-darkslategray-200">
                                {{ $doc->nama ?? 'Dokumentasi Lahan' }}
                            </div>
                        </div>

                        <div class="flex-1 flex items-center justify-center">
                            <div class="text-darkslategray-200 text-sm leading-5 font-medium text-justify px-4">
                                <p class="font-bold">Deskripsi:</p>
                                @if($doc->deskripsi)
                                    <p class="line-clamp-3">{{ Str::limit($doc->deskripsi, 100) }}</p>
                                @else
                                    <p class="text-gray-400 italic">Tidak ada deskripsi</p>
                                @endif
                            </div>
                        </div>

                        <div class="px-4 text-xs text-gray-500 text-center">
                            {{ $doc->created_at ? $doc->created_at->format('d M Y') : 'Tanggal tidak tersedia' }}
                        </div>
                    </div>
                    <x-dokumentasi.card-button :lahan="$lahan" :doc="$doc" />
                </div>
                <x-main.modal name="confirm-dokumentasi-deletion-{{ $doc->dokumentasi_id }}" focusable>
                    <form method="POST" action="{{ route('lahan.dokumentasi.destroy', [$lahan, $doc]) }}" class="p-6">
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
            @endforeach
        </div>
        <div class="mt-6">
            {{ $dokumentasi->links('components.main.pagination') }}
        </div>
    @else
        @if($hasFilter)
            <x-dokumentasi.no-filtered-data :lahan="$lahan" />
        @else
            <x-dokumentasi.empty-state :lahan="$lahan" />
        @endif
    @endif
</div>