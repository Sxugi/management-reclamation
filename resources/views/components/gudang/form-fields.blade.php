@props(['lahan', 'gudang' => null, 'existingItems' => []])

@php
    // Konfigurasi Data untuk AlpineJS
    $formConfig = [
        'items' => $existingItems ?? [],
        'initialType' => old('jenis_transaksi', $gudang?->jenis_transaksi ?? 'MASUK'),
        'initialCategory' => old('jenis_barang', $gudang?->jenis_barang ?? ''),
        'initialSku' => old('sku', $gudang?->sku ?? ''),
        'initialName' => old('nama_barang', $gudang?->nama_barang ?? ''),
        'initialUnit' => old('satuan', $gudang?->satuan ?? ''),
        'initialJumlah' => old('jumlah_barang', $gudang?->jumlah_barang ?? 0),
        'initialStatus' => old('status_barang', $gudang?->status_barang ?? ''),
        'isEdit' => (bool) $gudang,
        'lahanId' => $lahan->lahan_id,
        'gudangId' => $gudang?->data_gudang_id,
        'checkStockUrl' => route('lahan.gudang.check-stock', $lahan),
    ];
@endphp

<form method="POST" 
      action="{{ $gudang ? route('lahan.gudang.update', [$lahan, $gudang]) : route('lahan.gudang.store', $lahan) }}" 
      class="self-stretch"
      x-data='gudangForm(@json($formConfig))'
      x-cloak>
    
    @csrf
    @if($gudang) @method('PUT') @endif

    <div class="w-full relative rounded-2xl bg-white border-gainsboro border-solid border-[1px] box-border flex flex-col items-center justify-start text-left text-base text-gray font-outfit">
        
        <div class="self-stretch border-gainsboro border-solid border-b-[1px] flex flex-row items-start justify-start py-5 px-6 bg-gray-50 rounded-t-2xl">
            <div class="flex flex-col items-start justify-start gap-1">
                <div class="relative leading-6 font-bold text-darkslategray text-lg">
                    {{ $gudang ? 'Edit Transaksi Barang' : 'Form Transaksi Barang' }}
                </div>
                <p class="text-sm text-slategray">
                    <span x-text="transactionType === 'MASUK' ? 'Catat pembelian atau barang masuk baru (Restock).' : 'Catat pemakaian atau pengeluaran barang.'"></span>
                </p>
            </div>
        </div>
        
        <div class="self-stretch flex flex-col items-start justify-start p-6 gap-6 text-sm text-darkslategray-200">

            {{-- Jenis Transaksi --}}
            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">
                    Jenis Transaksi <span class="text-red-500">*</span>
                </x-main.input-label>
                <div class="grid grid-cols-2 gap-4 w-full">
                    <label class="cursor-pointer">
                        <input type="radio" name="jenis_transaksi" value="MASUK" x-model="transactionType" class="peer sr-only" required>
                        <div class="p-4 rounded-lg border-2 border-gray-200 text-center peer-checked:bg-green-50 peer-checked:border-green-500 hover:border-green-300 transition-all">
                            <span class="block text-2xl mb-1">⬇️</span>
                            <span class="block font-bold text-sm text-darkslategray">Barang Masuk</span>
                            <span class="block text-xs text-slategray mt-1">Restock / Beli Baru</span>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="jenis_transaksi" value="KELUAR" x-model="transactionType" class="peer sr-only" required>
                        <div class="p-4 rounded-lg border-2 border-gray-200 text-center peer-checked:bg-red-50 peer-checked:border-red-500 hover:border-red-300 transition-all">
                            <span class="block text-2xl mb-1">⬆️</span>
                            <span class="block font-bold text-sm text-darkslategray">Barang Keluar</span>
                            <span class="block text-xs text-slategray mt-1">Pakai / Rusak</span>
                        </div>
                    </label>
                </div>
                <x-main.input-error :messages="$errors->get('jenis_transaksi')" data-turbo-temporary class="mt-2" />
            </div>

            {{-- Tanggal Transaksi --}}
            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">
                    Tanggal Transaksi <span class="text-red-500">*</span>
                </x-main.input-label>
                <x-main.text-input 
                    type="date"
                    name="tanggal_masuk" 
                    value="{{ old('tanggal_masuk', $gudang?->tanggal_masuk ?? date('Y-m-d')) }}"
                    class="flex-1 leading-5 bg-transparent text-sm w-full"
                    required 
                    oninvalid="this.setCustomValidity('Tanggal transaksi harus diisi')"
                    oninput="this.setCustomValidity('')"
                />
                <x-main.input-error :messages="$errors->get('tanggal_masuk')" data-turbo-temporary class="mt-2" />
            </div>

            <hr class="self-stretch border-gray-200">

            {{-- Kategori Barang --}}
            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">
                    Kategori Barang <span class="text-red-500">*</span>
                </x-main.input-label>
                <select 
                    name="jenis_barang" 
                    x-model="category"
                    @change="onCategoryChange()"
                    required
                    oninvalid="this.setCustomValidity('Kategori barang harus diisi')"
                    oninput="this.setCustomValidity('')"
                    class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md px-3 py-2 box-border font-outfit flex-1 leading-5 bg-transparent">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach(['Pupuk', 'Pestisida', 'Benih', 'Alat Pertanian', 'Bahan Bakar', 'Lainnya'] as $opt)
                        <option value="{{ $opt }}">{{ $opt }}</option>
                    @endforeach
                </select>
                <x-main.input-error :messages="$errors->get('jenis_barang')" data-turbo-temporary class="mt-2" />
            </div>

            {{-- Detail Barang --}}
            <div x-show="category" x-transition class="self-stretch p-4 bg-blue-50 rounded-xl border border-blue-200">
                <div class="flex justify-between items-center mb-4">
                    <label class="font-bold text-darkslategray flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        Detail Barang
                    </label>
                    
                    <template x-if="!isEdit && transactionType === 'MASUK'">
                        <button type="button" 
                                @click="toggleNewItemMode()"
                                class="text-xs font-medium px-3 py-1.5 rounded-lg transition-all"
                                :class="isNewItemMode ? 'bg-darkslategray text-white hover:bg-slategray-200' : 'bg-blue-600 text-white hover:bg-blue-700'">
                            <span x-text="isNewItemMode ? '← Pilih dari Database' : '+ Input Barang Baru'"></span>
                        </button>
                    </template>
                </div>

                {{-- SKU --}}
                <div class="mb-4">
                    <x-main.input-label class="mb-1.5 text-xs text-gray-600">SKU / Kode (Opsional)</x-main.input-label>
                    <x-main.text-input 
                        type="text" 
                        name="sku" 
                        x-model="sku"
                        x-bind:readonly="!isNewItemMode"
                        placeholder="Auto-generated based on Category"
                        class="w-full text-sm"
                        x-bind:class="!isNewItemMode ? 'bg-gray-50 text-darkslategray cursor-not-allowed opacity-100' : 'bg-white'" />
                    <template x-if="isNewItemMode">
                        <p class="text-[10px] text-gray-500 mt-1">*SKU di-generate otomatis sesuai kategori, bisa diedit jika perlu.</p>
                    </template>
                </div>

                {{-- Nama Barang --}}
                <div class="mb-4">
                    <x-main.input-label class="mb-1.5 text-xs text-gray-600">Nama Barang <span class="text-red-500">*</span></x-main.input-label>
                    
                    <div x-show="!isNewItemMode">
                        <select 
                            name="nama_barang_select"
                            x-model="selectedItemName"
                            @change="fillItemDetails()"
                            x-bind:required="!isNewItemMode"
                            oninvalid="this.setCustomValidity('Nama barang harus diisi')"
                            oninput="this.setCustomValidity('')"
                            class="w-full text-sm border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md px-3 py-2 box-border font-outfit flex-1 leading-5">
                            <option value="">-- Pilih Barang --</option>
                            <template x-for="item in availableItems" :key="item.nama_barang">
                                <option 
                                    x-bind:value="item.nama_barang" 
                                    x-text="item.nama_barang"
                                    x-bind:selected="item.nama_barang === selectedItemName"></option>
                            </template>
                        </select>
                        <p x-show="availableItems.length === 0" class="text-xs text-amber-600 mt-1 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            Data kosong. Silakan Input Barang Baru.
                        </p>
                    </div>

                    <div x-show="isNewItemMode">
                        <x-main.text-input 
                            type="text" 
                            name="nama_barang_input"
                            x-model="manualItemName"
                            @input="onManualNameChange()"
                            x-bind:required="isNewItemMode"
                            oninvalid="this.setCustomValidity('Nama barang harus diisi')"
                            oninput="this.setCustomValidity('')"
                            placeholder="Contoh: Pupuk Urea 50kg"
                            class="w-full text-sm bg-white" />
                    </div>

                    <input type="hidden" name="nama_barang" x-bind:value="isNewItemMode ? manualItemName : selectedItemName">
                    <x-main.input-error :messages="$errors->get('nama_barang')" class="mt-2" />
                    
                    {{-- Real-time Stock Indicator --}}
                    <div x-show="shouldShowStockInfo" 
                         x-transition
                         class="mt-2 text-xs px-3 py-2 rounded-lg border flex items-center gap-2"
                         :class="stockStatusClass">
                        <template x-if="stokTersedia > 0">
                            <span>📦 Stok tersedia: <strong x-text="stokTersedia"></strong> <span x-text="unit || 'unit'"></span></span>
                        </template>
                        <template x-if="stokTersedia === 0">
                            <span>❌ Stok habis</span>
                        </template>
                    </div>
                    
                    {{-- Loading State --}}
                    <div x-show="isCheckingStock" class="mt-2 text-xs text-gray-500 flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Mengecek stok...</span>
                    </div>
                    
                    {{-- Error State --}}
                    <div x-show="stockCheckError" 
                         x-text="stockCheckError"
                         class="mt-2 text-xs text-red-600 bg-red-50 border border-red-200 rounded px-2 py-1"></div>
                </div>

                {{-- Satuan --}}
                <div>
                    <x-main.input-label class="mb-1.5 text-xs text-gray-600">Satuan <span class="text-red-500">*</span></x-main.input-label>
                    <x-main.text-input 
                        type="text" 
                        name="satuan" 
                        x-model="unit"
                        x-bind:readonly="!isNewItemMode"
                        placeholder="Pcs / Kg / Sak" 
                        required
                        oninvalid="this.setCustomValidity('Satuan harus diisi')"
                        oninput="this.setCustomValidity('')"
                        class="w-full text-sm"
                        x-bind:class="!isNewItemMode ? 'bg-gray-50 text-darkslategray cursor-not-allowed opacity-100' : 'bg-white'" />
                    <x-main.input-error :messages="$errors->get('satuan')" data-turbo-temporary class="mt-2" />
                </div>
            </div>

            {{-- Jumlah Barang --}}
            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">
                    <span x-text="transactionType === 'MASUK' ? 'Jumlah Masuk' : 'Jumlah Keluar'"></span> <span class="text-red-500">*</span>
                </x-main.input-label>
                <div class="relative w-full">
                    <x-main.text-input 
                        type="number" 
                        name="jumlah_barang"
                        x-model.number="jumlahBarang"
                        value="{{ old('jumlah_barang', $gudang?->jumlah_barang) }}"
                        placeholder="0"
                        min="1"
                        required
                        oninvalid="this.setCustomValidity('Jumlah barang harus diisi dan minimal 1')"
                        oninput="this.setCustomValidity('')"
                        class="w-full pl-10 text-sm" />
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-lg font-bold" 
                         :class="transactionType === 'MASUK' ? 'text-green-600' : 'text-red-600'"
                         x-text="transactionType === 'MASUK' ? '+' : '-'"></div>
                </div>
                
                {{-- Live Stock Calculation --}}
                <div x-show="shouldShowStockInfo && jumlahBarang > 0" 
                     x-transition
                     class="text-xs px-3 py-2 rounded-lg border"
                     :class="isStockInsufficient ? 'bg-red-50 border-red-200 text-red-700' : (willBeEmpty ? 'bg-orange-50 border-orange-200 text-orange-700' : 'bg-green-50 border-green-200 text-green-700')">
                    <template x-if="isStockInsufficient">
                        <span>❌ <strong>Jumlah melebihi stok tersedia!</strong> Kelebihan: <strong x-text="Math.abs(sisaStok)"></strong> <span x-text="unit || 'unit'"></span></span>
                    </template>
                    <template x-if="willBeEmpty">
                        <span>⚠️ Stok akan <strong>HABIS</strong> setelah transaksi ini</span>
                    </template>
                    <template x-if="willHaveStock">
                        <span>✅ Sisa stok setelah transaksi: <strong x-text="sisaStok"></strong> <span x-text="unit || 'unit'"></span></span>
                    </template>
                </div>
                
                <x-main.input-error :messages="$errors->get('jumlah_barang')" data-turbo-temporary class="mt-2" />
            </div>

            {{-- Lokasi Penyimpanan --}}
            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">
                    <span x-text="transactionType === 'MASUK' ? 'Lokasi Penyimpanan' : 'Lokasi Tujuan'"></span> <span class="text-red-500">*</span>
                </x-main.input-label>
                <x-main.text-input 
                    type="text" 
                    name="lokasi_penyimpanan" 
                    value="{{ old('lokasi_penyimpanan', $gudang?->lokasi_penyimpanan) }}"
                    x-bind:placeholder="transactionType === 'MASUK' ? 'Contoh: Gudang A / Rak 3' : 'Contoh: Plot A / Blok 2'"
                    required
                    oninvalid="this.setCustomValidity('Lokasi harus diisi')"
                    oninput="this.setCustomValidity('')"
                    class="w-full text-sm flex-1 leading-5 bg-transparent" />
                <x-main.input-error :messages="$errors->get('lokasi_penyimpanan')" data-turbo-temporary class="mt-2" />
            </div>

            {{-- Kondisi Fisik / Status --}}
            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">Kondisi Fisik <span class="text-red-500">*</span></x-main.input-label>
                <select 
                    name="status_barang"
                    x-model="statusBarang"
                    required
                    oninvalid="this.setCustomValidity('Kondisi fisik harus dipilih')"
                    oninput="this.setCustomValidity('')"
                    class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md px-3 py-2 box-border font-outfit flex-1 leading-5 bg-transparent">
                    <option value="">-- Pilih Kondisi --</option>
                    <option value="Tersedia" {{ old('status_barang', $gudang?->status_barang) == 'Tersedia' ? 'selected' : '' }}>
                        Tersedia
                    </option>
                    <option value="Kosong" {{ old('status_barang', $gudang?->status_barang) == 'Kosong' ? 'selected' : '' }}>
                        Stok Habis
                    </option>
                    <option value="Rusak" {{ old('status_barang', $gudang?->status_barang) == 'Rusak' ? 'selected' : '' }}>
                        Tidak Layak Pakai
                    </option>
                    <option value="Digunakan" {{ old('status_barang', $gudang?->status_barang) == 'Digunakan' ? 'selected' : '' }}>
                        Sedang Dipakai
                    </option>
                </select>
                
                {{-- Smart Status Suggestion --}}
                <div x-show="willBeEmpty && statusBarang !== 'Kosong'" 
                     x-transition
                     class="text-xs bg-yellow-50 border border-yellow-200 text-yellow-700 rounded px-3 py-2 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    <span>💡 Saran: Pilih status <strong>"Stok Habis"</strong> karena stok akan habis</span>
                </div>
                
                <div x-show="willHaveStock && statusBarang === 'Kosong'" 
                     x-transition
                     class="text-xs bg-red-50 border border-red-200 text-red-700 rounded px-3 py-2 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    <span>❌ Peringatan: Status "Stok Habis" tidak sesuai, stok masih tersisa <strong x-text="sisaStok"></strong> <span x-text="unit || 'unit'"></span></span>
                </div>
                
                <x-main.input-error :messages="$errors->get('status_barang')" class="mt-2" />
            </div>

            {{-- Catatan --}}
            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <div class="self-stretch flex-1 flex flex-col items-start justify-start gap-1.5">
                    <x-main.input-label class="relative leading-5 font-medium">Catatan</x-main.input-label>
                    <div class="self-stretch flex-1 rounded-lg bg-white border-gray-300 border-solid border-[1px] overflow-hidden flex flex-row items-start justify-start">
                        <textarea 
                            name="catatan" 
                            placeholder="Contoh: Untuk kebutuhan pemupukan plot A..."
                            class="flex-1 border-none outline-none resize-none leading-5 bg-transparent font-outfit text-sm min-h-[120px] resize-y p-3"
                            rows="6"
                        >{{ old('catatan', $gudang?->catatan) }}</textarea>
                    </div>
                    <x-main.input-error :messages="$errors->get('catatan')" data-turbo-temporary class="mt-2" />
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="self-stretch flex flex-row items-center justify-end gap-3 pt-4 border-t border-gray-200">
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

<style>
    [x-cloak] { display: none !important; }
</style>