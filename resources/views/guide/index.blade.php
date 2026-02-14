<x-guide-layout>

    <div x-show="activeTab === 'intro'" 
         x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        
        <h1 class="text-3xl font-extrabold text-darkslategray mb-4">Pendahuluan</h1>
        <p class="text-lg text-gray-600 leading-relaxed mb-8">
            Selamat datang di <strong>Pusat Bantuan Sistem Reklamasi</strong>. Panduan ini dirancang untuk membantu Anda memahami fitur-fitur penting dalam pengelolaan anggaran dan lahan pertambangan.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div @click="activeTab = 'lahan'" class="cursor-pointer bg-white border border-gainsboro rounded-xl p-6 hover:shadow-md hover:border-blue-300 transition-all group">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 8L18 5L12 2V12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M8.00011 11.99L2.50011 15.13C2.34621 15.2172 2.21821 15.3437 2.12915 15.4965C2.04009 15.6494 1.99316 15.8231 1.99316 16C1.99316 16.1769 2.04009 16.3506 2.12915 16.5035C2.21821 16.6563 2.34621 16.7828 2.50011 16.87L11.0001 21.73C11.3042 21.9055 11.649 21.9979 12.0001 21.9979C12.3512 21.9979 12.6961 21.9055 13.0001 21.73L21.5001 16.87C21.654 16.7828 21.782 16.6563 21.8711 16.5035C21.9601 16.3506 22.0071 16.1769 22.0071 16C22.0071 15.8231 21.9601 15.6494 21.8711 15.4965C21.782 15.3437 21.654 15.2172 21.5001 15.13L16.0001 12M6.49011 12.85L17.5101 19.15M17.5101 12.85L6.50011 19.15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>                
                </div>
                <h3 class="font-bold text-lg text-darkslategray mb-2">Manajemen Lahan</h3>
                <p class="text-sm text-gray-500">Cara menambahkan data lahan baru, input peta lokasi, dan memahami status lahan.</p>
            </div>

            <div @click="activeTab = 'anggaran'" class="cursor-pointer bg-white border border-gainsboro rounded-xl p-6 hover:shadow-md hover:border-green-300 transition-all group">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4 text-green-600 group-hover:bg-green-600 group-hover:text-white transition-colors">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 16C11.45 16 10.9793 15.8043 10.588 15.413C10.1967 15.0217 10.0007 14.5507 10 14C9.99933 13.4493 10.1953 12.9787 10.588 12.588C10.9807 12.1973 11.4513 12.0013 12 12C12.5487 11.9987 13.0197 12.1947 13.413 12.588C13.8063 12.9813 14.002 13.452 14 14C13.998 14.548 13.8023 15.019 13.413 15.413C13.0237 15.807 12.5527 16.0027 12 16ZM7.375 7H16.625L17.9 4.45C18.0667 4.11667 18.054 3.79167 17.862 3.475C17.67 3.15833 17.3827 3 17 3H7C6.61667 3 6.32933 3.15833 6.138 3.475C5.94667 3.79167 5.934 4.11667 6.1 4.45L7.375 7ZM8.4 21H15.6C17.1 21 18.375 20.4793 19.425 19.438C20.475 18.3967 21 17.1173 21 15.6C21 14.9667 20.8917 14.35 20.675 13.75C20.4583 13.15 20.15 12.6083 19.75 12.125L17.15 9H6.85L4.25 12.125C3.85 12.6083 3.54167 13.15 3.325 13.75C3.10833 14.35 3 14.9667 3 15.6C3 17.1167 3.521 18.396 4.563 19.438C5.605 20.48 6.884 21.0007 8.4 21Z" fill="currentColor"/>
                    </svg>    
                </div>
                <h3 class="font-bold text-lg text-darkslategray mb-2">Input Anggaran</h3>
                <p class="text-sm text-gray-500">Panduan lengkap input Actual, Projection, dan validasi Quarter.</p>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'lahan'" style="display: none;" 
         x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        
        <span class="text-blue-600 font-bold tracking-wider text-xs uppercase mb-2 block">Master Data</span>
        <h1 class="text-3xl font-extrabold text-darkslategray mb-6">Manajemen Lahan</h1>

        <div class="prose max-w-none text-gray-600">
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-8 rounded-r-lg">
                <p class="text-sm text-blue-900 font-medium">
                    <strong>Penting:</strong> Data lahan adalah data induk. Pastikan Luas (Ha) diisi dengan akurat karena mempengaruhi perhitungan di Dashboard.
                </p>
            </div>

            <h3 class="text-xl font-bold text-darkslategray mb-4">Cara Menambahkan Lahan Baru</h3>
            <ol class="list-decimal list-inside space-y-3 mb-8 bg-white p-6 rounded-xl border border-gray-200">
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
            <div class="bg-white p-6 rounded-xl border border-gray-200 mb-8 shadow-sm">
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
                    
                    <div class="bg-gray-50 rounded-lg flex flex-col items-center justify-center p-4 text-center border-2 border-dashed border-gray-300">
                        <div class="relative w-full h-40 bg-gray-200 rounded mb-2 overflow-hidden flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400 absolute" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                            <svg class="w-8 h-8 text-red-500 absolute -mt-4 drop-shadow-md animate-bounce" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                        </div>
                        <span class="text-xs text-gray-500 font-medium">Klik peta untuk menaruh pin lokasi</span>
                    </div>
                </div>
            </div>

            <h3 class="text-xl font-bold text-darkslategray mb-4">Status Lahan</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex items-center gap-3 p-3 border rounded-lg bg-white">
                    <span class="w-3 h-3 rounded-full bg-green-500 shadow-sm shadow-green-200"></span>
                    <div>
                        <span class="font-bold text-darkslategray block text-sm">Active</span>
                        <span class="text-xs text-gray-500">Lahan sedang dalam proses reklamasi berjalan.</span>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 border rounded-lg bg-white">
                    <span class="w-3 h-3 rounded-full bg-gray-400 shadow-sm"></span>
                    <div>
                        <span class="font-bold text-darkslategray block text-sm">Inactive/Done</span>
                        <span class="text-xs text-gray-500">Proses reklamasi selesai atau dihentikan sementara.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'team'" style="display: none;" 
         x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        
        <span class="text-purple-600 font-bold tracking-wider text-xs uppercase mb-2 block">Administrasi & Keamanan</span>
        <h1 class="text-3xl font-extrabold text-darkslategray mb-6">Tim Pengelola & Hak Akses</h1>

        <div class="prose max-w-none text-gray-600">
            <p class="mb-6">
                Sistem menggunakan manajemen hak akses bertingkat untuk menjaga keamanan data. Tidak semua user bisa mengedit data lahan.
            </p>

            <div class="bg-white border border-gainsboro rounded-xl overflow-hidden mb-8">
                <div class="bg-gray-50 px-6 py-4 border-b border-gainsboro">
                    <h3 class="font-bold text-darkslategray m-0">Tingkatan Role (Peran)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 font-medium border-b border-gainsboro">
                            <tr>
                                <th class="px-6 py-3">Role</th>
                                <th class="px-6 py-3">Deskripsi & Wewenang</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gainsboro">
                            <tr>
                                <td class="px-6 py-4 font-bold text-purple-700">Admin Utama</td>
                                <td class="px-6 py-4">
                                    Memiliki akses penuh ke seluruh sistem. Hanya Admin yang bisa:
                                    <ul class="list-disc list-inside mt-1 text-gray-500">
                                        <li>Membuat Lahan Baru.</li>
                                        <li>Menambahkan User baru ke sistem.</li>
                                        <li>Mengatur tim pengelola untuk setiap lahan.</li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 font-bold text-blue-600">Owner Lahan</td>
                                <td class="px-6 py-4">
                                    Ditunjuk oleh Admin untuk bertanggung jawab penuh atas lahan tertentu. Bisa mengedit data lahan, menambah plot, dan input anggaran.
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 font-bold text-green-600">Editor</td>
                                <td class="px-6 py-4">Bisa melakukan input data harian (Anggaran, Plot) namun tidak bisa menghapus data krusial atau mengubah tim.</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 font-bold text-gray-600">Viewer</td>
                                <td class="px-6 py-4">Hanya bisa melihat data (Read-Only) dan export laporan. Tidak bisa mengubah apapun.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <h3 class="text-xl font-bold text-darkslategray mb-4">Cara Menambahkan Anggota Tim (Khusus Admin)</h3>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <ol class="list-decimal list-inside space-y-4">
                    <li>
                        Buka halaman <a href="{{ route('lahan.index') }}" class="text-blue-600 hover:underline font-medium">List Lahan</a>.
                    </li>
                    <li>
                        Klik tombol opsi (titik tiga) pada lahan yang ingin diatur, lalu pilih <strong>Access Management</strong>.
                    </li>
                    <li>
                        Klik tombol <strong>"Tambah Anggota +"</strong>.
                    </li>
                    <li>
                        Pilih <strong>User</strong> dari dropdown dan tentukan <strong>Role</strong> (Owner/Editor/Viewer).
                    </li>
                    <li>
                        Klik <strong>Save</strong>. User tersebut sekarang memiliki akses ke lahan ini sesuai role yang diberikan.
                    </li>
                </ol>
                <div class="mt-4 p-4 bg-gray-50 rounded border border-gray-200 text-center">
                    <img src="{{ asset('path/to/image_cc871b.png') }}" class="max-w-full h-auto mx-auto rounded shadow-sm border" alt="Form Tambah Anggota">
                    <p class="text-xs text-gray-500 mt-2 italic">Tampilan form tambah anggota tim</p>
                </div>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'plot'" style="display: none;" 
         x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        
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
                        <h4 class="font-bold text-darkslategray">Masuk ke Menu Plot</h4>
                        <p class="text-sm mb-2">Dari Dashboard Lahan, pilih menu <strong>"Plot Lahan"</strong> di sidebar kiri.</p>
                        <p class="text-sm">Klik tombol <strong>"Add +"</strong> di pojok kanan atas peta.</p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-8 h-8 bg-darkslategray text-white rounded-full flex items-center justify-center font-bold">2</div>
                    <div class="flex-1">
                        <h4 class="font-bold text-darkslategray">Gambar Polygon di Peta</h4>
                        <p class="text-sm mb-4">Ada dua cara untuk membentuk area plot:</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-white border border-gray-200 p-4 rounded-lg">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="border border-gray-400 rounded p-1">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect></svg>
                                    </div>
                                    <span class="font-bold text-blue-600">Cara A: Menggambar (Recommended)</span>
                                </div>
                                <p class="text-xs">Klik tombol <strong>Polygon Tool</strong> (ikon kotak) pada kontrol peta.</p>
                                <ul class="list-disc list-inside text-xs mt-2 space-y-1 text-gray-500">
                                    <li>Klik di peta untuk membuat titik sudut pertama.</li>
                                    <li>Klik lagi untuk membuat titik berikutnya hingga membentuk area.</li>
                                    <li>Klik kembali ke titik awal untuk menutup polygon.</li>
                                </ul>
                            </div>

                            <div class="bg-white border border-gray-200 p-4 rounded-lg">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                    <span class="font-bold text-gray-700">Cara B: Input Manual</span>
                                </div>
                                <p class="text-xs">Jika Anda memiliki data koordinat GPS presisi.</p>
                                <ul class="list-disc list-inside text-xs mt-2 space-y-1 text-gray-500">
                                    <li>Scroll ke bawah ke bagian <strong>Input Koordinat</strong>.</li>
                                    <li>Masukkan Latitude & Longitude satu per satu.</li>
                                    <li>Klik "Tambah Titik +" untuk menambah sudut.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-8 h-8 bg-darkslategray text-white rounded-full flex items-center justify-center font-bold">3</div>
                    <div class="flex-1">
                        <h4 class="font-bold text-darkslategray">Verifikasi & Simpan</h4>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mt-2">
                            <ul class="space-y-2 text-sm">
                                <li>
                                    <strong>Nama Plot:</strong> Sistem akan otomatis mengisi (contoh: <code>Blok A</code>). Pastikan nama ini unik dalam satu lahan.
                                </li>
                                <li>
                                    <strong>Luas Area:</strong> Akan terhitung otomatis dalam satuan Hektar (Ha) berdasarkan besarnya gambar polygon Anda.
                                </li>
                            </ul>
                        </div>
                        <p class="text-sm mt-4">Klik tombol <strong>Save</strong> untuk menyimpan plot.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'anggaran'" style="display: none;" 
         x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        
        <span class="text-green-600 font-bold tracking-wider text-xs uppercase mb-2 block">Transaksi Utama</span>
        <h1 class="text-3xl font-extrabold text-darkslategray mb-6">Input Anggaran Reklamasi</h1>

        <div class="space-y-8">
            <p class="text-gray-600 text-lg">
                Sistem menggunakan metode pembagian <strong>Quarter (Q1 - Q4)</strong>. Anda dapat menginput data anggaran Actual, Projection, maupun Forecast.
            </p>

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <h3 class="font-bold text-darkslategray">Aturan Penting Sistem</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex gap-4">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold flex-shrink-0">1</div>
                        <div>
                            <h4 class="font-bold text-darkslategray">Unik Per Bulan</h4>
                            <p class="text-sm text-gray-600 mt-1">Anda tidak dapat memasukkan Kategori Anggaran yang sama di Bulan & Tahun yang sama dua kali. Gunakan fitur <strong>Edit</strong> jika ingin mengubah nominal.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center font-bold flex-shrink-0">2</div>
                        <div>
                            <h4 class="font-bold text-darkslategray">Urutan Quarter</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                Sistem mewajibkan <strong>Q1 (Jan-Mar)</strong> harus dibuat terlebih dahulu di tahun tersebut sebelum Anda bisa membuat Q2, Q3, atau Q4.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-xl font-bold text-darkslategray mb-4">Langkah Input Data</h3>
                <div class="relative pl-8 border-l-2 border-gainsboro space-y-8">
                    <div class="relative">
                        <span class="absolute -left-[41px] bg-white border-2 border-gainsboro w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold text-gray-500">1</span>
                        <h4 class="font-bold text-darkslategray">Masuk ke Detail Lahan</h4>
                        <p class="text-sm text-gray-500">Pilih lahan dari halaman utama.</p>
                    </div>
                    <div class="relative">
                        <span class="absolute -left-[41px] bg-white border-2 border-gainsboro w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold text-gray-500">2</span>
                        <h4 class="font-bold text-darkslategray">Pilih Tab Anggaran</h4>
                        <p class="text-sm text-gray-500">Pilih antara <strong>Actual, Projection, atau Forecast</strong> di bagian atas tabel.</p>
                    </div>
                    <div class="relative">
                        <span class="absolute -left-[41px] bg-white border-2 border-blue-500 w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold text-blue-600">3</span>
                        <h4 class="font-bold text-blue-600">Klik "Tambah Anggaran"</h4>
                        <p class="text-sm text-gray-500">Isi Quarter, Tahun, Bulan, Kategori, dan Nominal.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'laporan'" style="display: none;" 
         x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        
        <h1 class="text-3xl font-extrabold text-darkslategray mb-6">Laporan & Export Excel</h1>
        
        <div class="bg-green-50 rounded-xl p-6 border border-green-100 mb-6">
            <h3 class="text-lg font-bold text-green-800 mb-2">Download Data</h3>
            <p class="text-green-700 text-sm mb-4">Anda dapat mengunduh rekapitulasi anggaran ke dalam format Excel (.xlsx) yang rapi dan siap cetak.</p>
            <div class="flex items-center gap-2">
                <span class="bg-green-600 text-white text-xs font-bold px-2 py-1 rounded">Tombol Export Excel</span>
                <span class="text-sm text-gray-600">terletak di pojok kanan atas tabel Anggaran.</span>
            </div>
        </div>

        <h4 class="font-bold text-darkslategray mb-3">Format Laporan</h4>
        <ul class="list-disc list-inside text-gray-600 space-y-2 text-sm">
            <li>File Excel akan memiliki 3 Sheet (jika data tersedia): Actual, Projection, dan Forecast.</li>
            <li>Tabel dikelompokkan berdasarkan Quarter dengan warna berbeda untuk memudahkan pembacaan.</li>
            <li>Total per Quarter dihitung otomatis.</li>
        </ul>
    </div>

    <div x-show="activeTab === 'faq'" style="display: none;" 
         x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        
        <h1 class="text-3xl font-extrabold text-darkslategray mb-6">Pertanyaan Umum (FAQ)</h1>

        <div class="space-y-4">
            <details class="group bg-white border border-gainsboro rounded-lg overflow-hidden">
                <summary class="flex justify-between items-center font-medium cursor-pointer p-4 bg-gray-50 hover:bg-gray-100 text-darkslategray">
                    <span>Saya tidak bisa menghapus data anggaran?</span>
                    <span class="transition group-open:rotate-180"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"></path></svg></span>
                </summary>
                <div class="text-gray-600 text-sm p-4 border-t border-gainsboro bg-white">
                    Demi keamanan data, penghapusan data master atau anggaran sensitif hanya dapat dilakukan oleh akun dengan role <strong>Admin</strong>. Silakan hubungi admin jika ada kesalahan input yang fatal.
                </div>
            </details>

            <details class="group bg-white border border-gainsboro rounded-lg overflow-hidden">
                <summary class="flex justify-between items-center font-medium cursor-pointer p-4 bg-gray-50 hover:bg-gray-100 text-darkslategray">
                    <span>Muncul pesan error "Data Duplikat"?</span>
                    <span class="transition group-open:rotate-180"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"></path></svg></span>
                </summary>
                <div class="text-gray-600 text-sm p-4 border-t border-gainsboro bg-white">
                    Sistem mendeteksi bahwa Anda mencoba memasukkan <strong>Kategori yang sama</strong> pada <strong>Bulan & Tahun yang sama</strong>. Data tidak boleh ganda. Cek tabel, lalu gunakan fitur <strong>Edit</strong> jika ingin mengubah nominalnya.
                </div>
            </details>

            <details class="group bg-white border border-gainsboro rounded-lg overflow-hidden">
                <summary class="flex justify-between items-center font-medium cursor-pointer p-4 bg-gray-50 hover:bg-gray-100 text-darkslategray">
                    <span>Bagaimana cara menghubungi Admin?</span>
                    <span class="transition group-open:rotate-180"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"></path></svg></span>
                </summary>
                <div class="text-gray-600 text-sm p-4 border-t border-gainsboro bg-white">
                    Klik foto profil Anda di pojok kanan atas, lalu pilih menu <strong>"Hubungi Admin"</strong>. Anda akan melihat tombol WhatsApp dan Email admin yang bertugas.
                </div>
            </details>
        </div>
    </div>
</x-guide-layout>