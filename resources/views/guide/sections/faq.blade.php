<div x-show="activeTab === 'faq'" 
     style="display: none;" 
     data-guide-section="faq"
     data-keywords="faq, pertanyaan, masalah, error, bantuan, help, troubleshoot, kendala"
     data-title="FAQ & Troubleshooting"
     data-desc="Solusi untuk masalah umum dan pertanyaan yang sering diajukan"
     data-anchor=""
     x-transition:enter="transition ease-out duration-300 transform" 
     x-transition:enter-start="opacity-0 translate-y-2" 
     x-transition:enter-end="opacity-100 translate-y-0">
    
    <span class="text-red-600 font-bold tracking-wider text-xs uppercase mb-2 block">Troubleshooting</span>
    <h1 class="text-3xl font-extrabold text-darkslategray mb-6">Pertanyaan Umum (FAQ)</h1>

    <p class="text-gray-600 text-base leading-relaxed mb-8">
        Berikut adalah solusi untuk masalah dan pertanyaan yang sering diajukan pengguna. Jika masalah Anda tidak tercantum di sini, silakan hubungi admin.
    </p>

    {{-- FAQ CATEGORIES --}}
    <div class="space-y-4">
        {{-- KATEGORI: HAK AKSES & PERMISSION --}}
        <div class="bg-purple-50 border-l-4 border-purple-500 rounded-r-lg p-4 mb-6">
            <h3 class="font-bold text-purple-900 mb-2 flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"/></svg>
                Hak Akses & Permission
            </h3>
        </div>

        <details class="group bg-white border border-gainsboro rounded-lg overflow-hidden">
            <summary class="flex justify-between items-center font-medium cursor-pointer p-4 bg-gray-50 hover:bg-gray-100 text-darkslategray">
                <span>❌ Saya tidak bisa menghapus data anggaran / lahan / progress?</span>
                <span class="transition group-open:rotate-180"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"></path></svg></span>
            </summary>
            <div class="text-gray-600 text-sm p-4 border-t border-gainsboro bg-white space-y-3">
                <p class="font-bold text-red-600">🔒 Pembatasan untuk keamanan data:</p>
                <p>Penghapusan data krusial (lahan, target, anggaran) <strong>hanya dapat dilakukan oleh</strong>:</p>
                <ul class="list-disc list-inside space-y-1 ml-4">
                    <li><strong>Admin Utama</strong> - Full access ke semua fitur</li>
                    <li><strong>Owner Lahan</strong> - Untuk lahan yang ditugaskan</li>
                </ul>
                <div class="bg-amber-50 border border-amber-200 rounded p-3 mt-3">
                    <p class="text-amber-900 text-xs"><strong>💡 Solusi:</strong> Hubungi admin jika ada kesalahan input yang harus dihapus.</p>
                </div>
            </div>
        </details>

        <details class="group bg-white border border-gainsboro rounded-lg overflow-hidden">
            <summary class="flex justify-between items-center font-medium cursor-pointer p-4 bg-gray-50 hover:bg-gray-100 text-darkslategray">
                <span>❌ Saya tidak bisa mengakses lahan tertentu?</span>
                <span class="transition group-open:rotate-180"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"></path></svg></span>
            </summary>
            <div class="text-gray-600 text-sm p-4 border-t border-gainsboro bg-white space-y-3">
                <p>Kemungkinan penyebab:</p>
                <ol class="list-decimal list-inside space-y-2 ml-4">
                    <li><strong>Anda tidak ditambahkan ke tim lahan tersebut</strong> - Minta admin untuk menambahkan Anda via menu "Access Management"</li>
                    <li><strong>Role Anda hanya Viewer</strong> - Viewer tidak bisa edit data, hanya lihat</li>
                    <li><strong>Lahan sudah diarsipkan/dihapus</strong> - Hubungi admin untuk verifikasi status lahan</li>
                </ol>
                <div class="bg-blue-50 border border-blue-200 rounded p-3 mt-3">
                    <p class="text-blue-900 text-xs"><strong>💡 Cek role Anda:</strong> Lihat di sidebar lahan → icon role (Admin/Owner/Editor/Viewer)</p>
                </div>
            </div>
        </details>

        {{-- KATEGORI: DATA & VALIDASI --}}
        <div class="bg-red-50 border-l-4 border-red-500 rounded-r-lg p-4 mb-6 mt-8">
            <h3 class="font-bold text-red-900 mb-2 flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                Data & Validasi Error
            </h3>
        </div>

        <details class="group bg-white border border-gainsboro rounded-lg overflow-hidden">
            <summary class="flex justify-between items-center font-medium cursor-pointer p-4 bg-gray-50 hover:bg-gray-100 text-darkslategray">
                <span>⚠️ Muncul error "Data Duplikat" saat input anggaran?</span>
                <span class="transition group-open:rotate-180"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"></path></svg></span>
            </summary>
            <div class="text-gray-600 text-sm p-4 border-t border-gainsboro bg-white space-y-3">
                <p class="font-bold text-red-600">❗ Penyebab:</p>
                <p>Sistem mendeteksi Anda mencoba memasukkan <strong>kategori anggaran yang sama</strong> pada <strong>bulan & tahun yang sama</strong>.</p>
                <div class="bg-gray-100 p-3 rounded border-l-4 border-red-500 mt-2">
                    <p class="text-xs font-mono">Contoh: "Pupuk Organik" di Januari 2026 sudah ada data sebelumnya</p>
                </div>
                <p class="font-bold text-green-700 mt-3">✅ Solusi:</p>
                <ol class="list-decimal list-inside space-y-1 ml-4">
                    <li>Cek tabel anggaran untuk bulan/tahun yang sama</li>
                    <li>Gunakan fitur <strong>Edit</strong> jika ingin mengubah nominal</li>
                    <li>Atau pilih bulan/kategori lain</li>
                </ol>
            </div>
        </details>

        <details class="group bg-white border border-gainsboro rounded-lg overflow-hidden">
            <summary class="flex justify-between items-center font-medium cursor-pointer p-4 bg-gray-50 hover:bg-gray-100 text-darkslategray">
                <span>⚠️ Error "Q1 harus dibuat terlebih dahulu"?</span>
                <span class="transition group-open:rotate-180"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"></path></svg></span>
            </summary>
            <div class="text-gray-600 text-sm p-4 border-t border-gainsboro bg-white space-y-3">
                <p class="font-bold text-red-600">❗ Penyebab:</p>
                <p>Sistem <strong>mewajibkan Q1 (Jan-Mar)</strong> dibuat terlebih dahulu sebelum input Q2, Q3, atau Q4 di tahun yang sama.</p>
                <p class="font-bold text-green-700 mt-3">✅ Solusi:</p>
                <ol class="list-decimal list-inside space-y-1 ml-4">
                    <li>Buat Q1 terlebih dahulu untuk tahun tersebut</li>
                    <li>Pastikan Q1 minimal memiliki 1 bulan (Januari, Februari, atau Maret)</li>
                    <li>Setelah Q1 ada, baru bisa input Q2/Q3/Q4</li>
                </ol>
                <div class="bg-blue-50 border border-blue-200 rounded p-3 mt-3">
                    <p class="text-blue-900 text-xs"><strong>💡 Info:</strong> Aturan ini untuk menjaga konsistensi data per tahun anggaran</p>
                </div>
            </div>
        </details>

        <details class="group bg-white border border-gainsboro rounded-lg overflow-hidden">
            <summary class="flex justify-between items-center font-medium cursor-pointer p-4 bg-gray-50 hover:bg-gray-100 text-darkslategray">
                <span>⚠️ Error "Barang keluar melebihi stok sisa"?</span>
                <span class="transition group-open:rotate-180"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"></path></svg></span>
            </summary>
            <div class="text-gray-600 text-sm p-4 border-t border-gainsboro bg-white space-y-3">
                <p class="font-bold text-red-600">❗ Penyebab:</p>
                <p>Jumlah <strong>barang keluar</strong> yang Anda input <strong>lebih besar dari stok tersisa</strong> di gudang.</p>
                <p class="font-bold text-green-700 mt-3">✅ Solusi:</p>
                <ol class="list-decimal list-inside space-y-1 ml-4">
                    <li>Cek <strong>"Stok Saat Ini"</strong> di section atas Data Gudang</li>
                    <li>Input jumlah keluar <strong>tidak boleh melebihi stok sisa</strong></li>
                    <li>Jika stok benar-benar habis, input sesuai stok tersisa</li>
                </ol>
            </div>
        </details>

        {{-- KATEGORI: PETA & PLOTTING --}}
        <div class="bg-orange-50 border-l-4 border-orange-500 rounded-r-lg p-4 mb-6 mt-8">
            <h3 class="font-bold text-orange-900 mb-2 flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                Peta & Plotting
            </h3>
        </div>

        <details class="group bg-white border border-gainsboro rounded-lg overflow-hidden">
            <summary class="flex justify-between items-center font-medium cursor-pointer p-4 bg-gray-50 hover:bg-gray-100 text-darkslategray">
                <span>❓ Peta tidak muncul / error loading map?</span>
                <span class="transition group-open:rotate-180"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"></path></svg></span>
            </summary>
            <div class="text-gray-600 text-sm p-4 border-t border-gainsboro bg-white space-y-3">
                <p class="font-bold text-amber-600">⚡ Kemungkinan penyebab:</p>
                <ul class="list-disc list-inside space-y-1 ml-4">
                    <li>Koneksi internet lambat atau terputus</li>
                    <li>Browser cache penuh</li>
                    <li>Ekstensi browser memblokir map tile</li>
                </ul>
                <p class="font-bold text-green-700 mt-3">✅ Solusi:</p>
                <ol class="list-decimal list-inside space-y-1 ml-4">
                    <li>Refresh halaman (Ctrl + R atau F5)</li>
                    <li>Clear cache browser (Ctrl + Shift + Del)</li>
                    <li>Nonaktifkan AdBlock/ekstensi sementara</li>
                    <li>Coba browser lain (Chrome/Firefox recommended)</li>
                </ol>
            </div>
        </details>

        <details class="group bg-white border border-gainsboro rounded-lg overflow-hidden">
            <summary class="flex justify-between items-center font-medium cursor-pointer p-4 bg-gray-50 hover:bg-gray-100 text-darkslategray">
                <span>❓ Polygon plot tidak bisa digambar?</span>
                <span class="transition group-open:rotate-180"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"></path></svg></span>
            </summary>
            <div class="text-gray-600 text-sm p-4 border-t border-gainsboro bg-white space-y-3">
                <p class="font-bold text-green-700">✅ Cara gambar polygon:</p>
                <ol class="list-decimal list-inside space-y-2 ml-4">
                    <li>Klik tombol <strong>Polygon Tool</strong> (ikon kotak) di kontrol peta</li>
                    <li>Klik di peta untuk membuat titik sudut pertama</li>
                    <li>Klik lagi untuk titik berikutnya (minimal 3 titik)</li>
                    <li><strong>Klik kembali ke titik awal</strong> untuk menutup polygon</li>
                </ol>
                <div class="bg-amber-50 border border-amber-200 rounded p-3 mt-3">
                    <p class="text-amber-900 text-xs"><strong>💡 Tips:</strong> Polygon harus tertutup (titik akhir kembali ke titik awal)</p>
                </div>
            </div>
        </details>

        {{-- KATEGORI: PROGRESS & TARGET --}}
        <div class="bg-indigo-50 border-l-4 border-indigo-500 rounded-r-lg p-4 mb-6 mt-8">
            <h3 class="font-bold text-indigo-900 mb-2 flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                Progress & Target
            </h3>
        </div>

        <details class="group bg-white border border-gainsboro rounded-lg overflow-hidden">
            <summary class="flex justify-between items-center font-medium cursor-pointer p-4 bg-gray-50 hover:bg-gray-100 text-darkslategray">
                <span>❓ Progress tidak bisa diinput karena belum ada target?</span>
                <span class="transition group-open:rotate-180"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"></path></svg></span>
            </summary>
            <div class="text-gray-600 text-sm p-4 border-t border-gainsboro bg-white space-y-3">
                <p class="font-bold text-blue-600">📌 Aturan sistem:</p>
                <p><strong>Target harus dibuat terlebih dahulu</strong> sebelum bisa input progress untuk plot tersebut.</p>
                <p class="font-bold text-green-700 mt-3">✅ Solusi:</p>
                <ol class="list-decimal list-inside space-y-1 ml-4">
                    <li>Masuk ke detail plot yang ingin diinput progress</li>
                    <li>Scroll ke section <strong>"Target Reklamasi"</strong></li>
                    <li>Klik <strong>"+ Tambah Target"</strong></li>
                    <li>Pilih minimal 1 indikator target (misal: Penanaman Pohon Pionir)</li>
                    <li>Setelah target disimpan, baru bisa input progress</li>
                </ol>
            </div>
        </details>

        <details class="group bg-white border border-gainsboro rounded-lg overflow-hidden">
            <summary class="flex justify-between items-center font-medium cursor-pointer p-4 bg-gray-50 hover:bg-gray-100 text-darkslategray">
                <span>❓ Persentase progress tidak berubah setelah input data?</span>
                <span class="transition group-open:rotate-180"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"></path></svg></span>
            </summary>
            <div class="text-gray-600 text-sm p-4 border-t border-gainsboro bg-white space-y-3">
                <p class="font-bold text-amber-600">⚡ Kemungkinan penyebab:</p>
                <ul class="list-disc list-inside space-y-1 ml-4">
                    <li>Progress diinput untuk <strong>aktivitas yang tidak ada targetnya</strong></li>
                    <li>Target terlalu besar dibanding progress saat ini (progress masih <1%)</li>
                    <li>Cache browser belum refresh</li>
                </ul>
                <p class="font-bold text-green-700 mt-3">✅ Solusi:</p>
                <ol class="list-decimal list-inside space-y-1 ml-4">
                    <li>Pastikan ada target untuk indikator yang sama dengan progress</li>
                    <li>Refresh halaman (F5)</li>
                    <li>Cek di section Target apakah indikator sudah diatur</li>
                </ol>
            </div>
        </details>

        {{-- KATEGORI: KONTAK ADMIN --}}
        <div class="bg-green-50 border-l-4 border-green-500 rounded-r-lg p-4 mb-6 mt-8">
            <h3 class="font-bold text-green-900 mb-2 flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                Hubungi Admin
            </h3>
        </div>

        <details class="group bg-white border border-gainsboro rounded-lg overflow-hidden">
            <summary class="flex justify-between items-center font-medium cursor-pointer p-4 bg-gray-50 hover:bg-gray-100 text-darkslategray">
                <span>📞 Bagaimana cara menghubungi Admin?</span>
                <span class="transition group-open:rotate-180"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"></path></svg></span>
            </summary>
            <div class="text-gray-600 text-sm p-4 border-t border-gainsboro bg-white space-y-3">
                <p class="font-bold text-green-700">✅ Cara menghubungi:</p>
                <ol class="list-decimal list-inside space-y-2 ml-4">
                    <li>Klik <strong>foto profil Anda</strong> di pojok kanan atas navbar</li>
                    <li>Pilih menu <strong>"Hubungi Admin"</strong> dari dropdown</li>
                    <li>Anda akan melihat informasi kontak admin:
                        <ul class="list-disc list-inside ml-4 mt-1 text-xs">
                            <li>📱 WhatsApp (untuk bantuan cepat)</li>
                            <li>📧 Email (untuk laporan detail)</li>
                        </ul>
                    </li>
                </ol>
                <div class="bg-green-50 border border-green-200 rounded p-3 mt-3">
                    <p class="text-green-900 text-xs"><strong>💡 Sertakan screenshot</strong> jika ada error untuk mempercepat penanganan</p>
                </div>
            </div>
        </details>
    </div>

    {{-- TIPS UMUM --}}
    <div class="mt-12 bg-gradient-to-r from-cyan-50 to-blue-50 border-l-4 border-cyan-500 rounded-r-xl p-6">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-cyan-600 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="flex-1">
                <h4 class="font-bold text-cyan-900 mb-3">💡 Tips Umum</h4>
                <ul class="space-y-2 text-sm text-cyan-800">
                    <li>• <strong>Simpan perubahan secara berkala</strong> untuk menghindari data hilang</li>
                    <li>• <strong>Gunakan browser modern</strong> (Chrome/Firefox versi terbaru)</li>
                    <li>• <strong>Refresh halaman</strong> jika data tidak update real-time</li>
                    <li>• <strong>Screenshot error message</strong> sebelum hubungi admin</li>
                    <li>• <strong>Cek role Anda</strong> jika tidak bisa akses fitur tertentu</li>
                </ul>
            </div>
        </div>
    </div>
</div>