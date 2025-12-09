@props([
    'lahan',
    'rencana_reklamasi',
    'tahun_aktif',
    'readonly',
])

@php
    $reklamasi = $rencana_reklamasi[$tahun_aktif] ?? null;

    $lahanYangDibukaFields = [
        'Area Penambangan' => [
            'Area Penambangan' => ['kegiatan' => 'area_penambangan', 'kategori' => 'lahan_dibuka', 'satuan' => 'ha'],
        ],
        'Area Diluar Penambangan' => [
            'Timbunan Tanah Zona Pengakaran' => ['kegiatan' => 'timbunan_tanah_pengakaran', 'kategori' => 'lahan_dibuka', 'satuan' => 'ha'],
            'Timbunan Batuan Samping dan/atau Tanah/Butuan Penutup' => ['kegiatan' => 'timbunan_batuan_samping', 'kategori' => 'lahan_dibuka', 'satuan' => 'ha'],
            'Timbunan Komoditas Tambang' => ['kegiatan' => 'timbunan_komoditas_tambang', 'kategori' => 'lahan_dibuka', 'satuan' => 'ha'],
            'Jalan Tambang dan/atau Jalan Angkut' => ['kegiatan' => 'jalan_tambang', 'kategori' => 'lahan_dibuka', 'satuan' => 'ha'],
            'Kolam Sedimen' => ['kegiatan' => 'kolam_sedimen', 'kategori' => 'lahan_dibuka', 'satuan' => 'ha'],
            'Instalasi dan Fasilitas Pengolahan dan/atau Pemurnian' => ['kegiatan' => 'fasilitas_pengolahan', 'kategori' => 'lahan_dibuka', 'satuan' => 'ha'],
            'Kantor dan Perumahan (comp atau flying camp)' => ['kegiatan' => 'kantor_perumahan', 'kategori' => 'lahan_dibuka', 'satuan' => 'ha'],
            'Bengkel' => ['kegiatan' => 'bengkel', 'kategori' => 'lahan_dibuka', 'satuan' => 'ha'],
            'Fasilitas Penunjang Lainnya' => ['kegiatan' => 'fasilitas_penunjang', 'kategori' => 'lahan_dibuka', 'satuan' => 'ha'],
        ]
    ];

    $penambanganFields = [
        'Lahan Selesai Ditambang (ha)' => ['kegiatan' => 'lahan_selesai_ditambang', 'kategori' => 'penambangan', 'satuan' => 'ha'],
        'Lahan/Front Aktif ditambang (ha)' => ['kegiatan' => 'lahan_aktif_ditambang', 'kategori' => 'penambangan', 'satuan' => 'ha'],
        'Volume Batuan Samping dan/atau Tanah/Batuan Penutup yang Digali (BCM atau m<sup>3</sup>)' => ['kegiatan' => 'volume_batuan_samping', 'kategori' => 'penambangan', 'satuan' => 'm3'],
    ];

    $penimbunanFields = [
        'Di Bekas Tambang (ha)' => ['kegiatan' => 'penimbunan_bekas_tambang', 'kategori' => 'penimbunan', 'satuan' => 'ha'],
        'Di Luar Bekas Tambang (ha)' => ['kegiatan' => 'penimbunan_diluar_bekas_tambang', 'kategori' => 'penimbunan', 'satuan' => 'ha'],
        'Volume yang Ditimbun di Bekas Tambang (m<sup>3</sup>)' => ['kegiatan' => 'volume_bekas_tambang', 'kategori' => 'penimbunan', 'satuan' => 'm3'],
        'Volume yang Ditimbun di Luar Bekas Tambang (m<sup>3</sup>)' => ['kegiatan' => 'volume_diluar_bekas_tambang', 'kategori' => 'penimbunan', 'satuan' => 'm3'],
    ];

    $reklamasiFields = [
        'Penatagunaan Lahan' => [
            'Penataan Lahan (ha)' => ['kegiatan' => 'penataan_tanah', 'kategori' => 'penatagunaan_lahan', 'satuan' => 'ha'],
            'Penebaran Tanah Zona Pengakaran (ha)' => ['kegiatan' => 'penebaran_tanah_pengakaran', 'kategori' => 'penatagunaan_lahan', 'satuan' => 'ha'],
            'Pengendalian Erosi dan Sedimentasi' => ['kegiatan' => 'pengendalian_erosi', 'kategori' => 'penatagunaan_lahan', 'satuan' => 'unit'],
        ],
        'Revegetasi (ha)' => [
            'Analisis Kualitas Tanah' => ['kegiatan' => 'kualitas_tanah', 'kategori' => 'revegetasi', 'satuan' => 'unit'],
            'Pemupukan (ha)' => ['kegiatan' => 'pemupukan', 'kategori' => 'revegetasi', 'satuan' => 'ha'],
            'Pengadaan Bibit (Batang/kg)' => ['kegiatan' => 'pengadaan_bibit', 'kategori' => 'revegetasi', 'satuan' => 'batang'],
            'Penanaman (Batang)' => ['kegiatan' => 'penanaman', 'kategori' => 'revegetasi', 'satuan' => 'batang'],
            'Pemeliharaan Tanaman (ha)' => ['kegiatan' => 'pemeliharaan_tanaman', 'kategori' => 'revegetasi', 'satuan' => 'ha'],
        ]
    ];

    $pencegahanAirAsamFields = [
        'Pencegahan dan Penanggulangan Air Asam Tambang' => ['kegiatan' => 'pencegahan_air_asam', 'kategori' => 'pencegahan_air_asam', 'satuan' => 'unit'],
    ];

    $pekerjaanSipilFields = [
        'Pekerjaan Sipil Sesuai Peruntukan Lahan Pascatambang/Program Reklamasi Bentuk Lain (Satuan Luas)' => ['kegiatan' => 'pekerjaan_sipil', 'kategori' => 'pekerjaan_sipil', 'satuan' => 'ha'],
    ];

    $rencanaPemanfaatanFields = [
        'Stabilisasi Lereng (ha)' => ['kegiatan' => 'stabilisasi_lereng', 'kategori' => 'rencana_pemanfaatan', 'satuan' => 'ha'],
        'Pengamanan Lubang Bekas Tambang (void) (ha)' => ['kegiatan' => 'pengamanan_lubang', 'kategori' => 'rencana_pemanfaatan', 'satuan' => 'ha'],
        'Pemulihan dan Pemantauan Kualitas Air dan Serta Pengolahan Air dalam Lubang Bekas Tambang (void) Sesuai dengan Peruntukannya' => ['kegiatan' => 'pemulihan_kualitas_air', 'kategori' => 'rencana_pemanfaatan', 'satuan' => 'unit'],
        'Pemeliharaan Lubang Bekas Tambang (void)' => ['kegiatan' => 'pemeliharaan_lubang', 'kategori' => 'rencana_pemanfaatan', 'satuan' => 'unit'],
    ];

    $isEdit = !is_null($reklamasi);
    
    $getExistingVolume = function($kegiatan) use ($reklamasi, $isEdit) {
        if (!$isEdit) {
            return '';
        }
        
        if (!$reklamasi || !$reklamasi->detailDataReklamasi) {
            return 0;
        }
        
        $detail = $reklamasi->detailDataReklamasi->where('kegiatan', $kegiatan)->first();
        return $detail ? $detail->volume : 0;
    };
@endphp

<form
    method="POST"
    action="{{ $isEdit
        ? route('lahan.rencana-reklamasi.update', [$lahan->lahan_id, $reklamasi->data_reklamasi_id])
        : route('lahan.rencana-reklamasi.store', $lahan->lahan_id)
    }}"
    class="grid grid-cols-1 lg:grid-cols-2 gap-6 w-full font-outfit"
>
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <input type="hidden" name="lahan_id" value="{{ $lahan->lahan_id }}">
    <input type="hidden" name="tahun" value="{{ $tahun_aktif }}">
    <input type="hidden" name="tipe" value="rencana">

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
                        @foreach($fields as $label => $items)
                            <div class="self-stretch flex flex-col items-start justify-start gap-1.5 text-darkslategray-200">
                                <div class="relative leading-5 font-medium">{!! $label !!}
                                    <span class="text-red-500">*</span>
                                </div>
                                <input
                                    type="number"
                                    step="0.01"
                                    name="detail[{{ $items['kegiatan'] }}][volume]"
                                    value="{{ old('detail.' . $items['kegiatan'] . '.volume', $getExistingVolume($items['kegiatan'])) }}"
                                    class="block w-full text-sm border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit"
                                    @if($readonly ?? false) readonly disabled @endif
                                    min="0"
                                    required
                                    oninvalid="this.setCustomValidity('{!! strip_tags($label) !!}' + ' harus diisi')"
                                    oninput="this.setCustomValidity('')"
                                >
                                <input type="hidden" name="detail[{{ $items['kegiatan'] }}][kegiatan]" value="{{ $items['kegiatan'] }}">
                                <input type="hidden" name="detail[{{ $items['kegiatan'] }}][kategori]" value="{{ $items['kategori'] }}">
                                <input type="hidden" name="detail[{{ $items['kegiatan'] }}][satuan]" value="{{ $items['satuan'] }}">
                                <x-main.input-error :messages="$errors->get('detail.' . $items['kegiatan'] . '.volume')" data-turbo-temporary />
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
                @foreach($penambanganFields as $label => $items)
                    <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                        <div class="relative leading-5 font-medium">{!! $label !!}
                            <span class="text-red-500">*</span>
                        </div>
                        <input
                            type="number"
                            step="0.01"
                            name="detail[{{ $items['kegiatan'] }}][volume]"
                            value="{{ old('detail.' . $items['kegiatan'] . '.volume', $getExistingVolume($items['kegiatan'])) }}"
                            class="block w-full text-sm border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit"
                            @if($readonly ?? false) readonly disabled @endif
                            min="0"
                            required
                            oninvalid="this.setCustomValidity('{!! strip_tags($label) !!}' + ' harus diisi')"
                            oninput="this.setCustomValidity('')"
                        >
                        <input type="hidden" name="detail[{{ $items['kegiatan'] }}][kegiatan]" value="{{ $items['kegiatan'] }}">
                        <input type="hidden" name="detail[{{ $items['kegiatan'] }}][kategori]" value="{{ $items['kategori'] }}">
                        <input type="hidden" name="detail[{{ $items['kegiatan'] }}][satuan]" value="{{ $items['satuan'] }}">
                        <x-main.input-error :messages="$errors->get('detail.' . $items['kegiatan'] . '.volume')" data-turbo-temporary />
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
                @foreach($penimbunanFields as $label => $items)
                    <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                        <div class="relative leading-5 font-medium">{!! $label !!}
                            <span class="text-red-500">*</span>
                        </div>
                        <input
                            type="number"
                            step="0.01"
                            name="detail[{{ $items['kegiatan'] }}][volume]"
                            value="{{ old('detail.' . $items['kegiatan'] . '.volume', $getExistingVolume($items['kegiatan'])) }}"
                            class="block w-full text-sm border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit"
                            @if($readonly ?? false) readonly disabled @endif
                            min="0"
                            required
                            oninvalid="this.setCustomValidity('{!! strip_tags($label) !!}' + ' harus diisi')"
                            oninput="this.setCustomValidity('')"
                    >
                        <input type="hidden" name="detail[{{ $items['kegiatan'] }}][kegiatan]" value="{{ $items['kegiatan'] }}">
                        <input type="hidden" name="detail[{{ $items['kegiatan'] }}][kategori]" value="{{ $items['kategori'] }}">
                        <input type="hidden" name="detail[{{ $items['kegiatan'] }}][satuan]" value="{{ $items['satuan'] }}">
                        <x-main.input-error :messages="$errors->get('detail.' . $items['kegiatan'] . '.volume')" data-turbo-temporary />
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- RIGHT COLUMN -->
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
                        @foreach($fields as $label => $items)
                            <div class="self-stretch flex flex-col items-start justify-start gap-1.5 text-darkslategray-200">
                                <div class="relative leading-5 font-medium">{!! $label !!}
                                    @if($label !== 'Analisis Kualitas Tanah')
                                        <span class="text-red-500">*</span>
                                    @endif
                                </div>
                                <input
                                    type="number"
                                    step="0.01"
                                    name="detail[{{ $items['kegiatan'] }}][volume]"
                                    value="{{ old('detail.' . $items['kegiatan'] . '.volume', $getExistingVolume($items['kegiatan'])) }}"
                                    class="block w-full text-sm border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit"
                                    @if($readonly ?? false) readonly disabled @endif
                                    min="0"
                                    @if($label !== 'Analisis Kualitas Tanah')
                                        required
                                        oninvalid="this.setCustomValidity('{!! strip_tags($label) !!}' + ' harus diisi')"
                                        oninput="this.setCustomValidity('')"
                                    @endif
                                >
                                <input type="hidden" name="detail[{{ $items['kegiatan'] }}][kegiatan]" value="{{ $items['kegiatan'] }}">
                                <input type="hidden" name="detail[{{ $items['kegiatan'] }}][kategori]" value="{{ $items['kategori'] }}">
                                <input type="hidden" name="detail[{{ $items['kegiatan'] }}][satuan]" value="{{ $items['satuan'] }}">
                                <x-main.input-error :messages="$errors->get('detail.' . $items['kegiatan'] . '.volume')" data-turbo-temporary />
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
        
        <div class="self-stretch rounded-2xl bg-white border-gainsboro border-solid border-[1px] flex flex-col items-center justify-start">
            <div class="self-stretch flex flex-col items-start justify-start p-6 gap-6 text-sm text-darkslategray-200">
                @foreach($pencegahanAirAsamFields as $label => $items)
                    <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                        <div class="relative leading-5 font-bold">
                            {!! $label !!}
                        </div>
                        <input
                            type="number"
                            step="0.01"
                            name="detail[{{ $items['kegiatan'] }}][volume]"
                            value="{{ old('detail.' . $items['kegiatan'] . '.volume', $getExistingVolume($items['kegiatan'])) }}"
                            class="block w-full text-sm border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit"
                            @if($readonly ?? false) readonly disabled @endif
                        >
                        <input type="hidden" name="detail[{{ $items['kegiatan'] }}][kegiatan]" value="{{ $items['kegiatan'] }}">
                        <input type="hidden" name="detail[{{ $items['kegiatan'] }}][kategori]" value="{{ $items['kategori'] }}">
                        <input type="hidden" name="detail[{{ $items['kegiatan'] }}][satuan]" value="{{ $items['satuan'] }}">
                        <x-main.input-error :messages="$errors->get('detail.' . $items['kegiatan'] . '.volume')" data-turbo-temporary />
                    </div>
                @endforeach
                
                @foreach($pekerjaanSipilFields as $label => $items)
                    <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                        <div class="relative leading-5 font-bold">{!! $label !!}
                            <span class="text-red-500">*</span>
                        </div>
                        <input
                            type="number"
                            step="0.01"
                            name="detail[{{ $items['kegiatan'] }}][volume]"
                            value="{{ old('detail.' . $items['kegiatan'] . '.volume', $getExistingVolume($items['kegiatan'])) }}"
                            class="block w-full text-sm border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit"
                            @if($readonly ?? false) readonly disabled @endif
                            min="0"
                            required
                            oninvalid="this.setCustomValidity('{!! strip_tags($label) !!}' + ' harus diisi')"
                            oninput="this.setCustomValidity('')"
                        >
                        <input type="hidden" name="detail[{{ $items['kegiatan'] }}][kegiatan]" value="{{ $items['kegiatan'] }}">
                        <input type="hidden" name="detail[{{ $items['kegiatan'] }}][kategori]" value="{{ $items['kategori'] }}">
                        <input type="hidden" name="detail[{{ $items['kegiatan'] }}][satuan]" value="{{ $items['satuan'] }}">
                        <x-main.input-error :messages="$errors->get('detail.' . $items['kegiatan'] . '.volume')" data-turbo-temporary />
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
                @foreach($rencanaPemanfaatanFields as $label => $items)
                    <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                        <div class="relative leading-5 font-medium">{!! $label !!}
                            <span class="text-red-500">*</span>
                        </div>
                        <input
                            type="number"
                            step="0.01"
                            name="detail[{{ $items['kegiatan'] }}][volume]"
                            value="{{ old('detail.' . $items['kegiatan'] . '.volume', $getExistingVolume($items['kegiatan'])) }}"
                            class="block w-full text-sm border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit"
                            @if($readonly ?? false) readonly disabled @endif
                            min="0"
                            required
                            oninvalid="this.setCustomValidity('{!! strip_tags($label) !!}' + ' harus diisi')"
                            oninput="this.setCustomValidity('')"
                        >
                        <input type="hidden" name="detail[{{ $items['kegiatan'] }}][kegiatan]" value="{{ $items['kegiatan'] }}">
                        <input type="hidden" name="detail[{{ $items['kegiatan'] }}][kategori]" value="{{ $items['kategori'] }}">
                        <input type="hidden" name="detail[{{ $items['kegiatan'] }}][satuan]" value="{{ $items['satuan'] }}">
                        <x-main.input-error :messages="$errors->get('detail.' . $items['kegiatan'] . '.volume')" data-turbo-temporary />
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