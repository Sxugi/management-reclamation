<div x-show="activeTab === 'lahan'" 
     style="display: none;" 
     data-guide-section="lahan"
     data-keywords="lahan, tambah lahan, create land, master data, lokasi, peta lahan, koordinat, marker, map, gis lahan, area, tambah area"
     data-title="Manajemen Lahan"
     data-desc="Cara menambahkan lahan baru dan set lokasi"
     x-transition:enter="transition ease-out duration-300 transform" 
     x-transition:enter-start="opacity-0 translate-y-2" 
     x-transition:enter-end="opacity-100 translate-y-0">
    
    <span class="text-blue-600 font-bold tracking-wider text-xs uppercase mb-2 block">Master Data</span>
    <h1 class="text-3xl font-extrabold text-darkslategray mb-6">Manajemen Lahan</h1>

    <div class="prose max-w-none text-gray-600">
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-8 rounded-r-lg">
            <p class="text-sm text-blue-900 font-medium">
                <strong>Penting:</strong> Data lahan adalah data induk. Pastikan Luas (Ha) diisi dengan akurat karena mempengaruhi perhitungan di Dashboard.
            </p>
        </div>

        <h3 class="text-xl font-bold text-darkslategray mb-4">Cara Menambahkan Lahan Baru</h3>
        <ol class="list-decimal list-inside space-y-3 mb-8 bg-white p-6 rounded-xl border border-gainsboro">
            <li>Buka halaman <strong>List Lahan</strong> (Halaman Utama).</li>
            <li>Klik tombol <span class="bg-darkslategray text-white px-2 py-1 rounded text-xs font-bold">Tambah Lahan +</span> di pojok kanan atas tabel.</li>
            <li>Isi form yang muncul:
                <ul class="list-disc list-inside ml-5 mt-2 space-y-1 text-sm">
                    <li><strong>Nama Lahan:</strong> Nama area (Misal: Pit 3 Blok A).</li>
                    <li><strong>Luas:</strong> Gunakan titik (.) untuk desimal.</li>
                    <li><strong>Periode & PIC:</strong> Informasi tambahan periode proyek dan penanggung jawab.</li>
                </ul>
            </li>
            <li>Lakukan penentuan titik lokasi pada peta (lihat panduan di bawah).</li>
            <li>Klik <strong>Simpan</strong>.</li>
        </ol>

        <h3 class="text-xl font-bold text-darkslategray mb-4">Menentukan Lokasi (Titik Koordinat)</h3>
        <div class="bg-white p-6 rounded-xl border border-gainsboro mb-8 shadow-sm">
            <p class="text-gray-600 mb-4 text-sm">
                Sistem menggunakan peta interaktif untuk merekam lokasi geografis lahan. Hal ini berguna untuk visualisasi pada Dashboard utama.
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <h4 class="font-bold text-darkslategray text-sm">Langkah-langkah:</h4>
                    <ol class="list-decimal list-inside space-y-3 text-sm text-gray-700">
                        <li>
                            <strong>Cari Lokasi:</strong> Gunakan kolom pencarian <em>"Cari lokasi..."</em> di pojok kiri atas peta untuk menemukan desa, kecamatan, atau area terdekat.
                        </li>
                        <li>
                            <strong>Navigasi Peta:</strong> Gunakan tombol <strong>+ / -</strong> untuk memperbesar atau memperkecil tampilan (Zoom). Anda juga bisa menggeser peta dengan cara klik & tahan (Drag).
                        </li>
                        <li>
                            <strong>Tandai Titik:</strong> Klik satu kali pada area peta yang menjadi titik pusat lahan Anda. 
                            <span class="block mt-1 text-xs text-blue-600 bg-blue-50 p-2 rounded">
                                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Sebuah marker biru akan muncul di titik yang Anda klik.
                            </span>
                        </li>
                        <li>
                            <strong>Ganti Tampilan:</strong> Jika perlu, Anda bisa mengubah jenis peta (Satelit/Jalan) menggunakan tombol <em>ArcGIS Hybrid</em> atau <em>OpenStreetMap</em> di bagian bawah peta.
                        </li>
                    </ol>
                </div>
                
                <div class="bg-gray-50 rounded-lg flex flex-col items-center justify-center p-4 text-center border-2 border-dashed border-gainsboro">
                    <div class="relative w-full h-60 bg-gray-200 rounded mb-2 overflow-hidden flex items-center justify-center">
                        <img src="{{ asset('images/guide/map-lahan.png') }}" alt="Contoh Peta" class="object-cover w-full h-full">
                        <svg class="w-8 h-8 text-red-500 absolute -mt-4 drop-shadow-md animate-bounce" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                    </div>
                    <span class="text-xs text-gray-500 font-medium">Klik peta untuk menaruh pin lokasi</span>
                </div>
            </div>
        </div>

        <h3 class="text-xl font-bold text-darkslategray mb-4">Status Lahan</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="flex items-center gap-3 p-3 border border-gainsboro rounded-lg bg-white">
                <span class="w-3 h-3 rounded-full bg-green-500 shadow-sm shadow-green-200"></span>
                <div>
                    <span class="font-bold text-darkslategray block text-sm">Active</span>
                    <span class="text-xs text-gray-500">Lahan sedang dalam proses reklamasi berjalan.</span>
                </div>
            </div>
            <div class="flex items-center gap-3 p-3 border border-gainsboro rounded-lg bg-white">
                <span class="w-3 h-3 rounded-full bg-gray-400 shadow-sm"></span>
                <div>
                    <span class="font-bold text-darkslategray block text-sm">Inactive/Done</span>
                    <span class="text-xs text-gray-500">Proses reklamasi selesai atau dihentikan sementara.</span>
                </div>
            </div>
        </div>
    </div>
</div>