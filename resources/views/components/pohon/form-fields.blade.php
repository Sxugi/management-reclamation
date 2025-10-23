@props(['lahan', 'pohon', 'dataPohon' => null, 'jenisPohonList' => []])

<form method="POST" action="{{ $dataPohon ? route('lahan.pohon.update', [$lahan, $pohon, $dataPohon]) : route('lahan.pohon.store', $lahan) }}">
    @csrf
    @if($dataPohon)
        @method('PUT')
    @endif

    <div class="w-full relative rounded-2xl bg-white border-gainsboro border-solid border-[1px] box-border flex flex-col items-center justify-start text-left text-base text-gray font-outfit">
        <div class="self-stretch border-gainsboro border-solid border-b-[1px] border-[0px] flex flex-row items-start justify-start py-5 px-6">
            <div class="flex flex-col items-start justify-start">
                <div class="relative leading-6 font-medium">Informasi Pohon</div>
            </div>
        </div>
        
        <div class="self-stretch flex flex-col items-start justify-start p-6 gap-6 text-sm text-darkslategray-200">

            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">Jenis Pohon</x-main.input-label>
                <div class="flex gap-3 w-full">
                    <select 
                        id="jenis_pohon_select"
                        name="jenis_pohon"
                        class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md px-3 py-2 box-border font-outfit flex-1 leading-5 bg-transparent"
                        required
                    >
                        <option value="">Pilih Jenis Pohon</option>
                        @foreach($jenisPohonList as $jenis)
                            <option value="{{ $jenis }}" 
                                {{ old('jenis_pohon', $dataPohon?->pohon->jenis_pohon ?? '') == $jenis ? 'selected' : '' }}>
                                {{ $jenis }}
                            </option>
                        @endforeach
                    </select>
                    <div class="flex flex-row items-center justify-between gap-2 text-white">
                        <x-main.primary-button type="button" onclick="showInputJenisBaru()" class="gap-2 text-xs">
                            <span class="relative leading-5 font-normal">Tambah Jenis Pohon</span>
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 4.24951C10.4142 4.24951 10.75 4.58534 10.75 4.99951V9.24951H15.001L15.0771 9.25342C15.4553 9.29177 15.7508 9.61128 15.751 9.99951C15.751 10.3879 15.4554 10.7072 15.0771 10.7456L15.001 10.7495H10.75V15.0005L10.7461 15.0767C10.7077 15.4549 10.3884 15.7505 10 15.7505C9.61173 15.7504 9.29227 15.4548 9.25391 15.0767L9.25 15.0005V10.7495H5C4.58579 10.7495 4.25 10.4137 4.25 9.99951C4.25015 9.58543 4.58588 9.24951 5 9.24951H9.25V4.99951C9.25004 4.5854 9.58591 4.24962 10 4.24951Z" fill="white"/>
                            </svg>
                        </x-main.primary-button>
                    </div>  
                </div>
                <div class="mt-2 hidden flex flex-row gap-1" id="input-jenis-baru-wrapper">
                    <x-main.text-input type="text" id="input-jenis-baru" class="flex-1 text-sm leading-5 bg-transparent" placeholder="Jenis pohon baru" />
                    <button type="button" onclick="addJenisBaru()" class="bg-green-500 !text-white text-sm px-4 rounded-lg font-medium hover:bg-green-600 transition-colors no-underline border-none text-xs font-normal font-outfit">Tambah</button>
                    <button type="button" onclick="hideInputJenisBaru()" class="bg-red-500 !text-white text-sm px-4 rounded-lg font-medium hover:bg-red-600 transition-colors no-underline border-none text-xs font-normal font-outfit">Batal</button>
                </div>
                <x-main.input-error :messages="$errors->get('jenis_pohon')" data-turbo-temporary class="mt-2" />
            </div>

            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">Tahun</x-main.input-label>
                <x-main.text-input 
                    type="number" 
                    name="tahun"
                    value="{{ old('tahun', $dataPohon?->tahun) }}"
                    min="1900" max="2100"
                    placeholder="Masukkan tahun"
                    class="flex-1 leading-5 bg-transparent text-sm"
                    required
                />
                <x-main.input-error :messages="$errors->get('tahun')" data-turbo-temporary class="mt-2" />
            </div>

            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">Jumlah</x-main.input-label>
                <x-main.text-input 
                    type="number" 
                    name="jumlah"
                    value="{{ old('jumlah', $dataPohon?->jumlah) }}"
                    min="1"
                    placeholder="Masukkan jumlah pohon"
                    class="flex-1 leading-5 bg-transparent text-sm"
                    required
                />
                <x-main.input-error :messages="$errors->get('jumlah')" data-turbo-temporary class="mt-2" />
            </div>

            <div class="self-stretch flex flex-row items-center justify-end gap-3">
                <a href="{{ route('lahan.pohon.index', $lahan) }}" 
                   class="bg-red-500 !text-white text-sm py-3 px-4 rounded-lg font-semibold hover:bg-red-600 transition-colors no-underline">
                    Cancel
                </a>
                <x-main.primary-button type="submit" class="py-3 px-4 gap-2">
                    {{ $dataPohon ? 'Update' : 'Save' }}
                </x-main.primary-button>
            </div>
        </div>
    </div>
</form>

<script>
function showInputJenisBaru() {
    document.getElementById('input-jenis-baru-wrapper').classList.remove('hidden');
}
function hideInputJenisBaru() {
    document.getElementById('input-jenis-baru-wrapper').classList.add('hidden');
    document.getElementById('input-jenis-baru').value = '';
}
function addJenisBaru() {
    let val = document.getElementById('input-jenis-baru').value.trim();
    if (!val) return;
    let select = document.getElementById('jenis_pohon_select');
    let option = document.createElement('option');
    option.value = val;
    option.text = val;
    option.selected = true;
    select.add(option);
    hideInputJenisBaru();
}
</script>