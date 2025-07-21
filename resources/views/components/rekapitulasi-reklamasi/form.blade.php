@props([
    'lahan',
    'rekapitulasi_reklamasi',
    'tahun_aktif',
    'readonly',
])

@php
    $reklamasi = $rekapitulasi_reklamasi[$tahun_aktif] ?? null;

    $lahanYangDibukaFields = [
        'Area Penambangan' => [
            'area_penambangan' => 'Area Penambangan',
        ],
        'Area Diluar Penambangan' => [
            'timbuman_tanah_pengakaran' => 'Timbunan Tanah Zona Pengakaran',
            'timbuman_batuan_samping' => 'Timbunan Batuan Samping dan/atau Tanah/Butuan Penutup',
            'timbuman_komoditas_tambang' => 'Timbunan Komoditas Tambang',
            'timbuman_limbah_fasilitas' => 'Timbunan/Penyimpanan Limbah Fasilitas Penunjang',
            'jalan_tambang' => 'Jalan Tambang dan/atau Jalan Angkut',
            'kolam_sedimen' => 'Kolam Sedimen',
            'fasilitas_pengolahan' => 'Instalasi dan Fasilitas Pengolahan dan/atau Pemurnian',
            'kantor_perumahan' => 'Kantor dan Perumahan (comp atau flying camp)',
            'bengkel' => 'Bengkel',
            'fasilitas_penunjang' => 'Fasilitas Penunjang Lainnya',
        ]
    ];

    $penambanganFields = [
        'lahan_selesai_ditambang' => [
            'label' => 'Lahan Selesai Ditambang (ha)',
        ],
        'lahan_aktif_ditambang' => [
            'label' => 'Lahan/Front Aktif ditambang (ha)',
        ],
        'volume_batuan_samping' => [
            'label' => 'Volume Batuan Samping dan/atau Tanah/Batuan Penutup yang Digali (BCM atau m<sup>3</sup>)',
        ],
    ];

    $penimbunanFields = [
        'penimbunan_bekas_tambang' => [
            'label' => 'Di Bekas Tambang (ha)',
        ],
        'penimbunan_diluar_bekas_tambang' => [
            'label' => 'Di Luar Bekas Tambang (ha)',
        ],
        'volume_bekas_tambang' => [
            'label' => 'Volume yang Ditimbun di Bekas Tambang (m<sup>3</sup>)',
        ],
        'volume_diluar_bekas_tambang' => [
            'label' => 'Volume yang Ditimbun di Luar Bekas Tambang (m<sup>3</sup>)',
        ],
    ];

    $reklamasiFields = [
        'Penatagunaan Lahan' => [
            'penataan_tanah' => 'Penataan Lahan (ha)',
            'penebaran_tanah_pengakaran' => 'Penebaran Tanah Zona Pengakaran (ha)',
            'pengendalian_erosi' => 'Pengendalian Erosi dan Sedimentasi',
        ],
        'Revegetasi (ha)' => [
            'kualitas_tanah' => 'Analisis Kualitas Tanah',
            'pemupukan' => 'Pemupukan (ha)',
            'pengadaan_bibit' => 'Pengadaan Bibit (Batang/kg)',
            'penanaman' => 'Penanaman (Batang)',
            'pemeliharaan_tanaman' => 'Pemeliharaan Tanaman (ha)',
        ]
    ];

    $pencegahanAirAsamFields = [
        'pencegahan_air_asam' => [
            'label' => 'Pencegahan dan Penanggulangan Air Asam Tambang',
        ],
    ];

    $pekerjaanSipilFields = [
        'pekerjaan_sipil' => [
            'label' => 'Pekerjaan Sipil Sesuai Peruntukan Lahan Pascatambang/Program Reklamasi Bentuk Lain (Satuan Luas)',
        ],
    ];

    $rencanaPemanfaatanFields = [
        'stabilisasi_lereng' => [
            'label' => 'Stabilisasi Lereng (ha)',
        ],
        'pengamanan_lubang' => [
            'label' => 'Pengamanan Lubang Bekas Tambang (void) (ha)',
        ],
        'pemulihan_kualitas_air' => [
            'label' => 'Pemulihan dan Pemantauan Kualitas Air dan Serta Pengolahan Air dalam Lubang Bekas Tambang (void) Sesuai dengan Peruntukannya',
        ],
        'pemeliharaan_lubang' => [
            'label' => 'Pemeliharaan Lubang Bekas Tambang (void)',
        ],
    ];

    $isEdit = !is_null($reklamasi);
@endphp

<form
    method="POST"
    action="{{ $isEdit
        ? route('lahan.rekapitulasi-reklamasi.update', [$lahan->lahan_id, $reklamasi->data_reklamasi_id])
        : route('lahan.rekapitulasi-reklamasi.store', $lahan->lahan_id)
    }}"
    class="grid grid-cols-1 lg:grid-cols-2 gap-6 w-full font-outfit"
>
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <input type="hidden" name="lahan_id" value="{{ $lahan->lahan_id }}">
    <input type="hidden" name="tahun" value="{{ $tahun_aktif }}">
    <input type="hidden" name="type" value="rekapitulasi">

    <!-- LEFT COLUMN -->
    <div class="flex flex-col items-start justify-start gap-6">
        <div class="self-stretch rounded-2xl bg-white border-gainsboro border-solid border-[1px] flex flex-col items-center justify-start">
            <div class="self-stretch rounded-t-2xl border-gainsboro border-solid border-b-[1px] border-t-[0px] border-l-[0px] border-r-[0px] flex flex-row items-start justify-start py-5 px-6">
                <div class="flex flex-col items-start justify-start">
                    <b class="relative leading-6">Lahan yang Dibuka (ha)</b>
                </div>
            </div>
            <div class="self-stretch flex flex-col items-start justify-start p-6 gap-6 text-sm text-darkslategray-200">
                @foreach ($lahanYangDibukaFields as $section => $fields)
                    <div class="self-stretch flex flex-col items-start justify-start gap-1.5 text-gray">
                        <div class="self-stretch border-gainsboro border-solid border-b-[1px] border-t-[0px] border-l-[0px] border-r-[0px] flex flex-row items-start justify-center py-0 px-6">
                            <div class="flex flex-col items-start justify-start">
                                <b class="relative leading-6">{{ $section }}</b>
                            </div>
                        </div>
                        @foreach($fields as $key => $label)
                            <div class="self-stretch flex flex-col items-start justify-start gap-1.5 text-darkslategray-200">
                                <div class="relative leading-5 font-medium">{!! $label !!}</div>
                                <input
                                    type="number"
                                    step="0.01"
                                    name="{{ $key }}"
                                    value="{{ old($key, $reklamasi[$key] ?? 0) }}"
                                    class="block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit"
                                    @if($readonly ?? false) readonly disabled @endif
                                    min="0"
                                >
                                <x-main.input-error :messages="$errors->get($key)" data-turbo-temporary class="mt-2" />
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
        <div class="self-stretch rounded-2xl bg-white border-gainsboro border-solid border-[1px] flex flex-col items-center justify-start">
            <div class="self-stretch rounded-t-2xl border-gainsboro border-solid border-b-[1px] border-t-[0px] border-l-[0px] border-r-[0px] flex flex-row items-start justify-start py-5 px-6">
                <div class="flex flex-col items-start justify-start">
                    <b class="relative leading-6">Penambangan</b>
                </div>
            </div>
            <div class="self-stretch flex flex-col items-start justify-start p-6 gap-6 text-sm text-darkslategray-200">
                @foreach($penambanganFields as $key => $field)
                    <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                        <div class="relative leading-5 font-medium">{!! $field['label'] !!}</div>
                        <input
                            type="number"
                            step="0.01"
                            name="{{ $key }}"
                            value="{{ old($key, $reklamasi[$key] ?? 0) }}"
                            class="block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit"
                            @if($readonly ?? false) readonly disabled @endif
                            min="0"
                        >
                        <x-main.input-error :messages="$errors->get($key)" data-turbo-temporary class="mt-2" />
                    </div>
                @endforeach
            </div>
        </div>
        <div class="self-stretch rounded-2xl bg-white border-gainsboro border-solid border-[1px] flex flex-col items-center justify-start">
            <div class="self-stretch rounded-t-2xl border-gainsboro border-solid border-b-[1px] border-t-[0px] border-l-[0px] border-r-[0px] flex flex-row items-start justify-start py-5 px-6">
                <div class="flex flex-col items-start justify-start">
                    <b class="relative leading-6">Penimbunan</b>
                </div>
            </div>
            <div class="self-stretch flex flex-col items-start justify-start p-6 gap-6 text-sm text-darkslategray-200">
                @foreach($penimbunanFields as $key => $field)
                    <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                        <div class="relative leading-5 font-medium">{!! $field['label'] !!}</div>
                        <input
                            type="number"
                            step="0.01"
                            name="{{ $key }}"
                            value="{{ old($key, $reklamasi[$key] ?? 0) }}"
                            class="block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit"
                            @if($readonly ?? false) readonly disabled @endif
                            min="0"
                        >
                        <x-main.input-error :messages="$errors->get($key)" data-turbo-temporary class="mt-2" />
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- RIGHT COLUMN: Biaya Tidak Langsung -->
    <div class="flex flex-col items-start justify-start gap-6">
        <div class="self-stretch rounded-2xl bg-white border-gainsboro border-solid border-[1px] flex flex-col items-center justify-start">
            <div class="self-stretch rounded-t-2xl border-gainsboro border-solid border-b-[1px] border-t-[0px] border-l-[0px] border-r-[0px] flex flex-row items-start justify-start py-5 px-6">
                <div class="flex flex-col items-start justify-start">
                    <b class="relative leading-6">Reklamasi</b>
                </div>
            </div>
            <div class="self-stretch flex flex-col items-start justify-start p-6 gap-6 text-sm text-darkslategray-200">
                @foreach ($reklamasiFields as $section => $fields)
                    <div class="self-stretch flex flex-col items-start justify-start gap-1.5 text-gray">
                        <div class="self-stretch border-gainsboro border-solid border-b-[1px] border-t-[0px] border-l-[0px] border-r-[0px] flex flex-row items-start justify-center py-0 px-6">
                            <div class="flex flex-col items-start justify-start">
                                <b class="relative leading-6">{{ $section }}</b>
                            </div>
                        </div>
                        @foreach($fields as $key => $label)
                            <div class="self-stretch flex flex-col items-start justify-start gap-1.5 text-darkslategray-200">
                                <div class="relative leading-5 font-medium">{!! $label !!}</div>
                                <input
                                    type="number"
                                    step="0.01"
                                    name="{{ $key }}"
                                    value="{{ old($key, $reklamasi[$key] ?? 0) }}"
                                    class="block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit"
                                    @if($readonly ?? false) readonly disabled @endif
                                    min="0"
                                >
                                <x-main.input-error :messages="$errors->get($key)" data-turbo-temporary class="mt-2" />
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
        <div class="self-stretch rounded-2xl bg-white border-gainsboro border-solid border-[1px] flex flex-col items-center justify-start">
            <div class="self-stretch flex flex-col items-start justify-start p-6 gap-6 text-sm text-darkslategray-200">
                @foreach($pencegahanAirAsamFields as $key => $field)
                    <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                        <div class="relative leading-5 font-bold">{!! $field['label'] !!}</div>
                        <input
                            type="number"
                            step="0.01"
                            name="{{ $key }}"
                            value="{{ old($key, $reklamasi[$key] ?? 0) }}"
                            class="block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit"
                            @if($readonly ?? false) readonly disabled @endif
                        >
                        <x-main.input-error :messages="$errors->get($key)" data-turbo-temporary class="mt-2" />
                    </div>
                @endforeach
                @foreach($pekerjaanSipilFields as $key => $field)
                    <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                        <div class="relative leading-5 font-bold">{!! $field['label'] !!}</div>
                        <input
                            type="number"
                            step="0.01"
                            name="{{ $key }}"
                            value="{{ old($key, $reklamasi[$key] ?? 0) }}"
                            class="block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit"
                            @if($readonly ?? false) readonly disabled @endif
                            min="0"
                        >
                        <x-main.input-error :messages="$errors->get($key)" data-turbo-temporary class="mt-2" />
                    </div>
                @endforeach
            </div>
        </div>
        <div class="self-stretch rounded-2xl bg-white border-gainsboro border-solid border-[1px] flex flex-col items-center justify-start">
                <div class="self-stretch rounded-t-2xl border-gainsboro border-solid border-b-[1px] border-t-[0px] border-l-[0px] border-r-[0px] flex flex-row items-start justify-start py-5 px-6">
                    <div class="flex flex-col items-start justify-start">
                        <b class="relative leading-6">Rencana Pemanfaatan Lubang Bekastambang (void)</b>
                    </div>
                </div>
                <div class="self-stretch flex flex-col items-start justify-start p-6 gap-6 text-sm text-darkslategray-200">
                    @foreach($rencanaPemanfaatanFields as $key => $field)
                        <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                            <div class="relative leading-5 font-medium">{!! $field['label'] !!}</div>
                            <input
                                type="number"
                                step="0.01"
                                name="{{ $key }}"
                                value="{{ old($key, $reklamasi[$key] ?? 0) }}"
                                class="block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit"
                                @if($readonly ?? false) readonly disabled @endif
                                min="0"
                            >
                            <x-main.input-error :messages="$errors->get($key)" data-turbo-temporary class="mt-2" />
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="rounded-lg bg-darkslategray-300 overflow-hidden flex flex-row items-center justify-center py-3 px-4 gap-2 text-sm text-white">
                @if (!($readonly ?? false))
                    <x-main.primary-button type="submit" class="relative leading-5 font-medium">
                        {{ $isEdit ? 'Update' : 'Save' }}
                    </x-main.primary-button>
                @endif
            </div>
        </div>
    </div>
</form>