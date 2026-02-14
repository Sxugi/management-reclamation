@props(['plot', 'handover'])

<div class="mb-6 bg-white rounded-2xl overflow-hidden shadow-md">
    <!-- Header -->
    <div class="px-6 py-4 border-0 border-b-1 border-gainsboro border-solid">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div>
                    <h3 class="font-bold text-base text-darkslategray font-outfit">Serah Terima Lahan</h3>
                    <p class="text-xs text-slategray font-outfit">Informasi serah terima lahan reklamasi</p>
                </div>
            </div>
            
            @if($handover)
                <div class="flex gap-2">
                    @can('update', $handover)
                        <x-main.primary-button
                            type="button"
                            class="py-3 px-4 gap-2 font-medium cursor-pointer"
                            x-on:click="$dispatch('open-modal','form-handover')">
                            <span class="relative leading-5 font-medium">Edit</span>
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.83301 5.83333H4.99967C4.55765 5.83333 4.13372 6.00892 3.82116 6.32148C3.5086 6.63404 3.33301 7.05797 3.33301 7.49999V15C3.33301 15.442 3.5086 15.8659 3.82116 16.1785C4.13372 16.4911 4.55765 16.6667 4.99967 16.6667H12.4997C12.9417 16.6667 13.3656 16.4911 13.6782 16.1785C13.9907 15.8659 14.1663 15.442 14.1663 15V14.1667" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M13.3333 4.16666L15.8333 6.66666M16.9875 5.4875C17.3157 5.15929 17.5001 4.71415 17.5001 4.25C17.5001 3.78585 17.3157 3.3407 16.9875 3.0125C16.6593 2.68429 16.2142 2.49991 15.75 2.49991C15.2858 2.49991 14.8407 2.68429 14.5125 3.0125L7.5 10V12.5H10L16.9875 5.4875Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </x-main.primary-button>
                    @endcan
                    
                    @can('delete', $handover)
                        <x-main.primary-button
                            type="button"
                            class="py-3 px-4 bg-red-500 hover:!bg-red-600 gap-2 font-medium hidden cursor-pointer"
                            x-on:click="$dispatch('open-modal','confirm-handover-deletion-{{ $handover->plot_handover_id }}')">
                            <span>Delete</span>
                            <svg width="16" height="16" viewBox="0 0 15 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.52679 1.0877L4.28571 1.5625H1.07143C0.478795 1.5625 0 2.0373 0 2.625C0 3.2127 0.478795 3.6875 1.07143 3.6875H13.9286C14.5212 3.6875 15 3.2127 15 2.625C15 2.0373 14.5212 1.5625 13.9286 1.5625H10.7143L10.4732 1.0877C10.2924 0.725781 9.92076 0.5 9.51562 0.5H5.48438C5.07924 0.5 4.70759 0.725781 4.52679 1.0877ZM13.9286 4.75H1.07143L1.78125 16.0059C1.83482 16.8459 2.53795 17.5 3.38504 17.5H11.615C12.4621 17.5 13.1652 16.8459 13.2187 16.0059L13.9286 4.75Z" fill="white"/>
                            </svg>     
                        </x-main.primary-button>
                    @endcan
                </div>
            @endif
        </div>
    </div>

    <!-- Content -->
    @if($handover)
        <div class="p-6 font-outfit">
            <!-- Info Cards Grid - 4 columns desktop, 2 columns mobile -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Luas Card -->
                <div class="bg-white rounded-xl p-4 border border-gainsboro shadow-xs">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-almostgray">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="#1D2939" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-500">Area</span>
                    </div>
                    <div class="text-xs text-gray-500 font-medium mb-1">Luas Lahan</div>
                    <div class="text-xl font-bold text-gray-900">
                        {{ number_format($handover->luas, 2) }} 
                        <span class="text-sm font-normal">Ha</span>
                    </div>
                </div>

                <!-- Tanggal Card -->
                <div class="bg-white rounded-xl p-4 border border-gainsboro shadow-xs">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-almostgray">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="#1D2939" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-500">Tanggal</span>
                    </div>
                    <div class="text-xs text-gray-500 font-medium mb-1">Tanggal Serah Terima</div>
                    <div class="text-xl font-bold text-gray-900">
                        {{ \Carbon\Carbon::parse($handover->tanggal)->format('d M Y') }}
                    </div>
                </div>

                <!-- Total Files Card -->
                <div class="bg-white rounded-xl p-4 border border-gainsboro shadow-xs">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-almostgray">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="#1D2939" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-500">Dokumen</span>
                    </div>
                    <div class="text-xs text-gray-500 font-medium mb-1">Total Lampiran</div>
                    <div class="text-xl font-bold text-gray-900">
                        {{ $handover->files->count() }} 
                        <span class="text-sm font-normal">File</span>
                    </div>
                </div>

                <!-- Lokasi Card  -->
                <div class=" bg-white rounded-xl p-4 border border-gainsboro shadow-xs">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-almostgray">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="#1D2939" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-500">Lokasi</span>
                    </div>
                    <div class="text-xs text-gray-500 font-medium mb-1">Alamat</div>
                    <p class="text-sm text-gray-900 leading-tight line-clamp-2" title="{{ $handover->lokasi }}">
                        {{ $handover->lokasi }}
                    </p>
                </div>
            </div>

            <!-- Files Section - 2 columns -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Surat Files -->
                @if($handover->suratFiles->count() > 0)
                    <div class="bg-white rounded-xl border border-gainsboro overflow-hidden shadow-xs">
                        <div class="bg-almostgray px-4 py-3 border-b border-gainsboro">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-darkslategray" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <span class="font-semibold text-sm text-darkslategray">Surat Serah Terima</span>
                                <span class="ml-auto text-xs text-darkslategray bg-white px-2 py-1 rounded-full">{{ $handover->suratFiles->count() }} file</span>
                            </div>
                        </div>
                        <div class="p-4 space-y-2 max-h-64 overflow-y-auto">
                            @foreach($handover->suratFiles as $file)
                                <a href="{{ Storage::url($file->file_path) }}" 
                                   target="_blank"
                                   class="flex items-center gap-3 p-3 bg-almostgray hover:bg-slategray border border-gainsboro rounded-lg transition-colors group no-underline">
                                    <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-red-100 flex-shrink-0 group-hover:bg-red-200 transition-colors border border-red-200">
                                        <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 18h12V6h-4V2H4v16zm-2 1V0h10l6 6v14H2v-1z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate group-hover:text-red-600 transition-colors">{{ $file->file_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $file->formatted_size }}</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-500 group-hover:text-red-600 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Peta Files -->
                @if($handover->petaFiles->count() > 0)
                    <div class="bg-white rounded-xl border border-gainsboro overflow-hidden shadow-xs">
                        <div class="bg-almostgray px-4 py-3 border-b border-blue-100">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-darkslategray" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                </svg>
                                <span class="font-semibold text-sm text-darkslategray">Peta Lahan</span>
                                <span class="ml-auto text-xs text-darkslategray bg-white px-2 py-1 rounded-full">{{ $handover->petaFiles->count() }} file</span>
                            </div>
                        </div>
                        <div class="p-4 space-y-2 max-h-64 overflow-y-auto">
                            @foreach($handover->petaFiles as $file)
                                @php
                                    $extension = strtolower(pathinfo($file->file_path, PATHINFO_EXTENSION));
                                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                                    $isMap = in_array($extension, ['kml', 'kmz', 'geojson']);
                                @endphp
                                <a href="{{ Storage::url($file->file_path) }}" 
                                   target="_blank"
                                   @if($isImage) x-on:click.prevent="$dispatch('open-modal', 'peta-preview-{{ $file->plot_handover_file_id }}')" @endif
                                   class="flex items-center gap-3 p-3 bg-almostgray hover:slategray border border-gainsboro rounded-lg transition-colors group no-underline">
                                    @if($isImage)
                                        <div class="relative group/img">
                                            <img 
                                                src="{{ Storage::url($file->file_path) }}" 
                                                alt="{{ $file->file_name }}"
                                                class="w-10 h-10 object-cover rounded-lg border border-blue-200 shadow-sm flex-shrink-0"
                                            />
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover/img:bg-opacity-20 rounded-lg flex items-center justify-center opacity-0 group-hover/img:opacity-100 transition-all pointer-events-none">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                                </svg>
                                            </div>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-blue-100 flex-shrink-0 group-hover:bg-blue-200 transition-colors border border-blue-200">
                                            @if($isMap)
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M4 18h12V6h-4V2H4v16zm-2 1V0h10l6 6v14H2v-1z"/>
                                                </svg>
                                            @endif
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate group-hover:text-blue-600 transition-colors">{{ $file->file_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $file->formatted_size }} • {{ strtoupper($extension) }}</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-500 group-hover:text-blue-600 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>

                                @if($isImage)
                                    <x-main.modal name="peta-preview-{{ $file->plot_handover_file_id }}" maxWidth="5xl">
                                        <div class="p-6">
                                            <div class="flex justify-between items-center mb-4">
                                                <h3 class="text-lg font-semibold text-gray-900">{{ $file->file_name }}</h3>
                                                <button type="button" 
                                                        x-on:click="$dispatch('close')"
                                                        class="text-gray-400 hover:text-gray-600">
                                                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="flex justify-center bg-gray-100 rounded-lg p-4">
                                                <img src="{{ Storage::url($file->file_path) }}" 
                                                     alt="{{ $file->file_name }}" 
                                                     class="max-w-full h-auto rounded-lg shadow-lg">
                                            </div>
                                            <div class="mt-4 flex justify-end">
                                                <a href="{{ Storage::url($file->file_path) }}" 
                                                   download
                                                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 no-underline">
                                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                    </svg>
                                                    Download
                                                </a>
                                            </div>
                                        </div>
                                    </x-main.modal>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="p-12">
            <div class="rounded-xl p-8 flex flex-col items-center justify-center text-center gap-3">
                <div class="w-10 h-10 mx-auto mb-4 flex items-center justify-center rounded-full bg-whitesmoke">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-semibold text-gray-800 font-outfit">Belum ada data serah terima lahan</div>
                    <p class="text-xs text-gray-500 max-w-sm mx-auto font-outfit">
                        Tambahkan informasi serah terima lahan untuk dokumentasi reklamasi yang lebih lengkap.
                    </p>
                </div>
                @can('create', [App\Models\PlotHandover::class, $plot])
                    <x-main.primary-button
                        type="button"
                        class="mt-1 inline-flex items-center gap-2 px-4 py-2.5 text-white text-xs font-medium rounded-lg"
                        x-on:click="$dispatch('open-modal', 'form-handover')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span>Tambah Data Serah Terima</span>
                    </x-main.primary-button>
                @endcan
            </div>
        </div>
    @endif
</div>

<!-- Modals -->
<x-plot.handover.modal 
    name="form-handover" 
    title="Serah Terima Lahan" 
    :plot="$plot"
    :handover="$handover"
/>

@if($handover)
    @can('delete', $handover)
        <x-main.modal name="confirm-handover-deletion-{{ $handover->plot_handover_id }}" focusable>
            <form method="POST" action="{{ route('plot.handover.destroy', [$plot->plot_id, $handover->plot_handover_id]) }}" class="p-6 text-left whitespace-normal">
                @csrf
                @method('DELETE')
                <h2 class="text-lg font-medium text-gray-900">
                     {{ __('Are you sure you want to delete this data serah terima?') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Once deleted, all data related to this following data:') }}
                    <strong>{{ $handover->files->count() }} {{ __('file') }}</strong>
                    {{ __('will be permanently lost. This action cannot be undone.') }}
                </p>
                <div class="mt-6 flex justify-end font-outfit">
                    <x-main.secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-main.secondary-button>
                    <x-main.danger-button type="submit" class="ml-3">
                        {{ __('Delete') }}
                    </x-main.danger-button>
                </div>
            </form>
        </x-main.modal>
    @endcan
@endif