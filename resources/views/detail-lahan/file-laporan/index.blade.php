<x-main-layout>
    <x-slot name="header">
        <div class="flex flex-row items-center justify-between gap-4">
            <h2 class="text-xl font-bold text-darkslategray font-outfit">
                File Laporan Pelaksanaan Reklamasi
            </h2>
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
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="flex flex-col space-y-6">
                    <div class="rounded-2xl bg-white border-gainsboro border-solid border-[1px] box-border flex flex-col">
                        <div class="border-gainsboro border-solid border-b-[1px] border-t-[0px] border-r-[0px] border-l-[0px] flex flex-row items-center justify-start px-6">
                            <h3 class="text-base font-medium leading-6">File Input</h3>
                        </div>

                        <div class="p-6">
                            <form action="{{ route('lahan.file-laporan.store', $lahan) }}" method="POST" enctype="multipart/form-data" id="file-upload-form">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-2">Pilih Tahun</label>
                                        <select name="tahun" id="tahun-select" class="w-full rounded-lg border px-3 py-2 text-sm" onchange="handleYearChange()" required>
                                            <option value="">-- Pilih tahun laporan --</option>
                                            @for($year = $lahan->tahun_awal; $year <= $lahan->tahun_akhir; $year++)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div id="file-upload-section" class="hidden">
                                        <label class="block text-sm font-medium text-darkslategray-200 mb-2">Upload file</label>
                                        <div class="rounded-lg bg-white border-gainsboro border-solid border-[1px] overflow-hidden flex">
                                            <label for="file-upload" class="flex-shrink-0 bg-whitesmoke-100 border-gainsboro border-solid border-r-[1px] border-t-[0px] border-b-[0px] border-l-[0px] px-4 py-2.5 cursor-pointer hover:bg-gray-100 transition-colors text-sm">
                                                Choose File
                                            </label>
                                            <input type="file" name="file" id="file-upload" accept=".pdf" class="hidden" onchange="updateFileName(this)">
                                            <div class="flex-1 bg-white px-4 py-2.5 text-sm text-slategray whitespace-nowrap" id="file-name-display">
                                                @foreach($file as $year => $fileData)
                                                    @if($fileData->file_name)
                                                        {{ $fileData->file_name }}
                                                    @else
                                                        No file chosen
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                        <x-main.input-error :messages="$errors->get('file')" data-turbo-temporary class="mt-2" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="flex justify-center mt-8">
                        <x-main.primary-button 
                            type="submit" 
                            form="file-upload-form" 
                            class="py-3 px-4 gap-2 font-medium hidden" 
                            id="save-button">
                            Save
                        </x-main.primary-button>
                    </div>
                </div>

                <div class="flex flex-col justify-between space-y-6">
                    <div id="laporan-content" class="space-y-4">
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
                        <div id="laporan-actions-{{ $f->reklamasi_file_id }}" class="hidden flex flex-row gap-3 justify-center">
                            <a href="{{ route('lahan.file-laporan.preview', [$lahan, 'tahun' => $year]) }}" target="_blank"
                                class="w-fit rounded-md bg-darkslategray py-3 px-4 gap-2 !text-white text-sm no-underline hover:bg-slategray-200 font-medium">
                                    View Details
                            </a>

                            <x-main.primary-button 
                                x-data="" 
                                x-on:click.prevent="$dispatch('open-modal', 'confirm-laporan-deletion-{{ $year }}'); open = false"
                                class="bg-red-500 text-white py-3 px-4 rounded-lg font-medium hover:bg-red-600 transition-colors">
                                <span class="relative text-leading-5 font-medium">Delete</span>
                            </x-main.primary-button>

                            <x-main.modal name="confirm-laporan-deletion-{{ $year }}" focusable>
                                <form method="POST" action="{{ route('lahan.file-laporan.destroy', [$lahan, $year]) }}" class="p-6">
                                    @csrf
                                    @method('DELETE')

                                    <h2 class="text-lg font-medium text-gray-900">
                                        {{ __('Apakah Anda yakin ingin menghapus dokumen ini?') }}
                                    </h2>

                                    <p class="mt-1 text-sm text-gray-600">
                                        {{ __('Setelah dokumen ini dihapus, semua data terkait akan hilang secara permanen. Tindakan ini tidak dapat dibatalkan.') }}
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
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        window.laporanData = @json($file);

        function setFileNameDisplay(name, isSelected = false) {
            const fileNameDisplay = document.getElementById('file-name-display');
            fileNameDisplay.textContent = name || 'No file chosen';
            fileNameDisplay.classList.remove('text-darkslategray-300', 'text-slategray');
            fileNameDisplay.classList.add(isSelected ? 'text-darkslategray-300' : 'text-slategray');
        }

        function handleYearChange() {
            const yearSelect = document.getElementById('tahun-select');
            const selectedYear = yearSelect.value;
            const laporanContent = document.getElementById('laporan-content');
            const uploadSection = document.getElementById('file-upload-section');
            const saveButton = document.getElementById('save-button');

            if (!selectedYear) {
                setFileNameDisplay('No file chosen', false);
                uploadSection.classList.add('hidden');
                saveButton.classList.add('hidden');
                laporanContent.innerHTML = `
                    <div class="bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 p-12 text-center">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-gray-400 mx-auto mb-4">
                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" fill="currentColor"/>
                        </svg>
                        <div class="space-y-2">
                            <div class="font-medium text-gray-600">Belum ada file laporan pelaksanaan</div>
                            <div class="text-sm text-gray-500">Pilih tahun laporan untuk melihat detail file.</div>
                        </div>
                    </div>
                `;
                return;
            }

            const laporan = laporanData[selectedYear];

            document.querySelectorAll('[id^="laporan-actions-"]').forEach(el => {
                if (!laporan || el.id !== `laporan-actions-${laporan.reklamasi_file_id}`) {
                    el.classList.add('hidden');
                }
            });

            if (!laporan) {
                setFileNameDisplay('No file chosen', false);
                uploadSection.classList.remove('hidden');
                saveButton.classList.add('hidden');
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
            } else {
                setFileNameDisplay(laporan.file_name, true);
                uploadSection.classList.remove('hidden');
                saveButton.classList.add('hidden');
                laporanContent.innerHTML = `
                    <div class="space-y-4">
                        <div class="text-lg font-bold text-darkslategray-200">File Laporan Pelaksanaan Tahun ${selectedYear}</div>
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-red-500">
                                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" fill="currentColor"/>
                                        <text x="12" y="16" font-family="Arial" font-size="3" text-anchor="middle" fill="white">PDF</text>
                                    </svg>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-darkslategray-300 truncate">${laporan.file_name }</div>
                                    <div class="text-sm text-gray-500 mt-1">
                                        <div>Ukuran: ${(laporan.file_size / 1024).toFixed(2)} KB</div>
                                        <div>Diupload: ${new Date(laporan.created_at).toLocaleString()}</div>
                                        <div>Tipe: ${laporan.mime_type.toUpperCase()}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                const actions = document.getElementById(`laporan-actions-${laporan.reklamasi_file_id}`);
                if (actions) actions.classList.remove('hidden');
            }
        }

        function updateFileName(input) {
            const selectedYear = document.getElementById('tahun-select').value;
            const saveButton = document.getElementById('save-button');

            if (input.files && input.files[0] && selectedYear) {
                setFileNameDisplay(input.files[0].name, true);
                saveButton.classList.remove('hidden');
            } else {
                setFileNameDisplay('No file chosen', false);
                saveButton.classList.add('hidden');
            }
        }

        document.addEventListener('turbo:load', () => {
            const yearSelect = document.getElementById('tahun-select');
            if (yearSelect) {
                yearSelect.value = '';
                handleYearChange();
            }
            const fileUploadSection = document.getElementById('file-upload-section');
        });
    </script>
</x-main-layout>
