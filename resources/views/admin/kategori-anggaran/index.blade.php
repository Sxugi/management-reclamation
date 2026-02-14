<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-row items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-darkslategray font-outfit">Master Kategori Anggaran</h2>
                <p class="text-sm text-slategray">Kelola referensi kategori anggaran</p>
            </div>
            <button x-data="" @click="$dispatch('open-modal', 'add-kategori-modal')" 
               class="text-sm rounded-lg bg-darkslategray overflow-hidden flex flex-row items-center justify-center py-3 px-4 gap-2 !text-white no-underline hover:bg-slategray-200 font-medium transition-colors cursor-pointer">
                <span class="relative leading-5">Tambah Kategori</span>
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 4.24951C10.4142 4.24951 10.75 4.58534 10.75 4.99951V9.24951H15.001L15.0771 9.25342C15.4553 9.29177 15.7508 9.61128 15.751 9.99951C15.751 10.3879 15.4554 10.7072 15.0771 10.7456L15.001 10.7495H10.75V15.0005L10.7461 15.0767C10.7077 15.4549 10.3884 15.7505 10 15.7505C9.61173 15.7504 9.29227 15.4548 9.25391 15.0767L9.25 15.0005V10.7495H5C4.58579 10.7495 4.25 10.4137 4.25 9.99951C4.25015 9.58543 4.58588 9.24951 5 9.24951H9.25V4.99951C9.25004 4.5854 9.58591 4.24962 10 4.24951Z" fill="white"/>
                </svg>
            </button>
        </div>
    </x-slot>

    <div class="space-y-6 font-outfit" 
         x-data="{ 
            editUrl: '', 
            editName: '',
            handleEditEvent(detail) {
                this.editUrl = `{{ route('admin.kategori-anggaran.index') }}/${detail.kategori_anggaran_id}`;
                this.editName = detail.nama_kategori;
                $dispatch('open-modal', 'edit-kategori-modal');
            }
         }"
         @edit-kategori.window="handleEditEvent($event.detail)">

        @if(session('success'))
            <div data-turbo-temporary class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div data-turbo-temporary class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                @foreach($errors->all() as $error) {{ $error }} @endforeach
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-md border border-gainsboro p-4">
            <form method="GET" action="{{ route('admin.kategori-anggaran.index') }}" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <x-main.input-label for="search" class="block text-sm font-medium text-darkslategray mb-2">Search</x-main.input-label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari kategori..."
                        class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md px-3 py-2 box-border font-outfit flex-1 leading-5 bg-transparent">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" 
                            class="flex-1 text-sm rounded-lg bg-darkslategray text-white py-2 px-4 font-medium hover:bg-slategray-200 transition-colors">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="inline mr-1">
                            <path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0016 9.5 6.5 6.5 0 109.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" fill="currentColor"/>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('admin.jenis-pohon.index') }}" class="inline-flex items-center justify-center p-2 rounded-lg border border-gray-300 text-darkslategray hover:bg-gainsboro transition-colors h-[38px] w-[38px]">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.65 6.35A7.958 7.958 0 0012 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08A5.99 5.99 0 0112 18c-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z" fill="currentColor"/>
                        </svg>
                    </a>
                </div>
            </form>
        </div>

        <div class="self-stretch rounded-2xl bg-white shadow-md border border-gainsboro overflow-hidden">
            <div class="px-6 py-4 border-b border-gainsboro">
                <div class="font-bold text-base text-darkslategray font-outfit">Daftar Kategori</div>
            </div>

            <div class="grid flex-1 self-stretch auto-cols-fr gap-y-8 overflow-hidden">
                <div class="flex flex-col overflow-x-auto">
                    <table class="min-w-max w-full text-xs text-darkslategray font-outfit border-collapse table-auto">
                        <thead class="border-b border-gainsboro">
                            <tr>
                                <th class="py-3 px-6 text-center font-bold border-r border-gainsboro w-16">No</th>
                                <th class="py-3 px-6 text-left font-bold border-r border-gainsboro">Nama Kategori</th>
                                <th class="py-3 px-6 text-center font-bold border-r border-gainsboro">Total Transaksi</th>
                                <th class="py-3 px-6 text-center font-bold border-r border-gainsboro">Total Nominal</th>
                                <th class="py-3 px-6 text-center font-bold border-r border-gainsboro">Dibuat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gainsboro">
                            @forelse($data as $index => $item)
                                <tr class="border-b border-gainsboro hover:bg-lightgray transition-colors cursor-pointer"
                                    @click="$dispatch('open-detail-kategori', {{ json_encode($item) }})">
                                    
                                    <td class="py-3 px-6 text-center border-r border-gainsboro">{{ $data->firstItem() + $index }}</td>
                                    
                                    <td class="py-3 px-6 border-r border-gainsboro font-semibold text-darkslategray">
                                        {{ $item->nama_kategori }}
                                    </td>
                                    
                                    <td class="py-3 px-6 text-center border-r border-gainsboro">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold border bg-blue-100 text-blue-800 border-blue-200">
                                            {{ $item->anggaran_count }} Data
                                        </span>
                                    </td>

                                    <td class="py-3 px-6 text-right border-r border-gainsboro">
                                        <span class="font-bold text-darkslategray">
                                            Rp {{ number_format($item->anggaran_sum_nominal ?? 0, 0, ',', '.') }}
                                        </span>
                                    </td>

                                    <td class="py-3 px-6 text-center border-r border-gainsboro text-gray-500">
                                        {{ $item->created_at->format('d F Y') }}
                                    </td>

                                    <x-main.modal name="confirm-delete-{{ $item->kategori_anggaran_id }}" focusable>
                                        <form method="POST" action="{{ route('admin.kategori-anggaran.destroy', $item) }}" class="p-6 text-left" onclick="event.stopPropagation()">
                                            @csrf @method('DELETE')
                                            <h2 class="text-lg font-medium text-gray-900">Hapus Kategori?</h2>
                                            <p class="mt-1 text-sm text-gray-600">
                                                Yakin hapus <strong>{{ $item->nama_kategori }}</strong>?
                                                <br>Data tidak bisa dihapus jika sedang digunakan di anggaran.
                                            </p>
                                            <div class="mt-6 flex justify-end gap-3">
                                                <x-main.secondary-button @click="$dispatch('close')">Batal</x-main.secondary-button>
                                                <x-main.danger-button type="submit">Hapus</x-main.danger-button>
                                            </div>
                                        </form>
                                    </x-main.modal>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center">
                                        <div class="flex flex-col items-center justify-center gap-3 text-slategray">
                                            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="white" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                </svg>
                                            </div>
                                            <p class="font-medium text-sm">Tidak ada kategori ditemukan</p>
                                            <p class="text-xs text-gray-500">Coba ubah filter atau tambah kategori baru</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($data->count() > 0)
                <div class="px-6 py-4 border-gainsboro">
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div>
                            <div class="text-2xl font-bold text-darkslategray">{{ $data->total() }}</div>
                            <div class="text-xs text-slategray font-medium">Total Kategori</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-blue-600">{{ $data->sum('anggaran_count') }}</div>
                            <div class="text-xs text-slategray font-medium">Total Transaksi</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-green-600">
                                Rp {{ number_format($data->sum('anggaran_sum_nominal'), 0, ',', '.') }}
                            </div>
                            <div class="text-xs text-slategray font-medium">Total Nominal</div>
                        </div>
                    </div>
                </div>
            @endif

            @if($data->hasPages())
                <div class="px-6 py-4 border-t border-gainsboro">{{ $data->links() }}</div>
            @endif
        </div>

        <x-admin.kategori-anggaran.detail-modal />

        <x-main.modal name="add-kategori-modal" focusable>
            <form method="POST" action="{{ route('admin.kategori-anggaran.store') }}" class="p-6">
                @csrf
                <h2 class="text-lg font-medium text-darkslategray mb-4">Tambah Kategori</h2>
                <div class="mb-4">
                    <x-main.input-label value="Nama Kategori" />
                    <input type="text" name="nama_kategori" placeholder="Masukkan nama kategori" class="mt-1 block w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-darkslategray focus:ring-darkslategray" required>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <a @click="$dispatch('close')" class="bg-red-500 !text-white text-sm py-3 px-4 rounded-lg font-semibold hover:bg-red-600 transition-colors no-underline cursor-pointer">
                        Cancel
                    </a>

                    <x-main.primary-button type="submit" class="py-3 px-4 gap-2">                       
                        Save
                    </x-main.primary-button>
                </div>
            </form>
        </x-main.modal>

        <x-main.modal name="edit-kategori-modal" focusable>
            <form method="POST" :action="editUrl" class="p-6">
                @csrf 
                @method('PUT')
                <h2 class="text-lg font-medium text-darkslategray mb-4">Edit Kategori</h2>
                <div class="mb-4">
                    <x-main.input-label value="Nama Kategori" />
                    <input type="text" name="nama_kategori" placeholder="Masukkan nama kategori" x-model="editName" class="mt-1 block w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-darkslategray focus:ring-darkslategray" required>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <a @click="$dispatch('close')" class="bg-red-500 !text-white text-sm py-3 px-4 rounded-lg font-semibold hover:bg-red-600 transition-colors no-underline cursor-pointer">
                        Cancel
                    </a>

                    <x-main.primary-button type="submit" class="py-3 px-4 gap-2">                       
                        Update
                    </x-main.primary-button>
                </div>
            </form>
        </x-main.modal>
    </div>
</x-admin-layout>