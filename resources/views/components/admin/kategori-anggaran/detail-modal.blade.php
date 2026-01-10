<div x-data="{ 
        show: false, 
        data: {},
        lahanSummary: [],
        
        formatDate(dateStr) {
            if(!dateStr) return '-';
            return new Date(dateStr).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
        },
        
        formatCurrency(num) {
            return 'Rp ' + (parseFloat(num) || 0).toLocaleString('id-ID');
        },

        calculateLahanSummary() {
            if (!this.data.anggaran || this.data.anggaran.length === 0) {
                this.lahanSummary = [];
                return;
            }
            
            const summary = {};
            this.data.anggaran.forEach(item => {
                if (item.lahan) {
                    const name = item.lahan.nama_lahan;
                    if (!summary[name]) summary[name] = 0;
                    summary[name] += parseFloat(item.nominal);
                }
            });

            // Convert object to array and sort by value desc
            this.lahanSummary = Object.entries(summary)
                .map(([name, total]) => ({ name, total }))
                .sort((a, b) => b.total - a.total);
        }
     }"
     @open-detail-kategori.window="show = true; data = $event.detail; calculateLahanSummary()"
     x-show="show" 
     style="display: none;"
     class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
     
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto" @click.outside="show = false">
        
        {{-- Header --}}
        <div class="flex items-center justify-between p-6 border-b border-gainsboro bg-gradient-to-r from-purple-50 to-indigo-50 rounded-t-2xl">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="text-white" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12V7H5a2 2 0 0 1 0-4h14v4" />
                        <path d="M3 5v14a2 2 0 0 0 2 2h16v-5" />
                        <path d="M18 12a2 2 0 0 0 0 4h4v-4Z" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center self-stretch leading-7 font-semibold text-lg text-darkslategray font-outfit">
                        Detail Kategori Anggaran
                    </div>
                    <div class="text-sm text-slategray font-outfit">Informasi lengkap data master</div>
                </div>
            </div>
            <button @click="show = false" class="w-10 h-10 rounded-xl bg-white shadow-sm border border-gainsboro hover:bg-gray-500 flex items-center justify-center text-slategray hover:text-white transition-all duration-200 cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Content --}}
        <div class="p-6 font-outfit space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-sm space-y-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-darkslategray">Informasi Kategori</h3>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <div class="text-sm font-semibold text-darkslategray mb-1">Nama Kategori</div>
                            <div class="text-lg font-bold text-darkslategray" x-text="data.nama_kategori"></div>
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-darkslategray mb-1">ID System</div>
                            <div class="text-sm text-white font-mono bg-gray-100 px-2 py-1 rounded inline-block" x-text="'#' + data.kategori_anggaran_id"></div>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-sm space-y-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-darkslategray">Statistik Global</h3>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-3 rounded-lg bg-blue-50 border border-blue-200">
                            <div class="text-xs font-medium text-darkslategray mb-1">Total Transaksi</div>
                            <div class="text-xl font-bold text-blue-700">
                                <span x-text="data.anggaran_count"></span> <span class="text-xs font-normal text-slategray">Data</span>
                            </div>
                        </div>
                        
                        <div class="p-3 rounded-lg bg-green-50 border border-green-200">
                            <div class="text-xs font-medium text-darkslategray mb-1">Total Nominal</div>
                            <div class="text-xl font-bold text-green-700" x-text="formatCurrency(data.anggaran_sum_nominal)"></div>
                        </div>

                        <div class="space-y-1 text-sm pt-2">
                            <div><span class="font-semibold text-darkslategray">Dibuat:</span> <span class="ml-2 text-gray" x-text="formatDate(data.created_at)"></span></div>
                            <div><span class="font-semibold text-darkslategray">Update:</span> <span class="ml-2 text-gray" x-text="formatDate(data.updated_at)"></span></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-darkslategray">Sebaran Penggunaan per Lahan</h3>
                </div>

                <div class="overflow-y-auto max-h-64 pr-2">
                    <template x-if="lahanSummary.length === 0">
                        <div class="text-center py-4 text-slategray text-sm italic">
                            Belum ada penggunaan anggaran.
                        </div>
                    </template>

                    <template x-if="lahanSummary.length > 0">
                        <table class="min-w-full text-sm text-left">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="px-4 py-2 rounded-tl-lg">Nama Lahan</th>
                                    <th class="px-4 py-2 text-right rounded-tr-lg">Total Nominal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <template x-for="(summary, index) in lahanSummary" :key="index">
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 font-medium text-darkslategray" x-text="summary.name"></td>
                                        <td class="px-4 py-3 text-right">
                                            <span class="font-bold text-green-700" x-text="formatCurrency(summary.total)"></span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </template>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-end p-6 border-t border-gainsboro bg-gray-50 rounded-b-2xl">
            <div class="flex items-center space-x-3">
                <x-main.primary-button @click="show = false" class="bg-red-500 !text-white text-sm py-3 px-4 rounded-lg font-medium hover:bg-red-600 transition-colors no-underline">
                    Close
                </x-main.primary-button>
                
                <x-main.primary-button @click="show = false; $dispatch('edit-kategori', data)" class="py-3 px-4 gap-2 font-medium">
                    Edit
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 13V16H7L16 7L13 4L4 13Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </x-main.primary-button>

                <x-main.primary-button @click="show = false; setTimeout(() => $dispatch('open-modal', 'confirm-delete-' + data.kategori_anggaran_id), 300)" class="py-3 px-4 gap-2 font-medium hidden">
                    Delete
                    <svg width="18" height="18" viewBox="0 0 15 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.52679 1.0877L4.28571 1.5625H1.07143C0.478795 1.5625 0 2.0373 0 2.625C0 3.2127 0.478795 3.6875 1.07143 3.6875H13.9286C14.5212 3.6875 15 3.2127 15 2.625C15 2.0373 14.5212 1.5625 13.9286 1.5625H10.7143L10.4732 1.0877C10.2924 0.725781 9.92076 0.5 9.51562 0.5H5.48438C5.07924 0.5 4.70759 0.725781 4.52679 1.0877ZM13.9286 4.75H1.07143L1.78125 16.0059C1.83482 16.8459 2.53795 17.5 3.38504 17.5H11.615C12.4621 17.5 13.1652 16.8459 13.2187 16.0059L13.9286 4.75Z" fill="white"/>
                    </svg>                
                </x-main.primary-button>
            </div>
        </div>
    </div>
</div>