@props([
    'lahan',
    'kriteria_keberhasilan',
    'readonly',
])

@php
    $kriteria = $kriteria_keberhasilan ?? null;

    $penutupanTajukFields = [
        'Penutupan Tajuk' => [
            'rencana_penutupan_tajuk' => 'Rencana (ha)',
            'realisasi_penutupan_tajuk' => 'Realisasi/Hasil Penilaian (ha)',
            'standard_penutupan_tajuk' => 'Standar Keberhasilan',
            'evaluasi_penutupan_tajuk' => 'Hasil Evaluasi',
        ],
    ];

    $pemeliharaanFields = [
        'Pemupukan' => [
            'rencana_pemupukan' => 'Rencana',
            'realisasi_pemupukan' => 'Realisasi/Hasil Penilaian',
            'standard_pemupukan' => 'Standar Keberhasilan',
            'evaluasi_pemupukan' => 'Hasil Evaluasi',
        ],
        'Pengendalian Gulma, Hama, dan Penyakit' => [
            'rencana_pengendalian_hama' => 'Rencana',
            'realisasi_pengendalian_hama' => 'Realisasi/Hasil Penilaian',
            'standard_pengendalian_hama' => 'Standar Keberhasilan',
            'evaluasi_pengendalian_hama' => 'Hasil Evaluasi',
        ],
        'Penyulaman' => [
            'rencana_penyulaman' => 'Rencana',
            'realisasi_penyulaman' => 'Realisasi/Hasil Penilaian',
            'standard_penyulaman' => 'Standar Keberhasilan',
            'evaluasi_penyulaman' => 'Hasil Evaluasi',
        ],
    ];
@endphp

<div class="w-full relative flex flex-col items-center justify-start gap-6 font-outfit">
    <div class="self-stretch flex flex-col items-center justify-start gap-6">
        
        {{-- Penutupan Tajuk Section --}}
        <div class="self-stretch rounded-2xl bg-white border-gainsboro border-solid border-[1px] flex flex-col items-center justify-start">
            <div class="self-stretch border-gainsboro border-solid border-b-[1px] border-t-[0px] border-l-[0px] border-r-[0px] flex flex-row items-start justify-start py-5 px-6">
                <div class="flex flex-col items-start justify-start">
                    <b class="relative leading-6 text-gray-900">Penutupan Tajuk</b>
                </div>
            </div>
            <div class="self-stretch flex flex-col items-start justify-start p-6 gap-6 text-sm">
                @foreach($penutupanTajukFields as $section => $fields)
                    <div class="w-full flex flex-col items-start justify-start gap-6">
                        <div class="w-full flex flex-row items-start justify-start gap-1.5 text-gray-600">
                            @foreach($fields as $key => $label)
                                <div class="flex-1 flex flex-col items-start justify-start gap-1.5">
                                    <div class="relative leading-5 font-medium">{{ $label }}</div>
                                    @if(str_contains($key, 'standard_'))
                                        <div class="self-stretch h-11 flex flex-row items-center">
                                            <div class="flex-1 flex flex-row items-center justify-start gap-2 block w-full h-11 border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit">
                                                <b class="flex-1 relative leading-5 text-gray-700">
                                                    @if($key === 'standard_penutupan_tajuk')
                                                        > 80%
                                                    @endif
                                                </b>
                                            </div>
                                        </div>
                                    @else
                                        <div class="self-stretch h-11 flex flex-row items-center">
                                            <div class="flex-1 h-5 flex flex-row items-center justify-start gap-2">
                                                <input 
                                                    type="text" 
                                                    name="{{ $key }}" 
                                                    value="{{ old($key, $kriteria->$key ?? '') }}"
                                                    {{ $readonly ? 'readonly' : '' }}
                                                    class="block w-full h-11 border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit"
                                                    placeholder=""
                                                />
                                            </div>
                                        </div>
                                        <x-main.input-error :messages="$errors->get($key)" data-turbo-temporary class="mt-2" />
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Pemeliharaan Section --}}
        <div class="self-stretch rounded-2xl bg-white border-gainsboro border-solid border-[1px] flex flex-col items-center justify-start">
            <div class="self-stretch border-gainsboro border-solid border-b-[1px] border-t-[0px] border-l-[0px] border-r-[0px] flex flex-row items-start justify-start py-5 px-6">
                <div class="flex flex-col items-start justify-start">
                    <b class="relative leading-6 text-gray-900">Pemeliharaan</b>
                </div>
            </div>
            <div class="self-stretch flex flex-col items-start justify-start p-6 gap-6 text-sm">
                @foreach($pemeliharaanFields as $section => $fields)
                    <div class="w-full flex flex-col items-start justify-start gap-6">
                        <div class="w-full border-gainsboro border-solid border-b-[1px] border-t-[0px] border-l-[0px] border-r-[0px] box-border h-6 flex flex-row items-start justify-center py-0 px-6">
                            <div class="flex flex-col items-start justify-start">
                                <b class="relative leading-6 text-gray-900">{{ $section }}</b>
                            </div>
                        </div>
                        <div class="w-full flex flex-row items-start justify-start gap-1.5 text-gray-600">
                            @foreach($fields as $key => $label)
                                <div class="flex-1 flex flex-col items-start justify-start gap-1.5">
                                    <div class="relative leading-5 font-medium">{{ $label }}</div>
                                    @if(str_contains($key, 'standard_'))
                                        <div class="self-stretch h-11 flex flex-row items-center">
                                            <div class="flex-1 flex flex-row items-center justify-start gap-2 block w-full h-11 border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit">
                                                <b class="flex-1 relative leading-5 text-gray-700">
                                                    @if($key === 'standard_pemupukan')
                                                        Sesuai dengan Dosis
                                                    @elseif($key === 'standard_pengendalian_hama')
                                                        Pengendalian Berdasarkan Hasil Analisis
                                                    @elseif($key === 'standard_penyulaman')
                                                        Sesuai dengan Jumlah Tanaman yang Mati
                                                    @endif
                                                </b>
                                            </div>
                                        </div>
                                    @else
                                        <div class="self-stretch h-11 flex flex-row items-center">
                                            <div class="flex-1 h-5 flex flex-row items-center justify-start gap-2">
                                                <input 
                                                    type="text" 
                                                    name="{{ $key }}" 
                                                    value="{{ old($key, $kriteria->$key ?? '') }}"
                                                    {{ $readonly ? 'readonly' : '' }}
                                                    class="block w-full h-11 border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit"
                                                    placeholder=""
                                                />
                                            </div>
                                        </div>
                                        <x-main.input-error :messages="$errors->get($key)" data-turbo-temporary class="mt-2" />
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>