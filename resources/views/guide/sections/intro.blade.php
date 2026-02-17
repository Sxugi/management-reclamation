<div x-show="activeTab === 'intro'" 
     x-data="{
         searchQuery: '',
         showSuggestions: false,
         suggestions: [],
         filterSuggestions() {
             if (this.searchQuery.length < 2) {
                 this.suggestions = [];
                 this.showSuggestions = false;
                 return;
             }
             
             const query = this.searchQuery.toLowerCase();
             const sections = document.querySelectorAll('[data-guide-section]');
             this.suggestions = [];
             
             sections.forEach(section => {
                 const keywords = section.getAttribute('data-keywords').toLowerCase();
                 const title = section.getAttribute('data-title');
                 const desc = section.getAttribute('data-desc');
                 const tab = section.getAttribute('data-guide-section');
                 const anchor = section.getAttribute('data-anchor');
                 
                 if (keywords.includes(query)) {
                     this.suggestions.push({
                         tab: tab,
                         anchor: anchor,
                         title: title,
                         desc: desc
                     });
                 }
             });
             
             this.suggestions = this.suggestions.slice(0, 5);
             this.showSuggestions = this.suggestions.length > 0;
         },
         selectSuggestion(tab, anchor = null) {
             this.activeTab = tab;
             this.searchQuery = '';
             this.showSuggestions = false;
             
             if (anchor) {
                 this.$nextTick(() => {
                     setTimeout(() => {
                         const element = document.getElementById(anchor);
                         if (element) {
                             element.scrollIntoView({behavior: 'smooth', block: 'start'});
                         }
                     }, 100);
                 });
             }
         }
     }"
     x-transition:enter="transition ease-out duration-300 transform" 
     x-transition:enter-start="opacity-0 translate-y-2" 
     x-transition:enter-end="opacity-100 translate-y-0">
    
    <h1 class="text-3xl font-extrabold text-darkslategray mb-4">Selamat Datang di Pusat Bantuan</h1>
    <p class="text-lg text-gray-600 leading-relaxed mb-8">
        Sistem <strong>Management Reclamation</strong> membantu Anda mengelola data lahan reklamasi, anggaran, progress, dan pelaporan secara terintegrasi. Gunakan panduan ini untuk memaksimalkan fitur-fitur yang tersedia.
    </p>

    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 mb-8 shadow-sm">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-darkslategray">Cari Panduan</h2>
        </div>
        
        <div class="relative">
            <input 
                type="text" 
                x-model="searchQuery"
                @input="filterSuggestions()"
                @focus="filterSuggestions()"
                @click.away="showSuggestions = false"
                placeholder="Ketik kata kunci... (contoh: tambah lahan, input anggaran, plot peta)" 
                class="w-full px-4 py-3 pl-12 text-sm border-2 border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white"
            >
            <svg class="w-5 h-5 absolute left-4 top-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            
            <div 
                x-show="showSuggestions" 
                x-transition
                class="absolute z-50 w-full mt-2 bg-white border border-gray-200 rounded-lg shadow-xl overflow-hidden max-h-80 overflow-y-auto">
                <div class="py-2">
                    <template x-for="suggestion in suggestions" :key="suggestion.tab + (suggestion.anchor || '')">
                        <button 
                            @click="selectSuggestion(suggestion.tab, suggestion.anchor)"
                            class="w-full px-4 py-3 text-left hover:bg-blue-50 transition-colors flex items-start gap-3 group">
                            <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <div class="flex-1">
                                <div class="font-bold text-sm text-darkslategray group-hover:text-blue-600" x-text="suggestion.title"></div>
                                <div class="text-xs text-gray-500" x-text="suggestion.desc"></div>
                            </div>
                        </button>
                    </template>
                    <div x-show="suggestions.length === 0 && searchQuery.length >= 2" class="px-4 py-3 text-sm text-gray-500 text-center">
                        Tidak ada hasil ditemukan
                    </div>
                </div>
            </div>
        </div>
        
        <p class="text-xs text-blue-600 mt-2">💡 Tip: Ketik minimal 2 karakter untuk melihat saran panduan</p>
    </div>

    <div class="mb-8">
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-darkslategray">Aksi Cepat - Fitur Utama</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <button @click="activeTab = 'lahan'" class="group bg-white border-2 border-blue-200 hover:border-blue-400 rounded-xl p-5 hover:shadow-lg transition-all text-left">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-blue-600 transition-colors">
                        <svg class="w-6 h-6 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 8L18 5L12 2V12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8.00011 11.99L2.50011 15.13C2.34621 15.2172 2.21821 15.3437 2.12915 15.4965C2.04009 15.6494 1.99316 15.8231 1.99316 16C1.99316 16.1769 2.04009 16.3506 2.12915 16.5035C2.21821 16.6563 2.34621 16.7828 2.50011 16.87L11.0001 21.73C11.3042 21.9055 11.649 21.9979 12.0001 21.9979C12.3512 21.9979 12.6961 21.9055 13.0001 21.73L21.5001 16.87C21.654 16.7828 21.782 16.6563 21.8711 16.5035C21.9601 16.3506 22.0071 16.1769 22.0071 16C22.0071 15.8231 21.9601 15.6494 21.8711 15.4965C21.782 15.3437 21.654 15.2172 21.5001 15.13L16.0001 12M6.49011 12.85L17.5101 19.15M17.5101 12.85L6.50011 19.15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-lg text-darkslategray mb-1 group-hover:text-blue-600 transition-colors">Manajemen Lahan</h3>
                        <p class="text-sm text-gray-600 mb-2">Tambah lahan baru, set lokasi di peta, dan atur status lahan</p>
                        <span class="text-xs text-blue-600 font-medium">📍 Master Data → Lahan</span>
                    </div>
                </div>
            </button>

            <button @click="activeTab = 'team'" class="group bg-white border-2 border-purple-200 hover:border-purple-400 rounded-xl p-5 hover:shadow-lg transition-all text-left">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-purple-600 transition-colors">
                        <svg class="w-6 h-6 text-purple-600 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 28 28">
                            <path d="M17.754 11C18.72 11 19.504 11.784 19.504 12.75V19.499C19.504 20.958 18.9244 22.3572 17.8928 23.3888C16.8612 24.4204 15.462 25 14.003 25C12.544 25 11.1448 24.4204 10.1132 23.3888C9.08157 22.3572 8.502 20.958 8.502 19.499V12.75C8.502 11.784 9.285 11 10.252 11H17.754ZM3.75 11L8.132 10.998C7.77165 11.4322 7.5547 11.9674 7.511 12.53L7.501 12.75V19.499C7.501 20.632 7.792 21.698 8.301 22.626C7.61593 22.9252 6.86712 23.049 6.12221 22.9862C5.3773 22.9235 4.65975 22.6761 4.0344 22.2665C3.40905 21.8569 2.89558 21.298 2.5404 20.6402C2.18521 19.9825 1.99948 19.2466 2 18.499V12.75C2 12.5201 2.0453 12.2925 2.13331 12.0801C2.22132 11.8677 2.35031 11.6747 2.51292 11.5122C2.67553 11.3497 2.86856 11.2208 3.081 11.1329C3.29343 11.045 3.5201 10.9999 3.75 11ZM19.875 10.998L24.25 11C25.216 11 26 11.784 26 12.75V18.5C26.0003 19.2472 25.8146 19.9826 25.4595 20.6401C25.1045 21.2975 24.5914 21.8562 23.9664 22.2657C23.3415 22.6752 22.6244 22.9227 21.8799 22.9857C21.1354 23.0488 20.3869 22.9255 19.702 22.627L19.758 22.525C20.187 21.712 20.448 20.796 20.496 19.825L20.504 19.499V12.75C20.504 12.084 20.268 11.474 19.875 10.998ZM14 3C14.4596 3 14.9148 3.09053 15.3394 3.26642C15.764 3.44231 16.1499 3.70012 16.4749 4.02513C16.7999 4.35013 17.0577 4.73597 17.2336 5.16061C17.4095 5.58525 17.5 6.04037 17.5 6.5C17.5 6.95963 17.4095 7.41475 17.2336 7.83939C17.0577 8.26403 16.7999 8.64987 16.4749 8.97487C16.1499 9.29988 15.764 9.55769 15.3394 9.73358C14.9148 9.90947 14.4596 10 14 10C13.0717 10 12.1815 9.63125 11.5251 8.97487C10.8688 8.3185 10.5 7.42826 10.5 6.5C10.5 5.57174 10.8688 4.6815 11.5251 4.02513C12.1815 3.36875 13.0717 3 14 3ZM22.003 4C22.397 4 22.7871 4.0776 23.1511 4.22836C23.515 4.37913 23.8457 4.6001 24.1243 4.87868C24.4029 5.15726 24.6239 5.48797 24.7746 5.85195C24.9254 6.21593 25.003 6.60603 25.003 7C25.003 7.39397 24.9254 7.78407 24.7746 8.14805C24.6239 8.51203 24.4029 8.84274 24.1243 9.12132C23.8457 9.3999 23.515 9.62087 23.1511 9.77164C22.7871 9.9224 22.397 10 22.003 10C21.2074 10 20.4443 9.68393 19.8817 9.12132C19.3191 8.55871 19.003 7.79565 19.003 7C19.003 6.20435 19.3191 5.44129 19.8817 4.87868C20.4443 4.31607 21.2074 4 22.003 4ZM5.997 4C6.39097 4 6.78107 4.0776 7.14505 4.22836C7.50903 4.37913 7.83975 4.6001 8.11832 4.87868C8.3969 5.15726 8.61788 5.48797 8.76864 5.85195C8.9194 6.21593 8.997 6.60603 8.997 7C8.997 7.39397 8.9194 7.78407 8.76864 8.14805C8.61788 8.51203 8.3969 8.84274 8.11832 9.12132C7.83975 9.3999 7.50903 9.62087 7.14505 9.77164C6.78107 9.9224 6.39097 10 5.997 10C5.20135 10 4.43829 9.68393 3.87568 9.12132C3.31307 8.55871 2.997 7.79565 2.997 7C2.997 6.20435 3.31307 5.44129 3.87568 4.87868C4.43829 4.31607 5.20135 4 5.997 4Z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-lg text-darkslategray mb-1 group-hover:text-purple-600 transition-colors">Tim & Hak Akses</h3>
                        <p class="text-sm text-gray-600 mb-2">Tambah user, atur role (Admin, Owner, Editor, Viewer)</p>
                        <span class="text-xs text-purple-600 font-medium">👥 Admin → User Management</span>
                    </div>
                </div>
            </button>

            <button @click="activeTab = 'plot'" class="group bg-white border-2 border-orange-200 hover:border-orange-400 rounded-xl p-5 hover:shadow-lg transition-all text-left">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-orange-600 transition-colors">
                        <svg class="w-6 h-6 text-orange-600 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22.5001 4.5C22.4977 3.72206 22.1927 2.97557 21.6495 2.41862C21.1064 1.86168 20.3678 1.53802 19.5901 1.51618C18.8125 1.49434 18.0569 1.77605 17.4833 2.30163C16.9098 2.82722 16.5633 3.55541 16.5173 4.332L7.18956 6.19725C6.97147 5.74397 6.64263 5.35297 6.23345 5.0604C5.82426 4.76784 5.34793 4.58313 4.84847 4.52337C4.34901 4.46361 3.84255 4.53072 3.37589 4.71848C2.90922 4.90625 2.49743 5.20863 2.17855 5.59766C1.85967 5.9867 1.64401 6.44984 1.55149 6.94428C1.45897 7.43872 1.49258 7.9485 1.64922 8.42651C1.80585 8.90452 2.08046 9.33534 2.44765 9.67914C2.81485 10.0229 3.26278 10.2686 3.75006 10.3935V16.6065C3.23826 16.7377 2.77028 17.0019 2.39364 17.3724C2.01699 17.7429 1.7451 18.2065 1.60557 18.7161C1.46604 19.2257 1.46383 19.7631 1.59918 20.2738C1.73452 20.7845 2.0026 21.2503 2.37619 21.6239C2.74978 21.9975 3.21557 22.2655 3.72628 22.4009C4.23698 22.5362 4.77441 22.534 5.28398 22.3945C5.79356 22.255 6.25714 21.9831 6.62765 21.6064C6.99816 21.2298 7.26241 20.7618 7.39356 20.25H13.6066C13.7312 20.7376 13.9768 21.1859 14.3207 21.5534C14.6645 21.9209 15.0955 22.1958 15.5737 22.3527C16.0519 22.5095 16.5619 22.5433 17.0566 22.4508C17.5514 22.3583 18.0148 22.1425 18.404 21.8235C18.7932 21.5044 19.0957 21.0924 19.2835 20.6255C19.4713 20.1585 19.5384 19.6518 19.4785 19.1521C19.4185 18.6524 19.2336 18.1758 18.9407 17.7666C18.6478 17.3573 18.2564 17.0285 17.8028 16.8105L19.6681 7.48275C20.4316 7.44084 21.1502 7.10859 21.6767 6.55405C22.2032 5.99952 22.4978 5.26467 22.5001 4.5ZM19.5001 3C19.7967 3 20.0867 3.08798 20.3334 3.2528C20.5801 3.41762 20.7723 3.65189 20.8859 3.92598C20.9994 4.20007 21.0291 4.50167 20.9712 4.79264C20.9134 5.08361 20.7705 5.35088 20.5607 5.56066C20.3509 5.77044 20.0837 5.9133 19.7927 5.97118C19.5017 6.02906 19.2001 5.99935 18.926 5.88582C18.6519 5.77229 18.4177 5.58003 18.2529 5.33336C18.088 5.08668 18.0001 4.79667 18.0001 4.5C18.0001 4.10218 18.1581 3.72065 18.4394 3.43934C18.7207 3.15804 19.1022 3 19.5001 3ZM3.00006 7.5C3.00006 7.20333 3.08803 6.91332 3.25285 6.66665C3.41767 6.41997 3.65194 6.22771 3.92603 6.11418C4.20012 6.00065 4.50172 5.97095 4.79269 6.02882C5.08366 6.0867 5.35094 6.22956 5.56072 6.43934C5.77049 6.64912 5.91336 6.91639 5.97123 7.20737C6.02911 7.49834 5.99941 7.79994 5.88588 8.07403C5.77234 8.34812 5.58008 8.58238 5.33341 8.74721C5.08674 8.91203 4.79673 9 4.50006 9C4.10223 9 3.7207 8.84197 3.4394 8.56066C3.15809 8.27936 3.00006 7.89783 3.00006 7.5ZM4.50006 21C4.20338 21 3.91337 20.912 3.6667 20.7472C3.42003 20.5824 3.22777 20.3481 3.11424 20.074C3.0007 19.7999 2.971 19.4983 3.02888 19.2074C3.08676 18.9164 3.22962 18.6491 3.4394 18.4393C3.64917 18.2296 3.91645 18.0867 4.20742 18.0288C4.49839 17.9709 4.79999 18.0007 5.07408 18.1142C5.34817 18.2277 5.58244 18.42 5.74726 18.6666C5.91208 18.9133 6.00006 19.2033 6.00006 19.5C6.00006 19.8978 5.84202 20.2794 5.56072 20.5607C5.27941 20.842 4.89788 21 4.50006 21ZM13.6066 18.75H7.39356C7.25878 18.2346 6.98918 17.7643 6.61246 17.3876C6.23574 17.0109 5.76549 16.7413 5.25006 16.6065V10.3935C5.86267 10.2338 6.40885 9.88391 6.81005 9.39417C7.21125 8.90443 7.44679 8.30007 7.48281 7.668L16.8106 5.80275C17.1043 6.40759 17.5926 6.89622 18.1973 7.19025L16.3321 16.5173C15.7 16.5533 15.0956 16.7888 14.6059 17.19C14.1161 17.5912 13.7663 18.1374 13.6066 18.75ZM16.5001 21C16.2034 21 15.9134 20.912 15.6667 20.7472C15.42 20.5824 15.2278 20.3481 15.1142 20.074C15.0007 19.7999 14.971 19.4983 15.0289 19.2074C15.0868 18.9164 15.2296 18.6491 15.4394 18.4393C15.6492 18.2296 15.9164 18.0867 16.2074 18.0288C16.4984 17.9709 16.8 18.0007 17.0741 18.1142C17.3482 18.2277 17.5824 18.42 17.7473 18.6666C17.9121 18.9133 18.0001 19.2033 18.0001 19.5C18.0001 19.8978 17.842 20.2794 17.5607 20.5607C17.2794 20.842 16.8979 21 16.5001 21Z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-lg text-darkslategray mb-1 group-hover:text-orange-600 transition-colors">Plot Lahan (Peta)</h3>
                        <p class="text-sm text-gray-600 mb-2">Gambar polygon area di peta, hitung luas otomatis</p>
                        <span class="text-xs text-orange-600 font-medium">🗺️ GIS → Plot Lahan</span>
                    </div>
                </div>
            </button>

            <button @click="activeTab = 'progress'" class="group bg-white border-2 border-teal-200 hover:border-teal-400 rounded-xl p-5 hover:shadow-lg transition-all text-left">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-teal-600 transition-colors">
                        <svg class="w-6 h-6 text-teal-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-lg text-darkslategray mb-1 group-hover:text-teal-600 transition-colors">Progress Reklamasi</h3>
                        <p class="text-sm text-gray-600 mb-2">Set target, input progress harian, track persentase</p>
                        <span class="text-xs text-teal-600 font-medium">📊 Monitoring → Progress</span>
                    </div>
                </div>
            </button>
        </div>
    </div>

    <div class="mb-8">
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-darkslategray">Manajemen Data</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <button @click="activeTab = 'anggaran'" class="group bg-white border border-green-200 hover:border-green-400 rounded-lg p-4 hover:shadow-md transition-all text-left">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-green-600 transition-colors">
                        <svg class="w-5 h-5 text-green-600 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 16C11.45 16 10.9793 15.8043 10.588 15.413C10.1967 15.0217 10.0007 14.5507 10 14C9.99933 13.4493 10.1953 12.9787 10.588 12.588C10.9807 12.1973 11.4513 12.0013 12 12C12.5487 11.9987 13.0197 12.1947 13.413 12.588C13.8063 12.9813 14.002 13.452 14 14C13.998 14.548 13.8023 15.019 13.413 15.413C13.0237 15.807 12.5527 16.0027 12 16ZM7.375 7H16.625L17.9 4.45C18.0667 4.11667 18.054 3.79167 17.862 3.475C17.67 3.15833 17.3827 3 17 3H7C6.61667 3 6.32933 3.15833 6.138 3.475C5.94667 3.79167 5.934 4.11667 6.1 4.45L7.375 7ZM8.4 21H15.6C17.1 21 18.375 20.4793 19.425 19.438C20.475 18.3967 21 17.1173 21 15.6C21 14.9667 20.8917 14.35 20.675 13.75C20.4583 13.15 20.15 12.6083 19.75 12.125L17.15 9H6.85L4.25 12.125C3.85 12.6083 3.54167 13.15 3.325 13.75C3.10833 14.35 3 14.9667 3 15.6C3 17.1167 3.521 18.396 4.563 19.438C5.605 20.48 6.884 21.0007 8.4 21Z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-sm text-darkslategray mb-1 group-hover:text-green-600 transition-colors">Anggaran</h3>
                        <p class="text-xs text-gray-600">Input Actual, Projection, Forecast per Quarter</p>
                    </div>
                </div>
            </button>

            <button @click="activeTab = 'pohon'" class="group bg-white border border-emerald-200 hover:border-emerald-400 rounded-lg p-4 hover:shadow-md transition-all text-left">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-emerald-600 transition-colors">
                        <svg class="w-5 h-5 text-emerald-600 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.707 2.293C12.5195 2.10553 12.2652 2.00021 12 2.00021C11.7349 2.00021 11.4805 2.10553 11.293 2.293L7.29302 6.293C7.1414 6.44464 7.04255 6.641 7.01104 6.85311C6.97953 7.06522 7.01703 7.28183 7.11802 7.471C7.36502 7.934 7.75102 8.246 8.12802 8.458L5.29302 11.293C5.10555 11.4805 5.00023 11.7348 5.00023 12C5.00023 12.2652 5.10555 12.5195 5.29302 12.707C5.77102 13.185 6.37502 13.477 6.92702 13.659L4.29302 16.293C4.17228 16.4139 4.08452 16.5636 4.0381 16.7281C3.99169 16.8925 3.98817 17.066 4.02788 17.2322C4.06759 17.3983 4.1492 17.5516 4.26494 17.6772C4.38068 17.8029 4.52668 17.8968 4.68902 17.95C5.37902 18.177 6.09202 18.338 6.80402 18.48C7.58402 18.637 8.55002 18.792 9.64902 18.892L9.05102 20.684C9.00095 20.8343 8.98732 20.9944 9.01125 21.151C9.03518 21.3077 9.096 21.4564 9.18868 21.5849C9.28136 21.7134 9.40325 21.8181 9.54431 21.8903C9.68537 21.9624 9.84157 22 10 22H14C14.1585 22 14.3147 21.9624 14.4557 21.8903C14.5968 21.8181 14.7187 21.7134 14.8114 21.5849C14.904 21.4564 14.9649 21.3077 14.9888 21.151C15.0127 20.9944 14.9991 20.8343 14.949 20.684L14.351 18.892C15.451 18.792 16.417 18.637 17.196 18.481C17.908 18.338 18.626 18.177 19.316 17.949C19.4778 17.8951 19.6231 17.8008 19.7381 17.675C19.8531 17.5491 19.9341 17.396 19.9733 17.23C20.0125 17.0641 20.0086 16.8909 19.962 16.7269C19.9154 16.5629 19.8276 16.4135 19.707 16.293L17.073 13.659C17.625 13.478 18.229 13.185 18.707 12.707C18.8945 12.5195 18.9998 12.2652 18.9998 12C18.9998 11.7348 18.8945 11.4805 18.707 11.293L15.872 8.458C16.249 8.246 16.635 7.934 16.882 7.471C16.983 7.28183 17.0205 7.06522 16.989 6.85311C16.9575 6.641 16.8586 6.44464 16.707 6.293L12.707 2.293Z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-sm text-darkslategray mb-1 group-hover:text-emerald-600 transition-colors">Data Pohon</h3>
                        <p class="text-xs text-gray-600">Inventarisasi pohon per jenis & tahun tanam</p>
                    </div>
                </div>
            </button>

            <button @click="activeTab = 'gudang'" class="group bg-white border border-amber-200 hover:border-amber-400 rounded-lg p-4 hover:shadow-md transition-all text-left">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-amber-600 transition-colors">
                        <svg class="w-5 h-5 text-amber-600 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.487 3.138C11.8184 3.0174 12.1816 3.0174 12.513 3.138L20.013 5.865C20.3023 5.9703 20.5523 6.1621 20.7288 6.41435C20.9054 6.6666 21.0001 6.96708 21 7.275V19.5C21 19.8978 20.842 20.2794 20.5607 20.5607C20.2794 20.842 19.8978 21 19.5 21H4.5C4.10218 21 3.72064 20.842 3.43934 20.5607C3.15804 20.2794 3 19.8978 3 19.5V7.275C2.99991 6.96708 3.09458 6.6666 3.27115 6.41435C3.44773 6.1621 3.69766 5.9703 3.987 5.865L11.487 3.138ZM7.5 19.5H9V16.5C9 16.1022 9.15804 15.7206 9.43934 15.4393C9.72064 15.158 10.1022 15 10.5 15H13.5C13.8978 15 14.2794 15.158 14.5607 15.4393C14.842 15.7206 15 16.1022 15 16.5V19.5H16.5V10.5H7.5V19.5ZM18 19.5V10.5C18 10.1022 17.842 9.72064 17.5607 9.43934C17.2794 9.15803 16.8978 9 16.5 9H7.5C7.10218 9 6.72064 9.15803 6.43934 9.43934C6.15804 9.72064 6 10.1022 6 10.5V19.5H4.5V7.275L12 4.548L19.5 7.275V19.5H18Z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-sm text-darkslategray mb-1 group-hover:text-amber-600 transition-colors">Data Gudang</h3>
                        <p class="text-xs text-gray-600">Stok masuk/keluar bibit, pupuk, alat</p>
                    </div>
                </div>
            </button>
        </div>
    </div>

    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-200 rounded-xl p-6">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-darkslategray mb-2">💡 Tips Menggunakan Sistem</h3>
                <ul class="space-y-1 text-sm text-gray-700">
                    <li>• Gunakan <strong>Quick Search</strong> di atas untuk menemukan panduan dengan cepat</li>
                    <li>• Klik <strong>Fast Action</strong> untuk langsung ke tutorial yang Anda butuhkan</li>
                    <li>• Semua fitur memiliki <strong>Role Access</strong> - pastikan Anda memiliki hak akses</li>
                    <li>• Progress Reklamasi memerlukan <strong>Target</strong> terlebih dahulu sebelum input data</li>
                    <li>• Data Pohon secara otomatis terupdate dari input Progress Reklamasi</li>
                    <li>• Hubungi <strong>Admin</strong> jika mengalami kesulitan atau butuh bantuan</li>
                </ul>
            </div>
        </div>
    </div>
</div>