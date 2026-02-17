<div x-show="activeTab === 'team'" 
     style="display: none;" 
     x-transition:enter="transition ease-out duration-300 transform" 
     x-transition:enter-start="opacity-0 translate-y-2" 
     x-transition:enter-end="opacity-100 translate-y-0">
    
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
                                Ditunjuk oleh Admin untuk bertanggung jawab penuh atas lahan tertentu. Bisa mengedit/menghapus data lahan, menambah plot, dan input anggaran.
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
        <div class="bg-white p-6 rounded-xl border border-gainsboro shadow-sm">
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
            <div class="mt-6 p-4 bg-gray-50 flex flex-col items-center justify-center rounded border border-gainsboro">
                <img src="{{ asset('images/guide/form-management-team.png') }}" alt="Form Management Team" class="w-full max-w-2xl mx-auto">
                <p class="text-xs text-gray-500 text-center italic">Form input untuk menambahkan akses kepada user</p>
            </div>
        </div>
    </div>
</div>