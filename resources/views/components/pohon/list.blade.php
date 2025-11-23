@props([
    'lahan',
    'pohon' => null,
    'hasFilter' => false,
    'tahunList',
])

<div class="grid flex-1 self-stretch auto-cols-fr gap-y-8 rounded-b-2xl border-gainsboro border-solid border overflow-hidden">
    <div class="flex flex-col overflow-x-auto">
        <table class="min-w-max w-full text-xs text-darkslategray font-outfit border-collapse table-auto">
            <thead class="border-gainsboro border-solid border-b">
                <colgroup>
                    <col class="w-32">
                    @foreach($tahunList as $tahun)
                        <col class="w-auto">
                    @endforeach
                    <col class="w-20">
                </colgroup>
                <tr>
                    <x-main.sortable-header :rowspan="2" column="jenis_pohon" title="Jenis Pohon" class="h-6 py-3 border-gainsboro border-solid border text-sm" />
                    @if(empty($tahunList))
                        <th scope="col" class="h-6 py-3 px-3 text-center leading-5 font-bold border-gainsboro border-solid border">Tahun</th>
                    @else
                        <x-main.sortable-header :colspan="count($tahunList)" column="tahun" title="Tahun" class="text-center border-gainsboro border-solid border-l border-r text-sm p-2" />
                    @endif
                    <x-main.sortable-header :rowspan="2" column="total" title="Total" class="h-6 py-3 border-gainsboro border-solid border text-sm" />
                </tr>
                <tr>
                    @foreach($tahunList as $tahun)
                        <th scope="col" class="px-3 text-center leading-5 font-bold border-gainsboro border-solid border text-xs p-2">{{ $tahun }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($pohon ?? [] as $data)
                    <tr>
                        <td class="py-3 px-3 text-sm text-center text-gray leading-5 border-gainsboro border-solid border border-l-0 whitespace-nowrap">
                            {{ $data->jenis_pohon }}
                        </td>
                        @foreach($tahunList as $tahun)
                            @if(isset($data->dataPohonByTahun[$tahun]))
                                <td class="py-3 px-3 text-sm text-center text-gray leading-5 border-gainsboro border-solid border whitespace-nowrap cursor-pointer hover:bg-lightgray" 
                                onclick="window.openPohonModal({
                                    'jenis_pohon': '{{ $data->jenis_pohon }}',
                                    'tahun': '{{ $tahun }}',
                                    'jumlah': '{{ $data->dataPohonByTahun[$tahun]->jumlah ?? 0 }}',
                                    'created_at': '{{ $data->dataPohonByTahun[$tahun]->created_at ?? null }}',
                                    'updated_at': '{{ $data->dataPohonByTahun[$tahun]->updated_at ?? null }}',
                                    'edit_url': '{{ $data->dataPohonByTahun[$tahun] ? route('lahan.pohon.edit', [$lahan, $data, $data->dataPohonByTahun[$tahun]]) : null }}',
                                    'id': '{{ $data->pohon_id }}',
                                })">
                                    {{ $data->dataPohonByTahun[$tahun]->jumlah ?? '-' }}

                                    <x-main.modal name="confirm-pohon-deletion-{{ $data->pohon_id }}-{{ $tahun }}" focusable>
                                        <form method="POST" action="{{ route('lahan.pohon.destroy', [$lahan, $data, $tahun]) }}" class="p-6 text-left whitespace-normal">
                                            @csrf
                                            @method('DELETE')
                                            <h2 class="text-lg font-medium text-gray-900">
                                                {{ __('Are you sure you want to delete this data pohon?') }}
                                            </h2>
                                            <p class="mt-1 text-sm text-gray-600">
                                                {{ __('Once deleted, all data related to this data pohon will be permanently lost. This action cannot be undone.') }}
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
                            @else
                                <td class="py-3 px-3 text-sm text-center text-gray leading-5 border-gainsboro border-solid border whitespace-nowrap">
                                    {{ $data->dataPohonByTahun[$tahun]->jumlah ?? '-' }}
                                </td>
                            @endif
                        @endforeach
                        <td class="py-3 px-3 text-sm text-center text-gray leading-5 border-gainsboro border-solid border border-r-0 whitespace-nowrap">
                            {{ $data->SUM ?? '-' }}
                        </td>
                    </tr>
                @empty
                    @if($hasFilter)
                        <tr class="border-none">
                            <td colspan="3"><x-pohon.empty-state :hasFilter="true" /></td>
                        </tr>
                    @else
                        <tr class="border-none">
                            <td colspan="3" class="py-6 px-3 text-center text-darkslategray">No data pohon available yet</td>
                        </tr>
                    @endif
                @endforelse
            </tbody>
        </table>
        <div class="mt-6">
            {{ $pohon->links('components.main.pagination') }}
        </div>
    </div>
    <x-pohon.detail-modal />
</div>