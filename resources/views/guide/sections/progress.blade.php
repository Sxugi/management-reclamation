<div x-show="activeTab === 'progress'" 
     style="display: none;" 
     data-guide-section="progress"
     data-keywords="progress, progres, target, input progress, monitoring, penanaman, revegetasi, pemeliharaan, dokumentasi, tracking, pencapaian, plot, exif, gps"
     data-title="Progress Reklamasi"
     data-desc="Set target dan input progress harian reklamasi per plot"
     data-anchor=""
     x-transition:enter="transition ease-out duration-300 transform" 
     x-transition:enter-start="opacity-0 translate-y-2" 
     x-transition:enter-end="opacity-100 translate-y-0">
    
    <span class="text-indigo-600 font-bold tracking-wider text-xs uppercase mb-2 block">Data Operasional</span>
    <h1 class="text-3xl font-extrabold text-darkslategray mb-6">Progress Reklamasi</h1>

    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 border-l-4 border-indigo-500 p-6 rounded-r-xl mb-8">
        <p class="text-gray-700 text-base leading-relaxed mb-3">
            Modul Progress Reklamasi memungkinkan Anda untuk <strong>menetapkan target</strong> dan <strong>mencatat progress harian</strong> kegiatan reklamasi <strong>per plot lahan</strong>. 
            Sistem akan otomatis menghitung <strong>persentase pencapaian</strong> berdasarkan data yang diinput.
        </p>
        <div class="bg-white rounded-lg p-4 border border-indigo-200">
            <div class="flex items-center gap-2 text-sm">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-indigo-900 font-medium">Alur Kerja: <strong>Pilih Lahan</strong> → <strong>Pilih Plot di Peta</strong> → <strong>Set Target</strong> → <strong>Input Progress Harian</strong></span>
            </div>
        </div>
    </div>

    <div class="bg-white border-2 border-blue-200 rounded-xl p-6 mb-8 shadow-sm">
        <h3 class="text-lg font-bold text-darkslategray mb-4 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
            </svg>
            Akses Modul Progress
        </h3>
        
        <div class="space-y-4">
            <div class="flex gap-4">
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm">1</div>
                <div class="flex-1">
                    <h4 class="font-bold text-darkslategray mb-2">Pilih Plot di Peta</h4>
                    <p class="text-sm text-gray-600 mb-3">
                        Dari halaman <strong>Plot Lahan</strong>, klik salah satu plot di peta untuk membuka popup. Klik <strong>"Lihat Detail"</strong>.
                    </p>
                    
                    <div class="bg-gray-50 border-2 border-dashed border-gainsboro rounded-xl p-4">
                        <img 
                            src="{{ asset('images/guide/selected-plot.png') }}" 
                            alt="Pilih plot di peta"
                            class="w-full max-w-2xl mx-auto rounded-lg border border-gainsboro shadow-md"
                            loading="lazy"
                        >
                        <p class="text-xs text-gray-500 text-center mt-3 italic">
                            💡 Klik plot di peta (area merah) untuk membuka popup detail
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm">2</div>
                <div class="flex-1">
                    <h4 class="font-bold text-darkslategray mb-2">Masuk ke Halaman Detail Plot</h4>
                    <p class="text-sm text-gray-600 mb-3">
                        Halaman detail plot menampilkan <strong>ringkasan</strong> (Luas, Total Progress, Activity Logs) dan berbagai section untuk mengelola data.
                    </p>
                    
                    <div class="bg-gray-50 border-2 border-dashed border-gainsboro rounded-xl p-4">
                        <img 
                            src="{{ asset('images/guide/detail-plot.png') }}" 
                            alt="Halaman detail plot"
                            class="w-full max-w-4xl mx-auto rounded-lg border border-gainsboro shadow-md"
                            loading="lazy"
                        >
                        <p class="text-xs text-gray-500 text-center mt-3 italic">
                            💡 Halaman detail plot dengan summary cards dan section data
                        </p>
                    </div>

                    <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-xs font-bold text-blue-900 mb-2">📋 Section yang Tersedia:</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs text-blue-800">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-teal-500 rounded-full"></span>
                                <span><strong>Target Reklamasi</strong> - Set indikator target</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-indigo-500 rounded-full"></span>
                                <span><strong>Laporan Harian</strong> - Input progress kegiatan</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
                                <span><strong>Peta Dokumentasi</strong> - Lihat GPS dari foto EXIF</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-gray-500 rounded-full"></span>
                                <span><strong>QR Code</strong> - Akses cepat via smartphone</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TARGET --}}
    <div id="section-target" class="scroll-mt-20 mb-12">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-teal-600 rounded-lg flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-darkslategray">Step 3: Set Target Reklamasi</h2>
                <p class="text-sm text-gray-600">Target harus dibuat terlebih dahulu sebelum input progress</p>
            </div>
        </div>

        <div class="bg-white border border-gainsboro rounded-xl p-6 mb-6 shadow-sm">
            <h3 class="text-lg font-bold text-darkslategray mb-4">Apa itu Target Reklamasi?</h3>
            <p class="text-sm text-gray-700 mb-4">
                Target adalah <strong>indikator pencapaian</strong> yang ingin dicapai untuk setiap plot lahan. 
                Target ini digunakan untuk menghitung <strong>persentase progress</strong> secara otomatis.
            </p>
            
            <div class="bg-gray-50 rounded-lg p-4 border border-gainsboro">
                <p class="text-xs font-bold text-gray-700 mb-3">📊 Contoh Target:</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                        <span class="text-gray-700">Penanaman Pohon Pionir: <strong>500 batang</strong></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                        <span class="text-gray-700">Revegetasi Cover Crops: <strong>2 hektar</strong></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                        <span class="text-gray-700">Perbaikan Tanah: <strong>3 hektar</strong></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
                        <span class="text-gray-700">Pemeliharaan Tanaman: <strong>1000 batang</strong></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <h3 class="text-xl font-bold text-darkslategray mb-4">Cara Set Target</h3>

            <div class="bg-gray-50 border-2 border-dashed border-gainsboro rounded-xl p-4 mb-6">
                <img 
                    src="{{ asset('images/guide/target.png') }}" 
                    alt="Empty state target reklamasi"
                    class="w-full max-w-2xl mx-auto rounded-lg border border-gainsboro shadow-md"
                    loading="lazy"
                >
                <p class="text-xs text-gray-500 text-center mt-3 italic">
                    💡 Tampilan awal section target ketika belum ada target
                </p>
            </div>

            <div class="flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-teal-500 to-teal-600 text-white flex items-center justify-center font-bold shadow-lg">1</div>
                <div class="flex-1">
                    <h4 class="font-bold text-darkslategray text-lg mb-2">Scroll ke Section "Target Reklamasi"</h4>
                    <p class="text-sm text-gray-600 mb-3">
                        Di halaman detail plot, scroll ke bawah ke section <strong>"Target Reklamasi"</strong>, lalu klik tombol <strong>"+ Tambah Target"</strong>.
                    </p>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white flex items-center justify-center font-bold shadow-lg">2</div>
                <div class="flex-1">
                    <h4 class="font-bold text-darkslategray text-lg mb-2">Pilih Indikator Target</h4>
                    <p class="text-sm text-gray-600 mb-4">
                        Centang <strong>indikator yang relevan</strong> dengan kegiatan reklamasi Anda, lalu isi nilai target.
                    </p>
                    
                    <div class="bg-gray-50 border-2 border-dashed border-gainsboro rounded-xl p-4">
                        <img 
                            src="{{ asset('images/guide/form-target.png') }}" 
                            alt="Form input target reklamasi"
                            class="w-full max-w-3xl mx-auto rounded-lg border border-gainsboro shadow-md"
                            loading="lazy"
                        >
                        <p class="text-xs text-gray-500 text-center mt-3 italic">
                            💡 Form input target dengan checkbox untuk memilih indikator
                        </p>
                    </div>

                    <div class="mt-6 bg-white border border-gainsboro rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gainsboro">
                            <p class="font-bold text-sm text-darkslategray">📋 Daftar Indikator Target</p>
                        </div>
                        <table class="w-full text-xs">
                            <thead class="bg-gray-50 text-gray-600">
                                <tr>
                                    <th class="px-4 py-2 text-left font-semibold border-b">Indikator</th>
                                    <th class="px-4 py-2 text-left font-semibold border-b">Satuan</th>
                                    <th class="px-4 py-2 text-left font-semibold border-b">Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 text-gray-700">
                                <tr>
                                    <td class="px-4 py-2">Perbaikan Tanah (Soil Amendment)</td>
                                    <td class="px-4 py-2">ha</td>
                                    <td class="px-4 py-2">Luas area yang diperbaiki tanahnya</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2">Revegetasi Cover Crops</td>
                                    <td class="px-4 py-2">ha</td>
                                    <td class="px-4 py-2">Luas area yang ditanami tanaman penutup</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2">Penanaman Pohon Pionir</td>
                                    <td class="px-4 py-2">ha</td>
                                    <td class="px-4 py-2">Luas area penanaman pohon pionir</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2">Penanaman Pohon Lokal</td>
                                    <td class="px-4 py-2">ha</td>
                                    <td class="px-4 py-2">Luas area penanaman pohon lokal</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2">Penanaman MPTS</td>
                                    <td class="px-4 py-2">ha</td>
                                    <td class="px-4 py-2">Luas area Multi-Purpose Tree Species</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2">Pemeliharaan Tanaman</td>
                                    <td class="px-4 py-2">ha</td>
                                    <td class="px-4 py-2">Luas area yang dipelihara</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-green-600 text-white flex items-center justify-center font-bold shadow-lg">3</div>
                <div class="flex-1">
                    <h4 class="font-bold text-darkslategray text-lg mb-2">Simpan Target</h4>
                    <p class="text-sm text-gray-600 mb-3">
                        Klik tombol <strong>"Save"</strong> untuk menyimpan target. Target bisa diubah kapan saja.
                    </p>
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-xs text-amber-800">
                                <p class="font-bold mb-1">💡 Catatan Penting:</p>
                                <ul class="space-y-1">
                                    <li>• Target yang sudah ada bisa diubah atau dihapus</li>
                                    <li>• Perubahan target akan <strong>recalculate progress otomatis</strong></li>
                                    <li>• Minimal pilih 1 indikator untuk bisa mulai tracking progress</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DIVIDER --}}
    <div class="border-t-2 border-gainsboro my-12"></div>

    {{-- INPUT PROGRESS --}}
    <div id="section-input-progress" class="scroll-mt-20">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-indigo-600 rounded-lg flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-darkslategray">Step 4: Input Progress Harian</h2>
                <p class="text-sm text-gray-600">Catat aktivitas reklamasi yang sudah dilakukan</p>
            </div>
        </div>

        <div class="bg-white border border-gainsboro rounded-xl p-6 mb-6 shadow-sm">
            <h3 class="text-lg font-bold text-darkslategray mb-4">Apa itu Input Progress?</h3>
            <p class="text-sm text-gray-700 mb-4">
                Progress adalah <strong>laporan harian</strong> kegiatan reklamasi yang sudah dikerjakan. 
                Data progress akan otomatis dihitung sistem untuk mengetahui <strong>persentase pencapaian target</strong>.
            </p>
            <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 rounded-r-lg">
                <p class="text-sm text-indigo-900">
                    <strong>⚠️ Penting:</strong> Target harus sudah dibuat terlebih dahulu sebelum bisa input progress.
                </p>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 mb-6">
            <h3 class="text-lg font-bold text-darkslategray mb-4">📂 Kategori & Aktivitas Reklamasi</h3>
            <p class="text-sm text-gray-700 mb-4">
                Sistem memiliki <strong>kategori aktivitas</strong> yang sudah terdefinisi. Setiap kategori memiliki <strong>jenis aktivitas spesifik</strong> dengan field input yang berbeda-beda (dinamis).
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white rounded-lg p-4 border border-blue-200">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 bg-green-100 rounded flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-.5a1.5 1.5 0 000 3h.5a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-.5a1.5 1.5 0 00-3 0v.5a1 1 0 01-1 1H6a1 1 0 01-1-1v-3a1 1 0 00-1-1h-.5a1.5 1.5 0 010-3H4a1 1 0 001-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5z"/></svg>
                        </div>
                        <span class="font-bold text-green-700">Perbaikan Tanah</span>
                    </div>
                    <ul class="text-xs text-gray-600 space-y-1">
                        <li>• Aplikasi Kompos</li>
                        <li>• Aplikasi Pupuk Organik</li>
                        <li>• Liming (Pengapuran)</li>
                    </ul>
                </div>

                <div class="bg-white rounded-lg p-4 border border-blue-200">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 bg-teal-100 rounded flex items-center justify-center">
                            <svg class="w-5 h-5 text-teal-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd"/></svg>
                        </div>
                        <span class="font-bold text-teal-700">Revegetasi</span>
                    </div>
                    <ul class="text-xs text-gray-600 space-y-1">
                        <li>• Cover Crops</li>
                        <li>• Ground Cover</li>
                    </ul>
                </div>

                <div class="bg-white rounded-lg p-4 border border-blue-200">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 bg-emerald-100 rounded flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        </div>
                        <span class="font-bold text-emerald-700">Penanaman</span>
                    </div>
                    <ul class="text-xs text-gray-600 space-y-1">
                        <li>• Penanaman Pionir</li>
                        <li>• Penanaman Lokal</li>
                        <li>• Penanaman MPTS</li>
                        <li>• Penyulaman</li>
                    </ul>
                </div>

                <div class="bg-white rounded-lg p-4 border border-blue-200">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 bg-amber-100 rounded flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <span class="font-bold text-amber-700">Pemeliharaan</span>
                    </div>
                    <ul class="text-xs text-gray-600 space-y-1">
                        <li>• Pemupukan</li>
                        <li>• Penyiangan</li>
                        <li>• Pengendalian Hama</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <h3 class="text-xl font-bold text-darkslategray mb-4">Cara Input Progress</h3>

            <div class="bg-gray-50 border-2 border-dashed border-gainsboro rounded-xl p-4 mb-6">
                <img 
                    src="{{ asset('images/guide/progress.png') }}" 
                    alt="Empty state progress reklamasi"
                    class="w-full max-w-3xl mx-auto rounded-lg border border-gainsboro shadow-md"
                    loading="lazy"
                >
                <p class="text-xs text-gray-500 text-center mt-3 italic">
                    💡 Section "Laporan Harian" untuk input progress kegiatan
                </p>
            </div>

            <div class="flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-600 text-white flex items-center justify-center font-bold shadow-lg">1</div>
                <div class="flex-1">
                    <h4 class="font-bold text-darkslategray text-lg mb-2">Klik "Add +"</h4>
                    <p class="text-sm text-gray-600">
                        Scroll ke section <strong>"Laporan Harian"</strong>, lalu klik tombol <strong>"Add +"</strong> untuk input progress baru.
                    </p>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 text-white flex items-center justify-center font-bold shadow-lg">2</div>
                <div class="flex-1">
                    <h4 class="font-bold text-darkslategray text-lg mb-2">Isi Form Progress</h4>
                    <p class="text-sm text-gray-600 mb-4">
                        Form akan menyesuaikan dengan <strong>kategori dan aktivitas</strong> yang dipilih. Field akan berubah secara <strong>dinamis</strong>.
                    </p>

                    <div class="bg-gray-50 border-2 border-dashed border-gainsboro rounded-xl p-4 mb-4">
                        <img 
                            src="{{ asset('images/guide/form-progress.png') }}" 
                            alt="Form input progress reklamasi"
                            class="w-full max-w-2xl mx-auto rounded-lg border border-gainsboro shadow-md"
                            loading="lazy"
                        >
                        <p class="text-xs text-gray-500 text-center mt-3 italic">
                            💡 Form input progress dengan field dinamis sesuai aktivitas
                        </p>
                    </div>

                    <div class="bg-white border border-gainsboro rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gainsboro">
                            <p class="font-bold text-sm text-darkslategray">📋 Penjelasan Field Form</p>
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
                                    <td class="px-4 py-2 font-medium">Tanggal <span class="text-red-500">*</span></td>
                                    <td class="px-4 py-2">Tanggal pelaksanaan kegiatan</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium">Kategori <span class="text-red-500">*</span></td>
                                    <td class="px-4 py-2">Kategori aktivitas (dropdown)</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium">Aktivitas <span class="text-red-500">*</span></td>
                                    <td class="px-4 py-2">Jenis aktivitas spesifik (menyesuaikan kategori)</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium">Field Dinamis</td>
                                    <td class="px-4 py-2">Berubah sesuai aktivitas (misal: Luas Area, Jumlah Bibit, dll)</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium">Catatan</td>
                                    <td class="px-4 py-2">Keterangan tambahan (opsional)</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium">Dokumentasi Foto</td>
                                    <td class="px-4 py-2">Upload foto kegiatan (bisa lebih dari 1 foto, <strong>EXIF GPS akan di-extract otomatis</strong>)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="section-dokumentasi" class="flex gap-4 scroll-mt-20">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-pink-500 to-pink-600 text-white flex items-center justify-center font-bold shadow-lg">3</div>
                <div class="flex-1">
                    <h4 class="font-bold text-darkslategray text-lg mb-2">Upload Dokumentasi Foto</h4>
                    <p class="text-sm text-gray-600 mb-3">
                        Upload <strong>foto dokumentasi</strong> kegiatan. Bisa upload lebih dari 1 foto sekaligus. 
                        <strong>GPS dari EXIF foto akan otomatis di-extract</strong> untuk ditampilkan di peta dokumentasi.
                    </p>
                    <div class="bg-pink-50 border border-pink-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-pink-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <div class="text-xs text-pink-800">
                                <p class="font-bold mb-1">📷 Tips Upload Foto:</p>
                                <ul class="space-y-1">
                                    <li>• Format: JPG, PNG (max 10MB per file)</li>
                                    <li>• <strong>Foto dengan GPS EXIF</strong> akan otomatis di-extract koordinatnya untuk peta</li>
                                    <li>• Pastikan foto diambil dengan <strong>GPS enabled</strong> di kamera/smartphone</li>
                                    <li>• Foto akan muncul sebagai pin di "Peta Dokumentasi" section</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-green-600 text-white flex items-center justify-center font-bold shadow-lg">4</div>
                <div class="flex-1">
                    <h4 class="font-bold text-darkslategray text-lg mb-2">Simpan Progress</h4>
                    <p class="text-sm text-gray-600 mb-3">
                        Klik <strong>"Simpan"</strong>. Sistem akan:
                    </p>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Menyimpan data progress ke database</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Otomatis <strong>recalculate persentase progress</strong> berdasarkan target</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Update <strong>snapshot timeline</strong> untuk tracking historis</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span><strong>Extract GPS dari EXIF foto</strong> untuk ditampilkan di peta</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Sync data ke <strong>inventori pohon</strong> (jika aktivitas penanaman)</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-12 bg-gradient-to-br from-purple-50 to-pink-50 border-l-4 border-purple-500 rounded-r-xl p-6">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
            </div>
            <div class="flex-1">
                <h4 class="font-bold text-purple-900 mb-3">🗺️ Peta Dokumentasi (GPS dari EXIF)</h4>
                <p class="text-sm text-purple-800 mb-3">
                    Di halaman detail plot, terdapat section <strong>"Peta Dokumentasi"</strong> yang menampilkan <strong>titik GPS dari foto dokumentasi</strong> progress yang sudah di-upload.
                </p>
                <ul class="space-y-2 text-sm text-purple-800">
                    <li>• <strong>Automatic GPS extraction:</strong> Sistem otomatis membaca GPS dari EXIF foto</li>
                    <li>• <strong>Visual mapping:</strong> Semua foto dengan GPS akan muncul sebagai pin di peta</li>
                    <li>• <strong>Click to view:</strong> Klik pin untuk melihat detail foto dan aktivitas terkait</li>
                    <li>• <strong>Activity tracking:</strong> Mudah melihat distribusi geografis kegiatan reklamasi</li>
                </ul>
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
                <h4 class="font-bold text-cyan-900 mb-3">💡 Tips Progress Reklamasi</h4>
                <ul class="space-y-2 text-sm text-cyan-800">
                    <li>• <strong>Pilih plot dari peta:</strong> Klik polygon plot di peta untuk masuk detail</li>
                    <li>• <strong>Set target realistis:</strong> Target bisa disesuaikan kapan saja</li>
                    <li>• <strong>Input harian:</strong> Catat progress setiap hari untuk tracking akurat</li>
                    <li>• <strong>Dokumentasi lengkap:</strong> Selalu upload foto <strong>dengan GPS enabled</strong></li>
                    <li>• <strong>Cek peta dokumentasi:</strong> Lihat distribusi geografis kegiatan reklamasi</li>
                    <li>• <strong>Export laporan:</strong> Gunakan tombol Export untuk cetak laporan</li>
                </ul>
            </div>
        </div>
    </div>
</div>