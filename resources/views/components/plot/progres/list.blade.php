<div class="mb-6 bg-white rounded-2xl shadow-md flex flex-col">
    <div class="px-6 py-4 flex justify-between items-center">
        <div class="font-bold text-base font-outfit">
            Laporan Harian
        </div>
        <div class="flex gap-3">
            <x-plot.progres.filter-button :plot="$plot" :kategori="$kategori" :jenisAktivitas="$jenisAktivitas" />
            <div class="rounded-lg bg-darkslategray-300 overflow-hidden flex flex-row items-center justify-center gap-2">
                <button type="button" x-on:click="window.location.href='{{ route('plot.progres.export', $plot->plot_id) }}'" class="rounded-lg bg-darkslategray overflow-hidden flex flex-row items-center justify-center py-3 px-4 gap-2 !text-white no-underline hover:bg-slategray-200 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed" @disabled(!auth()->user()->can('view', $plot))>
                    <span class="relative leading-5 font-medium">Export</span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                </button>
            </div>
            <div class="rounded-lg bg-darkslategray-300 overflow-hidden flex flex-row items-center justify-center gap-2">
                <button type="button" x-on:click="window.location.href='{{ route('plot.progres.create', $plot->plot_id) }}'" class="rounded-lg bg-darkslategray overflow-hidden flex flex-row items-center justify-center py-3 px-4 gap-2 !text-white no-underline hover:bg-slategray-200 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed" @disabled(!auth()->user()->can('create', [\App\Models\ProgresReklamasi::class, $plot]))>
                    <span class="relative leading-5 font-medium">Add</span>
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 4.24951C10.4142 4.24951 10.75 4.58534 10.75 4.99951V9.24951H15.001L15.0771 9.25342C15.4553 9.29177 15.7508 9.61128 15.751 9.99951C15.751 10.3879 15.4554 10.7072 15.0771 10.7456L15.001 10.7495H10.75V15.0005L10.7461 15.0767C10.7077 15.4549 10.3884 15.7505 10 15.7505C9.61173 15.7504 9.29227 15.4548 9.25391 15.0767L9.25 15.0005V10.7495H5C4.58579 10.7495 4.25 10.4137 4.25 9.99951C4.25015 9.58543 4.58588 9.24951 5 9.24951H9.25V4.99951C9.25004 4.5854 9.58591 4.24962 10 4.24951Z" fill="white"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <div class="grid flex-1 self-stretch auto-cols-fr gap-y-8 border-gainsboro border-solid border-[0px] border-t-[1px] overflow-hidden">
        <div class="flex flex-col overflow-x-auto">
            <table class="min-w-max w-auto text-xs text-darkslategray font-outfit border-collapse table-auto">
                <thead class="border-gainsboro border-solid border-b">
                    <tr>
                        <x-main.sortable-header column="tanggal" title="Tanggal" class="h-6 py-3 border-r"/>
                        <x-main.sortable-header column="kategori" title="Kategori" class="h-6 py-3 border-r"/>
                        <th class="py-3 px-6 text-center font-bold border-gainsboro border-r whitespace-nowrap">Jenis Aktivitas</th>
                        <th class="py-3 px-6 text-center font-bold border-gainsboro border-r whitespace-nowrap">Data Input</th>
                        <th class="py-3 px-6 text-center font-bold border-gainsboro whitespace-nowrap">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($progres as $report)
                        <tr class="hover:bg-lightgray cursor-pointer border-gainsboro border-b" onclick='openDetailModal(@json($report->modal_data))'>
                            <td class="py-3 px-6 text-sm text-center text-gray leading-5 border-gainsboro border-t border-r whitespace-nowrap">{{ $report->tanggal->format('d F Y') }}</td>
                            <td class="py-3 px-6 text-sm text-center text-gray leading-5 border-gainsboro border-t border-r whitespace-nowrap">
                                {{ $report->jenisAktivitas?->kategoriAktivitas?->label ?? 'Unknown' }}
                            </td>
                            <td class="py-3 px-6 text-sm text-center text-gray leading-5 border-gainsboro border-t border-r whitespace-nowrap">
                                {{ $report->jenisAktivitas?->label ?? 'Unknown' }}
                            </td>
                            <td class="py-3 px-6 text-sm text-center text-gray leading-5 border-gainsboro border-t border-r whitespace-nowrap">
                                @if($report->fieldValues->count() > 0)
                                    @php
                                        // Get all JenisPohon records and key them by ID for easy lookup
                                        $jenisPohonMap = \App\Models\JenisPohon::all()->keyBy('jenis_pohon_id');

                                        // Build user-friendly field values using already loaded data
                                        $fieldData = $report->fieldValues->map(function($fieldValue) use ($jenisPohonMap) {
                                            $fieldDef = $fieldValue->fieldDefinition;
                                            $fieldKey = $fieldDef->field_key ?? null;
                                            $fieldLabel = $fieldDef->field_label ?? 'Unknown';
                                            $value = $fieldValue->field_value;
                                            $satuan = $fieldDef->satuan ?? '';
                                            
                                            // Special handling for certain field keys to replace IDs with names
                                            if ($fieldKey === 'jenis_pohon_id' && isset($jenisPohonMap[$value])) {
                                                // Jika key-nya jenis_pohon_id, timpa $value (yang aslinya ID) dengan nama pohon
                                                $value = $jenisPohonMap[$value]->nama_pohon;
                                                // Hilangkan satuan (opsional, karena satuan 'id' tidak relevan untuk nama)
                                                $satuan = ''; 
                                            } elseif ($fieldKey === 'metode_sampling') {
                                                $value = ucwords(str_replace('_', ' ', $value));
                                            }

                                            // Format value with unit if available
                                            $formattedValue = $value . ($satuan ? ' ' . $satuan : '');
                                            return $fieldLabel . ': ' . $formattedValue;
                                        })->toArray();
                                        
                                        $displayText = implode(",\n", $fieldData);
                                    @endphp
                                
                                    <div class="text-left">
                                        <x-main.tooltip :content="$displayText" :max-length="80" position="bottom" />
                                    </div>
                                @else
                                    <span class="text-gray-400">No data</span>
                                @endif

                                <x-main.modal name="confirm-progres-deletion-{{ $report->progres_id }}" focusable>
                                    <form method="POST" action="{{ route('plot.progres.destroy', [$plot, $report]) }}" class="p-6 text-left whitespace-normal">
                                        @csrf
                                        @method('DELETE')
                                        <h2 class="text-lg font-medium text-gray-900">
                                            {{ __('Are you sure you want to delete this data progres?') }}
                                        </h2>
                                        <p class="mt-1 text-sm text-gray-600">
                                            {{ __('Once deleted, all data related to this data progres will be permanently lost. This action cannot be undone.') }}
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
                            <td class="py-3 px-6 text-sm text-center text-gray leading-5 border-gainsboro border-t">
                                <x-main.tooltip :content="$report->catatan" :max-length="30" position="bottom" /> 
                            </td>
                        </tr>
                    @empty
                        @if($hasFilter)
                            <tr class="border-none">
                                <td colspan="5"><x-plot.progres.empty-state :hasFilter="true" /></td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="5" class="py-6 px-6 text-center text-gray-400">No data progress available yet</td>
                            </tr>
                        @endif
                    @endforelse
                </tbody>
            </table>
            <div class="mt-6">
                {{ $progres->links('components.main.pagination') }}
            </div>
        </div>
    </div>
    <x-plot.progres.detail-modal />
</div>