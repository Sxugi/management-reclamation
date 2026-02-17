<div x-show="activeTab === 'plot'" 
     style="display: none;" 
     data-guide-section="plot"
     data-keywords="plot, plotting, polygon, gis, peta, map, area, gambar lahan, koordinat, menggambar area, marking area"
     data-title="Plot Lahan (GIS)"
     data-desc="Gambar polygon dan hitung luas area"
     x-transition:enter="transition ease-out duration-300 transform" 
     x-transition:enter-start="opacity-0 translate-y-2" 
     x-transition:enter-end="opacity-100 translate-y-0">
    
    <span class="text-orange-600 font-bold tracking-wider text-xs uppercase mb-2 block">Pemetaan Area</span>
    <h1 class="text-3xl font-extrabold text-darkslategray mb-6">Plotting Lahan (GIS)</h1>

    <div class="prose max-w-none text-gray-600">
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8 rounded-r-lg flex gap-3">
            <svg class="w-6 h-6 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <div>
                <p class="text-sm text-yellow-800 font-bold">Akses Terbatas</p>
                <p class="text-xs text-yellow-700">Fitur menambah plot hanya tersedia untuk user dengan role <strong>Admin, Owner,</strong> atau <strong>Editor</strong>.</p>
            </div>
        </div>

        <p class="mb-6">
            Fitur ini memungkinkan Anda membagi satu lahan besar menjadi beberapa blok area (Plot) secara visual di atas peta. Luas area akan dihitung otomatis berdasarkan gambar yang Anda buat.
        </p>

        <h3 class="text-xl font-bold text-darkslategray mb-4">Langkah Menambah Plot Baru</h3>
        
        <div class="space-y-8">
            <div class="flex gap-4">
                <div class="flex-shrink-0 w-8 h-8 bg-darkslategray text-white rounded-full flex items-center justify-center font-bold">1</div>
                <div class="flex-1">
                    <h4 class="font-bold text-darkslategray mb-2">Masuk ke Menu Plot</h4>
                    <p class="text-sm mb-2">Dari Dashboard Lahan, pilih menu <strong>"Plot Lahan"</strong> di sidebar kiri.</p>
                    <p class="text-sm">Klik tombol <strong>"Add +"</strong> di pojok kanan atas peta.</p>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="flex-shrink-0 w-8 h-8 bg-darkslategray text-white rounded-full flex items-center justify-center font-bold">2</div>
                <div class="flex-1">
                    <h4 class="font-bold text-darkslategray mb-3">Gambar Polygon di Peta</h4>
                    <p class="text-sm mb-6 text-gray-700">Ada dua cara untuk membentuk area plot:</p>
                    
                    <div class="mb-8 bg-blue-50 border-l-4 border-blue-500 rounded-r-lg overflow-hidden">
                        <div class="p-5">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="flex-shrink-0 w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-bold text-blue-900 text-base">Cara A: Menggambar di Peta</h5>
                                    <span class="text-xs text-blue-700 font-medium">⭐ Recommended</span>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg p-4 mb-4 border border-blue-200">
                                <p class="text-sm text-gray-700 font-medium mb-3">📍 Langkah-langkah:</p>
                                <ol class="list-decimal list-inside space-y-2 text-sm text-gray-700 pl-2">
                                    <li>Klik tombol <strong class="text-blue-600">Polygon Tool</strong> (ikon kotak) pada kontrol peta.</li>
                                    <li>Klik di peta untuk membuat titik sudut pertama.</li>
                                    <li>Klik lagi untuk membuat titik berikutnya hingga membentuk area.</li>
                                    <li>Klik kembali ke titik awal untuk menutup polygon.</li>
                                </ol>
                            </div>

                            <div class="bg-white rounded-lg p-3 border border-gainsboro">
                                <img 
                                    src="{{ asset('images/guide/plot-tool.png') }}" 
                                    alt="Cara menggambar polygon di peta" 
                                    class="w-full rounded border border-gainsboro shadow-sm"
                                    loading="lazy"
                                >
                                <p class="text-xs text-gray-500 text-center mt-2 italic">
                                    💡 Klik pada peta untuk membuat titik polygon
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 border-l-4 border-gainsboro rounded-r-lg overflow-hidden">
                        <div class="p-5">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="flex-shrink-0 w-10 h-10 bg-gray-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-bold text-gray-900 text-base">Cara B: Input Manual Koordinat</h5>
                                    <span class="text-xs text-gray-600 font-medium">Untuk data GPS presisi</span>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg p-4 mb-4 border border-gainsboro">
                                <p class="text-sm text-gray-700 font-medium mb-3">🧭 Langkah-langkah:</p>
                                <ol class="list-decimal list-inside space-y-2 text-sm text-gray-700 pl-2">
                                    <li>Scroll ke bawah ke bagian <strong>"Input Koordinat"</strong>.</li>
                                    <li>Masukkan <strong>Latitude</strong> dan <strong>Longitude</strong> untuk setiap titik sudut.</li>
                                    <li>Klik tombol <strong>"Tambah Titik +"</strong> untuk menambah sudut polygon.</li>
                                    <li>Ulangi hingga semua titik sudut terdaftar.</li>
                                </ol>
                            </div>

                            <div class="bg-white rounded-lg p-3 border border-gainsboro">
                                <img 
                                    src="{{ asset('images/guide/coordinate-input.png') }}" 
                                    alt="Form input koordinat manual" 
                                    class="w-full rounded border border-gainsboro shadow-sm"
                                    loading="lazy"
                                >
                                <p class="text-xs text-gray-500 text-center mt-2 italic">
                                    💡 Input Latitude & Longitude secara manual
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 bg-amber-50 border border-amber-200 rounded-lg p-4">
                        <div class="flex gap-2">
                            <svg class="w-5 h-5 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-bold text-amber-900">💡 Tips Memilih Metode:</p>
                                <ul class="text-xs text-amber-800 space-y-1 mt-2">
                                    <li>• Gunakan <strong>Cara A (Gambar)</strong> jika Anda melihat langsung di peta</li>
                                    <li>• Gunakan <strong>Cara B (Manual)</strong> jika Anda memiliki data koordinat GPS yang akurat</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="flex-shrink-0 w-8 h-8 bg-darkslategray text-white rounded-full flex items-center justify-center font-bold">3</div>
                <div class="flex-1">
                    <h4 class="font-bold text-darkslategray mb-3">Verifikasi & Simpan</h4>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <ul class="space-y-3 text-sm">
                            <li class="flex gap-3">
                                <span class="flex-shrink-0 w-6 h-6 bg-green-600 text-white rounded-full flex items-center justify-center text-xs font-bold">✓</span>
                                <div>
                                    <strong class="text-green-900">Nama Plot:</strong>
                                    <p class="text-gray-700 text-xs mt-1">Sistem akan otomatis mengisi (contoh: <code class="bg-gray-50 px-1 py-0.5 rounded text-xs">Blok A</code>). Pastikan nama ini unik dalam satu lahan.</p>
                                </div>
                            </li>
                            <li class="flex gap-3">
                                <span class="flex-shrink-0 w-6 h-6 bg-green-600 text-white rounded-full flex items-center justify-center text-xs font-bold">✓</span>
                                <div>
                                    <strong class="text-green-900">Luas Area:</strong>
                                    <p class="text-gray-700 text-xs mt-1">Akan terhitung otomatis dalam satuan <strong>Hektar (Ha)</strong> berdasarkan besarnya gambar polygon Anda.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="mt-4 flex items-center gap-2">
                        <span class="bg-darkslategray text-white px-4 py-2 rounded-lg text-sm font-bold">Save</span>
                        <span class="text-sm text-gray-600">← Klik tombol ini untuk menyimpan plot</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>