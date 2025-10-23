@props([
    'lahan',
    'rencana_biaya',
    'tahun_aktif',
    'readonly',
    'currency',
])

@php
    $biaya = $rencana_biaya[$tahun_aktif] ?? null;

    $biayaLangsungFields = [
        'Biaya Penatagunaan Lahan' => [
            'penataan_tanah' => 'Penataan Permukaan Tanah',
            'penebaran_tanah_pengakaran' => 'Penebaran Tanah Zona Pengakaran',
            'pengendalian_erosi' => 'Pengendalian Erosi dan Sedimentasi',
        ],
        'Biaya Revegetasi' => [
            'kualitas_tanah' => 'Analisis Kualitas Tanah',
            'pemupukan' => 'Pemupukan',
            'pengadaan_bibit' => 'Pengadaan Bibit',
            'penanaman' => 'Penanaman',
            'pemeliharaan_tanaman' => 'Pemeliharaan Tanaman',
        ],
        'lain' => [
            'pencegahan_air_asam' => 'Biaya Pencegahan dan Penanggulangan Air Asam Tambang (apabila ada)',
            'pekerjaan_sipil' => 'Biaya untuk Pekerjaan Sipil Sesuai Peruntukan Lahan Pascatambang atau Program Reklamasi Bentuk Lain',
        ],
        'Biaya Pemanfaatan Lubang Bekas Tambang (void)' => [
            'stabilisasi_lereng' => 'Stabilitas Lereng',
            'pengamanan_lubang' => 'Pengamanan Lubang Bekas Tambang (void)',
            'pemulihan_kualitas_air' => 'Pemulihan dan Pemantauan Kualitas Air Serta Pengelolaan Air Dalam Lubang Bekas Tambang (void) Sesuai dengan Peruntukannya',
            'pemeliharaan_lubang' => 'Pemeliharaan Lubang Bekas Tambang (void)',
        ],
        'sub_total' => [
            'subtotal_1' => 'SUBTOTAL 1 (Rp/US$)'
        ],
    ];

    $biayaTidakLangsungFields = [
        'mobilisasi_demobilisasi_alat' => [
            'label' => 'Biaya Mobilisasi dan Demobilisasi Alat',
            'hint' => 'besarnya 2,5% dari biaya langsung atau berdasarkan perhitungan'
        ],
        'perencanaan_reklamasi' => [
            'label' => 'Biaya Perencanaan Reklamasi',
            'hint' => 'besarnya 2% - 10% dari biaya langsung (grafik Englemens Heavy Construction Cost File)'
        ],
        'administrasi_pihak_ketiga' => [
            'label' => 'Biaya Administrasi dan Keuntungan Pihak Ketiga Sebagai Pelaksana Reklamasi Tahap Eksplorasi',
            'hint' => 'besarnya 3% - 14% dari biaya langsung (grafik Englemens Heavy Construction Cost File)'
        ],
        'supervisi' => [
            'label' => 'Biaya Supervisi',
            'hint' => 'besarnya 2% - 7% dari biaya langsung (grafik Englemens Heavy Construction Cost File)'
        ],
        'subtotal_2' => [
            'label' => 'SUBTOTAL 2 (Rp/US$)',
            'hint' => ''
        ],
    ];

    $isEdit = !is_null($biaya);
    $details = $biaya?->detailBiayaReklamasi ?? collect();

    $getExistingBiaya = function($kegiatan) use ($details) {
        $item = $details->firstWhere('kegiatan', $kegiatan);
        return $item ? $item->biaya : '';
    };
@endphp

<form
    method="POST"
    action="{{ $isEdit
        ? route('lahan.rencana-biaya.update', [$lahan->lahan_id, $biaya->biaya_reklamasi_id])
        : route('lahan.rencana-biaya.store', $lahan->lahan_id)
    }}"
    class="grid grid-cols-1 lg:grid-cols-2 gap-6 w-full font-outfit"
    data-currency="{{ $currency }}"
>
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    @php
        $currency = request('currency') ?: 'default';
        $currencyLabel = $currency === 'USD' ? 'US$' : ($currency === 'IDR' ? 'Rp' : 'Rp/US$');
    @endphp

    <input type="hidden" name="lahan_id" value="{{ $lahan->lahan_id }}">
    <input type="hidden" name="tahun" value="{{ $tahun_aktif }}">
    <input type="hidden" name="tipe" value="rencana">
    <input type="hidden" name="currency" id="currencyHidden" value="{{ $currency }}">

    <!-- LEFT COLUMN: Biaya Langsung -->
    <div class="flex flex-col items-start justify-start gap-6">
        <div class="self-stretch rounded-2xl bg-white border-gainsboro border-solid border-[1px] flex flex-col items-center justify-start">
            <div class="self-stretch rounded-t-2xl border-gainsboro border-solid border-b-[1px] border-t-[0px] border-l-[0px] border-r-[0px] flex flex-row items-start justify-start py-5 px-6">
                <div class="flex flex-col items-start justify-start">
                    <b class="relative leading-6">Biaya Langsung (<span id="currencyLabelLangsung">{{ $currencyLabel }}</span>)</b>
                </div>
            </div>
            <div class="self-stretch flex flex-col items-start justify-start p-6 gap-6 text-sm text-darkslategray-200">
                @foreach ($biayaLangsungFields as $section => $fields)
                    @if ($section === 'lain')
                        @foreach($fields as $key => $label)
                            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                                <div class="relative leading-5 font-medium">{!! $label !!}</div>
                                <input
                                    type="text"
                                    id="biaya-display-{{ $key }}"
                                    class="block w-full text-sm border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit"
                                    placeholder="{{ $currencyLabel }}"
                                    autocomplete="off"
                                    inputmode="numeric"
                                    @if($readonly ?? false) readonly disabled @endif
                                >
                                <input
                                    type="hidden"
                                    name="detail[{{ $key }}][biaya]"
                                    id="biaya-input-{{ $key }}"
                                    value="{{ old('detail.' . $key . '.biaya', $getExistingBiaya($key)) }}"
                                    required
                                >
                                <input type="hidden" name="detail[{{ $key }}][kegiatan]" value="{{ $key }}">
                                <input type="hidden" name="detail[{{ $key }}][kategori]" value="biaya_langsung">
                                <x-main.input-error :messages="$errors->get('detail.' . $key . '.biaya')" data-turbo-temporary class="mt-2" />
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
                                    type="text"
                                    id="subtotal-display-{{ $key }}"
                                    class="block w-full text-sm border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit"
                                    placeholder="{{ $currencyLabel }}"
                                    autocomplete="off"
                                    inputmode="numeric"
                                    @if($readonly ?? false) readonly disabled @endif
                                >
                                <input
                                    type="hidden"
                                    name="subtotal_1"
                                    id="subtotal-input-{{ $key }}"
                                    value="{{ old('subtotal_1', $biaya->subtotal_1 ?? '') }}"
                                    required
                                >
                                <x-main.input-error :messages="$errors->get('subtotal_1')" data-turbo-temporary class="mt-2" />
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
                                        type="text"
                                        id="biaya-display-{{ $key }}"
                                        class="block w-full text-sm border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit"
                                        placeholder="{{ $currencyLabel }}"
                                        autocomplete="off"
                                        inputmode="numeric"
                                        @if($readonly ?? false) readonly disabled @endif
                                    >
                                    <input
                                        type="hidden"
                                        name="detail[{{ $key }}][biaya]"
                                        id="biaya-input-{{ $key }}"
                                        value="{{ old('detail.' . $key . '.biaya', $getExistingBiaya($key)) }}"
                                        required
                                    >
                                    <input type="hidden" name="detail[{{ $key }}][kegiatan]" value="{{ $key }}">
                                    <input type="hidden" name="detail[{{ $key }}][kategori]" value="biaya_langsung">
                                    <x-main.input-error :messages="$errors->get('detail.' . $key . '.biaya')" data-turbo-temporary class="mt-2" />
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
                    <b class="relative leading-6">Biaya Tidak Langsung (<span id="currencyLabelTidakLangsung">{{ $currencyLabel }}</span>)</b>
                </div>
            </div>
            <div class="self-stretch flex flex-col items-start justify-start p-6 gap-6 text-sm text-darkslategray-200">
                @foreach($biayaTidakLangsungFields as $key => $field)
                    @if ($key === 'subtotal_2')
                        <div class="self-stretch flex flex-col items-start justify-start gap-1.5 text-gray">
                            <div class="self-stretch border-gainsboro border-solid border-b-[1px] border-t-[0px] border-l-[0px] border-r-[0px] flex flex-row items-start justify-center py-0 px-6">
                                <div class="flex flex-col items-start justify-start">
                                    <b class="relative leading-6">SUBTOTAL 2 (Rp/US$)</b>
                                </div>
                            </div>
                            <input
                                type="text"
                                id="subtotal-display-{{ $key }}"
                                class="block w-full text-sm border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit"
                                placeholder="{{ $currencyLabel }}"
                                autocomplete="off"
                                inputmode="numeric"
                                @if($readonly ?? false) readonly disabled @endif
                            >
                            <input
                                type="hidden"
                                name="subtotal_2"
                                id="subtotal-input-{{ $key }}"
                                value="{{ old('subtotal_2', $biaya->subtotal_2 ?? '') }}"
                                required
                            >
                            <x-main.input-error :messages="$errors->get('subtotal_2')" data-turbo-temporary class="mt-2" />
                        </div>
                    @else
                        <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                            <div class="relative leading-5 font-medium">{!! $field['label'] !!}</div>
                            <input
                                type="text"
                                id="biaya-tidaklang-display-{{ $key }}"
                                class="block w-full text-sm border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit"
                                placeholder="{{ $currencyLabel }}"
                                autocomplete="off"
                                inputmode="numeric"
                                @if($readonly ?? false) readonly disabled @endif
                            >
                            <input
                                type="hidden"
                                name="detail[{{ $key }}][biaya]"
                                id="biaya-tidaklang-input-{{ $key }}"
                                value="{{ old('detail.' . $key . '.biaya', $getExistingBiaya($key)) }}"
                                required
                            >
                            <input type="hidden" name="detail[{{ $key }}][kegiatan]" value="{{ $key }}">
                            <input type="hidden" name="detail[{{ $key }}][kategori]" value="biaya_tidak_langsung">
                            @if (!empty($field['hint']))
                                <span class="self-stretch relative text-xs leading-[18px] text-darkslategray-100">{{ $field['hint'] }}</span>
                            @endif
                            <x-main.input-error :messages="$errors->get('detail.' . $key . '.biaya')" data-turbo-temporary class="mt-2" />
                        </div>
                    @endif
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