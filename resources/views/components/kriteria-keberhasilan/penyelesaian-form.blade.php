@props([
    'lahan',
    'details', // $kriteria->detailKriteriaKeberhasilan->where('kategori','penyelesaian')->keyBy('indikator')
    'readonly' => false,
])

@php
$sections = [
    'Penutupan Tajuk' => [
        'penutupan_tajuk' => [
            'label' => 'Penutupan Tajuk',
            'satuan' => 'ha',
            'rencana' => 'Rencana (ha)',
            'realisasi' => 'Realisasi/Hasil Penilaian (ha)',
            'standar_keberhasilan' => '> 80%',
            'hasil_evaluasi' => 'Hasil Evaluasi',
        ],
    ],
    'Pemeliharaan' => [
        'pemupukan' => [
            'label' => 'Pemupukan',
            'satuan' => null,
            'rencana' => 'Rencana',
            'realisasi' => 'Realisasi/Hasil Penilaian',
            'standar_keberhasilan' => 'Sesuai dengan Dosis',
            'hasil_evaluasi' => 'Hasil Evaluasi',
        ],
        'pengendalian_hama' => [
            'label' => 'Pengendalian Gulma, Hama, dan Penyakit',
            'satuan' => null,
            'rencana' => 'Rencana',
            'realisasi' => 'Realisasi/Hasil Penilaian',
            'standar_keberhasilan' => 'Pengendalian Berdasarkan Hasil Analisis',
            'hasil_evaluasi' => 'Hasil Evaluasi',
        ],
        'penyulaman' => [
            'label' => 'Penyulaman',
            'satuan' => null,
            'rencana' => 'Rencana',
            'realisasi' => 'Realisasi/Hasil Penilaian',
            'standar_keberhasilan' => 'Sesuai dengan Jumlah Tanaman yang Mati',
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
                                                <b class="flex-1 relative leading-5">{{ $field['standar_keberhasilan'] }}</b>
                                            </div>
                                        </div>
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