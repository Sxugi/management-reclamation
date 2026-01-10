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
                </tr>
            </thead>
            <tbody>
                @forelse($gudang ?? [] as $data)
                    <tr class="hover:bg-lightgray cursor-pointer" 
                        onclick="window.openInfoModal({
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
                            edit_url: '{{ route('lahan.gudang.edit', [$lahan, $data]) }}',
                            can_update: {{ auth()->user()->can('update', $data) ? 'true' : 'false' }},
                            can_delete: {{ auth()->user()->can('delete', $data) ? 'true' : 'false' }},
                        })">
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