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
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="flex flex-col space-y-6">
                    <div class="rounded-2xl bg-white border-gainsboro border-solid border-[1px] box-border flex flex-col">
                        <div class="border-gainsboro border-solid border-b-[1px] border-t-[0px] border-r-[0px] border-l-[0px] flex flex-row items-center justify-start px-6">
                            <h3 class="text-base font-medium leading-6">File Input</h3>
                        </div>
                        
                        <div class="p-6">
                            <form action="{{ route('lahan.file-rencana.store', $lahan) }}" method="POST" enctype="multipart/form-data" id="file-upload-form">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-darkslategray-200 mb-2">Upload file</label>
                                        <div class="rounded-lg bg-white border-gainsboro border-solid border-[1px] overflow-hidden flex">
                                            <label for="file-upload" class="flex-shrink-0 bg-whitesmoke-100 border-gainsboro border-solid border-r-[1px] border-t-[0px] border-b-[0px] border-l-[0px] px-4 py-2.5 cursor-pointer hover:bg-gray-100 transition-colors text-sm">
                                                Choose File
                                            </label>
                                            <input type="file" id="file-upload" name="file" accept=".pdf" class="hidden" onchange="updateFileName(this)">
                                            <div class="flex-1 bg-white px-4 py-2.5 text-sm text-slategray whitespace-nowrap" id="file-name-display">
                                                @if($file)
                                                    {{ $file->file_name }}
                                                @else
                                                    No file chosen
                                                @endif
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
                    @if($file)
                        <div class="space-y-4">
                            <div class="text-lg font-bold text-darkslategray-200">File Rencana</div>
                            <div class="bg-white rounded-lg border border-gray-200 p-6">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-red-500">
                                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" fill="currentColor"/>
                                            <text x="12" y="16" font-family="Arial" font-size="3" text-anchor="middle" fill="white">PDF</text>
                                        </svg>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="font-medium text-darkslategray-300 truncate">{{ $file->file_name }}</div>
                                        <div class="text-sm text-gray-500 mt-1">
                                            <div>Ukuran: {{ $file->file_size_human }}</div>
                                            <div>Diupload: {{ $file->created_at->format('d M Y H:i') }}</div>
                                            <div>Tipe: {{ strtoupper($file->mime_type) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-row gap-3 justify-center">
                            <a href="{{ route('lahan.file-rencana.preview', $lahan) }}" target="_blank"
                               class="w-fit rounded-md bg-darkslategray py-3 px-4 gap-2 !text-white text-sm no-underline hover:bg-slategray-200 font-medium">
                                View Details
                            </a>

                            <x-main.primary-button x-data="" 
                                    x-on:click.prevent="$dispatch('open-modal', 'confirm-file-rencana-deletion-{{ $file->reklamasi_file_id }}'); open = false"
                                    class="bg-red-500 text-white py-3 px-4 rounded-lg font-medium hover:bg-red-600 transition-colors">
                                <span class="relative text-leading-5 font-medium">Delete</span>
                            </x-main.primary-button>
                            
                            <x-main.modal name="confirm-file-rencana-deletion-{{ $file->reklamasi_file_id }}" focusable>
                                <form method="POST" action="{{ route('lahan.file-rencana.destroy', [$lahan, $file]) }}" class="p-6">
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
                    @else
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 p-12 text-center">
                                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-gray-400 mx-auto mb-4">
                                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" fill="currentColor"/>
                                </svg>
                                <div class="space-y-2">
                                    <div class="font-medium text-gray-600">Belum ada file rencana</div>
                                    <div class="text-sm text-gray-500">Upload file PDF rencana reklamasi untuk melihat preview</div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateFileName(input) {
            const display = document.getElementById('file-name-display');
            const saveButton = document.getElementById('save-button');
            if (input.files && input.files[0]) {
                display.textContent = input.files[0].name;
                display.classList.remove('text-slategray');
                display.classList.add('text-darkslategray-300');
                saveButton.classList.remove('hidden');
            } else {
                display.textContent = 'No file chosen';
                display.classList.remove('text-darkslategray-300');
                display.classList.add('text-slategray');
                saveButton.classList.add('hidden');
            }
        }
    </script>
</x-main-layout>