<div x-show="activeTab === 'gudang'" 
     style="display: none;" 
     data-guide-section="gudang"
     data-keywords="gudang, inventory, warehouse, stok, stock, barang, items, transaksi, transaction, masuk, keluar, restock, pembelian"
     data-title="Data Gudang & Inventaris"
     data-desc="Manajemen stok barang dan transaksi gudang"
     data-anchor=""
     x-transition:enter="transition ease-out duration-300 transform" 
     x-transition:enter-start="opacity-0 translate-y-2" 
     x-transition:enter-end="opacity-100 translate-y-0">
    
    <span class="text-amber-600 font-bold tracking-wider text-xs uppercase mb-2 block">Manajemen Inventaris</span>
    <h1 class="text-3xl font-extrabold text-darkslategray mb-6">Data Gudang & Stok Barang</h1>

    <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-l-4 border-amber-500 p-6 rounded-r-xl mb-8">
        <p class="text-gray-700 text-base leading-relaxed mb-3">
            Modul Data Gudang membantu Anda mengelola <strong>inventaris barang</strong> untuk kegiatan reklamasi, seperti pupuk, bibit, alat, dan material lainnya. 
            Sistem mencatat <strong>transaksi masuk dan keluar</strong> dengan tracking stok real-time.
        </p>
        <div class="bg-white rounded-lg p-4 border border-amber-200">
            <div class="flex items-center gap-2 text-sm">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-amber-900 font-medium">Sistem mencatat <strong>2 jenis transaksi:</strong> Barang Masuk (Restock) & Barang Keluar (Pemakaian)</span>
            </div>
        </div>
    </div>

    {{-- JENIS TRANSAKSI --}}
    <div class="bg-white border-2 border-amber-200 rounded-xl p-6 mb-8 shadow-sm">
        <h3 class="text-lg font-bold text-darkslategray mb-4 flex items-center gap-2">
            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Jenis Transaksi Gudang
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-green-50 border-l-4 border-green-500 rounded-r-lg p-4">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                        </svg>
                    </div>
                    <h4 class="font-bold text-green-800 text-base">Barang Masuk</h4>
                </div>
                <p class="text-sm text-green-700 mb-2">
                    Pencatatan barang yang <strong>masuk ke gudang</strong> (Restock / Pembelian / Donasi).
                </p>
                <div class="bg-white rounded-lg p-3 mt-2 border border-green-200">
                    <p class="text-xs text-gray-700 font-medium mb-1">💡 Contoh:</p>
                    <ul class="text-xs text-gray-600 space-y-1">
                        <li>• Pembelian pupuk NPK 100 kg</li>
                        <li>• Donasi bibit Jati 500 batang</li>
                        <li>• Restock alat kerja (cangkul, sekop)</li>
                    </ul>
                </div>
            </div>

            <div class="bg-blue-50 border-l-4 border-blue-500 rounded-r-lg p-4">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                        </svg>
                    </div>
                    <h4 class="font-bold text-blue-800 text-base">Barang Keluar</h4>
                </div>
                <p class="text-sm text-blue-700 mb-2">
                    Pencatatan barang yang <strong>keluar dari gudang</strong> untuk pemakaian kegiatan.
                </p>
                <div class="bg-white rounded-lg p-3 mt-2 border border-blue-200">
                    <p class="text-xs text-gray-700 font-medium mb-1">💡 Contoh:</p>
                    <ul class="text-xs text-gray-600 space-y-1">
                        <li>• Pemakaian pupuk untuk penanaman</li>
                        <li>• Distribusi bibit ke plot A</li>
                        <li>• Penggunaan alat untuk pemeliharaan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div id="section-stok-saat-ini" class="scroll-mt-20 mb-12">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-darkslategray">Stok Saat Ini</h2>
                <p class="text-sm text-gray-600">Ringkasan stok barang real-time</p>
            </div>
        </div>

        <div class="bg-white border border-gainsboro rounded-xl p-6 mb-6 shadow-sm">
            <h3 class="text-lg font-bold text-darkslategray mb-4">Cara Cek Stok</h3>
            <p class="text-sm text-gray-700 mb-4">
                Dari menu <strong>Data Gudang</strong>, bagian <strong>"Stok Saat Ini"</strong> menampilkan ringkasan stok seluruh barang dengan perhitungan otomatis.
            </p>

            <div class="bg-gray-50 border-2 border-dashed border-gainsboro rounded-xl p-4 mb-4">
                <img 
                    src="{{ asset('images/guide/stok-gudang.png') }}" 
                    alt="Tampilan stok gudang saat ini"
                    class="w-full max-w-4xl mx-auto rounded-lg border border-gainsboro shadow-md"
                    loading="lazy"
                >
                <p class="text-xs text-gray-500 text-center mt-3 italic">
                    💡 Card "Stok Saat Ini" dengan info update terakhir
                </p>
            </div>

            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                <p class="text-xs font-bold text-purple-900 mb-2">📊 Informasi Stok:</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs text-purple-800">
                    <div>• <strong>Nama Barang:</strong> Nama item inventaris</div>
                    <div>• <strong>Kategori:</strong> Jenis barang (Pupuk, Bibit, Alat, dll)</div>
                    <div>• <strong>Total Masuk:</strong> Jumlah barang yang pernah masuk</div>
                    <div>• <strong>Stok Sisa:</strong> Jumlah tersisa (Masuk - Keluar)</div>
                </div>
            </div>
        </div>
    </div>

    {{-- DIVIDER --}}
    <div class="border-t-2 border-gainsboro my-12"></div>

    {{-- TRANSAKSI BARANG --}}
    <div id="section-transaksi-barang" class="scroll-mt-20">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-amber-600 rounded-lg flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-darkslategray">Transaksi Barang</h2>
                <p class="text-sm text-gray-600">Input barang masuk atau keluar</p>
            </div>
        </div>

        <div class="bg-white border border-gainsboro rounded-xl p-6 mb-6 shadow-sm">
            <h3 class="text-lg font-bold text-darkslategray mb-4">List Barang (Riwayat Transaksi)</h3>
            <p class="text-sm text-gray-700 mb-4">
                Section <strong>"List Barang"</strong> menampilkan <strong>histori lengkap</strong> semua transaksi masuk dan keluar.
            </p>

            <div class="bg-gray-50 border-2 border-dashed border-gainsboro rounded-xl p-4 mb-4">
                <img 
                    src="{{ asset('images/guide/list-transaksi-gudang.png') }}" 
                    alt="Tabel list transaksi gudang"
                    class="w-full max-w-4xl mx-auto rounded-lg border border-gainsboro shadow-md"
                    loading="lazy"
                >
                <p class="text-xs text-gray-500 text-center mt-3 italic">
                    💡 Tabel riwayat transaksi dengan filter, export, dan tombol Add
                </p>
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                <p class="text-xs font-bold text-amber-900 mb-2">📋 Kolom Tabel:</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-xs text-amber-800">
                    <div>• <strong>Tanggal:</strong> Tanggal transaksi</div>
                    <div>• <strong>Tipe:</strong> Masuk/Keluar</div>
                    <div>• <strong>Barang:</strong> Nama barang</div>
                    <div>• <strong>Jumlah:</strong> Kuantitas + satuan</div>
                    <div>• <strong>Lokasi:</strong> Lokasi penyimpanan</div>
                    <div>• <strong>Status:</strong> Kondisi barang</div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <h3 class="text-xl font-bold text-darkslategray mb-4">Cara Input Transaksi</h3>

            <div class="flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-amber-500 to-amber-600 text-white flex items-center justify-center font-bold shadow-lg">1</div>
                <div class="flex-1">
                    <h4 class="font-bold text-darkslategray text-lg mb-2">Klik "Add +"</h4>
                    <p class="text-sm text-gray-600">
                        Dari section <strong>"List Barang"</strong>, klik tombol <strong>"Add +"</strong> untuk membuka form transaksi.
                    </p>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white flex items-center justify-center font-bold shadow-lg">2</div>
                <div class="flex-1">
                    <h4 class="font-bold text-darkslategray text-lg mb-2">Pilih Jenis Transaksi</h4>
                    <p class="text-sm text-gray-600 mb-4">
                        Form akan menampilkan <strong>2 pilihan besar:</strong> Barang Masuk atau Barang Keluar.
                    </p>

                    <div class="bg-gray-50 border-2 border-dashed border-gainsboro rounded-xl p-4 mb-4">
                        <img 
                            src="{{ asset('images/guide/form-transaksi-gudang.png') }}" 
                            alt="Form input transaksi gudang"
                            class="w-full max-w-3xl mx-auto rounded-lg border border-gainsboro shadow-md"
                            loading="lazy"
                        >
                        <p class="text-xs text-gray-500 text-center mt-3 italic">
                            💡 Form dengan 2 pilihan: Barang Masuk (hijau) atau Barang Keluar (biru)
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4"/>
                                    </svg>
                                </div>
                                <h5 class="font-bold text-green-800">Barang Masuk</h5>
                            </div>
                            <p class="text-xs text-green-700 mb-2">Pilih ini untuk:</p>
                            <ul class="text-xs text-gray-700 space-y-1">
                                <li>✅ Pembelian barang baru</li>
                                <li>✅ Restocking gudang</li>
                                <li>✅ Donasi/hibah barang</li>
                            </ul>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16V4m0 0l4 4m-4-4l-4 4"/>
                                    </svg>
                                </div>
                                <h5 class="font-bold text-blue-800">Barang Keluar</h5>
                            </div>
                            <p class="text-xs text-blue-700 mb-2">Pilih ini untuk:</p>
                            <ul class="text-xs text-gray-700 space-y-1">
                                <li>✅ Pemakaian barang lapangan</li>
                                <li>✅ Distribusi ke plot</li>
                                <li>✅ Barang rusak/hilang</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 text-white flex items-center justify-center font-bold shadow-lg">3</div>
                <div class="flex-1">
                    <h4 class="font-bold text-darkslategray text-lg mb-2">Lengkapi Form</h4>
                    <p class="text-sm text-gray-600 mb-4">
                        Setelah memilih kategori barang, sistem menyediakan <strong>2 cara input:</strong>
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-blue-50 border-2 border-blue-300 rounded-xl p-4">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </div>
                                <h5 class="font-bold text-blue-900">Cara 1: Barang Baru</h5>
                            </div>
                            <p class="text-xs text-blue-800 mb-2">
                                Input manual untuk barang yang <strong>belum pernah ada</strong> di database.
                            </p>
                            <div class="bg-white rounded-lg p-3 border border-blue-200 mt-2">
                                <p class="text-xs text-gray-700 font-medium mb-1">✨ Fitur:</p>
                                <ul class="text-xs text-gray-600 space-y-1">
                                    <li>• <strong>SKU auto-generate</strong> berdasarkan kategori</li>
                                    <li>• Isi nama barang dan satuan sendiri</li>
                                    <li>• SKU bisa diedit manual jika perlu</li>
                                </ul>
                            </div>
                        </div>

                        <div class="bg-green-50 border-2 border-green-300 rounded-xl p-4">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                                    </svg>
                                </div>
                                <h5 class="font-bold text-green-900">Cara 2: Pilih Database</h5>
                            </div>
                            <p class="text-xs text-green-800 mb-2">
                                Pilih dari barang yang <strong>sudah pernah diinput</strong> sebelumnya.
                            </p>
                            <div class="bg-white rounded-lg p-3 border border-green-200 mt-2">
                                <p class="text-xs text-gray-700 font-medium mb-1">✨ Fitur:</p>
                                <ul class="text-xs text-gray-600 space-y-1">
                                    <li>• <strong>Auto-fill</strong> SKU, nama, satuan</li>
                                    <li>• Konsistensi data terjaga</li>
                                    <li>• Klik tombol "← Pilih dari Database"</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 border-2 border-dashed border-gainsboro rounded-xl p-4 mb-4">
                        <img 
                            src="{{ asset('images/guide/form-gudang-sku.png') }}" 
                            alt="Form dengan SKU auto-generate dan pilih database"
                            class="w-full max-w-3xl mx-auto rounded-lg border border-gainsboro shadow-md"
                            loading="lazy"
                        >
                        <p class="text-xs text-gray-500 text-center mt-3 italic">
                            💡 Form dengan SKU auto-generate dan tombol "Pilih dari Database"
                        </p>
                    </div>

                    <div class="bg-indigo-50 border-l-4 border-indigo-500 rounded-r-lg p-4 mb-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                            </svg>
                            <div class="flex-1">
                                <h5 class="font-bold text-indigo-900 mb-2">📦 Apa itu SKU?</h5>
                                <p class="text-sm text-indigo-800 mb-2">
                                    <strong>SKU (Stock Keeping Unit)</strong> adalah kode unik untuk mengidentifikasi setiap item barang di gudang.
                                </p>
                                <div class="bg-white rounded-lg p-3 border border-indigo-200 mt-2">
                                    <p class="text-xs font-bold text-gray-700 mb-1">Format SKU Auto-Generate:</p>
                                    <div class="font-mono text-xs bg-almostgray p-2 rounded border border-gainsboro mb-2">
                                        <span class="text-blue-600 font-bold">PUK</span>-<span class="text-green-600">880143</span>-<span class="text-purple-600">711</span>
                                    </div>
                                    <ul class="text-xs text-gray-600 space-y-1">
                                        <li>• <span class="text-blue-600 font-bold">PUK</span> = Prefix kategori (3 huruf pertama dari "Pupuk")</li>
                                        <li>• <span class="text-green-600 font-bold">880143</span> = Random number (unique ID)</li>
                                        <li>• <span class="text-purple-600 font-bold">711</span> = Timestamp suffix</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gainsboro rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gainsboro">
                            <p class="font-bold text-sm text-darkslategray">📋 Field Form Transaksi</p>
                        </div>
                        <table class="w-full text-xs">
                            <thead class="bg-gray-50 text-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-left font-bold border-b">Field</th>
                                    <th class="px-4 py-3 text-left font-bold border-b">Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 text-gray-700">
                                <tr>
                                    <td class="px-4 py-3 font-medium">Jenis Transaksi <span class="text-red-500">*</span></td>
                                    <td class="px-4 py-3">Pilih Barang Masuk atau Barang Keluar</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 font-medium">Tanggal <span class="text-red-500">*</span></td>
                                    <td class="px-4 py-3">Tanggal transaksi terjadi</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 font-medium">Kategori <span class="text-red-500">*</span></td>
                                    <td class="px-4 py-3">Jenis barang (Pupuk, Bibit, Alat, Material, dll)</td>
                                </tr>
                                <tr class="bg-blue-50">
                                    <td class="px-4 py-3 font-medium">SKU / Kode</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <span class="text-blue-700 font-medium">Auto-generate atau pilih dari database</span>
                                            <span class="px-2 py-0.5 bg-blue-600 text-white rounded text-xs font-bold">OPSIONAL</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 font-medium">Nama Barang <span class="text-red-500">*</span></td>
                                    <td class="px-4 py-3">Nama item (contoh: Pupuk Urea 50kg)</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 font-medium">Satuan <span class="text-red-500">*</span></td>
                                    <td class="px-4 py-3">Unit (Pcs, Kg, Sak, Liter, dll)</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 font-medium">Jumlah <span class="text-red-500">*</span></td>
                                    <td class="px-4 py-3">Kuantitas barang (angka positif)</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 font-medium">Lokasi Penyimpanan <span class="text-red-500">*</span></td>
                                    <td class="px-4 py-3">Lokasi gudang (contoh: Gudang A / Rak 3)</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 font-medium">Kondisi Fisik <span class="text-red-500">*</span></td>
                                    <td class="px-4 py-3">Baik, Rusak, atau Rusak Berat</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 font-medium">Catatan</td>
                                    <td class="px-4 py-3">Keterangan tambahan (opsional)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 bg-gradient-to-br from-purple-50 to-indigo-50 border border-purple-200 rounded-xl p-6">
                        <h5 class="font-bold text-purple-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Alur Input Form
                        </h5>
                        
                        <div class="space-y-4">
                            {{-- Path A: Barang Baru --}}
                            <div class="bg-white rounded-lg p-4 border-l-4 border-blue-500">
                                <p class="text-xs font-bold text-blue-900 mb-2">🔵 Path A: Input Barang Baru</p>
                                <ol class="text-xs text-gray-700 space-y-1 ml-4 list-decimal">
                                    <li>Pilih <strong>Kategori Barang</strong> (misal: Pupuk)</li>
                                    <li>SKU <strong>otomatis ter-generate</strong> (contoh: PUK-880143-711)</li>
                                    <li>Isi <strong>Nama Barang</strong> manual (contoh: Pupuk Urea 50kg)</li>
                                    <li>Isi <strong>Satuan</strong> manual (contoh: Sak)</li>
                                    <li>Lanjut isi field lainnya → Simpan</li>
                                </ol>
                            </div>

                            {{-- Path B: Database --}}
                            <div class="bg-white rounded-lg p-4 border-l-4 border-green-500">
                                <p class="text-xs font-bold text-green-900 mb-2">🟢 Path B: Pilih dari Database</p>
                                <ol class="text-xs text-gray-700 space-y-1 ml-4 list-decimal">
                                    <li>Pilih <strong>Kategori Barang</strong> (misal: Pupuk)</li>
                                    <li>Klik tombol <strong>"← Pilih dari Database"</strong></li>
                                    <li>Pilih barang yang sudah pernah diinput sebelumnya</li>
                                    <li>SKU, Nama, Satuan <strong>otomatis terisi</strong></li>
                                    <li>Lanjut isi field lainnya → Simpan</li>
                                </ol>
                            </div>
                        </div>

                        <div class="mt-4 bg-purple-100 border border-purple-300 rounded-lg p-3">
                            <p class="text-xs text-purple-900">
                                <strong>💡 Tips:</strong> Gunakan "Pilih dari Database" untuk <strong>konsistensi nama barang</strong> dan menghindari duplikasi data.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-green-600 text-white flex items-center justify-center font-bold shadow-lg">4</div>
                <div class="flex-1">
                    <h4 class="font-bold text-darkslategray text-lg mb-2">Simpan Transaksi</h4>
                    <p class="text-sm text-gray-600 mb-3">
                        Klik <strong>"Save"</strong> untuk menyimpan. Sistem akan:
                    </p>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Menyimpan transaksi ke database</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Otomatis <strong>update stok saat ini</strong> (menambah atau mengurangi)</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Tampilkan data di tabel riwayat transaksi</span>
                        </li>
                    </ul>

                    <div class="mt-4 bg-amber-50 border border-amber-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-xs text-amber-800">
                                <p class="font-bold mb-1">💡 Catatan Penting:</p>
                                <ul class="space-y-1">
                                    <li>• <strong>Barang Keluar</strong> akan mengurangi stok otomatis</li>
                                    <li>• <strong>Barang Masuk</strong> akan menambah stok otomatis</li>
                                    <li>• Pastikan jumlah barang keluar <strong>tidak melebihi stok sisa</strong></li>
                                    <li>• Data transaksi bisa diedit/hapus kapan saja</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-12 bg-gradient-to-br from-gray-50 to-slate-50 border-l-4 border-gray-500 rounded-r-xl p-6">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-gray-600 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h4 class="font-bold text-gray-900 mb-3">📊 Export Data Gudang ke Excel</h4>
                <p class="text-sm text-gray-700 mb-3">
                    Gunakan tombol <strong>"Export"</strong> untuk download riwayat transaksi dalam format Excel (.xlsx).
                </p>
                <div class="bg-white rounded-lg p-4 border border-gainsboro">
                    <p class="text-xs font-bold text-gray-700 mb-2">Format Excel:</p>
                    <ul class="text-xs text-gray-600 space-y-1">
                        <li>• Semua transaksi dengan detail lengkap</li>
                        <li>• Kolom: Tanggal, Tipe, SKU, Kategori, Nama, Jumlah, Lokasi, Kondisi</li>
                        <li>• Diurutkan berdasarkan tanggal terbaru</li>
                        <li>• Siap untuk audit dan laporan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 bg-gradient-to-r from-cyan-50 to-blue-50 border-l-4 border-cyan-500 rounded-r-xl p-6">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-cyan-600 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h4 class="font-bold text-cyan-900 mb-3">💡 Tips Data Gudang</h4>
                <ul class="space-y-2 text-sm text-cyan-800">
                    <li>• <strong>Cek stok real-time:</strong> Selalu lihat "Stok Saat Ini" sebelum input transaksi keluar</li>
                    <li>• <strong>Input rutin:</strong> Catat setiap transaksi segera agar stok akurat</li>
                    <li>• <strong>Lokasi jelas:</strong> Gunakan kode lokasi yang konsisten (misal: GudangA-Rak1)</li>
                    <li>• <strong>Filter tanggal:</strong> Gunakan filter untuk audit periode tertentu</li>
                    <li>• <strong>Export berkala:</strong> Download Excel untuk backup dan laporan bulanan</li>
                </ul>
            </div>
        </div>
    </div>
</div>