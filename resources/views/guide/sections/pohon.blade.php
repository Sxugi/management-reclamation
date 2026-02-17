<div x-show="activeTab === 'pohon'" 
     style="display: none;" 
     data-guide-section="pohon"
     data-keywords="pohon, tree, inventarisasi, inventory, jenis pohon, species, penanaman, planting, realisasi progres, input manual, monitoring, survival rate, pertumbuhan"
     data-title="Data Pohon & Biologi"
     data-desc="Inventarisasi dan monitoring data pohon reklamasi"
     data-anchor=""
     x-transition:enter="transition ease-out duration-300 transform" 
     x-transition:enter-start="opacity-0 translate-y-2" 
     x-transition:enter-end="opacity-100 translate-y-0">
    
    <span class="text-emerald-600 font-bold tracking-wider text-xs uppercase mb-2 block">Data Biologis</span>
    <h1 class="text-3xl font-extrabold text-darkslategray mb-6">Data Pohon & Inventarisasi</h1>

    <div class="bg-gradient-to-r from-emerald-50 to-green-50 border-l-4 border-emerald-500 p-6 rounded-r-xl mb-8">
        <p class="text-gray-700 text-base leading-relaxed mb-3">
            Modul Data Pohon menyimpan <strong>inventarisasi lengkap</strong> pohon yang ditanam dalam proses reklamasi. 
            Data ini mencakup <strong>jenis pohon, jumlah batang per tahun, dan lokasi penanaman</strong>.
        </p>
        <div class="bg-white rounded-lg p-4 border border-emerald-200">
            <div class="flex items-center gap-2 text-sm">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-emerald-900 font-medium">Data pohon berasal dari <strong>2 sumber:</strong> Realisasi Progress (auto-sync) & Input Manual</span>
            </div>
        </div>
    </div>

    <div class="bg-white border-2 border-emerald-200 rounded-xl p-6 mb-8 shadow-sm">
        <h3 class="text-lg font-bold text-darkslategray mb-4 flex items-center gap-2">
            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Sumber Data Pohon
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-green-50 border-l-4 border-green-500 rounded-r-lg p-4">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h4 class="font-bold text-green-800">1. Realisasi Progress</h4>
                </div>
                <p class="text-sm text-green-700 mb-2">
                    Data <strong>otomatis ter-sync</strong> dari input progress penanaman (Penanaman Pionir, Lokal, MPTS, Penyulaman).
                </p>
                <div class="bg-white rounded-lg p-3 mt-2 border border-green-200">
                    <p class="text-xs text-gray-700"><strong>Contoh:</strong></p>
                    <ul class="text-xs text-gray-600 space-y-1 mt-1">
                        <li>• Penanaman Pionir - Jati: 50 batang di Blok A (2026)</li>
                        <li>• Auto-sync ke inventory pohon</li>
                        <li>• Tidak bisa diedit manual (read-only)</li>
                    </ul>
                </div>
            </div>

            <div class="bg-blue-50 border-l-4 border-blue-500 rounded-r-lg p-4">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <h4 class="font-bold text-blue-800">2. Input Manual</h4>
                </div>
                <p class="text-sm text-blue-700 mb-2">
                    Data yang <strong>diinput manual</strong> oleh user untuk stok pohon yang tidak terkait plot tertentu (lahan-wide).
                </p>
                <div class="bg-white rounded-lg p-3 mt-2 border border-blue-200">
                    <p class="text-xs text-gray-700"><strong>Contoh:</strong></p>
                    <ul class="text-xs text-gray-600 space-y-1 mt-1">
                        <li>• Stok umum: Mahoni 200 batang (2026)</li>
                        <li>• Tidak terikat plot spesifik</li>
                        <li>• Bisa diedit/hapus kapan saja</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- DATA POHON --}}
    <div id="section-data-pohon" class="scroll-mt-20 mb-12">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-emerald-600 rounded-lg flex items-center justify-center">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12.707 2.293C12.5195 2.10553 12.2652 2.00021 12 2.00021C11.7349 2.00021 11.4805 2.10553 11.293 2.293L7.29302 6.293C7.1414 6.44464 7.04255 6.641 7.01104 6.85311C6.97953 7.06522 7.01703 7.28183 7.11802 7.471C7.36502 7.934 7.75102 8.246 8.12802 8.458L5.29302 11.293C5.10555 11.4805 5.00023 11.7348 5.00023 12C5.00023 12.2652 5.10555 12.5195 5.29302 12.707C5.77102 13.185 6.37502 13.477 6.92702 13.659L4.29302 16.293C4.17228 16.4139 4.08452 16.5636 4.0381 16.7281C3.99169 16.8925 3.98817 17.066 4.02788 17.2322C4.06759 17.3983 4.1492 17.5516 4.26494 17.6772C4.38068 17.8029 4.52668 17.8968 4.68902 17.95C5.37902 18.177 6.09202 18.338 6.80402 18.48C7.58402 18.637 8.55002 18.792 9.64902 18.892L9.05102 20.684C9.00095 20.8343 8.98732 20.9944 9.01125 21.151C9.03518 21.3077 9.096 21.4564 9.18868 21.5849C9.28136 21.7134 9.40325 21.8181 9.54431 21.8903C9.68537 21.9624 9.84157 22 10 22H14C14.1585 22 14.3147 21.9624 14.4557 21.8903C14.5968 21.8181 14.7187 21.7134 14.8114 21.5849C14.904 21.4564 14.9649 21.3077 14.9888 21.151C15.0127 20.9944 14.9991 20.8343 14.949 20.684L14.351 18.892C15.451 18.792 16.417 18.637 17.196 18.481C17.908 18.338 18.626 18.177 19.316 17.949C19.4778 17.8951 19.6231 17.8008 19.7381 17.675C19.8531 17.5491 19.9341 17.396 19.9733 17.23C20.0125 17.0641 20.0086 16.8909 19.962 16.7269C19.9154 16.5629 19.8276 16.4135 19.707 16.293L17.073 13.659C17.625 13.478 18.229 13.185 18.707 12.707C18.8945 12.5195 18.9998 12.2652 18.9998 12C18.9998 11.7348 18.8945 11.4805 18.707 11.293L15.872 8.458C16.249 8.246 16.635 7.934 16.882 7.471C16.983 7.28183 17.0205 7.06522 16.989 6.85311C16.9575 6.641 16.8586 6.44464 16.707 6.293L12.707 2.293Z" fill="white"/>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-darkslategray">Inventarisasi Data Pohon</h2>
                <p class="text-sm text-gray-600">Lihat detail stok pohon per jenis dan tahun</p>
            </div>
        </div>

        <div class="bg-white border border-gainsboro rounded-xl p-6 mb-6 shadow-sm">
            <h3 class="text-lg font-bold text-darkslategray mb-4">Cara Mengakses Data Pohon</h3>
            <p class="text-sm text-gray-700 mb-4">
                Dari <strong>Dashboard Lahan</strong>, klik menu <strong>"Data Pohon"</strong> di sidebar untuk melihat inventarisasi lengkap.
            </p>

            <div class="bg-gray-50 border-2 border-dashed border-gainsboro rounded-xl p-4 mb-4">
                <img 
                    src="{{ asset('images/guide/pohon.png') }}" 
                    alt="List inventarisasi pohon"
                    class="w-full max-w-4xl mx-auto rounded-lg border border-gainsboro shadow-md"
                    loading="lazy"
                >
                <p class="text-xs text-gray-500 text-center mt-3 italic">
                    💡 Tabel inventarisasi pohon dengan filter, export, dan tombol Add
                </p>
            </div>

            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                <p class="text-xs font-bold text-emerald-900 mb-2">📋 Kolom Tabel:</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs text-emerald-800">
                    <div>• <strong>Jenis Pohon:</strong> Nama spesies pohon</div>
                    <div>• <strong>Kategori:</strong> Pionir, Lokal, MPTS</div>
                    <div>• <strong>Tahun Tanam:</strong> Tahun penanaman</div>
                    <div>• <strong>Total:</strong> Jumlah batang keseluruhan</div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <h3 class="text-xl font-bold text-darkslategray mb-4">Detail Data Pohon</h3>

            <div class="flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 text-white flex items-center justify-center font-bold shadow-lg">1</div>
                <div class="flex-1">
                    <h4 class="font-bold text-darkslategray text-lg mb-2">Klik Row Pohon</h4>
                    <p class="text-sm text-gray-600 mb-3">
                        Dari list pohon, klik salah satu row untuk membuka <strong>detail lengkap</strong> data pohon tersebut.
                    </p>

                    <div class="bg-gray-50 border-2 border-dashed border-gainsboro rounded-xl p-4">
                        <img 
                            src="{{ asset('images/guide/detail-pohon-modal.png') }}" 
                            alt="Modal detail data pohon"
                            class="w-full max-w-3xl mx-auto rounded-lg border border-gainsboro shadow-md"
                            loading="lazy"
                        >
                        <p class="text-xs text-gray-500 text-center mt-3 italic">
                            💡 Modal detail pohon dengan breakdown per tahun dan lokasi
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white flex items-center justify-center font-bold shadow-lg">2</div>
                <div class="flex-1">
                    <h4 class="font-bold text-darkslategray text-lg mb-2">Lihat Rincian Data</h4>
                    <p class="text-sm text-gray-600 mb-4">
                        Modal detail menampilkan <strong>rincian per tahun</strong> dengan 2 tipe data:
                    </p>

                    <div class="bg-white border border-gainsboro rounded-lg overflow-hidden mb-4">
                        <table class="w-full text-xs">
                            <thead class="bg-gray-50 text-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-left font-bold border-b">Tipe</th>
                                    <th class="px-4 py-3 text-left font-bold border-b">Deskripsi</th>
                                    <th class="px-4 py-3 text-left font-bold border-b">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gainsboro text-gray-700">
                                <tr>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium">
                                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                            Realisasi Progres
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">Data auto-sync dari input progress penanaman. Menampilkan lokasi plot.</td>
                                    <td class="px-4 py-3 text-gray-400 italic">Read-only</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium">
                                            <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                            Input Manual
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">Data yang diinput manual oleh user. Tidak terkait plot.</td>
                                    <td class="px-4 py-3">
                                        <div class="flex gap-2">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-gray-50 border border-gainsboro rounded-lg p-4">
                        <p class="text-xs font-bold text-gray-700 mb-2">📊 Total Summary:</p>
                        <div class="grid grid-cols-3 gap-3 text-center text-xs">
                            <div class="bg-white rounded-lg p-3 border border-gainsboro">
                                <div class="text-green-600 font-bold text-lg">75</div>
                                <div class="text-gray-600">Total Realisasi</div>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-gainsboro">
                                <div class="text-blue-600 font-bold text-lg">25</div>
                                <div class="text-gray-600">Total Manual</div>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-gainsboro">
                                <div class="text-emerald-600 font-bold text-lg">100</div>
                                <div class="text-gray-600">Grand Total</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DIVIDER --}}
    <div class="border-t-2 border-gainsboro my-12"></div>

    {{-- INPUT MANUAL --}}
    <div id="section-data-gudang" class="scroll-mt-20">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-darkslategray">Input Manual Data Pohon</h2>
                <p class="text-sm text-gray-600">Tambah stok pohon secara manual (lahan-wide)</p>
            </div>
        </div>

        <div class="bg-white border border-gainsboro rounded-xl p-6 mb-6 shadow-sm">
            <h3 class="text-lg font-bold text-darkslategray mb-4">Kapan Menggunakan Input Manual?</h3>
            <p class="text-sm text-gray-700 mb-4">
                Input manual digunakan untuk mencatat <strong>stok pohon umum</strong> yang tidak terkait dengan plot tertentu atau progress penanaman spesifik.
            </p>

            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                <p class="text-sm text-blue-900 mb-2"><strong>💡 Contoh Use Case:</strong></p>
                <ul class="text-xs text-blue-800 space-y-1">
                    <li>• Stok bibit di gudang yang belum ditanam</li>
                    <li>• Data historis pohon yang ditanam sebelum sistem ada</li>
                    <li>• Pohon hasil donasi atau dari pihak ketiga</li>
                    <li>• Data agregat yang tidak perlu breakdown per plot</li>
                </ul>
            </div>
        </div>

        <div class="space-y-6">
            <h3 class="text-xl font-bold text-darkslategray mb-4">Cara Input Manual</h3>

            <div class="flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white flex items-center justify-center font-bold shadow-lg">1</div>
                <div class="flex-1">
                    <h4 class="font-bold text-darkslategray text-lg mb-2">Klik "Add +"</h4>
                    <p class="text-sm text-gray-600">
                        Dari halaman <strong>List Pohon</strong>, klik tombol <strong>"Add +"</strong> untuk membuka form input manual.
                    </p>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 text-white flex items-center justify-center font-bold shadow-lg">2</div>
                <div class="flex-1">
                    <h4 class="font-bold text-darkslategray text-lg mb-2">Isi Form Input</h4>
                    <p class="text-sm text-gray-600 mb-4">
                        Form input manual hanya memerlukan 3 field sederhana:
                    </p>

                    <div class="bg-gray-50 border-2 border-dashed border-gainsboro rounded-xl p-4 mb-4">
                        <img 
                            src="{{ asset('images/guide/form-pohon.png') }}" 
                            alt="Form input manual data pohon"
                            class="w-full max-w-2xl mx-auto rounded-lg border border-gainsboro shadow-md"
                            loading="lazy"
                        >
                        <p class="text-xs text-gray-500 text-center mt-3 italic">
                            💡 Form input manual dengan 3 field: Jenis Pohon, Tahun, Jumlah
                        </p>
                    </div>

                    <div class="bg-white border border-gainsboro rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gainsboro">
                            <p class="font-bold text-sm text-darkslategray">📋 Penjelasan Field</p>
                        </div>
                        <table class="w-full text-xs">
                            <thead class="bg-gray-50 text-gray-600">
                                <tr>
                                    <th class="px-4 py-2 text-left font-semibold border-b">Field</th>
                                    <th class="px-4 py-2 text-left font-semibold border-b">Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 text-gray-700">
                                <tr>
                                    <td class="px-4 py-2 font-medium">Jenis Pohon <span class="text-red-500">*</span></td>
                                    <td class="px-4 py-2">Pilih spesies pohon dari dropdown (master data)</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium">Tahun Tanam <span class="text-red-500">*</span></td>
                                    <td class="px-4 py-2">Tahun penanaman atau pencatatan stok</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium">Jumlah Batang <span class="text-red-500">*</span></td>
                                    <td class="px-4 py-2">Total batang yang akan dicatat (minimal 1 batang)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-green-600 text-white flex items-center justify-center font-bold shadow-lg">3</div>
                <div class="flex-1">
                    <h4 class="font-bold text-darkslategray text-lg mb-2">Simpan Data</h4>
                    <p class="text-sm text-gray-600 mb-3">
                        Klik <strong>"Save"</strong> untuk menyimpan. Data akan muncul di list dengan label <strong>"Input Manual"</strong>.
                    </p>

                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-xs text-amber-800">
                                <p class="font-bold mb-1">💡 Catatan Penting:</p>
                                <ul class="space-y-1">
                                    <li>• Data manual <strong>tidak terikat plot</strong> tertentu</li>
                                    <li>• Bisa <strong>diedit atau dihapus</strong> kapan saja</li>
                                    <li>• Minimal input 1 batang per entri</li>
                                    <li>• Data ini untuk stok <strong>lahan-wide</strong>, bukan per plot</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-12 bg-gradient-to-br from-gray-50 to-slate-50 border-l-4 border-gainsboro rounded-r-xl p-6">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-gray-600 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h4 class="font-bold text-gray-900 mb-3">📊 Export Data Pohon ke Excel</h4>
                <p class="text-sm text-gray-700 mb-3">
                    Gunakan tombol <strong>"Export"</strong> untuk download inventarisasi pohon dalam format Excel (.xlsx).
                </p>
                <div class="bg-white rounded-lg p-4 border border-gainsboro">
                    <p class="text-xs font-bold text-gray-700 mb-2">Format Excel:</p>
                    <ul class="text-xs text-gray-600 space-y-1">
                        <li>• Breakdown per jenis pohon (rows)</li>
                        <li>• Kolom per tahun (columns) dengan total</li>
                        <li>• Grand total di baris terakhir</li>
                        <li>• Data sudah merge realisasi + manual</li>
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
                <h4 class="font-bold text-cyan-900 mb-3">💡 Tips Data Pohon</h4>
                <ul class="space-y-2 text-sm text-cyan-800">
                    <li>• <strong>Realisasi Progres:</strong> Auto-sync, tidak perlu input manual untuk data penanaman</li>
                    <li>• <strong>Input Manual:</strong> Hanya untuk stok umum yang tidak terkait plot</li>
                    <li>• <strong>Filter tahun:</strong> Gunakan filter untuk lihat data tahun tertentu</li>
                    <li>• <strong>Export Excel:</strong> Download untuk laporan dan backup data</li>
                    <li>• <strong>Monitoring:</strong> Lihat "Analisis Trend Monitoring" untuk survival rate</li>
                </ul>
            </div>
        </div>
    </div>
</div>