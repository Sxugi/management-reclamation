@props([
    'lahan',
    'details',
    'readonly' => false,
])

@php
$sections = [
    'Penanaman' => [
        'luas_penutup' => [
            'label' => 'Luas Area yang Ditanam (Tanaman Penutup/Cover Crops)',
            'satuan' => 'ha',
            'rencana' => 'Rencana (ha)',
            'realisasi' => 'Realisasi/Hasil Penilaian (ha)',
            'standar_keberhasilan' => 'Sesuai dengan Dosis',
            'hasil_evaluasi' => 'Hasil Evaluasi',
        ],
        'luas_cepat_tumbuh' => [
            'label' => 'Luas Area yang Ditanam (Tanaman Cepat Tumbuh)',
            'satuan' => 'ha',
            'rencana' => 'Rencana (ha)',
            'realisasi' => 'Realisasi/Hasil Penilaian (ha)',
            'standar_keberhasilan' => 'Sesuai dengan Dosis',
            'hasil_evaluasi' => 'Hasil Evaluasi',
        ],
        'luas_lokal' => [
            'label' => 'Luas Area yang Ditanam (Tanaman Lokal)',
            'satuan' => 'ha',
            'rencana' => 'Rencana (ha)',
            'realisasi' => 'Realisasi/Hasil Penilaian (ha)',
            'standar_keberhasilan' => 'Sesuai dengan Dosis',
            'hasil_evaluasi' => 'Hasil Evaluasi',
        ],
        'pertumbuhan_penutup' => [
            'label' => 'Pertumbuhan Tanaman Penutup/Cover Crops',
            'satuan' => null,
            'rencana' => 'Rencana',
            'realisasi' => 'Realisasi/Hasil Penilaian',
            'standar_keberhasilan' => [
                'label' => 'Baik/Sedang',
                'hint' => '<li>Baik (Rasio tumbuh > 80%)</li><li>Sedang (Rasio Tumbuh 60% - 80%)</li>'
            ],
            'hasil_evaluasi' => 'Hasil Evaluasi',
        ],
        'pertumbuhan_cepat_tumbuh' => [
            'label' => 'Pertumbuhan Tanaman Cepat Tumbuh',
            'satuan' => null,
            'rencana' => 'Rencana',
            'realisasi' => 'Realisasi/Hasil Penilaian',
            'standar_keberhasilan' => [
                'label' => 'Baik/Sedang',
                'hint' => '<li>Baik (Rasio tumbuh > 80%)</li><li>Sedang (Rasio Tumbuh 60% - 80%)</li>'
            ],
            'hasil_evaluasi' => 'Hasil Evaluasi',
        ],
        'pertumbuhan_lokal' => [
            'label' => 'Pertumbuhan Tanaman Lokal',
            'satuan' => null,
            'rencana' => 'Rencana',
            'realisasi' => 'Realisasi/Hasil Penilaian',
            'standar_keberhasilan' => [
                'label' => 'Baik/Sedang',
                'hint' => '<li>Baik (Rasio tumbuh > 80%)</li><li>Sedang (Rasio Tumbuh 60% - 80%)</li>'
            ],
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
                        
                        <div class="w-full grid grid-cols-4 gap-2">
                            @foreach(['rencana', 'realisasi', 'standar_keberhasilan', 'hasil_evaluasi'] as $col)
                                <div class="flex flex-col items-start justify-start gap-1.5">
                                    <div class="relative leading-5 font-medium min-h-[20px] w-full">
                                        <div class="overflow-hidden whitespace-nowrap text-ellipsis">
                                            {{ $col === 'standar_keberhasilan' 
                                                ? 'Standar Keberhasilan'
                                                : ($field[$col] ?? ucfirst($col)) 
                                            }}
                                        </div>
                                    </div>
                                    
                                    <div class="w-full h-11 flex flex-row items-center relative">
                                        @if($col === 'standar_keberhasilan')
                                            <div class="w-full h-full flex flex-row items-center justify-start border-solid border-[1px] border-gray-300 rounded-md px-3 py-2 bg-gray-50 group cursor-help">
                                                <div class="flex-1 relative leading-5 overflow-hidden whitespace-nowrap text-ellipsis text-sm font-medium">
                                                    {{ is_array($field[$col]) ? $field[$col]['label'] : $field[$col] }}
                                                </div>
                                                
                                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 text-xs text-white bg-gray-900 rounded-lg whitespace-normal opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 max-w-xs pointer-events-none">
                                                    <div class="text-center">
                                                        {{ is_array($field[$col]) ? $field[$col]['label'] : $field[$col] }}
                                                        @if(is_array($field[$col]) && isset($field[$col]['hint']))
                                                            <div class="mt-2 text-left">
                                                                <ul class="list-disc list-inside space-y-1">
                                                                    {!! $field[$col]['hint'] !!}
                                                                </ul>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>
                                                </div>
                                            </div>
                                        @else
                                            <input
                                                type="text"
                                                name="indikator[{{ $indikator }}][{{ $col }}]"
                                                value="{{ old('indikator.' . $indikator . '.' . $col, $details[$indikator][$col] ?? '') }}"
                                                class="w-full h-full text-sm border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 font-outfit"
                                                placeholder="{{ $field['satuan'] && in_array($col, ['rencana','realisasi']) ? $field['satuan'] : '' }}"
                                                @if($readonly ?? false) readonly disabled @endif
                                            />
                                        @endif
                                    </div>
                                    
                                    @if($col !== 'standar_keberhasilan')
                                        <x-main.input-error :messages="$errors->get('indikator.' . $indikator . '.' . $col)" data-turbo-temporary class="mt-2" />
                                    @else
                                        @if(is_array($field[$col]) && isset($field[$col]['hint']))
                                            <div class="relative text-xs leading-[18px] text-gray-500">
                                                <ul class="m-0 font-inherit text-[length:inherit] pl-6 list-disc list-outside">
                                                    {!! $field[$col]['hint'] !!}
                                                </ul>
                                            </div>
                                        @endif
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