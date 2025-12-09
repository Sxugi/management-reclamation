@props(['lahan', 'gudang' => null])

<form method="POST" action="{{ $gudang ? route('lahan.gudang.update', [$lahan, $gudang]) : route('lahan.gudang.store', $lahan) }}" class="self-stretch">
    @csrf
    @if($gudang)
        @method('PUT')
    @endif

    <div class="w-full relative rounded-2xl bg-white border-gainsboro border-solid border-[1px] box-border flex flex-col items-center justify-start text-left text-base text-gray font-outfit">
        <div class="self-stretch border-gainsboro border-solid border-b-[1px] border-t-[0px] border-r-[0px] border-l-[0px] flex flex-row items-start justify-start py-5 px-6">
            <div class="flex flex-col items-start justify-start">
                <div class="relative leading-6 font-medium">Informasi Barang</div>
            </div>
        </div>
        
        <div class="self-stretch flex flex-col items-start justify-start p-6 gap-6 text-sm text-darkslategray-200">
            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                    <x-main.input-label class="relative leading-5 font-medium">Tanggal Masuk
                        <span class="text-red-500">*</span>
                    </x-main.input-label>
                    <x-main.text-input 
                        type="date"
                        name="tanggal_masuk" 
                        value="{{ old('tanggal_masuk', $gudang?->tanggal_masuk) }}"
                        class="flex-1 leading-5 bg-transparent text-sm"
                        required
                        oninvalid="this.setCustomValidity('Tanggal masuk harus diisi')"
                        oninput="this.setCustomValidity('')"
                    />
                    <x-main.input-error :messages="$errors->get('tanggal_masuk')" data-turbo-temporary class="mt-2" />
                </div>
            </div>

            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                    <x-main.input-label class="relative leading-5 font-medium">Jenis Barang
                        <span class="text-red-500">*</span>
                    </x-main.input-label>
                    <select 
                        name="jenis_barang" 
                        required
                        oninvalid="this.setCustomValidity('Jenis barang harus diisi')"
                        oninput="this.setCustomValidity('')"
                        class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md px-3 py-2 box-border font-outfit flex-1 leading-5 bg-transparent"
                    >
                        <option value="">Select Option</option>
                        <option value="Pupuk" {{ old('jenis_barang', $gudang?->jenis_barang) == 'Pupuk' ? 'selected' : '' }}>Pupuk</option>
                        <option value="Pestisida" {{ old('jenis_barang', $gudang?->jenis_barang) == 'Pestisida' ? 'selected' : '' }}>Pestisida</option>
                        <option value="Benih" {{ old('jenis_barang', $gudang?->jenis_barang) == 'Benih' ? 'selected' : '' }}>Benih</option>
                        <option value="Alat Pertanian" {{ old('jenis_barang', $gudang?->jenis_barang) == 'Alat Pertanian' ? 'selected' : '' }}>Alat Pertanian</option>
                        <option value="Lainnya" {{ old('jenis_barang', $gudang?->jenis_barang) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    <x-main.input-error :messages="$errors->get('jenis_barang')" data-turbo-temporary class="mt-2" />
                </div>
            </div>

            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                    <x-main.input-label class="relative leading-5 font-medium">Nama Barang
                        <span class="text-red-500">*</span>
                    </x-main.input-label>
                    <x-main.text-input 
                        type="text" 
                        name="nama_barang" 
                        value="{{ old('nama_barang', $gudang?->nama_barang) }}"
                        placeholder="Masukkan nama barang"
                        class="flex-1 leading-5 bg-transparent text-sm"
                        required
                        oninvalid="this.setCustomValidity('Nama barang harus diisi')"
                        oninput="this.setCustomValidity('')"
                    />
                    <x-main.input-error :messages="$errors->get('nama_barang')" data-turbo-temporary class="mt-2" />
                </div>
            </div>

            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                    <x-main.input-label class="relative leading-5 font-medium">Jumlah Barang
                        <span class="text-red-500">*</span>
                    </x-main.input-label>
                    <x-main.text-input 
                        type="number" 
                        name="jumlah_barang" 
                        value="{{ old('jumlah_barang', $gudang?->jumlah_barang) }}"
                        placeholder="Masukkan jumlah barang"
                        class="flex-1 leading-5 bg-transparent text-sm"
                        min="1"
                        required
                        oninvalid="this.setCustomValidity('Jumlah barang harus diisi')"
                        oninput="this.setCustomValidity('')"
                    />
                    <x-main.input-error :messages="$errors->get('jumlah_barang')" data-turbo-temporary class="mt-2" />
                </div>
            </div>

            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                    <x-main.input-label class="relative leading-5 font-medium">Lokasi Penyimpanan
                        <span class="text-red-500">*</span>
                    </x-main.input-label>
                    <x-main.text-input 
                        type="text" 
                        name="lokasi_penyimpanan" 
                        value="{{ old('lokasi_penyimpanan', $gudang?->lokasi_penyimpanan) }}"
                        placeholder="Masukkan lokasi penyimpanan"
                        class="flex-1 leading-5 bg-transparent text-sm"
                        required
                        oninvalid="this.setCustomValidity('Lokasi penyimpanan harus diisi')"
                        oninput="this.setCustomValidity('')"
                    />
                    <x-main.input-error :messages="$errors->get('lokasi_penyimpanan')" data-turbo-temporary class="mt-2" />
                </div>
            </div>

            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                    <x-main.input-label class="relative leading-5 font-medium">Status Barang
                        <span class="text-red-500">*</span>
                    </x-main.input-label>
                    <select 
                        name="status_barang" 
                        required
                        oninvalid="this.setCustomValidity('Status barang harus diisi')"
                        oninput="this.setCustomValidity('')"
                        class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md px-3 py-2 box-border font-outfit flex-1 leading-5 bg-transparent"
                    >
                        <option value="">Select Option</option>
                        <option value="Tersedia" {{ old('status_barang', $gudang?->status_barang) == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="Kosong" {{ old('status_barang', $gudang?->status_barang) == 'Kosong' ? 'selected' : '' }}>Kosong</option>
                        <option value="Rusak" {{ old('status_barang', $gudang?->status_barang) == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                        <option value="Digunakan" {{ old('status_barang', $gudang?->status_barang) == 'Digunakan' ? 'selected' : '' }}>Digunakan</option>
                    </select>
                    <x-main.input-error :messages="$errors->get('status_barang')" data-turbo-temporary class="mt-2" />
                </div>
            </div>

            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <div class="self-stretch flex-1 flex flex-col items-start justify-start gap-1.5">
                    <x-main.input-label class="relative leading-5 font-medium">Catatan</x-main.input-label>
                        <div class="self-stretch flex-1 rounded-lg bg-white border-gray-300 border-solid border-[1px] overflow-hidden flex flex-row items-start justify-start">
                            <textarea 
                                name="catatan" 
                                placeholder="Masukan catatan..."
                                class="flex-1 border-none outline-none resize-none leading-5 bg-transparent font-outfit text-sm min-h-[120px] resize-y"
                                rows="6"
                            >{{ old('catatan', $gudang?->catatan) }}</textarea>
                        </div>
                    <x-main.input-error :messages="$errors->get('catatan')" data-turbo-temporary class="mt-2" />
                </div>
            </div>

            <div class="self-stretch flex flex-row items-center justify-end gap-3">
                <a href="{{ route('lahan.gudang.index', $lahan) }}" 
                   class="bg-red-500 !text-white text-sm py-3 px-4 rounded-lg font-medium hover:bg-red-600 transition-colors no-underline">
                    Cancel
                </a>
                <x-main.primary-button type="submit" 
                        class="py-3 px-4 gap-2 font-medium">
                    {{ $gudang ? 'Update' : 'Save' }}
                </x-main.primary-button>
            </div>
        </div>
    </div>
</form>