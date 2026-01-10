<x-main-layout>
    <x-slot name="header">
        <div class="flex flex-row items-center justify-between gap-4">
            <h2 class="text-xl font-bold text-darkslategray font-outfit">File Rencana Reklamasi</h2>
            <div class="self-stretch flex flex-row items-center justify-start gap-1.5 text-left text-sm text-slategray font-outfit">
                <a type="button" href="{{ route('lahan.index') }}" class="relative leading-5 text-darkslategray no-underline visited:text-darkslategray">List Lahan</a>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.83333 12.6667L10 8.5L5.83333 4.33333" stroke="#667085" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                <div class="relative leading-5 text-darkslategray-200 font-medium">File Rencana Reklamasi</div>
            </div>
        </div>
    </x-slot>

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
    
    <div class="flex flex-col items-center justify-center py-12 px-6 rounded-lg bg-white shadow-sm">
        <div class="w-full max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                <!-- Left Column -->
                <div class="flex flex-col h-full">
                    <div class="rounded-2xl bg-white border-gainsboro border-solid border-[1px] box-border flex flex-col flex-1">
                        <div class="border-gainsboro border-solid border-b-[1px] border-t-[0px] border-r-[0px] border-l-[0px] flex flex-row items-center justify-start px-6">
                            <div class="text-base font-medium leading-6 p-3 pl-0">File Input</div>
                        </div>
                        
                        <div class="p-6 flex-1 flex flex-col">
                            <form action="{{ route('lahan.file-rencana.store', $lahan) }}" 
                                  method="POST" 
                                  enctype="multipart/form-data" 
                                  id="file-upload-form" 
                                  class="flex-1 flex flex-col justify-between">
                                @csrf
                                
                                <div class="space-y-4 text-sm">
                                    <div>
                                        <label class="block text-sm font-medium text-darkslategray-200 mb-2">Upload file</label>
                                        <div class="rounded-lg bg-white border-gainsboro border-solid border-[1px] overflow-hidden flex">
                                            
                                            @can('create', [\App\Models\ReklamasiFile::class, $lahan])  
                                                <label for="file-upload" class="flex-shrink-0 bg-whitesmoke-100 border-gainsboro border-solid border-r-[1px] border-t-[0px] border-b-[0px] border-l-[0px] px-4 py-2.5 cursor-pointer hover:bg-gray-100 transition-colors text-sm">
                                                    Choose File
                                                </label>
                                                <input type="file" 
                                                    id="file-upload" 
                                                    name="file" 
                                                    accept=".pdf" 
                                                    class="hidden" 
                                                    @if(!$file) required @endif>
                                            @else
                                                <label class="flex-shrink-0 bg-whitesmoke-100 border-gainsboro border-solid border-r-[1px] border-t-[0px] border-b-[0px] border-l-[0px] px-4 py-2.5 text-sm opacity-50 cursor-not-allowed text-gray-500">
                                                    Choose File 
                                                </label>
                                                <input type="file" disabled class="hidden">
                                            @endcan
                                            
                                            <div class="flex-1 bg-white px-4 py-2.5 text-sm overflow-hidden text-ellipsis whitespace-nowrap" 
                                                 id="file-name-display"
                                                 data-has-file="{{ $file ? 'true' : 'false' }}"
                                                 data-original-file="{{ $file ? $file->file_name : '' }}">
                                                @if($file)
                                                    <span class="text-darkslategray-300">{{ $file->file_name }}</span>
                                                @else
                                                    <span class="text-slategray">No file chosen</span>
                                                @endif
                                            </div>
                                        </div>
                                        <x-main.input-error :messages="$errors->get('file')" data-turbo-temporary class="mt-2" />
                                        @if($file)
                                            <p class="flex flex-row text-xs text-gray-500 mt-2">
                                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                @can('create', [\App\Models\ReklamasiFile::class, $lahan])
                                                    Pilih file baru untuk mengganti file yang ada
                                                @else
                                                    Anda tidak memiliki izin untuk mengubah file ini
                                                @endcan
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex justify-center mt-8">
                                    <x-main.primary-button 
                                        type="submit" 
                                        class="py-3 px-4 gap-2 font-medium hidden" 
                                        id="save-button"
                                        style="display: none;">
                                        <span id="button-text">{{ $file ? 'Update' : 'Save' }}</span>
                                    </x-main.primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="flex flex-col h-full">
                    @if($file)
                        <div class="rounded-2xl bg-white border-gainsboro border-solid border-[1px] box-border flex flex-col flex-1">
                            <div class="border-gainsboro border-solid border-b-[1px] border-t-[0px] border-r-[0px] border-l-[0px] flex flex-row items-center justify-start px-6">
                                <div class="text-base font-medium leading-6 p-3 pl-0">File Details</div>
                            </div>

                            <div class="p-6 flex-1 flex flex-col justify-between">
                                <div class="bg-white rounded-lg border border-gainsboro p-6">
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0">
                                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-red-500">
                                                <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" fill="currentColor"/>
                                                <text x="12" y="16" font-family="Arial" font-size="3" text-anchor="middle" fill="white">PDF</text>
                                            </svg>
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <div class="font-medium text-darkslategray-300 truncate">{{ $file->file_name }}</div>
                                            <div class="text-sm text-gray-500 mt-1 space-y-1">
                                                <div>Ukuran: {{ $file->file_size_human }}</div>
                                                <div>Diupload: {{ $file->created_at->format('d M Y H:i') }}</div>
                                                <div>Tipe: {{ strtoupper($file->mime_type) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-row gap-3 justify-center mt-8">
                                    <a href="{{ route('lahan.file-rencana.preview', $lahan) }}" target="_blank"
                                       class="rounded-md bg-darkslategray py-3 px-4 gap-2 !text-white text-sm no-underline hover:bg-slategray-200 font-medium">
                                        View Details
                                    </a>

                                    @can('delete', [\App\Models\ReklamasiFile::class, $lahan])
                                        <x-main.primary-button x-data="" 
                                                x-on:click.prevent="$dispatch('open-modal', 'confirm-file-rencana-deletion-{{ $file->reklamasi_file_id }}')"
                                                class="bg-red-500 text-white py-3 px-4 rounded-lg font-medium hover:bg-red-600 transition-colors">
                                            <span class="relative text-leading-5 font-medium">Delete</span>
                                        </x-main.primary-button>
                                        
                                        <x-main.modal name="confirm-file-rencana-deletion-{{ $file->reklamasi_file_id }}" focusable>
                                            <form method="POST" action="{{ route('lahan.file-rencana.destroy', [$lahan, $file]) }}" class="p-6">
                                                @csrf
                                                @method('DELETE')

                                                <h2 class="text-lg font-medium text-gray-900">
                                                    {{ __('Are you sure you want to delete this file reclamation plan?') }}
                                                </h2>

                                                <p class="mt-1 text-sm text-gray-600">
                                                    {{ __('After this file is deleted, all related data will be permanently lost. This action cannot be undone.') }}
                                                </p>

                                                <div class="mt-6 flex justify-end font-outfit">
                                                    <x-main.secondary-button @click="$dispatch('close')">
                                                        {{ __('Cancel') }}
                                                    </x-main.secondary-button>
                                                    <x-main.danger-button type="submit" class="ml-3">
                                                        {{ __('Delete') }}
                                                    </x-main.danger-button>
                                                </div>
                                            </form>
                                        </x-main.modal>
                                    @else
                                        <x-main.primary-button disabled class="bg-red-500 text-white py-3 px-4 rounded-lg font-medium hover:bg-red-600 transition-colors">
                                            Delete
                                        </x-main.primary-button>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="rounded-2xl bg-white border-gainsboro border-solid border-[1px] box-border flex flex-col flex-1">
                            <div class="border-gainsboro border-solid border-b-[1px] border-t-[0px] border-r-[0px] border-l-[0px] flex flex-row items-center justify-start px-6">
                                <div class="text-base font-medium leading-6 p-3 pl-0">File Details</div>
                            </div>

                            <div class="p-6 flex-1 flex items-center justify-center">
                                <div class="bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 p-12 text-center w-full">
                                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-gray-400 mx-auto mb-4">
                                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" fill="currentColor"/>
                                    </svg>
                                    <div class="space-y-2">
                                        <div class="font-medium text-gray-600">Belum ada file rencana</div>
                                        <div class="text-sm text-gray-500">Upload file PDF rencana reklamasi untuk melihat preview</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Check policy status
        const canCreate = {{ auth()->user()->can('create', [\App\Models\ReklamasiFile::class, $lahan]) ? 'true' : 'false' }};

        // Function to reset file display to original state
        function resetFileDisplay() {
            const display = document.getElementById('file-name-display');
            const saveButton = document.getElementById('save-button');
            const fileInput = document.getElementById('file-upload');
            
            if (!display || !saveButton || !fileInput) return;
            
            const originalFile = display.dataset.originalFile;
            const hasExistingFile = display.dataset.hasFile === 'true';
            
            // Clear file input
            fileInput.value = '';
            
            // Reset display text
            if (hasExistingFile && originalFile) {
                display.innerHTML = `<span class="text-darkslategray-300">${originalFile}</span>`;
            } else {
                display.innerHTML = '<span class="text-slategray">No file chosen</span>';
            }
            
            // Hide button dengan dua cara (class dan inline style)
            saveButton.classList.add('hidden');
            saveButton.style.display = 'none';
        }
        
        // Function to update file name when user selects a file
        function updateFileName(input) {
            if (!canCreate) return;

            const display = document.getElementById('file-name-display');
            const saveButton = document.getElementById('save-button');
            const buttonText = document.getElementById('button-text');
            
            if (!display || !saveButton || !buttonText) return;
            
            const hasExistingFile = display.dataset.hasFile === 'true';
            
            if (input.files && input.files[0]) {
                const fileName = input.files[0].name;
                
                // Update display
                display.innerHTML = `<span class="text-darkslategray-300">${fileName}</span>`;
                
                // Only show button if user has permission
                if(canCreate) {
                    saveButton.classList.remove('hidden');
                    saveButton.style.display = '';
                    
                    // Update button text
                    buttonText.textContent = hasExistingFile ? 'Update' : 'Save';
                }
            } else {
                // User canceled - reset
                resetFileDisplay();
            }
        }
        
        // Setup file input listener
        function setupFileInput() {
            const fileInput = document.getElementById('file-upload');
            if (fileInput && canCreate) {
                // Remove existing listener if any
                fileInput.removeEventListener('change', handleFileChange);
                // Add new listener
                fileInput.addEventListener('change', handleFileChange);
            }
            // Reset display saat setup
            resetFileDisplay();
        }
        
        function handleFileChange(event) {
            updateFileName(event.target);
        }
        
        // Initialize saat DOM ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupFileInput);
        } else {
            setupFileInput();
        }
        
        // Handle Turbo events
        document.addEventListener('turbo:load', setupFileInput);
        document.addEventListener('turbo:before-cache', resetFileDisplay);
        
        // Handle page show
        window.addEventListener('pageshow', function(event) {
            setupFileInput();
        });
    </script>
</x-main-layout>