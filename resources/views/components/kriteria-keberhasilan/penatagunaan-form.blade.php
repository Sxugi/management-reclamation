@props([
    'lahan',
    'kriteria_keberhasilan',
    'readonly',
])

@php
    $kriteria = $kriteria_keberhasilan ?? null;

    $penataanLahanFields = [
        'Luas Area yang Ditata' => [
            'rencana_luas_ditata' => 'Rencana (ha)',
            'realisasi_luas_ditata' => 'Realisasi/Hasil Penilaian (ha)',
            'standard_luas_ditata' => 'Standar Keberhasilan',
            'evaluasi_luas_ditata' => 'Hasil Evaluasi',
        ],
        'Stabilitas Timbunan' => [
            'rencana_stabilitas_ditata' => 'Rencana',
            'realisasi_stabilitas_ditata' => 'Realisasi/Hasil Penilaian',
            'standard_stabilitas_ditata' => 'Standar Keberhasilan',
            'evaluasi_stabilitas_ditata' => 'Hasil Evaluasi',
        ],
    ];

    $penimbunanKembaliFields = [
        'Luas Area yang Ditimbun' => [
            'rencana_luas_ditimbun' => 'Rencana (ha)',
            'realisasi_luas_ditimbun' => 'Realisasi/Hasil Penilaian (ha)',
            'standard_luas_ditimbun' => 'Standar Keberhasilan',
            'evaluasi_luas_ditimbun' => 'Hasil Evaluasi',
        ],
        'Stabilitas Timbunan' => [
            'rencana_stabilitas_ditimbun' => 'Rencana',
            'realisasi_stabilitas_ditimbun' => 'Realisasi/Hasil Penilaian',
            'standard_stabilitas_ditimbun' => 'Standar Keberhasilan',
            'evaluasi_stabilitas_ditimbun' => 'Hasil Evaluasi',
        ],
    ];

    $penebaranTanahFields = [
        'Luas Area yang Ditebar' => [
            'rencana_luas_ditebar' => 'Rencana (ha)',
            'realisasi_luas_ditebar' => 'Realisasi/Hasil Penilaian (ha)',
            'standard_luas_ditebar' => [
                'label' => 'Standar Keberhasilan',
                'hint' => '<li>Baik (Lebih dari 75% dari luas keseluruhan areal bekas tambang)</li><li>Sedang (50% - 75% dari luas keseluruhan areal bekas tambang)</li>',
            ],
            'evaluasi_luas_ditebar' => 'Hasil Evaluasi',
        ],
        'pH Tanah' => [
            'rencana_ph_tanah' => 'Rencana',
            'realisasi_ph_tanah' => 'Realisasi/Hasil Penilaian',
            'standard_ph_tanah' => [
                'label' => 'Standar Keberhasilan',
                'hint' => '<li>Baik (5 - 6)</li><li>Sedang (4,5 - &lt;5)</li>',
            ],
            'evaluasi_ph_tanah' => 'Hasil Evaluasi',
        ],
    ];

    $pengendalianErosiFields = [
        'Saluran Drainase' => [
            'rencana_saluran_drainase' => 'Rencana',
            'realisasi_saluran_drainase' => 'Realisasi/Hasil Penilaian',
            'standard_saluran_drainase' => 'Standar Keberhasilan',
            'evaluasi_saluran_drainase' => 'Hasil Evaluasi',
        ],
        'Bangunan Pengendali Erosi' => [
            'rencana_pengendalian_erosi' => 'Rencana',
            'realisasi_pengendalian_erosi' => 'Realisasi/Hasil Penilaian',
            'standard_pengendalian_erosi' => 'Standar Keberhasilan',
            'evaluasi_pengendalian_erosi' => 'Hasil Evaluasi',
        ],
    ];
@endphp

<div class="w-full relative flex flex-col items-center justify-start gap-6 font-outfit">
    <div class="self-stretch flex flex-col items-center justify-start gap-6">
        
        {{-- Penataan Lahan Section --}}
        <div class="self-stretch rounded-2xl bg-white border-gainsboro border-solid border-[1px] flex flex-col items-center justify-start">
            <div class="self-stretch border-gainsboro border-solid border-b-[1px] border-t-[0px] border-l-[0px] border-r-[0px] flex flex-row items-start justify-start py-5 px-6">
                <div class="flex flex-col items-start justify-start">
                    <b class="relative leading-6">Penataan Lahan</b>
                </div>
            </div>
            <div class="self-stretch flex flex-col items-start justify-start p-6 gap-6 text-sm">
                @foreach($penataanLahanFields as $section => $fields)
                    <div class="w-full flex flex-col items-start justify-start gap-6">
                        <div class="w-full border-gainsboro border-solid border-b-[1px] border-t-[0px] border-l-[0px] border-r-[0px] box-border h-6 flex flex-row items-start justify-center py-0 px-6">
                            <div class="flex flex-col items-start justify-start">
                                <b class="relative leading-6">{{ $section }}</b>
                            </div>
                        </div>
                        <div class="w-full flex flex-row items-start justify-start gap-1.5 ">
                            @foreach($fields as $key => $label)
                                <div class="flex-1 flex flex-col items-start justify-start gap-1.5">
                                    <div class="relative leading-5 font-medium">{{ $label }}</div>
                                    @if(str_contains($key, 'standard_'))
                                        <div class="self-stretch h-11 flex flex-row items-center">
                                            <div class="flex-1 flex flex-row items-center justify-start gap-2 block w-full h-11 border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit">
                                                <b class="flex-1 relative leading-5">
                                                    @if($key === 'standard_luas_ditata')
                                                        Sesuai dengan Rencana
                                                    @elseif($key === 'standard_stabilitas_ditata')
                                                        Tidak Ada Longsoran
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

        {{-- Penimbunan Kembali Lahan Bekas Tambang Section --}}
        <div class="self-stretch rounded-2xl bg-white border-gainsboro border-solid border-[1px] flex flex-col items-center justify-start">
            <div class="self-stretch border-gainsboro border-solid border-b-[1px] border-t-[0px] border-l-[0px] border-r-[0px] flex flex-row items-start justify-start py-5 px-6">
                <div class="flex flex-col items-start justify-start">
                    <b class="relative leading-6 text-gray-900">Penimbunan Kembali Lahan Bekas Tambang</b>
                </div>
            </div>
            <div class="self-stretch flex flex-col items-start justify-start p-6 gap-6 text-sm">
                @foreach($penimbunanKembaliFields as $section => $fields)
                    <div class="w-full flex flex-col items-start justify-start gap-6">
                        <div class="w-full border-gainsboro border-solid border-b-[1px] border-t-[0px] border-l-[0px] border-r-[0px] box-border h-6 flex flex-row items-start justify-center py-0 px-6">
                            <div class="flex flex-col items-start justify-start">
                                <b class="relative leading-6 text-gray-900">{{ $section }}</b>
                            </div>
                        </div>
                        <div class="w-full flex flex-row items-start justify-start gap-1.5 ">
                            @foreach($fields as $key => $label)
                                <div class="flex-1 flex flex-col items-start justify-start gap-1.5">
                                    <div class="relative leading-5 font-medium">{{ $label }}</div>
                                    @if(str_contains($key, 'standard_'))
                                        <div class="self-stretch h-11 flex flex-row items-center">
                                            <div class="flex-1 flex flex-row items-center justify-start gap-2 block w-full h-11 border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit">
                                                <b class="flex-1 relative leading-5">
                                                    @if($key === 'standard_luas_ditimbun')
                                                        Sesuai / Melebihi Rencana
                                                    @elseif($key === 'standard_stabilitas_ditimbun')
                                                        Tidak Ada Longsoran
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

        {{-- Penebaran Tanah Zona Pengakaran Section --}}
        <div class="self-stretch rounded-2xl bg-white border-gainsboro border-solid border-[1px] flex flex-col items-center justify-start">
            <div class="self-stretch border-gainsboro border-solid border-b-[1px] border-t-[0px] border-l-[0px] border-r-[0px] flex flex-row items-start justify-start py-5 px-6">
                <div class="flex flex-col items-start justify-start">
                    <b class="relative leading-6 text-gray-900">Penebaran Tanah Zona Pengakaran</b>
                </div>
            </div>
            <div class="self-stretch flex flex-col items-start justify-start p-6 gap-6 text-sm">
                @foreach($penebaranTanahFields as $section => $fields)
                    <div class="w-full flex flex-col items-start justify-start gap-6">
                        <div class="w-full border-gainsboro border-solid border-b-[1px] border-t-[0px] border-l-[0px] border-r-[0px] box-border h-6 flex flex-row items-start justify-center py-0 px-6">
                            <div class="flex flex-col items-start justify-start">
                                <b class="relative leading-6 text-gray-900">{{ $section }}</b>
                            </div>
                        </div>
                        <div class="w-full flex flex-row items-start justify-start gap-1.5 ">
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
                                                <b class="flex-1 relative leading-5">Baik / Sedang</b>
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

        {{-- Pengendalian Erosi dan Sedimentasi Section --}}
        <div class="self-stretch rounded-2xl bg-white border-gainsboro border-solid border-[1px] flex flex-col items-center justify-start">
            <div class="self-stretch border-gainsboro border-solid border-b-[1px] border-t-[0px] border-l-[0px] border-r-[0px] flex flex-row items-start justify-start py-5 px-6">
                <div class="flex flex-col items-start justify-start">
                    <b class="relative leading-6 text-gray-900">Pengendalian Erosi dan Sedimentasi</b>
                </div>
            </div>
            <div class="self-stretch flex flex-col items-start justify-start p-6 gap-6 text-sm">
                @foreach($pengendalianErosiFields as $section => $fields)
                    <div class="w-full flex flex-col items-start justify-start gap-6">
                        <div class="w-full border-gainsboro border-solid border-b-[1px] border-t-[0px] border-l-[0px] border-r-[0px] box-border h-6 flex flex-row items-start justify-center py-0 px-6">
                            <div class="flex flex-col items-start justify-start">
                                <b class="relative leading-6 text-gray-900">{{ $section }}</b>
                            </div>
                        </div>
                        <div class="w-full flex flex-row items-start justify-start gap-1.5 ">
                            @foreach($fields as $key => $label)
                                <div class="flex-1 flex flex-col items-start justify-start gap-1.5">
                                    <div class="relative leading-5 font-medium">{{ $label }}</div>
                                    @if(str_contains($key, 'standard_'))
                                        <div class="self-stretch h-11 flex flex-row items-center">
                                            <div class="flex-1 flex flex-row items-center justify-start gap-2 block w-full h-11 border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit">
                                                <b class="flex-1 relative leading-5">
                                                    @if($key === 'standard_saluran_drainase')
                                                        Tidak Terjadi Erosi dan Sedimentasi Aktif
                                                    @elseif($key === 'standard_pengendalian_erosi')
                                                        Tidak Terjadi Alur - Alur Erosi
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