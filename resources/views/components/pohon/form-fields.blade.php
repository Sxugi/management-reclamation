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
                <x-main.input-label class="relative leading-5 font-medium">Jenis Pohon
                    <span class="text-red-500">*</span>
                </x-main.input-label>
                <div class="flex gap-3 w-full">
                    <select 
                        id="jenis_pohon_select"
                        name="jenis_pohon_id"
                        required oninvalid="this.setCustomValidity('Jenis pohon harus diisi')" 
                        oninput="this.setCustomValidity('')"
                        class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md px-3 py-2 box-border font-outfit flex-1 leading-5 bg-transparent"
                    >
                        <option value="">Pilih Jenis Pohon</option>
                        @foreach($jenisPohonList as $item)
                            <option value="{{ $item->jenis_pohon_id }}" 
                                {{ old('jenis_pohon_id', $pohon->jenis_pohon_id ?? '') == $item->jenis_pohon_id ? 'selected' : '' }}>
                                {{ $item->nama_pohon }}
                            </option>
                        @endforeach
                    </select> 
                </div>
                <x-main.input-error :messages="$errors->get('jenis_pohon_id')" data-turbo-temporary />
            </div>

            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">Tahun
                    <span class="text-red-500">*</span>
                </x-main.input-label>
                <x-main.text-input 
                    type="number" 
                    name="tahun"
                    value="{{ old('tahun', $dataPohon?->tahun) }}"
                    min="1900" max="2100"
                    placeholder="Masukkan tahun"
                    class="flex-1 leading-5 bg-transparent text-sm"
                    required
                    oninvalid="this.setCustomValidity('Tahun harus diisi')"
                    oninput="this.setCustomValidity('')"
                />
                <x-main.input-error :messages="$errors->get('tahun')" data-turbo-temporary />
            </div>

            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">Jumlah
                    <span class="text-red-500">*</span>
                </x-main.input-label>
                <x-main.text-input 
                    type="number" 
                    name="jumlah"
                    value="{{ old('jumlah', $dataPohon?->jumlah) }}"
                    min="1"
                    placeholder="Masukkan jumlah pohon"
                    class="flex-1 leading-5 bg-transparent text-sm"
                    required
                    oninvalid="this.setCustomValidity('Jumlah harus diisi')"
                    oninput="this.setCustomValidity('')"
                />
                <x-main.input-error :messages="$errors->get('jumlah')" data-turbo-temporary />
            </div>

            <div class="self-stretch flex flex-row items-center justify-end gap-3">
                <a href="{{ route('lahan.pohon.index', $lahan) }}" 
                   class="bg-red-500 !text-white text-sm py-3 px-4 rounded-lg font-semibold hover:bg-red-600 transition-colors no-underline cursor-pointer">
                    Cancel
                </a>
                <x-main.primary-button type="submit" class="py-3 px-4 gap-2">
                    {{ $dataPohon ? 'Update' : 'Save' }}
                </x-main.primary-button>
            </div>
        </div>
    </div>
</form>