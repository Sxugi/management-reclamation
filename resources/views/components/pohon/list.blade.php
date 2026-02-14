@props([
    'lahan',
    'pohon' => null,
    'hasFilter' => false,
])

<div class="grid flex-1 self-stretch auto-cols-fr gap-y-8 rounded-b-2xl border-gainsboro border-solid border overflow-hidden">
    <div class="flex flex-col overflow-x-auto">
        <table class="min-w-max w-full text-xs text-darkslategray font-outfit border-collapse table-auto">
            <thead>
                <tr>
                    <x-main.sortable-header column="jenis_pohon" title="Jenis Pohon" class="h-6 py-3 border-gainsboro border-solid border text-sm" />                 
                    <th scope="col" class="h-6 py-3 px-3 text-center leading-5 font-bold border-gainsboro border-solid border-r border-l text-sm">
                        Kategori
                    </th>
                    <x-main.sortable-header column="tahun" title="Tahun Tanam" class="h-6 py-3 border-gainsboro border-solid border-r border-l text-sm" />                  
                    <x-main.sortable-header column="total" title="Total" class="h-6 py-3 border-gainsboro border-solid border text-sm" />
                </tr>
            </thead>
            <tbody>
                @forelse($pohon ?? [] as $data)
                    @php
                        $allDataPohon = collect();
                        
                        // Add realisasi data
                        foreach ($data->dataRealisasi as $dr) {
                            $allDataPohon->push((object)[
                                'id' => "R-{$dr->data_pohon_realisasi_id}",
                                'tahun' => $dr->tahun,
                                'tipe' => 'realisasi',
                                'stok_manual' => 0,
                                'realisasi_progres' => $dr->jumlah_batang,
                                'total' => $dr->jumlah_batang,
                                'plot_name' => $dr->plot?->nama_plot,
                                'edit_url' => null, // Realisasi can't be edited manually
                                'can_update' => false,
                                'can_delete' => false,
                                'created_at' => $dr->created_at?->toISOString(),
                                'updated_at' => $dr->updated_at?->toISOString(),
                            ]);
                        }
                        
                        // Add manual data
                        foreach ($data->dataManual as $dm) {
                            $allDataPohon->push((object)[
                                'id' => "M-{$dm->data_pohon_manual_id}",
                                'data_pohon_manual_id' => $dm->data_pohon_manual_id, // For delete modal
                                'tahun' => $dm->tahun,
                                'tipe' => 'manual',
                                'stok_manual' => $dm->jumlah_batang,
                                'realisasi_progres' => 0,
                                'total' => $dm->jumlah_batang,
                                'plot_name' => null,
                                'edit_url' => route('lahan.pohon.edit', [$lahan, $data, $dm]),
                                'can_update' => auth()->user()->can('update', $data),
                                'can_delete' => auth()->user()->can('delete', $data),
                                'created_at' => $dm->created_at?->toISOString(),
                                'updated_at' => $dm->updated_at?->toISOString(),
                            ]);
                        }
                        
                        $sortedDataPohon = $allDataPohon->sortBy('tahun');
                        
                        $detailPerTahun = $sortedDataPohon->map(function($item) {
                            return (array)$item;
                        })->values();

                        $monitoringData = \App\Services\MonitoringService::getMonitoringDataForModal(
                            $lahan, 
                            $data->jenis_pohon_id
                        );

                        $modalPayload = [
                            'lahan_id' => $lahan->lahan_id,
                            'pohon_id' => $data->pohon_id,
                            'jenis_pohon_id' => $data->jenis_pohon_id,
                            'jenis_pohon_nama' => $data->jenisPohon->nama_pohon ?? '-',
                            'kategori' => ucwords(str_replace('_', ' ', $data->jenisPohon->kategori)) ?? '-',
                            'grand_total' => $data->SUM ?? 0,
                            'details' => $detailPerTahun,
                            'monitoring_data' => $monitoringData,
                        ];
                        
                        try {
                            $modalPayloadJson = json_encode($modalPayload, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
                            if ($modalPayloadJson === false) {
                                \Log::error('Failed to encode modal payload', ['pohon_id' => $data->pohon_id]);
                                $modalPayloadJson = '{}';
                            }
                        } catch (\Exception $e) {
                            \Log::error('Exception encoding modal payload', ['pohon_id' => $data->pohon_id, 'error' => $e->getMessage()]);
                            $modalPayloadJson = '{}';
                        }
                    @endphp

                    <tr class="cursor-pointer hover:bg-lightgray transition-colors"
                        onclick="window.openPohonModal({{ $modalPayloadJson }})">
                        
                        <td class="py-3 px-3 text-sm text-center text-gray leading-5 border-gainsboro border-solid border border-l-0 whitespace-nowrap">
                            {{ $data->jenisPohon->nama_pohon ?? '-' }}
                        </td>

                        <td class="py-3 px-3 text-sm text-center text-gray leading-5 border-gainsboro border-solid border whitespace-nowrap">
                            @php
                                $kategori = strtoupper($data->jenisPohon->kategori ?? '-');
                                $badgeClass = match($kategori) {
                                    'PIONIR' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    'LOKAL' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'MPTS' => 'bg-green-100 text-green-800 border-green-200',
                                    'COVER_CROP' => 'bg-purple-100 text-purple-800 border-purple-200',
                                    default => 'bg-gray-100 text-gray-800 border-gray-200'
                                };
                            @endphp
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold border {{ $badgeClass }}">
                                {{ ucwords(str_replace('_', ' ', $kategori)) }}
                            </span>
                        </td>

                        <td class="py-3 px-3 text-sm text-center text-gray leading-5 border-gainsboro border-solid border whitespace-nowrap">
                            @php
                                $years = $sortedDataPohon->pluck('tahun')->unique();
                                $count = $years->count();
                            @endphp

                            @if($count === 0)
                                -
                            @elseif($count <= 3)
                                {{ $years->join(', ') }}
                            @else
                                {{ $years->take(2)->join(', ') }}
                                <span class="inline-block text-[10px] bg-gray-100 text-gray-600 px-1 py-0.5 rounded border border-gray-200 ml-1" 
                                      title="{{ $years->join(', ') }}">
                                    +{{ $count - 2 }}
                                </span>
                            @endif
                        </td>

                        <td class="py-3 px-3 text-sm text-center text-gray leading-5 border-gainsboro border-solid border border-r-0 whitespace-nowrap font-semibold">
                            {{ number_format($data->SUM ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    @if($hasFilter)
                        <tr class="border-t border-solid border-gainsboro">
                            <td colspan="4"><x-pohon.empty-state :hasFilter="true" /></td>
                        </tr>
                    @else
                        <tr class="border-t border-solid border-gainsboro">
                            <td colspan="4" class="py-6 px-3 text-center text-darkslategray">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span class="text-sm text-gray-500">Belum ada data inventarisasi pohon</span>
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforelse
            </tbody>
        </table>
        <div class="mt-6">
            {{ $pohon->links('components.main.pagination') }}
        </div>
    </div>
    
    <x-pohon.detail-modal :pohon="$pohon" :lahan="$lahan" />
    <x-pohon.monitoring-trend-modal />
</div>