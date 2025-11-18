@props([
    'lahan',
    'gudang' => null,
    'hasFilter' => false,
])

<div class="grid flex-1 self-stretch auto-cols-fr gap-y-8 rounded-b-2xl border-gainsboro border-solid border-[1px] overflow-hidden">
    <div class="flex flex-col overflow-x-auto">
        <table class="min-w-max w-full text-xs text-darkslategray font-outfit border-collapse table-auto">
            <thead class="border-gainsboro border-solid border-b-[1px] border-[0px]">
                <tr>
                    <x-main.sortable-header column="tanggal_masuk" title="Tanggal Masuk" class="h-6 py-3 border-r-[1px]"/>
                    <x-main.sortable-header column="jenis_barang" title="Jenis" class="h-6 py-3 border-r-[1px]"/>
                    <x-main.sortable-header column="nama_barang" title="Nama" class="h-6 py-3 border-r-[1px]"/>
                    <x-main.sortable-header column="jumlah_barang" title="Jumlah" class="h-6 py-3 border-r-[1px]"/>
                    <x-main.sortable-header column="lokasi_penyimpanan" title="Lokasi" class="h-6 py-3 border-r-[1px]"/>
                    <x-main.sortable-header column="status_barang" title="Status" class="h-6 py-3 border-r-[1px]"/>
                    <th scope="col" class="h-6 py-3 px-3 text-center leading-5 font-bold border-gainsboro border-solid border-b-[0px] border-t-[0px] border-r-[1px] border-l-[0px] whitespace-nowrap">
                        Catatan
                    </th>
                    <th scope="col" class="h-6 py-3 px-3 text-center leading-5 font-bold border-gainsboro border-solid border-b-[0px] border-t-[0px] border-r-[0px] border-l-[1px] whitespace-nowrap">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($gudang ?? [] as $data)
                    <tr>
                        <td class="py-3 px-3 text-sm text-left text-gray leading-5 border-gainsboro border-solid border-b-[1px] border-t-[0px] border-r-[1px] border-l-[0px] whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($data->tanggal_masuk)->format('d F Y') }}
                        </td>
                        <td class="py-3 px-3 text-sm text-left text-gray leading-5 border-gainsboro border-solid border-b-[1px] border-t-[0px] border-r-[1px] border-l-[0px] whitespace-nowrap">
                            {{ $data->jenis_barang }}
                        </td>
                        <td class="py-3 px-3 text-sm text-left text-gray leading-5 border-gainsboro border-solid border-b-[1px] border-t-[0px] border-r-[1px] border-l-[0px] whitespace-nowrap">
                            {{ $data->nama_barang }}
                        </td>
                        <td class="py-3 px-3 text-sm text-left text-gray leading-5 border-gainsboro border-solid border-b-[1px] border-t-[0px] border-r-[1px] border-l-[0px] whitespace-nowrap">
                            {{ $data->jumlah_barang }}
                        </td>
                        <td class="py-3 px-3 text-sm text-left text-gray leading-5 border-gainsboro border-solid border-b-[1px] border-t-[0px] border-r-[1px] border-l-[0px] whitespace-nowrap">
                            {{ $data->lokasi_penyimpanan }}
                        </td>
                        <td class="py-3 px-3 text-sm text-left text-gray leading-5 border-gainsboro border-solid border-b-[1px] border-t-[0px] border-r-[1px] border-l-[0px] whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($data->status_barang == 'Tersedia') 
                                    bg-green-100 text-green-800
                                @elseif($data->status_barang == 'Kosong') 
                                    bg-orange-100 text-orange-800
                                @elseif($data->status_barang == 'Rusak') 
                                    bg-red-100 text-red-800
                                @elseif($data->status_barang == 'Digunakan') 
                                    bg-yellow-100 text-yellow-800
                                @else 
                                    bg-gray-100 text-gray-800
                                @endif
                            ">
                                {{ $data->status_barang }}
                            </span>
                        </td>
                        <td class="py-3 px-3 text-sm text-left text-gray leading-5 border-gainsboro border-solid border-b-[1px] border-t-[0px] border-r-[1px] border-l-[0px]">
                             <x-main.tooltip :content="$data->catatan" :max-length="40" position="bottom" />
                        </td>
                        <td class="py-3 px-3 border-gainsboro border-solid border-b-[1px] border-t-[0px] border-r-[0px] border-l-[0px]">
                            <div class="flex flex-row items-center justify-center gap-3">
                                <a onclick="window.openInfoModal({
                                        id: '{{ $data->data_gudang_id }}',
                                        nama_barang: '{{ addslashes($data->nama_barang) }}',
                                        jenis_barang: '{{ addslashes($data->jenis_barang) }}',
                                        jumlah_barang: '{{ $data->jumlah_barang }}',
                                        satuan: '{{ $data->satuan ?? 'unit' }}',
                                        status_barang: '{{ $data->status_barang }}',
                                        lokasi_penyimpanan: '{{ addslashes($data->lokasi_penyimpanan) }}',
                                        tanggal_masuk: '{{ $data->tanggal_masuk }}',
                                        catatan: '{{ addslashes($data->catatan ?? '') }}',
                                        created_at: '{{ $data->created_at }}',
                                        updated_at: '{{ $data->updated_at }}',
                                        lahan_nama: '{{ addslashes($lahan->nama_lahan ?? 'Unknown') }}',
                                        edit_url: '{{ route('lahan.gudang.edit', [$lahan, $data]) }}'
                                    })"
                                    class="cursor-pointer">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="20" height="20" fill="white"/>
                                        <path d="M0.200195 9.99997C1.12139 8.19299 2.52424 6.67588 4.25372 5.61631C5.98319 4.55674 7.97195 3.99597 10.0002 3.99597C12.0284 3.99597 14.0172 4.55674 15.7467 5.61631C17.4762 6.67588 18.879 8.19299 19.8002 9.99997C18.879 11.807 17.4762 13.3241 15.7467 14.3836C14.0172 15.4432 12.0284 16.004 10.0002 16.004C7.97195 16.004 5.98319 15.4432 4.25372 14.3836C2.52424 13.3241 1.12139 11.807 0.200195 9.99997ZM10.0002 14C11.0611 14 12.0785 13.5785 12.8286 12.8284C13.5788 12.0783 14.0002 11.0608 14.0002 9.99997C14.0002 8.93911 13.5788 7.92169 12.8286 7.17155C12.0785 6.4214 11.0611 5.99997 10.0002 5.99997C8.93933 5.99997 7.92191 6.4214 7.17177 7.17155C6.42162 7.92169 6.0002 8.93911 6.0002 9.99997C6.0002 11.0608 6.42162 12.0783 7.17177 12.8284C7.92191 13.5785 8.93933 14 10.0002 14ZM10.0002 12C9.46976 12 8.96106 11.7893 8.58598 11.4142C8.21091 11.0391 8.0002 10.5304 8.0002 9.99997C8.0002 9.46954 8.21091 8.96083 8.58598 8.58576C8.96106 8.21069 9.46976 7.99997 10.0002 7.99997C10.5306 7.99997 11.0393 8.21069 11.4144 8.58576C11.7895 8.96083 12.0002 9.46954 12.0002 9.99997C12.0002 10.5304 11.7895 11.0391 11.4144 11.4142C11.0393 11.7893 10.5306 12 10.0002 12Z" fill="#1D2939"/>
                                    </svg>
                                </a>

                                <a href="{{ route('lahan.gudang.edit', [$lahan, $data]) }}">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5.83301 5.83333H4.99967C4.55765 5.83333 4.13372 6.00892 3.82116 6.32148C3.5086 6.63404 3.33301 7.05797 3.33301 7.49999V15C3.33301 15.442 3.5086 15.8659 3.82116 16.1785C4.13372 16.4911 4.55765 16.6667 4.99967 16.6667H12.4997C12.9417 16.6667 13.3656 16.4911 13.6782 16.1785C13.9907 15.8659 14.1663 15.442 14.1663 15V14.1667" stroke="#1D2939" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M13.3333 4.16666L15.8333 6.66666M16.9875 5.4875C17.3157 5.15929 17.5001 4.71415 17.5001 4.25C17.5001 3.78585 17.3157 3.3407 16.9875 3.0125C16.6593 2.68429 16.2142 2.49991 15.75 2.49991C15.2858 2.49991 14.8407 2.68429 14.5125 3.0125L7.5 10V12.5H10L16.9875 5.4875Z" stroke="#1D2939" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>

                                <x-main.modal name="confirm-gudang-deletion-{{ $data->data_gudang_id }}" focusable>
                                    <form method="POST" action="{{ route('lahan.gudang.destroy', [$lahan, $data]) }}" class="p-6">
                                        @csrf
                                        @method('DELETE')
                                        <h2 class="text-lg font-medium text-gray-900">
                                            {{ __('Are you sure you want to delete this data gudang?') }}
                                        </h2>
                                        <p class="mt-1 text-sm text-gray-600">
                                            {{ __('Once deleted, all data related to this data gudang will be permanently lost. This action cannot be undone.') }}
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
                                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-gudang-deletion-{{ $data->data_gudang_id }}')" class="border-none bg-transparent cursor-pointer p-0">
                                    <svg width="20" height="20" viewBox="0 0 25 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7.54464 1.50254L7.14286 2.3125H1.78571C0.797991 2.3125 0 3.12246 0 4.125C0 5.12754 0.797991 5.9375 1.78571 5.9375H23.2143C24.202 5.9375 25 5.12754 25 4.125C25 3.12246 24.202 2.3125 23.2143 2.3125H17.8571L17.4554 1.50254C17.154 0.885156 16.5346 0.5 15.8594 0.5H9.14062C8.4654 0.5 7.84598 0.885156 7.54464 1.50254ZM23.2143 7.75H1.78571L2.96875 26.9512C3.05804 28.3842 4.22991 29.5 5.64174 29.5H19.3583C20.7701 29.5 21.942 28.3842 22.0312 26.9512L23.2143 7.75Z" fill="#F24822"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    @if($hasFilter)
                        <tr class="border-none">
                            <td colspan="8"><x-gudang.empty-state :hasFilter="true" /></td>
                        </tr>
                    @else
                        <tr class="border-none">
                            <td colspan="8" class="py-6 px-3 text-center text-darkslategray">No data gudang available yet</td>
                        </tr>
                    @endif
                @endforelse
            </tbody>
        </table>
        <div class="mt-6">
            {{ $gudang->links('components.main.pagination') }}
        </div>
    </div>
    <x-gudang.detail-modal />
</div>