<x-main-layout>
    <x-slot name="header">
        <div class="flex flex-row items-center justify-between gap-4">
            <h2 class="text-xl font-bold text-darkslategray font-outfit">
                File Laporan Pelaksanaan Reklamasi
            </h2>
            <div class="self-stretch flex flex-row items-center justify-start gap-1.5 text-left text-sm text-slategray font-outfit">
                <a type="button" href="{{ route('lahan.index') }}" class="relative leading-5 text-darkslategray no-underline visited:text-darkslategray">List Lahan</a>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.83333 12.6667L10 8.5L5.83333 4.33333" stroke="#667085" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                <div class="relative leading-5 text-darkslategray-200 font-medium">File Laporan Pelaksanaan Reklamasi</div>
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
                            <form action="{{ route('lahan.file-laporan.store', $lahan) }}" 
                                  method="POST" 
                                  enctype="multipart/form-data" 
                                  id="file-upload-form"
                                  class="flex-1 flex flex-col justify-between">
                                @csrf
                                
                                <div class="space-y-4 text-sm">
                                    <div>
                                        <label class="block text-sm font-medium text-darkslategray-200 mb-2">Pilih Tahun</label>
                                        <select name="tahun" 
                                                id="tahun-select" 
                                                @if(isset($tahun)) value="{{ $tahun }}" @endif
                                                class="border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md block w-full font-outfit text-sm" 
                                                required>
                                            <option value="">- Pilih Tahun Laporan -</option>
                                            @for($year = $lahan->tahun_awal; $year <= $lahan->tahun_akhir; $year++)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    
                                    <div id="file-upload-section" class="hidden">
                                        <label class="block text-sm font-medium text-darkslategray-200 mb-2">Upload file</label>
                                        <div class="rounded-lg bg-white border-gainsboro border-solid border-[1px] overflow-hidden flex">

                                            @can('create', [\App\Models\ReklamasiFile::class, $lahan])
                                                <label for="file-upload" class="flex-shrink-0 bg-whitesmoke-100 border-gainsboro border-solid border-r-[1px] border-t-[0px] border-b-[0px] border-l-[0px] px-4 py-2.5 cursor-pointer hover:bg-gray-100 transition-colors text-sm">
                                                    Choose File
                                                </label>
                                                <input type="file" 
                                                    name="file" 
                                                    id="file-upload" 
                                                    accept=".pdf" 
                                                    class="hidden">
                                            @else
                                                <label class="flex-shrink-0 bg-whitesmoke-100 border-gainsboro border-solid border-r-[1px] border-t-[0px] border-b-[0px] border-l-[0px] px-4 py-2.5 text-sm opacity-50 cursor-not-allowed text-gray-500">
                                                    Choose File
                                                </label>
                                                <input type="file" disabled class="hidden">
                                            @endcan

                                            <div class="flex-1 bg-white px-4 py-2.5 text-sm overflow-hidden text-ellipsis whitespace-nowrap" 
                                                 id="file-name-display"
                                                 data-original-file="">
                                                <span class="text-slategray">No file chosen</span>
                                            </div>
                                        </div>
                                        <x-main.input-error :messages="$errors->get('file')" data-turbo-temporary class="mt-2" />
                                        <p class="flex flex-row text-xs text-gray-500 mt-2" id="file-info-text" style="display: none;">
                                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Pilih file baru untuk mengganti file yang ada
                                        </p>
                                    </div>
                                </div>

                                <div class="flex justify-center mt-8">
                                    <x-main.primary-button 
                                        type="submit" 
                                        class="py-3 px-4 gap-2 font-medium hidden" 
                                        id="save-button"
                                        style="display: none;">
                                        <span id="button-text">Save</span>
                                    </x-main.primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="flex flex-col h-full">
                    <div class="rounded-2xl bg-white border-gainsboro border-solid border-[1px] box-border flex flex-col flex-1">
                        <div class="border-gainsboro border-solid border-b-[1px] border-t-[0px] border-r-[0px] border-l-[0px] flex flex-row items-center justify-start px-6">
                            <div class="text-base font-medium leading-6 p-3 pl-0">File Details</div>
                        </div>

                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div id="laporan-content">
                                <div class="bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 p-12 text-center">
                                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-gray-400 mx-auto mb-4">
                                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" fill="currentColor"/>
                                    </svg>
                                    <div class="space-y-2">
                                        <div class="font-medium text-gray-600">Belum ada file laporan pelaksanaan</div>
                                        <div class="text-sm text-gray-500">Pilih tahun untuk melihat atau mengunggah file</div>
                                    </div>
                                </div>
                            </div>

                            @foreach($file as $year => $f)
                                <div id="laporan-actions-{{ $f->reklamasi_file_id }}" class="hidden flex flex-row gap-3 justify-center mt-8">
                                    <a href="{{ route('lahan.file-laporan.preview', [$lahan, 'tahun' => $year]) }}" target="_blank"
                                       class="rounded-md bg-darkslategray py-3 px-4 gap-2 !text-white text-sm no-underline hover:bg-slategray-200 font-medium">
                                        View Details
                                    </a>

                                    @can('delete', [\App\Models\ReklamasiFile::class, $lahan])
                                        <x-main.primary-button 
                                            x-data="" 
                                            x-on:click.prevent="$dispatch('open-modal', 'confirm-laporan-deletion-{{ $year }}')"
                                            class="bg-red-500 text-white py-3 px-4 rounded-lg font-medium hover:bg-red-600 transition-colors">
                                            <span class="relative text-leading-5 font-medium">Delete</span>
                                        </x-main.primary-button>

                                        <x-main.modal name="confirm-laporan-deletion-{{ $year }}" focusable>
                                            <form method="POST" action="{{ route('lahan.file-laporan.destroy', [$lahan, $year]) }}" class="p-6">
                                                @csrf
                                                @method('DELETE')

                                                <h2 class="text-lg font-medium text-gray-900">
                                                    {{ __('Are you sure you want to delete this file reclamation report?') }}
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
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.laporanData = @json($file);

        // Pass policy permissions to JavaScript
        window.userPermissions = {
            canCreate: {{ auth()->user()->can('create', [\App\Models\ReklamasiFile::class, $lahan]) ? 'true' : 'false' }},
            canDelete: {{ auth()->user()->can('delete', [\App\Models\ReklamasiFile::class, $lahan]) ? 'true' : 'false' }}
        };

        // Function to set file name display
        function setFileNameDisplay(name, isSelected = false) {
            const fileNameDisplay = document.getElementById('file-name-display');
            if (!fileNameDisplay) return;
            
            fileNameDisplay.innerHTML = isSelected 
                ? `<span class="text-darkslategray-300">${name}</span>`
                : `<span class="text-slategray">${name || 'No file chosen'}</span>`;
        }

        // Function to hide/show save button
        function toggleSaveButton(show) {
            const saveButton = document.getElementById('save-button');
            if (!saveButton) return;
            
            if (show && window.userPermissions.canCreate) {
                saveButton.classList.remove('hidden');
                saveButton.style.display = '';
            } else {
                saveButton.classList.add('hidden');
                saveButton.style.display = 'none';
            }
        }

        // Function to reset file input
        function resetFileInput() {
            const fileInput = document.getElementById('file-upload');
            const fileNameDisplay = document.getElementById('file-name-display');
            const fileInfoText = document.getElementById('file-info-text');
            
            if (fileInput) {
                fileInput.value = '';
            }
            
            const originalFile = fileNameDisplay?.dataset.originalFile || '';
            
            if (originalFile) {
                setFileNameDisplay(originalFile, true);
                if (fileInfoText) fileInfoText.style.display = '';
            } else {
                setFileNameDisplay('No file chosen', false);
                if (fileInfoText) fileInfoText.style.display = 'none';
            }
            
            toggleSaveButton(false);
        }

        // Function to format file size
        function formatFileSize(bytes) {
            const units = ['B', 'KB', 'MB', 'GB'];
            let i = 0;
            while (bytes > 1024 && i < units.length - 1) {
                bytes /= 1024;
                i++;
            }
            return bytes.toFixed(2) + ' ' + units[i];
        }

        // Handle year change
        function handleYearChange() {
            const yearSelect = document.getElementById('tahun-select');
            const selectedYear = yearSelect?.value;
            const laporanContent = document.getElementById('laporan-content');
            const uploadSection = document.getElementById('file-upload-section');
            const fileNameDisplay = document.getElementById('file-name-display');
            const fileInfoText = document.getElementById('file-info-text');

            if (!selectedYear) {
                uploadSection?.classList.add('hidden');
                toggleSaveButton(false);
                resetFileInput();
                
                if (laporanContent) {
                    laporanContent.innerHTML = `
                        <div class="bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 p-12 text-center">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-gray-400 mx-auto mb-4">
                                <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" fill="currentColor"/>
                            </svg>
                            <div class="space-y-2">
                                <div class="font-medium text-gray-600">Belum ada file laporan pelaksanaan</div>
                                <div class="text-sm text-gray-500">Pilih tahun untuk melihat atau mengunggah file</div>
                            </div>
                        </div>
                    `;
                }
                
                // Hide all action buttons
                document.querySelectorAll('[id^="laporan-actions-"]').forEach(el => {
                    el.classList.add('hidden');
                });
                
                return;
            }

            const laporan = laporanData[selectedYear];

            // Hide all action buttons first
            document.querySelectorAll('[id^="laporan-actions-"]').forEach(el => {
                el.classList.add('hidden');
            });

            // Show upload section
            uploadSection?.classList.remove('hidden');

            if (!laporan) {
                // There is no file for this year
                if (fileNameDisplay) {
                    fileNameDisplay.dataset.originalFile = '';
                }
                resetFileInput();
                if (fileInfoText) fileInfoText.style.display = 'none';
                
                if (laporanContent) {
                    laporanContent.innerHTML = `
                        <div class="bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 p-12 text-center">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-gray-400 mx-auto mb-4">
                                <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" fill="currentColor"/>
                            </svg>
                            <div class="space-y-2">
                                <div class="font-medium text-gray-600">Belum ada file laporan untuk tahun ${selectedYear}</div>
                                <div class="text-sm text-gray-500">Silakan unggah file laporan untuk tahun ini.</div>
                            </div>
                        </div>
                    `;
                }
            } else {
                // There is a file for this year
                if (fileNameDisplay) {
                    fileNameDisplay.dataset.originalFile = laporan.file_name;
                }
                resetFileInput();
                if (fileInfoText) fileInfoText.style.display = '';
                
                if (laporanContent) {
                    laporanContent.innerHTML = `
                        <div class="bg-white rounded-lg border border-gainsboro p-6">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-red-500">
                                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" fill="currentColor"/>
                                        <text x="12" y="16" font-family="Arial" font-size="3" text-anchor="middle" fill="white">PDF</text>
                                    </svg>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-darkslategray-300 truncate">${laporan.file_name}</div>
                                    <div class="text-sm text-gray-500 mt-1 space-y-1">
                                        <div>Ukuran: ${formatFileSize(laporan.file_size)}</div>
                                        <div>Diupload: ${new Date(laporan.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</div>
                                        <div>Tipe: ${laporan.mime_type.toUpperCase()}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }
                
                const actions = document.getElementById(`laporan-actions-${laporan.reklamasi_file_id}`);
                if (actions) actions.classList.remove('hidden');
            }
        }

        // Handle file input change
        function updateFileName(input) {
            // Prevent interaction if user has no permission
            if (!window.userPermissions.canCreate) return;
            
            const selectedYear = document.getElementById('tahun-select')?.value;
            const buttonText = document.getElementById('button-text');

            if (input.files && input.files[0] && selectedYear) {
                setFileNameDisplay(input.files[0].name, true);
                
                const fileNameDisplay = document.getElementById('file-name-display');
                const hasExistingFile = fileNameDisplay?.dataset.originalFile !== '';
                
                if (buttonText) {
                    buttonText.textContent = hasExistingFile ? 'Update' : 'Save';
                }
                
                toggleSaveButton(true);
            } else {
                resetFileInput();
            }
        }

        // Setup file input listeners
        function setupFileInput() {
            const fileInput = document.getElementById('file-upload');
            const yearSelect = document.getElementById('tahun-select');
            
            if (fileInput) {
                fileInput.removeEventListener('change', handleFileInputChange);
                fileInput.addEventListener('change', handleFileInputChange);
            }
            
            if (yearSelect) {
                yearSelect.removeEventListener('change', handleYearChange);
                yearSelect.addEventListener('change', handleYearChange);
                yearSelect.value = '';
            }
            
            handleYearChange();
        }

        function handleFileInputChange(event) {
            updateFileName(event.target);
        }

        // Initialize on page load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupFileInput);
        } else {
            setupFileInput();
        }

        // Handle Turbo events
        document.addEventListener('turbo:load', setupFileInput);
        document.addEventListener('turbo:before-cache', function() {
            resetFileInput();
            const yearSelect = document.getElementById('tahun-select');
            if (yearSelect) yearSelect.value = '';
        });

        // Handle page show 
        window.addEventListener('pageshow', function(event) {
            setupFileInput();
        });
    </script>
</x-main-layout>