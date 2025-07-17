@props([
    'lahan',
    'kriteria_keberhasilan',
    'readonly' => false,
])

@php
    $kriteria = $kriteria_keberhasilan ?? null;

    $penanamanFields = [
        'Luas Area yang Ditanam (1. Tanaman Penutup/Cover Crops; 2. Tanaman Cepat Tumbuh; 3. Tanaman Lokal)' => [
            'rencana_luas_penanaman' => 'Rencana (ha)',
            'realisasi_luas_penanaman' => 'Realisasi/Hasil Penilaian (ha)',
            'standard_luas_penanaman' => 'Standar Keberhasilan',
            'evaluasi_luas_penanaman' => 'Hasil Evaluasi',
        ],
        'Pertumbuhan Tanaman (1. Tanaman Penutup/Cover Crops; 2. Tanaman Cepat Tumbuh; 3. Tanaman Lokal)' => [
            'rencana_pertumbuhan_tanaman' => 'Rencana',
            'realisasi_pertumbuhan_tanaman' => 'Realisasi/Hasil Penilaian',
            'standard_pertumbuhan_tanaman' => [
                'label' => 'Standar Keberhasilan',
                'hint' => '<li>Baik (Rasio tumbuh > 80%)</li><li>Sedang (Rasio Tumbuh 60% - 80%)</li>',
            ],
            'evaluasi_pertumbuhan_tanaman' => 'Hasil Evaluasi',
        ],
    ];

    $materialPembangkitFields = [
        'Pengelolaan Material' => [
            'rencana_pengelolaan_material' => 'Rencana',
            'realisasi_pengelolaan_material' => 'Realisasi/Hasil Penilaian',
            'standard_pengelolaan_material' => 'Standar Keberhasilan',
            'evaluasi_pengelolaan_material' => 'Hasil Evaluasi',
        ],
        'Bangunan Pengendali Erosi' => [
            'rencana_bangunan_erosi' => 'Rencana',
            'realisasi_bangunan_erosi' => 'Realisasi/Hasil Penilaian',
            'standard_bangunan_erosi' => 'Standar Keberhasilan',
            'evaluasi_bangunan_erosi' => 'Hasil Evaluasi',
        ],
        'Kolam Pengendap Sedimen' => [
            'rencana_kolam_sedimen' => 'Rencana',
            'realisasi_kolam_sedimen' => 'Realisasi/Hasil Penilaian',
            'standard_kolam_sedimen' => 'Standar Keberhasilan',
            'evaluasi_kolam_sedimen' => 'Hasil Evaluasi',
        ],
    ];
@endphp

<div class="w-full relative flex flex-col items-center justify-start gap-6 font-outfit">
    <div class="self-stretch flex flex-col items-center justify-start gap-6">
        
        {{-- Penanaman Section --}}
        <div class="self-stretch rounded-2xl bg-white border-gainsboro border-solid border-[1px] flex flex-col items-center justify-start">
            <div class="self-stretch border-gainsboro border-solid border-b-[1px] border-t-[0px] border-l-[0px] border-r-[0px] flex flex-row items-start justify-start py-5 px-6">
                <div class="flex flex-col items-start justify-start">
                    <b class="relative leading-6 text-gray-900">Penanaman</b>
                </div>
            </div>
            <div class="self-stretch flex flex-col items-start justify-start p-6 gap-6 text-sm">
                @foreach($penanamanFields as $section => $fields)
                    <div class="w-full flex flex-col items-start justify-start gap-6">
                        <div class="w-full border-gainsboro border-solid border-b-[1px] border-t-[0px] border-l-[0px] border-r-[0px] box-border h-6 flex flex-row items-start justify-center py-0 px-6">
                            <div class="flex flex-col items-start justify-start">
                                <b class="relative leading-6 text-gray-900">{{ $section }}</b>
                            </div>
                        </div>
                        <div class="w-full flex flex-row items-start justify-start gap-1.5 text-gray-600">
                            @foreach($fields as $key => $fieldData)
                                <div class="flex-1 flex flex-col items-start justify-start gap-1.5">
                                    @php
                                        $label = is_array($fieldData) ? $fieldData['label'] : $fieldData;
                                        $hint = is_array($fieldData) && isset($fieldData['hint']) ? $fieldData['hint'] : null;
                                    @endphp
                                    <div class="relative leading-5 font-medium">{{ $label }}</div>
                                    @if(str_contains($key, 'standard_'))
                                        <div class="self-stretch h-11 flex flex-row items-center">
                                            <div class="flex-1 flex flex-row items-center justify-start gap-2 block w-full h-11 border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit">
                                                <b class="flex-1 relative leading-5 text-gray-700">
                                                    @if($key === 'standard_luas_penanaman')
                                                        Sesuai dengan Dosis
                                                    @elseif($key === 'standard_pertumbuhan_tanaman')
                                                        Baik / Sedang
                                                    @endif
                                                </b>
                                            </div>
                                        </div>
                                        @if($hint)
                                            <div class="relative text-xs leading-[18px] text-gray-500">
                                                <ul class="m-0 font-inherit text-[length:inherit] pl-4">
                                                    {!! $hint !!}
                                                </ul>
                                            </div>
                                        @endif
                                    @else
                                        <div class="self-stretch h-11 flex flex-row items-center">
                                            <div class="flex-1 h-5 flex flex-row items-center justify-start gap-2">
                                                <input 
                                                    type="text" 
                                                    name="{{ $key }}" 
                                                    value="{{ old($key, $kriteria->$key ?? '') }}"
                                                    {{ $readonly ? 'readonly' : '' }}
                                                    class="block w-full h-11 border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit"
                                                    placeholder="ex: 1. ...; 2. ...; 3. ..."
                                                />
                                            </div>
                                        </div>
                                        <x-main.input-error :messages="$errors->get($key)" class="mt-2" />
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Material Pembangkit Section --}}
        <div class="self-stretch rounded-2xl bg-white border-gainsboro border-solid border-[1px] flex flex-col items-center justify-start">
            <div class="self-stretch border-gainsboro border-solid border-b-[1px] border-t-[0px] border-l-[0px] border-r-[0px] flex flex-row items-start justify-start py-5 px-6">
                <div class="flex flex-col items-start justify-start">
                    <b class="relative leading-6 text-gray-900">Material Pembangkit</b>
                </div>
            </div>
            <div class="self-stretch flex flex-col items-start justify-start p-6 gap-6 text-sm">
                @foreach($materialPembangkitFields as $section => $fields)
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
                                                    @if($key === 'standard_pengelolaan_material')
                                                        Sesuai dengan Rencana
                                                    @elseif($key === 'standard_bangunan_erosi')
                                                        Tidak Terjadi Alur - Alur Erosi
                                                    @elseif($key === 'standard_kolam_sedimen')
                                                        Kualitas Air Keluaran Memenuhi Baku Mutu Lingkungan
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
                                        <x-main.input-error :messages="$errors->get($key)" class="mt-2" />
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