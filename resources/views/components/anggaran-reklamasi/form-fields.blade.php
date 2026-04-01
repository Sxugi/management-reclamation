@props(['lahan', 'anggaran' => null, 'kategoriAnggaranList' => [], 'quarterList' => ['Q1', 'Q2', 'Q3', 'Q4']])

<form method="POST" action="{{ $anggaran ? route('lahan.anggaran.update', [$lahan, $anggaran]) : route('lahan.anggaran.store', $lahan) }}">
    @csrf
    @if($anggaran)
        @method('PUT')
    @endif

    <div class="w-full relative rounded-2xl bg-white border-gainsboro border-solid border-[1px] box-border flex flex-col items-center justify-start text-left text-base text-gray font-outfit">
        <div class="self-stretch border-gainsboro border-solid border-b-[1px] border-[0px] flex flex-row items-start justify-start py-5 px-6">
            <div class="flex flex-col items-start justify-start">
                <div class="relative leading-6 font-medium">Form Anggaran Reklamasi</div>
            </div>
        </div>
        
        <div class="self-stretch flex flex-col items-start justify-start p-6 gap-6 text-sm text-darkslategray-200">

            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">Quarter
                    <span class="text-red-500">*</span>
                </x-main.input-label>
                <select 
                    name="quarter" 
                    id="quarter_select"
                    required
                    oninvalid="this.setCustomValidity('Silakan pilih quarter.')"
                    oninput="this.setCustomValidity('')"
                    class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md px-3 py-2 box-border font-outfit flex-1 leading-5 bg-transparent"
                >
                    <option value="">Pilih Quarter</option>
                    @foreach($quarterList as $q)
                        <option value="{{ $q }}" {{ old('quarter', $anggaran?->quarter ?? '') == $q ? 'selected' : '' }}>
                            {{ $q }}
                        </option>
                    @endforeach
                </select>
                <x-main.input-error :messages="$errors->get('quarter')" data-turbo-temporary />
            </div>

            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">Jenis Anggaran
                    <span class="text-red-500">*</span>
                </x-main.input-label>
                <select 
                    name="jenis_anggaran"
                    id="jenis_anggaran_select"
                    required
                    oninvalid="this.setCustomValidity('Silakan pilih jenis anggaran.')"
                    oninput="this.setCustomValidity('')"
                    class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md px-3 py-2 box-border font-outfit flex-1 leading-5 bg-transparent"
                >
                    <option value="">Pilih Jenis Anggaran</option>
                    <option value="actual" {{ old('jenis_anggaran', $anggaran?->jenis_anggaran ?? '') == 'actual' ? 'selected' : '' }}>Actual Cost</option>
                    <option value="projection" {{ old('jenis_anggaran', $anggaran?->jenis_anggaran ?? '') == 'projection' ? 'selected' : '' }}>Projection Cost</option>
                    <option value="forecast" {{ old('jenis_anggaran', $anggaran?->jenis_anggaran ?? '') == 'forecast' ? 'selected' : '' }}>Forecast Cost</option>
                </select>
                <x-main.input-error :messages="$errors->get('jenis_anggaran')" data-turbo-temporary />
            </div>

            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">Kategori Anggaran
                    <span class="text-red-500">*</span>
                </x-main.input-label>
                <div class="flex gap-3 w-full">
                    <select 
                        name="kategori_anggaran_id"
                        id="kategori_anggaran_select"
                        required
                        oninvalid="this.setCustomValidity('Kategori anggaran harus diisi.')"
                        oninput="this.setCustomValidity('')"
                        class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md px-3 py-2 box-border font-outfit flex-1 leading-5 bg-transparent"
                    >
                        <option value="">Pilih Kategori Anggaran</option>
                        @foreach($kategoriAnggaranList as $item)
                            <option value="{{ $item->kategori_anggaran_id }}" 
                                {{ old('kategori_anggaran', $anggaran->kategori_anggaran_id ?? '') == $item->kategori_anggaran_id ? 'selected' : '' }}>
                                {{ $item->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <x-main.input-error :messages="$errors->get('kategori_anggaran_id')" data-turbo-temporary />
            </div>

            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">Tahun
                    <span class="text-red-500">*</span>
                </x-main.input-label>
                <x-main.text-input 
                    type="number" 
                    name="tahun"
                    value="{{ old('tahun', $anggaran?->tahun) }}"
                    min="2000" max="2100"
                    placeholder="Masukkan tahun"
                    class="flex-1 leading-5 bg-transparent text-sm"
                    required
                    oninvalid="this.setCustomValidity('Tahun harus diisi')"
                    oninput="this.setCustomValidity('')"
                />
                <x-main.input-error :messages="$errors->get('tahun')" data-turbo-temporary />
            </div>
            @php
                $months = [
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                ];
            @endphp
            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">Bulan
                    <span class="text-red-500">*</span>
                </x-main.input-label>
                <select 
                    name="bulan"
                    id="bulan_select"
                    required
                    oninvalid="this.setCustomValidity('Silakan pilih bulan.')"
                    oninput="this.setCustomValidity('')"
                    class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md px-3 py-2 box-border font-outfit flex-1 leading-5 bg-transparent"
                >
                    <option value="">Pilih Bulan</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ old('bulan', $anggaran?->bulan ?? '') == $i ? 'selected' : '' }}>
                                {{ $months[$i] }}
                            </option>
                        @endfor
                </select>
                <x-main.input-error :messages="$errors->get('bulan')" data-turbo-temporary />
            </div>

            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">Nominal
                    <span class="text-red-500">*</span>
                </x-main.input-label>
                <x-main.text-input 
                    type="text" 
                    id="nominal-display"
                    placeholder="Rp. 0"
                    class="flex-1 leading-5 bg-transparent"
                    inputmode="numeric"
                    autocomplete="off"
                    required
                    oninvalid="this.setCustomValidity('Nominal harus diisi')"
                    oninput="this.setCustomValidity('')"
                />

                <input 
                    type="hidden" 
                    name="nominal"
                    id="nominal-input"
                    value="{{ old('nominal', $anggaran?->nominal) }}"
                />
                <x-main.input-error :messages="$errors->get('nominal')" data-turbo-temporary />
            </div>

            <div class="self-stretch flex flex-row items-center justify-end gap-3">
                <a href="{{ route('lahan.anggaran.index', $lahan) }}" 
                   class="bg-red-500 !text-white text-sm py-3 px-4 rounded-lg font-semibold hover:bg-red-600 transition-colors no-underline">
                    Cancel
                </a>
                <x-main.primary-button type="submit" class="py-3 px-4">
                    {{ $anggaran ? 'Update' : 'Save' }}
                </x-main.primary-button>
            </div>
        </div>
    </div>
</form>

<script>
    function formatRupiah(number) {
        if (!number) return '';
        // Format with Indonesian locale, preserving exact decimal places
        return 'Rp. ' + parseFloat(number).toLocaleString('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 3 // Allow up to 3 decimal places for exact numbers
        });
    }

    function parseRupiah(formatted) {
        if (!formatted) return '';
        // Remove all non-numeric characters except decimal separator
        return formatted.replace(/[^\d,]/g, '').replace(',', '.');
    }

    function initializeNominalInput() {
        let displayInput = document.getElementById('nominal-display');
        let hiddenInput = document.getElementById('nominal-input');
        
        if (!displayInput || !hiddenInput) return;

        // Set initial formatted value
        let initialValue = hiddenInput.value;
        if (initialValue) {
            displayInput.value = formatRupiah(initialValue);
        }

        // Handle input formatting
        displayInput.addEventListener('input', function(e) {
            let rawValue = e.target.value;
            let numericValue = parseRupiah(rawValue);
            
            if (numericValue && !isNaN(parseFloat(numericValue))) {
                e.target.value = formatRupiah(numericValue);
                hiddenInput.value = numericValue; // Store exact decimal value
            } else {
                e.target.value = rawValue.startsWith('Rp.') ? rawValue : '';
                hiddenInput.value = '';
            }
        });

        displayInput.addEventListener('focus', function(e) {
            setTimeout(() => {
                e.target.setSelectionRange(e.target.value.length, e.target.value.length);
            }, 10);
        });
    }

    document.addEventListener('DOMContentLoaded', initializeNominalInput);
    document.addEventListener('turbo:load', initializeNominalInput);
</script>