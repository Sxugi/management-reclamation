@props([
    'lahan',
    'data' => null,
    'hasFilter' => false,
    'rowspanMap' => [],
    'quarterTotals' => [],
])

@php
    $items = method_exists($data,'items') ? collect($data->items()) : collect($data ?? []);
    $flags = $rowspanMap['flags'] ?? [];
    $hideTotal = $flags['hideTotal'] ?? false;
    $isQuarterSort = $flags['isQuarterSort'] ?? false;
    $isNominalSort = $flags['isNominalSort'] ?? false;
    $useBasicRowspan = $flags['useBasicRowspan'] ?? false;
    $useContiguousQuarter = $flags['useContiguousQuarter'] ?? false;

    // quarter totals lookup (existing)
    $quarterTotalLookup = [];
    foreach($quarterTotals as $qt){
        $quarterTotalLookup[$qt->quarter_label] = $qt->total;
    }

    $basicMapYear = $rowspanMap['mapYear'] ?? [];
    $basicMapQuarter = $rowspanMap['mapQuarter'] ?? [];
    $segmentYear = $rowspanMap['segments']['year'] ?? [];
    $segmentQuarter = $rowspanMap['segments']['quarter'] ?? [];

    // Track shown (for basic mode)
    $shownYear = [];
    $shownQuarter = [];
    $shownQuarterTotal = [];
@endphp

<div class="grid flex-1 self-stretch auto-cols-fr gap-y-8 rounded-b-2xl border-gainsboro border-solid border-[1px] overflow-hidden">
    <div class="flex flex-col overflow-x-auto">
        <table class="min-w-max w-full text-xs text-darkslategray font-outfit border-collapse table-auto">
            <thead class="border-gainsboro border-solid border-b-[1px] border-[0px]">
                <tr>
                    <x-main.sortable-header column="tahun" title="Tahun" class="h-6 py-3 border-r-[1px]" />
                    <x-main.sortable-header column="bulan" title="Bulan" class="h-6 py-3 border-r-[1px]" />
                    <x-main.sortable-header column="nominal" title="Nominal" class="h-6 py-3 border-r-[1px]" />
                    <x-main.sortable-header column="quarter" title="Quarter" class="h-6 py-3 border-r-[1px]" />
                    
                    @if(!$hideTotal)
                        <th class="h-6 py-3 px-3 leading-5 font-bold border-gainsboro border-solid border-[0px] border-l-[1px] whitespace-nowrap">
                            Total / Quarter
                        </th>
                    @endif
                </tr>
            </thead>
            <tbody>
            @forelse($items->values() as $idx => $row)
                @php
                    $quarterLabel = $row->quarter_label ?? $row->quarter;

                    if ($isQuarterSort) {
                        // Contiguous logic
                        $showYear = array_key_exists($idx, $segmentYear);
                        $yearRowspan = $showYear ? $segmentYear[$idx] : 0;

                        $showQuarter = array_key_exists($idx, $segmentQuarter);
                        $quarterRowspan = $showQuarter ? $segmentQuarter[$idx] : 0;

                        $showQuarterTotal = !$hideTotal && $showQuarter;
                        $quarterTotalRowspan = $quarterRowspan;
                    } elseif ($isNominalSort) {
                        // No rowspans
                        $showYear = true;
                        $yearRowspan = 1;
                        $showQuarter = true;
                        $quarterRowspan = 1;
                        $showQuarterTotal = false;
                        $quarterTotalRowspan = 1;
                    } else {
                        // Basic grouped logic
                        $showYear = !isset($shownYear[$row->tahun]);
                        $yearRowspan = $showYear ? ($basicMapYear[$row->tahun] ?? 1) : 0;

                        $showQuarter = !isset($shownQuarter[$quarterLabel]);
                        $quarterRowspan = $showQuarter ? ($basicMapQuarter[$quarterLabel] ?? 1) : 0;

                        $showQuarterTotal = !$hideTotal && !isset($shownQuarterTotal[$quarterLabel]);
                        $quarterTotalRowspan = $showQuarterTotal ? ($basicMapQuarter[$quarterLabel] ?? 1) : 0;

                        if ($showYear) $shownYear[$row->tahun] = true;
                        if ($showQuarter) $shownQuarter[$quarterLabel] = true;
                        if ($showQuarterTotal) $shownQuarterTotal[$quarterLabel] = true;
                    }

                    $quarterTotal = $quarterTotalLookup[$quarterLabel] ?? 0;

                    $groupItems = collect(json_decode($row->items_json ?? '[]', true))->map(function($item) use ($lahan) {
                        return [
                            'anggaran_reklamasi_id' => $item['id'],
                            'kategori_anggaran' => $item['kategori'],
                            'nominal' => $item['nominal'],
                            'tahun' => $item['tahun'],
                            'bulan' => $item['bulan'],
                            'quarter' => $item['quarter'],
                            'created_at' => $item['created_at'],
                            'updated_at' => $item['updated_at'],
                            'edit_url' => route('lahan.anggaran.edit', [$lahan->lahan_id, $item['id']]),
                            'delete_url' => route('lahan.anggaran.destroy', [$lahan->lahan_id, $item['id']]),
                            'can_update' => auth()->user()->can('update', \App\Models\AnggaranReklamasi::find($item['id'])),
                            'can_delete' => auth()->user()->can('delete', \App\Models\AnggaranReklamasi::find($item['id'])),
                        ];
                    })->values();
                @endphp
                    <tr class="hover-group">
                        @if($showYear)
                            <td @if($yearRowspan > 1) rowspan="{{ $yearRowspan }}" @endif
                                class="py-3 px-3 text-sm text-center text-gray leading-5 border-gainsboro border-solid border-r-[1px] border-b-[1px] border-[0px] whitespace-nowrap align-top" style="vertical-align: middle;">
                                {{ $row->tahun }}
                            </td>
                        @endif

                        <td class="bulan-cell py-3 px-3 text-sm text-center text-gray leading-5 border-gainsboro border-solid border-r-[1px] border-b-[1px] border-[0px] whitespace-nowrap cursor-pointer"
                            onclick="window.openAnggaranModal({{ ($groupItems->toJson()) }})">
                            {{ ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$row->bulan-1] ?? $row->bulan }}
                        </td>

                        <td class="nominal-cell py-3 px-3 text-sm text-center text-gray leading-5 border-gainsboro border-solid border-r-[1px] border-b-[1px] border-[0px] whitespace-nowrap cursor-pointer"
                            onclick="window.openAnggaranModal({{ ($groupItems->toJson()) }})">
                            Rp.{{ number_format($row->nominal, 0, ',', '.') }}

                            @foreach($groupItems as $item)
                                <x-main.modal name="confirm-anggaran-deletion-{{ $item['anggaran_reklamasi_id'] }}" focusable>
                                    <form method="POST" action="{{ route('lahan.anggaran.destroy', [$lahan, $item['anggaran_reklamasi_id']]) }}" class="p-6 text-left whitespace-normal">
                                        @csrf
                                        @method('DELETE')
                                        <h2 class="text-lg font-medium text-gray-900">
                                            {{ __('Are you sure you want to delete this data anggaran?') }}
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
                            @endforeach
                        </td>
                        
                        @if($showQuarter)
                            <td @if($quarterRowspan > 1) rowspan="{{ $quarterRowspan }}" @endif
                                class="py-3 px-3 text-sm text-center text-gray leading-5 border-gainsboro border-solid border-r-[1px] border-b-[1px] border-[0px] whitespace-nowrap">
                                {{ $row->quarter }}
                            </td>
                        @endif
                        
                        @if(!$hideTotal && $showQuarterTotal)
                            <td @if($quarterTotalRowspan > 1) rowspan="{{ $quarterTotalRowspan }}" @endif
                                class="py-3 px-3 text-sm text-center text-gray leading-5 border-gainsboro border-solid border-b-[1px] border-[0px] whitespace-nowrap align-top font-bold" style="vertical-align: middle;">
                                Rp.{{ number_format($quarterTotal, 2, ',', '.') }}
                            </td>
                        @endif
                    </tr>
                @empty
                    @if($hasFilter)
                        <tr class="border-none">
                            <td colspan="5"><x-anggaran-reklamasi.empty-state :hasFilter="true" /></td>
                        </tr>
                    @else
                        <tr class="border-none">
                            <td colspan="5" class="py-6 px-3 text-center text-darkslategray">No data anggaran reklamasi available yet</td>
                        </tr>
                    @endif
                @endforelse
            </tbody>
        </table>

        <div class="mt-6">
            @if(method_exists($data, 'links'))
                {{ $data->links('components.main.pagination') }}
            @endif
        </div>
    </div>
    <x-anggaran-reklamasi.detail-modal />
</div>