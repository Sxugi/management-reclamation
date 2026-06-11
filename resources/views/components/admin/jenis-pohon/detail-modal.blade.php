<div x-data="{ 
        show: false, 
        data: {},
        formatDate(dateStr) {
            if(!dateStr) return '-';
            const date = new Date(dateStr);
            return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
        },
        formatNumber(num) {
            return (parseInt(num) || 0).toLocaleString('id-ID');
        }
     }"
     @open-detail-pohon.window="show = true; data = $event.detail"
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
        <div class="flex items-center justify-between p-6 border-b border-gainsboro bg-gradient-to-r from-green-50 to-emerald-50 rounded-t-2xl">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="text-white" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 10a6 6 0 0 0-6-6H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2a6 6 0 0 0 6-6Z"/>
                        <path d="M12 10a6 6 0 0 1 6-6h0a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-2a6 6 0 0 1-6-6Z"/>
                        <path d="M12 22v-8"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center self-stretch leading-7 font-semibold text-lg text-darkslategray font-outfit">
                        Detail Jenis Pohon
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
                {{-- Informasi Pohon --}}
                <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-sm space-y-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-darkslategray">Informasi Pohon</h3>
                    </div>
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="text-sm font-semibold text-darkslategray mb-1">Nama Jenis Pohon</div>
                                <div class="text-lg font-bold text-darkslategray" x-text="data.nama_pohon"></div>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-darkslategray mb-1">Kategori</div>
                                <div class="text-lg font-bold text-darkslategray uppercase" x-text="data.kategori"></div>
                            </div>
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-darkslategray mb-1">ID System</div>
                            <div class="text-sm text-white font-mono bg-gray-100 px-2 py-1 rounded inline-block" x-text="'#' + data.jenis_pohon_id"></div>
                        </div>
                        <div class="space-y-1 text-sm pt-2 border-t border-gray-200">
                            <div><span class="font-semibold text-darkslategray">Dibuat:</span> <span class="ml-2 text-gray-600" x-text="formatDate(data.created_at)"></span></div>
                            <div><span class="font-semibold text-darkslategray">Update:</span> <span class="ml-2 text-gray-600" x-text="formatDate(data.updated_at)"></span></div>
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

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-3 rounded-lg bg-blue-50 border border-blue-200">
                                <div class="text-xs font-medium text-darkslategray mb-1">Total Lahan</div>
                                <div class="text-xl font-bold text-blue-700">
                                    <span x-text="data.pohon_count || 0"></span> 
                                    <span class="text-xs font-normal text-slategray">Lahan</span>
                                </div>
                            </div>
                            
                            <div class="p-3 rounded-lg bg-green-50 border border-green-200">
                                <div class="text-xs font-medium text-darkslategray mb-1">Realisasi</div>
                                <div class="text-xl font-bold text-green-700">
                                    <span x-text="formatNumber(data.total_realisasi || 0)"></span> 
                                    <span class="text-xs font-normal text-slategray">Btg</span>
                                </div>
                            </div>

                            <div class="p-3 rounded-lg bg-purple-50 border border-purple-200">
                                <div class="text-xs font-medium text-darkslategray mb-1">Manual</div>
                                <div class="text-xl font-bold text-purple-700">
                                    <span x-text="formatNumber(data.total_manual || 0)"></span> 
                                    <span class="text-xs font-normal text-slategray">Btg</span>
                                </div>
                            </div>

                            <div class="p-3 rounded-lg bg-orange-50 border border-orange-200">
                                <div class="text-xs font-medium text-darkslategray mb-1">Total</div>
                                <div class="text-xl font-bold text-orange-700">
                                    <span x-text="formatNumber(data.grand_total || 0)"></span> 
                                    <span class="text-xs font-normal text-slategray">Btg</span>
                                </div>
                            </div>
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
                    <h3 class="text-base font-semibold text-darkslategray">Sebaran di Lahan</h3>
                </div>

                <div class="overflow-y-auto max-h-64 pr-2">
                    <template x-if="!data.pohon || data.pohon.length === 0">
                        <div class="text-center py-4 text-slategray text-sm italic">
                            Belum digunakan di lahan manapun.
                        </div>
                    </template>

                    <template x-if="data.pohon && data.pohon.length > 0">
                        <table class="min-w-full text-sm text-left">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="px-4 py-2 rounded-tl-lg">Nama Lahan</th>
                                    <th class="px-4 py-2 text-right">Realisasi</th>
                                    <th class="px-4 py-2 text-right">Manual</th>
                                    <th class="px-4 py-2 text-right rounded-tr-lg">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <template x-for="item in data.pohon" :key="item.pohon_id">
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 font-medium text-darkslategray" x-text="item.lahan?.nama_lahan || 'Lahan dihapus'"></td>
                                        
                                        <td class="px-4 py-3 text-right">
                                            <span class="font-bold text-green-700" x-text="formatNumber(item.total_realisasi || 0)"></span>
                                        </td>

                                        <td class="px-4 py-3 text-right">
                                            <span class="font-bold text-purple-700" x-text="formatNumber(item.total_manual || 0)"></span>
                                        </td>

                                        <td class="px-4 py-3 text-right">
                                            <span class="font-bold text-darkslategray" x-text="formatNumber((item.total_realisasi || 0) + (item.total_manual || 0))"></span>
                                            <span class="text-xs text-gray-500 ml-1">Btg</span>
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
                <button @click="show = false" 
                        class="bg-red-500 !text-white text-sm py-3 px-4 rounded-lg font-medium hover:bg-red-600 transition-colors border-none cursor-pointer">
                    Close
                </button>
                
                <x-main.primary-button @click="show = false; $dispatch('edit-pohon', data)" class="py-3 px-4 gap-2 font-medium">
                    Edit
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 13V16H7L16 7L13 4L4 13Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </x-main.primary-button>

                <x-main.primary-button @click="show = false; setTimeout(() => $dispatch('open-modal', 'confirm-delete-' + data.jenis_pohon_id), 300)" class="py-3 px-4 gap-2 font-medium hidden">
                    Delete
                    <svg width="18" height="18" viewBox="0 0 15 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.52679 1.0877L4.28571 1.5625H1.07143C0.478795 1.5625 0 2.0373 0 2.625C0 3.2127 0.478795 3.6875 1.07143 3.6875H13.9286C14.5212 3.6875 15 3.2127 15 2.625C15 2.0373 14.5212 1.5625 13.9286 1.5625H10.7143L10.4732 1.0877C10.2924 0.725781 9.92076 0.5 9.51562 0.5H5.48438C5.07924 0.5 4.70759 0.725781 4.52679 1.0877ZM13.9286 4.75H1.07143L1.78125 16.0059C1.83482 16.8459 2.53795 17.5 3.38504 17.5H11.615C12.4621 17.5 13.1652 16.8459 13.2187 16.0059L13.9286 4.75Z" fill="white"/>
                    </svg>                
                </x-main.primary-button>
            </div>
        </div>
    </div>
</div>