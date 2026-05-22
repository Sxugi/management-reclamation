@props(['plot', 'handover' => null])

@php
    $isEdit = $handover !== null;
    $action = $isEdit 
        ? route('plot.handover.update', [$plot->plot_id, $handover->plot_handover_id]) 
        : route('plot.handover.store', $plot->plot_id);
@endphp

<form
    id="handover-form"
    method="POST"
    action="{{ $action }}"
    enctype="multipart/form-data"
    class="space-y-5"
    autocomplete="off"
    data-handover-form
    x-data="{
        suratFiles: [],
        petaFiles: [],
        suratFilesToRemove: [],
        petaFilesToRemove: []
    }"
>
    @csrf
    @if($isEdit) @method('PUT') @endif

    <input type="hidden" name="plot_polygon" value="{{ $plot->polygon }}" id="plot-polygon">

    <div class="grid grid-cols-1 gap-4">
        <!-- Luas -->
        <div class="space-y-2">
            <x-main.input-label for="luas">
                Luas Lahan (Ha) <span class="text-red-500">*</span>
            </x-main.input-label>
            <x-main.text-input  
                type="number"
                step="0.01"
                name="luas"
                id="luas"
                value="{{ old('luas', $handover->luas ?? '') }}"
                required
                class="w-full text-sm"
                placeholder="Masukkan luas lahan dalam hektar"
            />
            <x-main.input-error :messages="$errors->get('luas')" data-turbo-temporary />
        </div>

        <!-- Lokasi -->
        <div class="space-y-2">
            <x-main.input-label for="lokasi">
                Lokasi <span class="text-red-500">*</span>
                <button 
                    type="button" 
                    id="fetch-location-btn"
                    class="ml-2 text-xs text-blue-600 hover:text-blue-800 underline"
                    title="Auto-fill dari koordinat polygon"
                >
                    Isi Otomatis
                </button>
            </x-main.input-label>
            <textarea 
                name="lokasi"
                id="lokasi"
                rows="2"
                required
                class="w-full border-solid border-1 border-gray-300 focus:border-darkslategray focus:ring-0 rounded-md px-3 py-2 resize-none leading-5 bg-transparent font-outfit text-sm min-h-fit resize-y mb-0"
                placeholder="Lokasi akan diisi otomatis dari koordinat polygon..."
            >{{ old('lokasi', $handover->lokasi ?? '') }}</textarea>
            <p class="text-xs text-slategray" id="location-status"></p>
            <x-main.input-error :messages="$errors->get('lokasi')" data-turbo-temporary />
        </div>

        <!-- Tanggal -->
        <div class="space-y-2">
            <x-main.input-label for="tanggal">
                Tanggal Serah Terima <span class="text-red-500">*</span>
            </x-main.input-label>
            <x-main.text-input  
                type="date"
                name="tanggal"
                id="tanggal"
                value="{{ old('tanggal', $handover?->tanggal?->format('Y-m-d') ?? '') }}"
                required
                class="w-full text-sm"
            />
            <x-main.input-error :messages="$errors->get('tanggal')" data-turbo-temporary />
        </div>

        <!-- Surat Files -->
        <div class="space-y-3">
            <x-main.input-label>
                Surat Serah Terima (PDF)
            </x-main.input-label>

            <!-- File Input -->
            <div class="relative">
                <input 
                    type="file"
                    name="surat[]"
                    id="surat-files"
                    accept=".pdf"
                    multiple
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                    @change="suratFiles = Array.from($event.target.files)"
                />
                <div class="flex items-stretch border border-gray-300 rounded-lg overflow-hidden bg-white hover:border-gray-400 transition-colors">
                    <div class="flex-shrink-0 bg-gray-50 border-gray-300 border-solid border-r-[1px] px-4 py-2.5 cursor-pointer hover:bg-gray-100 transition-colors text-sm font-medium text-gray-700">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        Choose File
                    </div>
                    <div class="flex-1 px-4 py-2.5 text-sm text-gray-700 bg-white truncate flex items-center min-w-0">
                        <span class="truncate" x-text="suratFiles.length > 0 ? (suratFiles.length === 1 ? suratFiles[0].name : suratFiles.length + ' files selected') : 'No file chosen'"></span>
                    </div>
                </div>
            </div>

            <!-- Existing Files -->
            @if($isEdit && $handover->suratFiles->count() > 0)
                <div class="space-y-2">
                    <div class="text-xs font-medium text-gray-600">Current Files:</div>
                    
                    @foreach($handover->suratFiles as $file)
                        <div class="border rounded-lg p-3 transition-all"
                             x-data="{ marked: false }"
                             :class="marked ? 'bg-red-50 border-red-200 opacity-60' : 'bg-blue-50 border-blue-200'">
                            <div class="flex items-center gap-3">
                                <!-- File Icon -->
                                <div class="w-9 h-9 flex items-center justify-center rounded-lg bg-red-100 flex-shrink-0 border border-red-200">
                                    <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 18h12V6h-4V2H4v16zm-2 1V0h10l6 6v14H2v-1z"/>
                                    </svg>
                                </div>
                                
                                <!-- File Info -->
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate" title="{{ $file->file_name }}">
                                        {{ $file->file_name }}
                                    </p>
                                    <p class="text-xs text-gray-500" x-text="marked ? 'Marked for removal' : '{{ $file->formatted_size }}'"></p>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <!-- View Button -->
                                    <a href="{{ Storage::url($file->file_path) }}" 
                                       target="_blank"
                                       class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200 transition-colors no-underline">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    
                                    <!-- Remove/Restore Button -->
                                    <template x-if="!marked">
                                        <button type="button" 
                                                @click="marked = true; suratFilesToRemove.push({{ $file->plot_handover_file_id }})"
                                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-red-700 bg-red-100 rounded-md hover:bg-red-200 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </template>
                                    
                                    <template x-if="marked">
                                        <button type="button" 
                                                @click="marked = false; suratFilesToRemove = suratFilesToRemove.filter(id => id !== {{ $file->plot_handover_file_id }})"
                                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-green-700 bg-green-100 rounded-md hover:bg-green-200 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Hidden inputs for removed files -->
                <template x-for="fileId in suratFilesToRemove" :key="'remove-surat-' + fileId">
                    <input type="hidden" name="remove_surat[]" :value="fileId" />
                </template>
            @endif

            <x-main.input-error :messages="$errors->get('surat.*')" data-turbo-temporary />
            <p class="text-xs text-gray-500">Dapat memasukan lebih dari satu file, Format: PDF (Maksimal 10MB per file)</p>
        </div>

        <!-- Peta Files -->
        <div class="space-y-3">
            <x-main.input-label>
                Peta Lahan (PDF, JPG, PNG, KML, KMZ, GeoJSON)
            </x-main.input-label>

            <!-- File Input -->
            <div class="relative">
                <input 
                    type="file"
                    name="peta[]"
                    id="peta-files"
                    accept=".pdf,.jpg,.jpeg,.png,.kml,.kmz,.geojson"
                    multiple
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                    @change="petaFiles = Array.from($event.target.files)"
                />
                <div class="flex items-stretch border border-gray-300 rounded-lg overflow-hidden bg-white hover:border-gray-400 transition-colors">
                    <div class="flex-shrink-0 bg-gray-50 border-gray-300 border-solid border-r-[1px] px-4 py-2.5 cursor-pointer hover:bg-gray-100 transition-colors text-sm font-medium text-gray-700">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                        Choose File
                    </div>
                    <div class="flex-1 px-4 py-2.5 text-sm text-gray-700 bg-white truncate flex items-center min-w-0">
                        <span class="truncate" x-text="petaFiles.length > 0 ? (petaFiles.length === 1 ? petaFiles[0].name : petaFiles.length + ' files selected') : 'No file chosen'"></span>
                    </div>
                </div>
            </div>

            <!-- Existing Files -->
            @if($isEdit && $handover->petaFiles->count() > 0)
                <div class="space-y-2">
                    <div class="text-xs font-medium text-gray-600">Current Files:</div>
                    
                    @foreach($handover->petaFiles as $file)
                        @php
                            $extension = strtolower(pathinfo($file->file_path, PATHINFO_EXTENSION));
                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                        @endphp
                        <div class="border rounded-lg p-3 transition-all"
                             x-data="{ marked: false }"
                             :class="marked ? 'bg-red-50 border-red-200 opacity-60' : 'bg-green-50 border-green-200'">
                            <div class="flex items-center gap-3">
                                <!-- Image Preview or Icon -->
                                @if($isImage)
                                    <div class="relative group/img flex-shrink-0">
                                        <img 
                                            src="{{ Storage::url($file->file_path) }}" 
                                            alt="{{ $file->file_name }}"
                                            class="w-9 h-9 object-cover rounded-lg border border-gray-200 shadow-sm cursor-pointer"
                                            @click="$dispatch('open-modal', 'peta-preview-{{ $file->plot_handover_file_id }}')"
                                        />
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover/img:bg-opacity-20 rounded-lg flex items-center justify-center opacity-0 group-hover/img:opacity-100 transition-all pointer-events-none">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                            </svg>
                                        </div>
                                    </div>
                                @else
                                    <div class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-100 flex-shrink-0 border border-blue-200">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- File Info -->
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate" title="{{ $file->file_name }}">
                                        {{ $file->file_name }}
                                    </p>
                                    <p class="text-xs text-gray-500" x-text="marked ? 'Marked for removal' : '{{ $file->formatted_size }} • {{ strtoupper($extension) }}'"></p>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <!-- View Button -->
                                    @if($isImage)
                                        <button type="button"
                                                @click="$dispatch('open-modal', 'peta-preview-{{ $file->plot_handover_file_id }}')"
                                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                    @else
                                        <a href="{{ Storage::url($file->file_path) }}" 
                                           target="_blank"
                                           class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200 transition-colors no-underline">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                    @endif
                                    
                                    <!-- Remove/Restore Button -->
                                    <template x-if="!marked">
                                        <button type="button" 
                                                @click="marked = true; petaFilesToRemove.push({{ $file->plot_handover_file_id }})"
                                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-red-700 bg-red-100 rounded-md hover:bg-red-200 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </template>
                                    
                                    <template x-if="marked">
                                        <button type="button" 
                                                @click="marked = false; petaFilesToRemove = petaFilesToRemove.filter(id => id !== {{ $file->plot_handover_file_id }})"
                                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-green-700 bg-green-100 rounded-md hover:bg-green-200 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Image Preview Modal -->
                        @if($isImage)
                            <x-main.modal name="peta-preview-{{ $file->plot_handover_file_id }}" maxWidth="5xl">
                                <div class="p-6">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-lg font-semibold text-gray-900 truncate pr-4">{{ $file->file_name }}</h3>
                                        <button type="button" 
                                                x-on:click="$dispatch('close')"
                                                class="text-gray-400 hover:text-gray-600 flex-shrink-0">
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

                <!-- Hidden inputs for removed files -->
                <template x-for="fileId in petaFilesToRemove" :key="'remove-peta-' + fileId">
                    <input type="hidden" name="remove_peta[]" :value="fileId" />
                </template>
            @endif

            <x-main.input-error :messages="$errors->get('peta.*')" data-turbo-temporary />
            <p class="text-xs text-gray-500">Dapat memasukan lebih dari satu file, Format: PDF, JPG, PNG, KML, KMZ, GeoJSON (Maksimal 20MB per file)</p>
        </div>
    </div>

    <div class="flex justify-end gap-3 pt-4 border-t">
        <button 
            type="button" 
            x-on:click="$dispatch('close-modal', 'form-handover')"
            class="bg-red-500 !text-white text-sm py-3 px-4 rounded-lg font-medium hover:bg-red-600 transition-colors no-underline"
        >
            Cancel
        </button>
        
        <x-main.primary-button type="submit" class="!font-medium py-3 px-4 text-sm">
            {{ $isEdit ? 'Update' : 'Save' }}
        </x-main.primary-button>
    </div>
</form>