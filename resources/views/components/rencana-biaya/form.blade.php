@props([
    'lahan',
    'rencana_biaya',
    'tahun_aktif',
    'readonly',
])

@php
    $rencana_biaya = $rencana_biaya[$tahun_aktif] ?? null;

    $biayaLangsungFields = [
        'Biaya Penatagunaan Lahan' => [
            'penataan_permukaan_tanah' => 'Penataan Permukaan Tanah',
            'penebaran_tanah_zona_pengakaran' => 'Penebaran Tanah Zona Pengakaran',
            'pengendalian_erosi' => 'Pengendalian Erosi dan Sedimentasi',
        ],
        'Biaya Revegetasi' => [
            'analisis_kualitas_tanah' => 'Analisis Kualitas Tanah',
            'pemupukan' => 'Pemupukan',
            'pengadaan_bibit' => 'Pengadaan Bibit',
            'penanaman' => 'Penanaman',
            'pemeliharaan_tanaman' => 'Pemeliharaan Tanaman',
        ],
        'lain' => [
            'biaya_pencegahan' => 'Biaya Pencegahan dan Penanggulangan Air Asam Tambang (apabila ada)',
            'biaya_pekerjaan_sipil' => 'Biaya untuk Pekerjaan Sipil Sesuai Peruntukan Lahan Pascatambang atau Program Reklamasi Bentuk Lain',
        ],
        'Biaya Pemanfaatan Lubang Bekas Tambang (void)' => [
            'stabilitas_lereng' => 'Stabilitas Lereng',
            'pengamanan_void' => 'Pengamanan Lubang Bekas Tambang (void)',
            'pemulihan_void' => 'Pemulihan dan Pemantauan Kualitas Air Serta Pengelolaan Air Dalam Lubang Bekas Tambang (void) Sesuai dengan Peruntukannya.',
            'pemeliharaan_void' => 'Pemeliharaan Lubang Bekas Tambang (void)',
        ],
        'sub_total' => [
            'subtotal_1' => 'SUBTOTAL 1 (Rp/US$)'
        ],
    ];
    $biayaTidakLangsungFields = [
        'biaya_mobilisasi' => [
            'label' => 'Biaya Mobilisasi dan Demobilisasi Alat',
            'hint' => 'besarnya 2,5% dari biaya langsung atau berdasarkan perhitungan'
        ],
        'biaya_perencanaan' => [
            'label' => 'Biaya Perencanaan Reklamasi',
            'hint' => 'besarnya 2% - 10% dari biaya langsung (grafik Englemens Heavy Construction Cost File)'
        ],
        'biaya_admin' => [
            'label' => 'Biaya Administrasi dan Keuntungan Pihak Ketiga Sebagai Pelaksana Reklamasi Tahap Eksplorasi',
            'hint' => 'besarnya 3% - 14% dari biaya langsung (grafik Englemens Heavy Construction Cost File)'
        ],
        'biaya_supervisi' => [
            'label' => 'Biaya Supervisi',
            'hint' => 'besarnya 2% - 7% dari biaya langsung (grafik Englemens Heavy Construction Cost File)'
        ],
        'subtotal_2' => [
            'label' => 'SUBTOTAL 2 (Rp/US$)',
            'hint' => ''
        ],
    ];

    $biayaLangsung = $rencana_biaya ? $rencana_biaya->biaya_langsung : [];
    $biayaTidakLangsung = $rencana_biaya ? $rencana_biaya->biaya_tidak_langsung : [];
    $isEdit = !is_null($rencana_biaya);
@endphp

<form
    method="POST"
    action="{{ $isEdit
        ? route('lahan.rencana-biaya.update', [$lahan->lahan_id, $rencana_biaya->rencana_biaya_id])
        : route('lahan.rencana-biaya.store', $lahan->lahan_id)
    }}"
    class="grid grid-cols-1 lg:grid-cols-2 gap-6 w-full font-outfit"
>
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <input type="hidden" name="lahan_id" value="{{ $lahan->lahan_id }}">
    <input type="hidden" name="tahun" value="{{ $tahun_aktif }}">

    <!-- LEFT COLUMN: Biaya Langsung -->
    <div class="flex flex-col items-start justify-start gap-6">
        <div class="self-stretch rounded-2xl bg-white border-gainsboro border-solid border-[1px] flex flex-col items-center justify-start">
            <div class="self-stretch rounded-t-2xl border-gainsboro border-solid border-b-[1px] border-t-[0px] border-l-[0px] border-r-[0px] flex flex-row items-start justify-start py-5 px-6">
                <div class="flex flex-col items-start justify-start">
                    <b class="relative leading-6">Biaya Langsung (Rp/US$)</b>
                </div>
            </div>
            <div class="self-stretch flex flex-col items-start justify-start p-6 gap-6 text-sm text-darkslategray-200">
                @foreach ($biayaLangsungFields as $section => $fields)
                    @if ($section === 'lain')
                        @foreach($fields as $key => $label)
                            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                                <div class="relative leading-5 font-medium">{!! $label !!}</div>
                                <input
                                    type="number"
                                    name="biaya_langsung[{{ $key }}]"
                                    value="{{ old('biaya_langsung.$key', $biayaLangsung[$key] ?? 0) }}"
                                    class="block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit"
                                    min="0"
                                    @if($readonly ?? false) readonly disabled @endif
                                >
                            </div>
                        @endforeach
                    @elseif ($section === 'sub_total')
                        <div class="self-stretch flex flex-col items-start justify-start gap-1.5 text-gray">
                            <div class="self-stretch border-gainsboro border-solid border-b-[1px] border-t-[0px] border-l-[0px] border-r-[0px] flex flex-row items-start justify-center py-0 px-6">
                                <div class="flex flex-col items-start justify-start">
                                    <b class="relative leading-6">SUBTOTAL 1 (Rp/US$)</b>
                                </div>
                            </div>
                            @foreach($fields as $key => $label)
                                <input
                                    type="number"
                                    name="biaya_langsung[{{ $key }}]"
                                    value="{{ old('biaya_langsung.$key', $biayaLangsung[$key] ?? 0) }}"
                                    class="block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit"
                                    min="0"
                                    @if($readonly ?? false) readonly disabled @endif
                                >
                            @endforeach
                        </div>
                    @else
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
                                        name="biaya_langsung[{{ $key }}]"
                                        value="{{ old('biaya_langsung.$key', $biayaLangsung[$key] ?? 0) }}"
                                        class="block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit"
                                        min="0"
                                        @if($readonly ?? false) readonly disabled @endif
                                    >
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN: Biaya Tidak Langsung -->
    <div class="flex flex-col items-start justify-start gap-6">
        <div class="self-stretch rounded-2xl bg-white border-gainsboro border-solid border-[1px] flex flex-col items-center justify-start">
            <div class="self-stretch rounded-t-2xl border-gainsboro border-solid border-b-[1px] border-t-[0px] border-l-[0px] border-r-[0px] flex flex-row items-start justify-start py-5 px-6">
                <div class="flex flex-col items-start justify-start">
                    <b class="relative leading-6">Biaya Tidak Langsung (Rp/US$)</b>
                </div>
            </div>
            <div class="self-stretch flex flex-col items-start justify-start p-6 gap-6 text-sm text-darkslategray-200">
                @foreach($biayaTidakLangsungFields as $key => $field)
                    <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                        <div class="relative leading-5 font-medium">{!! $field['label'] !!}</div>
                        <input
                            type="number"
                            name="biaya_tidak_langsung[{{ $key }}]"
                            value="{{ old('biaya_langsung.$key', $biayaTidakLangsung[$key] ?? 0) }}"
                            class="block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit"
                            min="0"
                            @if($readonly ?? false) readonly disabled @endif
                        >
                        @if (!empty($field['hint']))
                            <span class="self-stretch relative text-xs leading-[18px] text-darkslategray-100">{{ $field['hint'] }}</span>
                        @endif
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
</form>