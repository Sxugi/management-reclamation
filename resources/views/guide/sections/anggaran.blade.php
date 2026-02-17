<div x-show="activeTab === 'anggaran'" 
     style="display: none;" 
     data-guide-section="anggaran"
     data-keywords="anggaran, budget, actual, projection, forecast, quarter, q1, q2, q3, q4, input cost, nominal, kategori anggaran, tambah anggaran"
     data-title="Input Anggaran Reklamasi"
     data-desc="Input Actual, Projection, dan Forecast per Quarter"
     x-transition:enter="transition ease-out duration-300 transform" 
     x-transition:enter-start="opacity-0 translate-y-2" 
     x-transition:enter-end="opacity-100 translate-y-0">
    
    <span class="text-green-600 font-bold tracking-wider text-xs uppercase mb-2 block">Pencatatan Anggaran</span>
    <h1 class="text-3xl font-extrabold text-darkslategray mb-6">Input Anggaran Reklamasi</h1>

    <div class="space-y-8">
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-6 rounded-r-xl">
            <p class="text-gray-700 text-base leading-relaxed">
                Sistem menggunakan metode pembagian <strong>Quarter (Q1 - Q4)</strong> untuk manajemen anggaran. 
                Setiap quarter terdiri dari <strong>3 bulan berurutan</strong> yang harus diisi secara konsisten.
            </p>
            <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-3">
                <div class="bg-white rounded-lg p-3 text-center border border-green-200">
                    <div class="font-bold text-green-700">Q1</div>
                    <div class="text-xs text-gray-600">Jan - Mar</div>
                </div>
                <div class="bg-white rounded-lg p-3 text-center border border-blue-200">
                    <div class="font-bold text-blue-700">Q2</div>
                    <div class="text-xs text-gray-600">Apr - Jun</div>
                </div>
                <div class="bg-white rounded-lg p-3 text-center border border-yellow-200">
                    <div class="font-bold text-yellow-700">Q3</div>
                    <div class="text-xs text-gray-600">Jul - Sep</div>
                </div>
                <div class="bg-white rounded-lg p-3 text-center border border-orange-200">
                    <div class="font-bold text-orange-700">Q4</div>
                    <div class="text-xs text-gray-600">Oct - Dec</div>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gainsboro rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-red-50 to-orange-50 px-6 py-4 border-b border-gainsboro flex items-center gap-3">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <h3 class="font-bold text-darkslategray text-lg">Aturan Validasi Sistem</h3>
            </div>
            <div class="p-6 space-y-6">
                {{-- Rule 1: Q1 Mandatory --}}
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-bold">1</div>
                    <div class="flex-1">
                        <h4 class="font-bold text-red-700 mb-2">Q1 Harus Dibuat Terlebih Dahulu</h4>
                        <p class="text-sm text-gray-700 mb-3">
                            Sebelum membuat Q2, Q3, atau Q4, <strong>Q1 tahun tersebut harus sudah dibuat dan lengkap</strong> (3 bulan berurutan).
                        </p>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-xs">
                            <p class="font-mono text-red-800">
                                ❌ <strong>TIDAK BISA:</strong> Membuat Q2-2025 jika Q1-2025 belum ada<br>
                                ✅ <strong>HARUS:</strong> Buat Q1-2025 lengkap (Jan-Feb-Mar) → baru bisa buat Q2-2025
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Rule 2: Unique per Month --}}
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">2</div>
                    <div class="flex-1">
                        <h4 class="font-bold text-blue-700 mb-2">Unik Per Bulan & Kategori</h4>
                        <p class="text-sm text-gray-700 mb-3">
                            Satu <strong>Kategori Anggaran</strong> tidak boleh diinput dua kali di <strong>Bulan & Tahun yang sama</strong>.
                        </p>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-xs">
                            <p class="font-mono text-blue-800">
                                ❌ <strong>DUPLIKAT:</strong> Biaya Pupuk - Januari 2025 (sudah ada)<br>
                                ✅ <strong>SOLUSI:</strong> Edit data yang sudah ada, atau pilih bulan lain
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Rule 3: Sequential Months --}}
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center font-bold">3</div>
                    <div class="flex-1">
                        <h4 class="font-bold text-purple-700 mb-2">Bulan Harus Berurutan</h4>
                        <p class="text-sm text-gray-700 mb-3">
                            Dalam satu Quarter, bulan-bulan harus <strong>berurutan tanpa lompatan</strong>. Sistem akan menyarankan bulan yang bisa ditambahkan.
                        </p>
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-3 text-xs">
                            <p class="font-mono text-purple-800">
                                ✅ <strong>BENAR:</strong> Q1 → Jan, Feb, Mar (berurutan)<br>
                                ❌ <strong>SALAH:</strong> Q1 → Jan, Mar (melompati Februari)
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Rule 4: Quarter Complete (3 months) --}}
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center font-bold">4</div>
                    <div class="flex-1">
                        <h4 class="font-bold text-amber-700 mb-2">Quarter Maksimal 3 Bulan</h4>
                        <p class="text-sm text-gray-700 mb-3">
                            Setelah 3 bulan berurutan diisi, Quarter dianggap <strong>lengkap</strong>. Tidak bisa menambah bulan ke-4.
                        </p>
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 text-xs">
                            <p class="font-mono text-amber-800">
                                ✅ <strong>LENGKAP:</strong> Q2 → Apr, Mei, Jun (3 bulan)<br>
                                ❌ <strong>TIDAK BISA:</strong> Menambah Juli ke Q2 (sudah penuh)
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Rule 5: Cross-Year Support --}}
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">5</div>
                    <div class="flex-1">
                        <h4 class="font-bold text-indigo-700 mb-2">Mendukung Cross-Year Quarter</h4>
                        <p class="text-sm text-gray-700 mb-3">
                            Quarter bisa melewati tahun (misal: Q1 → Nov 2024, Des 2024, Jan 2025). Sistem otomatis membuat label <code class="bg-almostgray px-1 rounded">Q1-2024/2025</code>.
                        </p>
                        <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-3 text-xs">
                            <p class="font-mono text-indigo-800">
                                ✅ <strong>VALID:</strong> Q4-2024 → Okt 2024, Nov 2024, Des 2024<br>
                                ✅ <strong>CROSS-YEAR:</strong> Q1-2024/2025 → Nov 2024, Des 2024, Jan 2025
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Rule 6: Q1 Cannot Overlap with Previous Q4 --}}
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center font-bold">6</div>
                    <div class="flex-1">
                        <h4 class="font-bold text-rose-700 mb-2">Q1 Tidak Boleh Overlap dengan Q4 Sebelumnya</h4>
                        <p class="text-sm text-gray-700 mb-3">
                            Jika Q4 tahun sebelumnya sudah menggunakan bulan tertentu, Q1 tahun ini tidak boleh menggunakan bulan yang sama.
                        </p>
                        <div class="bg-rose-50 border border-rose-200 rounded-lg p-3 text-xs">
                            <p class="font-mono text-rose-800">
                                ❌ <strong>OVERLAP:</strong> Q4-2024 (Okt-Nov-Des) + Q1-2025 (Des-Jan-Feb)<br>
                                ✅ <strong>BENAR:</strong> Q4-2024 (Okt-Nov-Des) + Q1-2025 (Jan-Feb-Mar)
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-2xl font-bold text-darkslategray mb-6 flex items-center gap-3">
                <span class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </span>
                Langkah-Langkah Input Anggaran
            </h3>
            
            <div class="space-y-6">
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-green-600 text-white flex items-center justify-center font-bold shadow-lg">1</div>
                    <div class="flex-1">
                        <h4 class="font-bold text-darkslategray text-lg mb-2">Masuk ke Detail Lahan</h4>
                        <p class="text-sm text-gray-600">
                            Dari halaman utama, klik salah satu lahan untuk masuk ke halaman detail.
                        </p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white flex items-center justify-center font-bold shadow-lg">2</div>
                    <div class="flex-1">
                        <h4 class="font-bold text-darkslategray text-lg mb-2">Pilih Tab Jenis Anggaran</h4>
                        <p class="text-sm text-gray-600 mb-3">
                            Di halaman detail lahan, terdapat 3 tab jenis anggaran:
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                <div class="font-bold text-green-700 text-sm">📊 Actual</div>
                                <p class="text-xs text-gray-600 mt-1">Anggaran yang sudah direalisasikan</p>
                            </div>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                <div class="font-bold text-blue-700 text-sm">📈 Projection</div>
                                <p class="text-xs text-gray-600 mt-1">Proyeksi anggaran ke depan</p>
                            </div>
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-3">
                                <div class="font-bold text-purple-700 text-sm">🔮 Forecast</div>
                                <p class="text-xs text-gray-600 mt-1">Prakiraan anggaran jangka panjang</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 text-white flex items-center justify-center font-bold shadow-lg">3</div>
                    <div class="flex-1">
                        <h4 class="font-bold text-darkslategray text-lg mb-2">Klik Tombol "Add +"</h4>
                        <p class="text-sm text-gray-600 mb-4">
                            Tombol berada di pojok kanan atas tabel. Akan muncul form input.
                        </p>
                        
                        <div class="bg-gray-50 border-2 border-dashed border-gainsboro rounded-xl p-4">
                            <img 
                                src="{{ asset('images/guide/form-anggaran.png') }}" 
                                alt="Form input anggaran reklamasi"
                                class="w-full max-w-3xl mx-auto rounded-lg border border-gainsboro shadow-md"
                                loading="lazy"
                            >
                            <p class="text-xs text-gray-500 text-center mt-3 italic">
                                💡 Form input data anggaran dengan field Quarter, Tahun, Bulan, Kategori, dan Nominal
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 text-white flex items-center justify-center font-bold shadow-lg">4</div>
                    <div class="flex-1">
                        <h4 class="font-bold text-darkslategray text-lg mb-3">Isi Form Input</h4>
                        <div class="bg-white border border-gainsboro rounded-lg overflow-hidden">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-bold text-gray-700 border-b">Field</th>
                                        <th class="px-4 py-3 text-left font-bold text-gray-700 border-b">Penjelasan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-gray-700">Quarter <span class="text-red-500">*</span></td>
                                        <td class="px-4 py-3 text-gray-600">Pilih Q1, Q2, Q3, atau Q4. <strong>Q1 harus dibuat dulu.</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-gray-700">Tahun <span class="text-red-500">*</span></td>
                                        <td class="px-4 py-3 text-gray-600">Tahun anggaran (contoh: 2025)</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-gray-700">Bulan <span class="text-red-500">*</span></td>
                                        <td class="px-4 py-3 text-gray-600">Pilih bulan. Sistem akan validasi apakah bulan berurutan.</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-gray-700">Kategori <span class="text-red-500">*</span></td>
                                        <td class="px-4 py-3 text-gray-600">Pilih kategori anggaran (dikelola oleh Admin)</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-gray-700">Nominal <span class="text-red-500">*</span></td>
                                        <td class="px-4 py-3 text-gray-600">Masukkan nilai dalam Rupiah (angka saja, tanpa "Rp" atau titik)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-teal-500 to-teal-600 text-white flex items-center justify-center font-bold shadow-lg">5</div>
                    <div class="flex-1">
                        <h4 class="font-bold text-darkslategray text-lg mb-2">Simpan Data</h4>
                        <p class="text-sm text-gray-600 mb-3">
                            Klik tombol <strong>"Simpan"</strong>. Sistem akan:
                        </p>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Validasi apakah data sudah duplikat</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Cek urutan bulan dalam quarter</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Generate label quarter otomatis (misal: Q1-2025 atau Q1-2024/2025)</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Tampilkan data baru di tabel</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-cyan-50 to-blue-50 border-l-4 border-cyan-500 rounded-r-xl p-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-cyan-600 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-cyan-900 mb-3">💡 Tips Input Anggaran</h4>
                    <ul class="space-y-2 text-sm text-cyan-800">
                        <li>• <strong>Mulai dari Q1:</strong> Selalu buat Q1 dulu sebelum quarter lainnya</li>
                        <li>• <strong>Isi berurutan:</strong> Jangan skip bulan, isi dari awal quarter sampai akhir</li>
                        <li>• <strong>Gunakan Edit:</strong> Jika salah input, gunakan tombol Edit, jangan buat baru</li>
                        <li>• <strong>Export Excel:</strong> Gunakan fitur export untuk backup dan laporan</li>
                        <li>• <strong>Cross-year otomatis:</strong> Sistem otomatis detect jika quarter melewati tahun</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>