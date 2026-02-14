@props(['lahan', 'pohon' => null, 'dataPohon' => null, 'jenisPohonList' => []])

<form method="POST" action="{{ $dataPohon ? route('lahan.pohon.update', [$lahan, $pohon, $dataPohon]) : route('lahan.pohon.store', $lahan) }}">
    @csrf
    @if($dataPohon)
        @method('PUT')
    @endif

    <div class="w-full relative rounded-2xl bg-white border-gainsboro border-solid border-[1px] box-border flex flex-col items-center justify-start text-left text-base text-gray font-outfit">
        <div class="self-stretch border-gainsboro border-solid border-b-[1px] border-[0px] flex flex-row items-start justify-start py-5 px-6">
            <div class="flex flex-col items-start justify-start">
                <div class="relative leading-6 font-medium">Informasi Data Pohon</div>
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
                <x-main.input-label class="relative leading-5 font-medium">Tahun Tanam
                    <span class="text-red-500">*</span>
                </x-main.input-label>
                <x-main.text-input 
                    type="number" 
                    name="tahun"
                    value="{{ old('tahun', $dataPohon?->tahun) }}"
                    min="1900" 
                    max="{{ date('Y') + 1 }}"
                    placeholder="Masukkan tahun"
                    class="flex-1 leading-5 bg-transparent text-sm"
                    required
                    oninvalid="this.setCustomValidity('Tahun harus diisi')"
                    oninput="this.setCustomValidity('')"
                />
                <x-main.input-error :messages="$errors->get('tahun')" data-turbo-temporary />
            </div>

            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <div class="flex justify-between items-center w-full">
                    <x-main.input-label class="relative leading-5 font-medium">Jumlah Batang
                        <span class="text-red-500">*</span>
                    </x-main.input-label>
                    <span class="text-xs text-gray-400 italic">Input manual (stok lahan-wide)</span>
                </div>
                
                <x-main.text-input 
                    type="number" 
                    name="jumlah_batang"
                    value="{{ old('jumlah_batang', $dataPohon?->jumlah_batang) }}"
                    min="1"
                    max="1000000"
                    placeholder="Masukkan jumlah batang"
                    class="flex-1 leading-5 bg-transparent text-sm"
                    required
                    oninvalid="this.setCustomValidity('Jumlah batang harus diisi')"
                    oninput="this.setCustomValidity('')"
                />
                <p class="text-xs text-gray-500 mt-1">
                    Minimal 1 batang. Data ini tidak terikat ke plot tertentu.
                </p>
                <x-main.input-error :messages="$errors->get('jumlah_batang')" data-turbo-temporary />
            </div>

            @if($dataPohon)
                <div class="self-stretch flex flex-col items-start justify-start gap-1.5 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="text-sm font-semibold text-blue-800">Edit Input Data Manual</div>
                    </div>
                    <div class="text-xs text-blue-700">
                        Anda sedang mengedit data pohon yang di-input manual (tidak terikat ke plot). 
                        Data realisasi dari progres harian akan ditampilkan terpisah di detail modal.
                    </div>
                </div>
            @endif

            <div class="self-stretch flex flex-row items-center justify-end gap-3 mt-4">
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