<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-row items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-darkslategray font-outfit">Master Jenis Pohon</h2>
                <p class="text-sm text-slategray">Kelola referensi jenis pohon</p>
            </div>
            <button x-data="" @click="$dispatch('open-modal', 'add-pohon-modal')" 
               class="text-sm rounded-lg bg-darkslategray overflow-hidden flex flex-row items-center justify-center py-3 px-4 gap-2 !text-white no-underline hover:bg-slategray-200 font-medium transition-colors cursor-pointer">
                <span class="relative leading-5">Tambah Pohon</span>
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
                this.editUrl = `{{ route('admin.jenis-pohon.index') }}/${detail.jenis_pohon_id}`;
                this.editName = detail.nama_pohon;
                $dispatch('open-modal', 'edit-pohon-modal');
            }
         }"
         @edit-pohon.window="handleEditEvent($event.detail)">

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
            <form method="GET" action="{{ route('admin.jenis-pohon.index') }}" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <x-main.input-label for="search" class="block text-sm font-medium text-darkslategray mb-2">Search</x-main.input-label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari nama pohon..."
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
                <div class="font-bold text-base text-darkslategray font-outfit">Daftar Jenis Pohon</div>
            </div>

            <div class="grid flex-1 self-stretch auto-cols-fr gap-y-8 overflow-hidden">
                <div class="flex flex-col overflow-x-auto">
                    <table class="min-w-max w-full text-xs text-darkslategray font-outfit border-collapse table-auto">
                        <thead class="border-b border-gainsboro">
                            <tr>
                                <th class="py-3 px-6 text-center font-bold border-r border-gainsboro w-16">No</th>
                                <th class="py-3 px-6 text-left font-bold border-r border-gainsboro">Nama Jenis Pohon</th>
                                <th class="py-3 px-6 text-center font-bold border-r border-gainsboro">Digunakan Di</th>
                                <th class="py-3 px-6 text-center font-bold border-r border-gainsboro">Total Pohon</th> 
                                <th class="py-3 px-6 text-center font-bold border-gainsboro">Dibuat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gainsboro">
                            @forelse($data as $index => $item)
                                <tr class="border-gainsboro hover:bg-lightgray transition-colors cursor-pointer"
                                    @click="$dispatch('open-detail-pohon', {{ json_encode($item) }})">
                                    
                                    <td class="py-3 px-6 text-center border-r border-gainsboro">
                                        {{ $data->firstItem() + $index }}
                                    </td>
                                    
                                    <td class="py-3 px-6 border-r border-gainsboro font-semibold text-darkslategray">
                                        {{ $item->nama_pohon }}
                                    </td>
                                    
                                    <td class="py-3 px-6 text-center border-r border-gainsboro">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold border bg-blue-100 text-blue-800 border-blue-200">
                                            {{ $item->pohon_count }} Lahan
                                        </span>
                                    </td>

                                    <td class="py-3 px-6 text-center border-r border-gainsboro">
                                        <span class="font-bold text-darkslategray">
                                            {{ number_format($item->data_pohon_sum_jumlah ?? 0, 0, ',', '.') }}
                                        </span>
                                        <span class="text-xs text-slategray ml-1">Btg</span>
                                    </td>
                                    
                                    <td class="py-3 px-6 text-center border-gainsboro text-gray-500">
                                        {{ $item->created_at->format('d F Y') }}

                                        <x-main.modal name="confirm-delete-{{ $item->jenis_pohon_id }}" focusable>
                                            <form method="POST" action="{{ route('admin.jenis-pohon.destroy', $item->jenis_pohon_id) }}" class="p-6 text-left" onclick="event.stopPropagation()">
                                                @csrf 
                                                @method('DELETE')

                                                <h2 class="text-lg font-medium text-gray-900">
                                                    {{ __('Are you sure you want to delete this Jenis Pohon ' . $item->nama_pohon . '?') }}
                                                </h2>
                                                
                                                <p class="mt-1 text-sm text-gray-600">
                                                    {{ __('Once deleted, all data related to this jenis pohon will be permanently lost.This action cannot be undone. Data cannot be deleted if it is currently used in any lahan.') }}
                                                </p>

                                                <div class="mt-6 flex justify-end gap-3 font-outfit">
                                                    <x-main.secondary-button @click="$dispatch('close')">
                                                        {{ __('Cancel') }}
                                                    </x-main.secondary-button>
                                                    <x-main.danger-button type="submit">
                                                        {{ __('Delete') }}
                                                    </x-main.danger-button>
                                                </div>
                                            </form>
                                        </x-main.modal>
                                    </td>                                
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center">
                                        <div class="flex flex-col items-center justify-center gap-3 text-slategray">
                                            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M14.2499 19V17.75H10.7499V21C10.7499 21.1989 10.6709 21.3897 10.5302 21.5303C10.3896 21.671 10.1988 21.75 9.99991 21.75C9.801 21.75 9.61023 21.671 9.46958 21.5303C9.32893 21.3897 9.24991 21.1989 9.24991 21V17.75H2.99991C2.86308 17.7499 2.72889 17.7124 2.61187 17.6415C2.49484 17.5706 2.39944 17.469 2.33599 17.3478C2.27255 17.2265 2.24347 17.0903 2.25192 16.9537C2.26036 16.8171 2.30601 16.6855 2.38391 16.573L6.06891 11.25H4.49991C4.36147 11.2498 4.22578 11.2112 4.10789 11.1387C3.99 11.0661 3.8945 10.9623 3.83197 10.8388C3.76943 10.7153 3.7423 10.5768 3.75359 10.4389C3.76488 10.3009 3.81413 10.1687 3.89591 10.057L9.39591 2.557L9.45191 2.488C9.52723 2.40741 9.6194 2.34444 9.72186 2.30355C9.82431 2.26267 9.93452 2.24489 10.0446 2.25148C10.1547 2.25807 10.262 2.28887 10.3589 2.34168C10.4557 2.39448 10.5397 2.46801 10.6049 2.557L13.0489 5.89L14.4689 4.47L14.5299 4.415C14.6089 4.35154 14.7 4.30475 14.7976 4.27746C14.8952 4.25017 14.9973 4.24295 15.0978 4.25622C15.1982 4.26949 15.295 4.30298 15.3821 4.35468C15.4693 4.40638 15.5451 4.4752 15.6049 4.557L21.1049 12.057L21.1609 12.145C21.2223 12.2592 21.253 12.3874 21.2501 12.517C21.2471 12.6466 21.2106 12.7733 21.1441 12.8846C21.0777 12.9959 20.9834 13.0881 20.8707 13.1521C20.758 13.2162 20.6306 13.2499 20.5009 13.25H19.0329L21.5919 16.54C21.678 16.6508 21.7312 16.7836 21.7456 16.9232C21.76 17.0627 21.7349 17.2036 21.6732 17.3296C21.6116 17.4557 21.5158 17.5619 21.3967 17.6362C21.2777 17.7105 21.1402 17.7499 20.9999 17.75H15.7499V19C15.7499 19.1989 15.6709 19.3897 15.5302 19.5303C15.3896 19.671 15.1988 19.75 14.9999 19.75C14.801 19.75 14.6102 19.671 14.4696 19.5303C14.3289 19.3897 14.2499 19.1989 14.2499 19Z" fill="white"/>
                                                </svg>
                                            </div>
                                            <p class="font-medium text-sm">Tidak ada jenis pohon ditemukan</p>
                                            <p class="text-xs text-gray-500">Coba ubah filter atau tambah jenis pohon baru</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($data->count() > 0)
                <div class="px-6 py-4 border-t border-gainsboro">
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div>
                            <div class="text-2xl font-bold text-darkslategray">{{ $data->total() }}</div>
                            <div class="text-xs text-slategray font-medium">Total Jenis Pohon</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-blue-600">{{ $data->sum('pohon_count') }}</div>
                            <div class="text-xs text-slategray font-medium">Total Penggunaan</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-green-600">{{ number_format($data->sum('data_pohon_sum_jumlah'), 0, ',', '.') }}</div>
                            <div class="text-xs text-slategray font-medium">Total Batang Pohon</div>
                        </div>
                    </div>
                </div>
            @endif

            @if($data->hasPages())
                <div class="px-6 py-4 border-t border-gainsboro">{{ $data->links() }}</div>
            @endif
        </div>
        
        <x-admin.jenis-pohon.detail-modal />

        <x-main.modal name="add-pohon-modal" focusable>
            <form method="POST" action="{{ route('admin.jenis-pohon.store') }}" class="p-6">
                @csrf
                <h2 class="text-lg font-medium text-darkslategray mb-4">Tambah Jenis Pohon</h2>
                <div class="mb-4">
                    <x-main.input-label value="Nama Pohon" />
                    <input type="text" name="nama_pohon" class="mt-1 block w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-darkslategray focus:ring-darkslategray" required placeholder="Contoh: Jati, Mahoni...">
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

        <x-main.modal name="edit-pohon-modal" focusable>
            <form method="POST" :action="editUrl" class="p-6">
                @csrf 
                @method('PUT')
                <h2 class="text-lg font-medium text-darkslategray mb-4">Edit Jenis Pohon</h2>
                <div class="mb-4">
                    <x-main.input-label value="Nama Pohon" />
                    <input type="text" name="nama_pohon" x-model="editName" class="mt-1 block w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-darkslategray focus:ring-darkslategray" required>
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