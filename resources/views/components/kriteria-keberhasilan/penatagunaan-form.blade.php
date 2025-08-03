@props([
    'lahan',
    'details',
    'readonly' => false,
])

@php
$sections = [
    'Penataan Lahan' => [
        'luas_ditata' => [
            'label' => 'Luas Area yang Ditata',
            'satuan' => 'ha',
            'rencana' => 'Rencana (ha)',
            'realisasi' => 'Realisasi/Hasil Penilaian (ha)',
            'standar_keberhasilan' => 'Sesuai dengan Rencana',
            'hasil_evaluasi' => 'Hasil Evaluasi',
        ],
        'stabilitas_ditata' => [
            'label' => 'Stabilitas Timbunan',
            'satuan' => null,
            'rencana' => 'Rencana',
            'realisasi' => 'Realisasi/Hasil Penilaian',
            'standar_keberhasilan' => 'Tidak Ada Longsoran',
            'hasil_evaluasi' => 'Hasil Evaluasi',
        ],
    ],
    'Penimbunan Kembali Lahan Bekas Tambang' => [
        'luas_ditimbun' => [
            'label' => 'Luas Area yang Ditimbun',
            'satuan' => 'ha',
            'rencana' => 'Rencana (ha)',
            'realisasi' => 'Realisasi/Hasil Penilaian (ha)',
            'standar_keberhasilan' => 'Sesuai / Melebihi Rencana',
            'hasil_evaluasi' => 'Hasil Evaluasi',
        ],
        'stabilitas_ditimbun' => [
            'label' => 'Stabilitas Timbunan',
            'satuan' => null,
            'rencana' => 'Rencana',
            'realisasi' => 'Realisasi/Hasil Penilaian',
            'standar_keberhasilan' => 'Tidak Ada Longsoran',
            'hasil_evaluasi' => 'Hasil Evaluasi',
        ],
    ],
    'Penebaran Tanah Zona Pengakaran' => [
        'luas_ditebar' => [
            'label' => 'Luas Area yang Ditebar',
            'satuan' => 'ha',
            'rencana' => 'Rencana (ha)',
            'realisasi' => 'Realisasi/Hasil Penilaian (ha)',
            'standar_keberhasilan' => [
                'label' => 'Baik/Sedang',
                'hint' => '<li>Baik (Lebih dari 75% dari luas keseluruhan areal bekas tambang)</li><li>Sedang (50% - 75% dari luas keseluruhan areal bekas tambang)</li>',
            ],
            'hasil_evaluasi' => 'Hasil Evaluasi',
        ],
        'ph_tanah' => [
            'label' => 'pH Tanah',
            'satuan' => null,
            'rencana' => 'Rencana',
            'realisasi' => 'Realisasi/Hasil Penilaian',
            'standar_keberhasilan' => [
                'label' => 'Baik/Sedang',
                'hint' => '<li>Baik (5 - 6)</li><li>Sedang (4,5 - &lt;5)</li>',
            ],
            'hasil_evaluasi' => 'Hasil Evaluasi',
        ],

    ],
    'Pengendalian Erosi dan Sedimentasi' => [
        'saluran_drainase' => [
            'label' => 'Saluran Drainase',
            'satuan' => null,
            'rencana' => 'Rencana',
            'realisasi' => 'Realisasi/Hasil Penilaian',
            'standar_keberhasilan' => 'Tidak Terjadi Erosi dan Sedimentasi Aktif',
            'hasil_evaluasi' => 'Hasil Evaluasi',
        ],
        'pengendalian_erosi' => [
            'label' => 'Bangunan Pengendali Erosi',
            'satuan' => null,
            'rencana' => 'Rencana',
            'realisasi' => 'Realisasi/Hasil Penilaian',
            'standar_keberhasilan' => 'Tidak Terjadi Alur - Alur Erosi',
            'hasil_evaluasi' => 'Hasil Evaluasi',
        ],
    ],
];
@endphp

<div class="w-full relative flex flex-col items-center justify-start gap-6 font-outfit">
    <div class="self-stretch flex flex-col items-center justify-start gap-6">
        @foreach($sections as $section => $fields)
        <div class="self-stretch rounded-2xl bg-white border-gainsboro border-solid border-[1px] flex flex-col items-center justify-start">
            <div class="self-stretch border-gainsboro border-solid border-b-[1px] border-[0px] flex flex-row items-start justify-start py-5 px-6">
                <div class="flex flex-col items-start justify-start">
                    <b class="relative leading-6">{{ $section }}</b>
                </div>
            </div>
            <div class="self-stretch flex flex-col items-start justify-start p-6 gap-6 text-sm">
                @foreach($fields as $indikator => $field)
                    <div class="w-full flex flex-col items-start justify-start gap-4">
                        <div class="w-full border-gainsboro border-solid border-b-[1px] border-[0px] h-6 flex flex-row items-start justify-center py-0 px-6">
                            <div class="flex flex-col items-start justify-start">
                                <b class="relative leading-6">{{ $field['label'] }}</b>
                            </div>
                        </div>
                        <div class="w-full flex flex-row items-start justify-start gap-2">
                            @foreach(['rencana', 'realisasi', 'standar_keberhasilan', 'hasil_evaluasi'] as $col)
                                <div class="flex-1 flex flex-col items-start justify-start gap-1.5">
                                    <div class="relative leading-5 font-medium">
                                        {{ $col === 'standar_keberhasilan' 
                                            ? ('Standar Keberhasilan')
                                            : ($field[$col] ?? ucfirst($col)) 
                                        }}                                    
                                    </div>
                                    @if($col === 'standar_keberhasilan')
                                        <div class="self-stretch h-11 flex flex-row items-center">
                                            <div class="flex-1 flex flex-row items-center justify-start gap-2 block w-full h-11 border-solid border-[1px] border-gray-300 rounded-md shadow-sm px-3 py-2 box-border font-outfit">
                                                <b class="flex-1 relative leading-5">
                                                    {{ is_array($field[$col]) ? $field[$col]['label'] : $field[$col] }}
                                                </b>
                                            </div>
                                        </div>
                                        @if(is_array($field[$col]) && isset($field[$col]['hint']))
                                            <div class="relative text-xs leading-[18px] text-gray-500">
                                                <ul class="m-0 font-inherit text-[length:inherit] pl-4">
                                                    {!! $field[$col]['hint'] !!}
                                                </ul>
                                            </div>
                                        @endif
                                    @else
                                        <div class="self-stretch h-11 flex flex-row items-center">
                                            <div class="flex-1 h-5 flex flex-row items-center justify-start gap-2">
                                                <input
                                                    type="text"
                                                    name="indikator[{{ $indikator }}][{{ $col }}]"
                                                    value="{{ old('indikator.' . $indikator . '.' . $col, $details[$indikator][$col] ?? '') }}"
                                                    class="block w-full h-11 border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit"
                                                    placeholder="{{ $field['satuan'] && in_array($col, ['rencana','realisasi']) ? $field['satuan'] : '' }}"
                                                    @if($readonly ?? false) readonly disabled @endif
                                                />
                                            </div>
                                        </div>
                                        <x-main.input-error :messages="$errors->get('indikator.' . $indikator . '.' . $col)" data-turbo-temporary class="mt-2" />
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>