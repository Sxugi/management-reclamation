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
                <x-main.input-label class="relative leading-5 font-medium">Quarter</x-main.input-label>
                <select 
                    name="quarter" 
                    id="quarter_select"
                    class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit flex-1 leading-5 bg-transparent"
                    required
                >
                    <option value="">Pilih Quarter</option>
                    @foreach($quarterList as $q)
                        <option value="{{ $q }}" {{ old('quarter', $anggaran?->quarter ?? '') == $q ? 'selected' : '' }}>
                            {{ $q }}
                        </option>
                    @endforeach
                </select>
                <x-main.input-error :messages="$errors->get('quarter')" data-turbo-temporary class="mt-2" />
            </div>

            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">Jenis Anggaran</x-main.input-label>
                <select 
                    name="jenis_anggaran"
                    id="jenis_anggaran_select"
                    class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit flex-1 leading-5 bg-transparent"
                    required
                >
                    <option value="">Pilih Jenis Anggaran</option>
                    <option value="actual" {{ old('jenis_anggaran', $anggaran?->jenis_anggaran ?? '') == 'actual' ? 'selected' : '' }}>Actual Cost</option>
                    <option value="projection" {{ old('jenis_anggaran', $anggaran?->jenis_anggaran ?? '') == 'projection' ? 'selected' : '' }}>Projection Cost</option>
                    <option value="forecast" {{ old('jenis_anggaran', $anggaran?->jenis_anggaran ?? '') == 'forecast' ? 'selected' : '' }}>Forecast Cost</option>
                </select>
                <x-main.input-error :messages="$errors->get('jenis_anggaran')" data-turbo-temporary class="mt-2" />
            </div>

            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">Kategori Anggaran</x-main.input-label>
                <div class="flex gap-3 w-full">
                    <select 
                        name="kategori_anggaran"
                        id="kategori_anggaran_select"
                        class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit flex-1 leading-5 bg-transparent"
                        required
                    >
                        <option value="">Pilih Kategori Anggaran</option>
                        @foreach($kategoriAnggaranList as $kategori)
                            <option value="{{ $kategori }}" {{ old('kategori_anggaran', $anggaran?->kategori_anggaran ?? '') == $kategori ? 'selected' : '' }}>
                                {{ $kategori }}
                            </option>
                        @endforeach
                    </select>
                    <div class="flex flex-row items-center justify-between gap-2 text-white">
                        <x-main.primary-button type="button" onclick="showInputKategoriAnggaranBaru()" class="gap-2 text-xs">
                            <span class="relative leading-5 font-normal">Tambah Kategori</span>
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M10 4.24951C10.4142 4.24951 10.75 4.58534 10.75 4.99951V9.24951H15.001L15.0771 9.25342C15.4553 9.29177 15.7508 9.61128 15.751 9.99951C15.751 10.3879 15.4554 10.7072 15.0771 10.7456L15.001 10.7495H10.75V15.0005L10.7461 15.0767C10.7077 15.4549 10.3884 15.7505 10 15.7505C9.61173 15.7504 9.29227 15.4548 9.25391 15.0767L9.25 15.0005V10.7495H5C4.58579 10.7495 4.25 10.4137 4.25 9.99951C4.25015 9.58543 4.58588 9.24951 5 9.24951H9.25V4.99951C9.25004 4.5854 9.58591 4.24962 10 4.24951Z" fill="white"/></svg>
                        </x-main.primary-button>
                    </div>
                </div>
                <div class="mt-2 hidden flex flex-row gap-1" id="input-kategori-anggaran-baru-wrapper">
                    <x-main.text-input type="text" id="input-kategori-anggaran-baru" class="flex-1 text-sm leading-5 bg-transparent" placeholder="Kategori anggaran baru" />
                    <button type="button" onclick="addKategoriAnggaranBaru()" class="bg-green-500 !text-white text-sm px-4 rounded-lg font-medium hover:bg-green-600 transition-colors no-underline border-none text-xs font-normal font-outfit">Tambah</button>
                    <button type="button" onclick="hideInputKategoriAnggaranBaru()" class="bg-red-500 !text-white text-sm px-4 rounded-lg font-medium hover:bg-red-600 transition-colors no-underline border-none text-xs font-normal font-outfit">Batal</button>
                </div>
                <x-main.input-error :messages="$errors->get('kategori_anggaran')" data-turbo-temporary class="mt-2" />
            </div>

            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">Tahun</x-main.input-label>
                <x-main.text-input 
                    type="number" 
                    name="tahun"
                    value="{{ old('tahun', $anggaran?->tahun) }}"
                    min="2000" max="2100"
                    placeholder="Masukkan tahun"
                    class="flex-1 leading-5 bg-transparent"
                    required
                />
                <x-main.input-error :messages="$errors->get('tahun')" data-turbo-temporary class="mt-2" />
            </div>
            @php
                $months = [
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                ];
            @endphp
            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">Bulan</x-main.input-label>
                <select 
                    name="bulan"
                    id="bulan_select"
                    class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit flex-1 leading-5 bg-transparent"
                    required
                >
                    <option value="">Pilih Bulan</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ old('bulan', $anggaran?->bulan ?? '') == $i ? 'selected' : '' }}>
                                {{ $months[$i] }}
                            </option>
                        @endfor
                </select>
                <x-main.input-error :messages="$errors->get('bulan')" data-turbo-temporary class="mt-2" />
            </div>

            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">Nominal</x-main.input-label>
                <x-main.text-input 
                    type="text" 
                    id="nominal-display"
                    placeholder="Rp. 0"
                    class="flex-1 leading-5 bg-transparent"
                    inputmode="numeric"
                    autocomplete="off"
                />

                <input 
                    type="hidden" 
                    name="nominal"
                    id="nominal-input"
                    value="{{ old('nominal', $anggaran?->nominal) }}"
                    required
                />
                <x-main.input-error :messages="$errors->get('nominal')" data-turbo-temporary class="mt-2" />
            </div>

            <div class="self-stretch flex flex-row items-center justify-end gap-3">
                <a href="{{ route('lahan.anggaran.index', $lahan) }}" 
                   class="bg-red-500 !text-white text-sm py-3 px-4 rounded-lg font-medium hover:bg-red-600 transition-colors no-underline">
                    Cancel
                </a>
                <x-main.primary-button type="submit" class="py-3 px-4 gap-2 font-medium">
                    {{ $anggaran ? 'Update' : 'Save' }}
                </x-main.primary-button>
            </div>
        </div>
    </div>
</form>

<script>
    function showInputKategoriAnggaranBaru() {
        document.getElementById('input-kategori-anggaran-baru-wrapper').classList.remove('hidden');
    }
    function hideInputKategoriAnggaranBaru() {
        document.getElementById('input-kategori-anggaran-baru-wrapper').classList.add('hidden');
        document.getElementById('input-kategori-anggaran-baru').value = '';
    }
    function addKategoriAnggaranBaru() {
        let val = document.getElementById('input-kategori-anggaran-baru').value.trim();
        if (!val) return;
        let select = document.getElementById('kategori_anggaran_select');
        let option = document.createElement('option');
        option.value = val;
        option.text = val;
        option.selected = true;
        select.add(option);
        hideInputKategoriAnggaranBaru();
    }

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

        // Handle focus
        displayInput.addEventListener('focus', function(e) {
            setTimeout(() => {
                e.target.setSelectionRange(e.target.value.length, e.target.value.length);
            }, 10);
        });

        // Validate hidden input on form submit
        let form = hiddenInput.closest('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                if (!hiddenInput.value || isNaN(parseFloat(hiddenInput.value))) {
                    e.preventDefault();
                    displayInput.focus();
                    return false;
                }
            });
        }
    }

    document.addEventListener('DOMContentLoaded', initializeNominalInput);
    document.addEventListener('turbo:load', initializeNominalInput);
</script>