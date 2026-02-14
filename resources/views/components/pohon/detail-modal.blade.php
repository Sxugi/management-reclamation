<div id="pohonModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gainsboro bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-2xl">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                        <path d="M20 7V5C20 3.89543 19.1046 3 18 3H6C4.89543 3 4 3.89543 4 5V7M20 7H4M20 7V19C20 20.1046 19.1046 21 18 21H6C4.89543 21 6 20.1046 6 19V7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9 11V17M15 11V17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center self-stretch leading-7 font-semibold text-lg text-darkslategray font-outfit">Detail Data Pohon</div>
                    <div class="text-sm text-slategray font-outfit">Informasi lengkap data pohon yang ditanam</div>
                </div>
            </div>
            <button onclick="window.closePohonModal()" class="w-10 h-10 rounded-xl bg-white shadow-sm border border-gainsboro hover:bg-gray-500 flex items-center justify-center text-slategray hover:text-white transition-all duration-200 cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6 font-outfit">
            <div id="pohonModalContent" class="space-y-6">
                <!-- Content will be injected by JavaScript -->
            </div>
        </div>

        <div class="flex items-center justify-end p-6 border-t border-gainsboro bg-gray-50 rounded-b-2xl">
            <button onclick="window.closePohonModal()" class="bg-red-500 !text-white text-sm py-3 px-6 rounded-lg font-medium font-outfit hover:bg-red-600 transition-colors border-none cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

@if(isset($pohon))
    @foreach($pohon as $data)
        @foreach($data->dataManual ?? [] as $item)
            <x-main.modal name="confirm-pohon-deletion-{{ $item->data_pohon_manual_id }}" focusable>
                <form method="POST" action="{{ route('lahan.pohon.destroy', [$lahan, $data, $item]) }}" class="p-6 text-left whitespace-normal">
                    @csrf
                    @method('DELETE')
                    <h2 class="text-lg font-medium text-gray-900 font-outfit">
                        Confirmation Delete Data Pohon
                    </h2>
                    <p class="mt-2 text-sm text-gray-600 font-outfit">
                        Are you sure you want to delete the tree data for <strong>{{ $data->jenisPohon->nama_pohon ?? 'this' }}</strong> for the year <strong>{{ $item->tahun }}</strong>?
                    </p>
                    <div class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-sm text-yellow-800 font-medium font-outfit">
                            ⚠️ Manual data: <strong>{{ number_format($item->jumlah_batang, 0, ',', '.') }} batang</strong>
                        </p>
                    </div>
                    <p class="mt-3 text-sm text-red-600 font-semibold font-outfit">
                        Deleted data cannot be recovered.
                    </p>
                    <div class="mt-6 flex justify-end gap-3 font-outfit">
                        <x-main.secondary-button @click="$dispatch('close')">
                            Cancel
                        </x-main.secondary-button>
                        <x-main.danger-button type="submit">
                            Delete
                        </x-main.danger-button>
                    </div>
                </form>
            </x-main.modal>
        @endforeach
    @endforeach
@endif