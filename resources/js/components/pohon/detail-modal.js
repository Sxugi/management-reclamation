document.addEventListener("turbo:load", () => {
    /**
     * Open Pohon Modal with data
     * @param {Object} data - Modal payload from server
     */
    window.openPohonModal = (data) => {
        const modal = document.getElementById('pohonModal');
        const content = document.getElementById('pohonModalContent');

        if (!modal || !content) {
            console.error('Modal elements not found!');
            return;
        }

        // Validate data
        if (!data || typeof data !== 'object') {
            console.error('Invalid modal data:', data);
            content.innerHTML = '<div class="text-center py-8 text-red-500">Error: Invalid data</div>';
            modal.classList.remove('hidden');
            return;
        }

        // Set current lahan ID globally
        if (data.lahan_id) {
            window.currentLahanId = data.lahan_id;
            console.log('Lahan ID set:', window.currentLahanId);
        } else {
            console.warn('No lahan_id in modal data');
        }

        // Initialize Survival Rate Slider if monitoring data exists
        if (data.monitoring_data && data.monitoring_data.length > 0) {
            setTimeout(() => {
                if (window.srSlider) {
                    window.srSlider.init(data.monitoring_data);
                }
            }, 100);
        }

        // Helper: format date time
        const formatDateTime = (dateString) => {
            if (!dateString) return '-';
            try {
                const date = new Date(dateString);
                if (isNaN(date.getTime())) return '-';
                return date.toLocaleString('id-ID', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    timeZoneName: 'short'
                });
            } catch (e) {
                console.error('Date formatting error:', e);
                return '-';
            }
        };

        // Safe data extraction
        const jenisPohon = data.jenis_pohon_nama || '-';
        const kategori = data.kategori || '-';
        const grandTotal = Number(data.grand_total || 0);
        const details = Array.isArray(data.details) ? data.details : [];

        // Group details by year
        const groupedByYear = {};
        details.forEach(item => {
            const year = item.tahun;
            if (!groupedByYear[year]) {
                groupedByYear[year] = {
                    realisasi: [],
                    manual: []
                };
            }
            
            if (item.plot_name) {
                groupedByYear[year].realisasi.push(item);
            } else {
                groupedByYear[year].manual.push(item);
            }
        });

        // Sort years
        const sortedYears = Object.keys(groupedByYear).sort((a, b) => a - b);

        // Calculate totals
        let totalRealisasi = 0;
        let totalManual = 0;
        details.forEach(item => {
            totalRealisasi += Number(item.realisasi_progres || 0);
            totalManual += Number(item.stok_manual || 0);
        });

        // Get timestamps
        const created_at = details.length 
            ? details.reduce((oldest, item) => {
                if (!oldest || !item.created_at) return oldest || item.created_at;
                return new Date(item.created_at) < new Date(oldest) ? item.created_at : oldest;
            }, null)
            : null;
            
        const updated_at = details.length
            ? details.reduce((latest, item) => {
                if (!latest || !item.updated_at) return latest || item.updated_at;
                return new Date(item.updated_at) > new Date(latest) ? item.updated_at : latest;
            }, null)
            : null;

        // Build modal content
        content.innerHTML = `
            <!-- Informasi Umum Pohon -->
            <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-sm space-y-4">
                <h3 class="text-sm font-semibold text-darkslategray mb-4 flex items-center gap-2">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 2L2 5L8 8L14 5L8 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M2 11L8 14L14 11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M2 8L8 11L14 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    Informasi Umum Pohon
                </h3>
                
                <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slategray uppercase tracking-wide mb-2">Jenis Pohon</label>
                        <div class="bg-gray-50 border border-gainsboro rounded-lg p-2">
                            <div class="text-sm font-medium text-darkslategray">${jenisPohon}</div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slategray uppercase tracking-wide mb-2">Kategori</label>
                        <div class="bg-gray-50 border border-gainsboro rounded-lg p-2 flex items-center">
                            <div class="text-sm font-medium text-darkslategray">${kategori}</div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slategray uppercase tracking-wide mb-2">Total Keseluruhan</label>
                        <div class="bg-gray-50 border border-gainsboro rounded-lg p-2">
                            <div class="text-sm font-bold text-darkslategray">${grandTotal.toLocaleString('id-ID')} batang</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rincian Data Pohon -->
            <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-darkslategray">Rincian Data Pohon</h3>
                    </div>
                    
                    <!-- Legend -->
                    <div class="flex items-center gap-4 text-xs">
                        <div class="flex items-center gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            <span class="text-gray-600">Realisasi Progres</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                            <span class="text-gray-600">Input Manual</span>
                        </div>
                    </div>
                </div>

                ${details.length === 0 ? `
                    <div class="text-center py-8 text-sm text-gray-400 italic">
                        Belum ada data pohon
                    </div>
                ` : `
                    <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                        ${sortedYears.map(year => {
                            const yearData = groupedByYear[year];
                            const yearRealisasi = yearData.realisasi.reduce((sum, item) => sum + Number(item.realisasi_progres || 0), 0);
                            const yearManual = yearData.manual.reduce((sum, item) => sum + Number(item.stok_manual || 0), 0);
                            const yearTotal = yearRealisasi + yearManual;
                            
                            return `
                                <!-- Year Card -->
                                <div class="border border-gainsboro rounded-lg overflow-hidden">
                                    <!-- Year Header -->
                                    <div class="bg-gray-50 px-4 py-2 border-b border-gainsboro">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                                </svg>
                                                <span class="font-bold text-darkslategray text-sm">Tahun ${year}</span>
                                            </div>
                                            <span class="text-sm font-semibold text-gray-600">
                                                Total: ${yearTotal.toLocaleString('id-ID')} btg
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Table Headers -->
                                    <div class="bg-gray-50 border-b border-gainsboro">
                                        <div class="grid grid-cols-12 gap-2 px-4 py-2 text-xs font-bold text-gray-600 uppercase">
                                            <div class="col-span-6">Lokasi</div>
                                            <div class="col-span-3 text-right">Jumlah Pohon</div>
                                            <div class="col-span-3 text-center">Aksi</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Data Rows -->
                                    <div class="divide-y divide-gray-100">
                                        ${yearData.realisasi.length > 0 ? yearData.realisasi.map(item => {
                                            const canUpdate = item.can_update === true;
                                            const editAction = canUpdate && item.edit_url
                                                ? `window.location.href='${item.edit_url}'`
                                                : `return false;`;
                                            
                                            return `
                                                <div class="grid grid-cols-12 gap-2 items-center px-4 py-3 hover:bg-gray-50 transition-colors">
                                                    <!-- Lokasi Column -->
                                                    <div class="col-span-6 flex items-center gap-3">
                                                        <div class="w-2 h-2 rounded-full bg-green-500 flex-shrink-0"></div>
                                                        <div class="flex items-center gap-2">
                                                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 16 16">
                                                                <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                                            </svg>
                                                            <span class="text-sm font-medium text-darkslategray">${item.plot_name}</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Jumlah Pohon Column -->
                                                    <div class="col-span-3 text-right">
                                                        <span class="text-sm font-bold text-green-700">
                                                            ${Number(item.realisasi_progres || 0).toLocaleString('id-ID')}
                                                        </span>
                                                        <span class="text-xs text-gray-500 ml-1">Btg</span>
                                                    </div>
                                                    
                                                    <!-- Empty for realisasi -->
                                                    <div class="col-span-3 flex items-center justify-center gap-2">
                                                        <span class="text-xs text-gray-400 italic">Auto dari progres</span>
                                                    </div>
                                                </div>
                                            `;
                                        }).join('') : ''}
                                        
                                        ${yearData.manual.length > 0 ? yearData.manual.map(item => {
                                            const canUpdate = item.can_update === true;
                                            const canDelete = item.can_delete === true;
                                            
                                            const editAction = canUpdate && item.edit_url
                                                ? `window.location.href='${item.edit_url}'`
                                                : `return false;`;
                                            
                                            const deleteAction = canDelete && item.data_pohon_manual_id
                                                ? `window.openDeletePohonModal(${item.data_pohon_manual_id}, '${item.tahun}')`
                                                : `return false;`;
                                            
                                            return `
                                                <div class="grid grid-cols-12 gap-2 items-center px-4 py-3 hover:bg-gray-50 transition-colors">
                                                    <!-- Lokasi Column -->
                                                    <div class="col-span-6 flex items-center gap-3">
                                                        <div class="w-2 h-2 rounded-full bg-blue-500 flex-shrink-0"></div>
                                                        <span class="text-sm font-medium text-gray-500 italic">Input Manual</span>
                                                    </div>
                                                    
                                                    <!-- Jumlah Pohon Column -->
                                                    <div class="col-span-3 text-right">
                                                        <span class="text-sm font-bold text-blue-700">
                                                            ${Number(item.stok_manual || 0).toLocaleString('id-ID')}
                                                        </span>
                                                        <span class="text-xs text-gray-500 ml-1">Btg</span>
                                                    </div>
                                                    
                                                    <!-- Aksi Column -->
                                                    <div class="col-span-3 flex items-center justify-center gap-2">
                                                        <button 
                                                            onclick="${editAction}" 
                                                            ${!canUpdate ? 'disabled' : ''}
                                                            class="${canUpdate ? 'cursor-pointer' : 'opacity-50 cursor-not-allowed'}"
                                                            title="${canUpdate ? 'Edit data manual' : 'Tidak ada permission'}">
                                                            <svg width="14" height="14" viewBox="0 0 20 20" fill="none">
                                                                <path d="M5.83301 5.83334H4.99967C4.55765 5.83334 4.13372 6.00894 3.82116 6.3215C3.5086 6.63406 3.33301 7.05798 3.33301 7.50001V15C3.33301 15.442 3.5086 15.866 3.82116 16.1785C4.13372 16.4911 4.55765 16.6667 4.99967 16.6667H12.4997C12.9417 16.6667 13.3656 16.4911 13.6782 16.1785C13.9907 15.866 14.1663 15.442 14.1663 15V14.1667" stroke="#27374D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                <path d="M13.3333 4.16666L15.8333 6.66666M16.9875 5.4875C17.3157 5.15929 17.5001 4.71415 17.5001 4.25C17.5001 3.78585 17.3157 3.3407 16.9875 3.0125C16.6593 2.68429 16.2142 2.49991 15.75 2.49991C15.2858 2.49991 14.8407 2.68429 14.5125 3.0125L7.5 10V12.5H10L16.9875 5.4875Z" stroke="#27374D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </button>
                                                        <button 
                                                            onclick="${deleteAction}" 
                                                            ${!canDelete ? 'disabled' : ''}
                                                            class="${canDelete ? 'cursor-pointer' : 'opacity-50 cursor-not-allowed'}"
                                                            title="${canDelete ? 'Hapus data manual' : 'Tidak ada permission'}">
                                                            <svg width="14" height="14" viewBox="0 0 13 16" fill="none">
                                                                <path d="M3.92321 1.01855L3.71429 1.4375H0.928571C0.414955 1.4375 0 1.85645 0 2.375C0 2.89355 0.414955 3.3125 0.928571 3.3125H12.0714C12.585 3.3125 13 2.89355 13 2.375C13 1.85645 12.585 1.4375 12.0714 1.4375H9.28571L9.07679 1.01855C8.92009 0.699219 8.59799 0.5 8.24688 0.5H4.75312C4.40201 0.5 4.07991 0.699219 3.92321 1.01855ZM12.0714 4.25H0.928571L1.54375 14.1816C1.59018 14.9229 2.19955 15.5 2.93371 15.5H10.0663C10.8004 15.5 11.4098 14.9229 11.4562 14.1816L12.0714 4.25Z" fill="#F24822"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            `;
                                        }).join('') : ''}
                                    </div>
                                </div>
                            `;
                        }).join('')}
                    </div>
                    
                    <!-- Grand Total Footer -->
                    <div class="mt-4 pt-4 border-t-2 border-gainsboro">
                        <div class="grid grid-cols-3 gap-4 px-4">
                            <div class="text-center">
                                <div class="text-xs text-gray-500 mb-1">Total Realisasi</div>
                                <div class="text-lg font-bold text-green-700">${totalRealisasi.toLocaleString('id-ID')} <span class="text-xs text-gray-500">Btg</span></div>
                            </div>
                            <div class="text-center border-l-2 border-gainsboro">
                                <div class="text-xs text-gray-500 mb-1">Total Manual</div>
                                <div class="text-lg font-bold text-blue-700">${totalManual.toLocaleString('id-ID')} <span class="text-xs text-gray-500">Btg</span></div>
                            </div>
                            <div class="text-center border-l-2 border-gainsboro">
                                <div class="text-xs text-gray-500 mb-1">Grand Total</div>
                                <div class="text-xl font-bold text-darkslategray">${grandTotal.toLocaleString('id-ID')} <span class="text-xs text-gray-500">Btg</span></div>
                            </div>
                        </div>
                    </div>
                `}
            </div>

            ${data.monitoring_data && data.monitoring_data.length > 0 ? `
                <div class="bg-white border border-gainsboro rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-darkslategray">Analisis Trend Monitoring</h3>
                                <p class="text-xs text-gray-500">Lihat perkembangan survival rate dari waktu ke waktu</p>
                            </div>
                        </div>
                        
                        <button 
                            type="button"
                            onclick="openTrendModal(${data.jenis_pohon_id || jenisPohon.id})"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors cursor-pointer">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            Lihat Trend
                        </button>
                    </div>
                </div>
            ` : ''}

            ${kategori !== 'COVER CROP' ? `
                <!-- Survival Rate Monitoring Card -->
                <div class="bg-white border border-gainsboro rounded-xl shadow-sm">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-base font-semibold text-darkslategray">Monitoring Survival Rate</h3>
                            </div>
                            
                            <!-- Navigation dots -->
                            <div class="flex items-center gap-2">
                                <div id="sr-dots" class="flex gap-1"></div>
                            </div>
                        </div>
                        
                        <!-- Slider content -->
                        <div id="sr-slider-content" class="min-h-[150px]"></div>
                    </div>
                </div>
            ` : ''}

            <!-- Log Card -->
            ${created_at || updated_at ? `
            <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-sm">
                <h3 class="text-sm font-semibold text-darkslategray mb-4 flex items-center gap-2">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg width="17" height="16" viewBox="0 0 17 16" fill="none"><path d="M8.6665 14C7.13317 14 5.79717 13.4918 4.6585 12.4753C3.51984 11.4589 2.86695 10.1893 2.69984 8.66667H4.0665C4.22206 9.82222 4.73606 10.7778 5.6085 11.5333C6.48095 12.2889 7.50028 12.6667 8.6665 12.6667C9.9665 12.6667 11.0694 12.214 11.9752 11.3087C12.8809 10.4033 13.3336 9.30044 13.3332 8C13.3327 6.69956 12.8801 5.59689 11.9752 4.692C11.0703 3.78711 9.96739 3.33422 8.6665 3.33333C7.89984 3.33333 7.18317 3.51111 6.5165 3.86667C5.84984 4.22222 5.28873 4.71111 4.83317 5.33333H6.6665V6.66667H2.6665V2.66667H3.99984V4.23333C4.5665 3.52222 5.25828 2.97222 6.07517 2.58333C6.89206 2.19444 7.75584 2 8.6665 2C9.49984 2 10.2805 2.15844 11.0085 2.47533C11.7365 2.79222 12.3698 3.21978 12.9085 3.758C13.4472 4.29622 13.8749 4.92956 14.1918 5.658C14.5087 6.38644 14.6669 7.16711 14.6665 8C14.6661 8.83289 14.5078 9.61356 14.1918 10.342C13.8758 11.0704 13.4481 11.7038 12.9085 12.242C12.3689 12.7802 11.7356 13.208 11.0085 13.5253C10.2814 13.8427 9.50073 14.0009 8.6665 14ZM10.5332 10.8L7.99984 8.26667V4.66667H9.33317V7.73333L11.4665 9.86667L10.5332 10.8Z" fill="currentColor"/></svg>
                    </div>
                    Log
                </h3>
                <div class="flex flex-col gap-4">
                    ${created_at ? `
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M9.99984 9.66667L12.0832 11.75C12.2359 11.9028 12.3123 12.0972 12.3123 12.3333C12.3123 12.5694 12.2359 12.7639 12.0832 12.9167C11.9304 13.0694 11.7359 13.1458 11.4998 13.1458C11.2637 13.1458 11.0693 13.0694 10.9165 12.9167L8.58317 10.5833C8.49984 10.5 8.43734 10.4064 8.39567 10.3025C8.354 10.1986 8.33317 10.0908 8.33317 9.97917V6.66666C8.33317 6.43055 8.41317 6.23278 8.57317 6.07333C8.73317 5.91389 8.93095 5.83389 9.1665 5.83333C9.40206 5.83278 9.60012 5.91278 9.76067 6.07333C9.92123 6.23389 10.0009 6.43166 9.99984 6.66666V9.66667ZM14.9998 5H13.3332C13.0971 5 12.8993 4.92028 12.7398 4.76083C12.5804 4.60139 12.5004 4.40333 12.4998 4.16666C12.4993 3.93 12.5793 3.73222 12.7398 3.57333C12.9004 3.41444 13.0982 3.33444 13.3332 3.33333H14.9998V1.66666C14.9998 1.43055 15.0798 1.23278 15.2398 1.07333C15.3998 0.913887 15.5976 0.833887 15.8332 0.833331C16.0687 0.832776 16.2668 0.912776 16.4273 1.07333C16.5879 1.23389 16.6676 1.43166 16.6665 1.66666V3.33333H18.3332C18.5693 3.33333 18.7673 3.41333 18.9273 3.57333C19.0873 3.73333 19.1671 3.93111 19.1665 4.16666C19.1659 4.40222 19.0859 4.60028 18.9265 4.76083C18.7671 4.92139 18.5693 5.00111 18.3332 5H16.6665V6.66666C16.6665 6.90278 16.5865 7.10083 16.4265 7.26083C16.2665 7.42083 16.0687 7.50055 15.8332 7.5C15.5976 7.49944 15.3998 7.41944 15.2398 7.26C15.0798 7.10055 14.9998 6.90278 14.9998 6.66666V5ZM9.1665 17.5C8.12484 17.5 7.14928 17.3056 6.23984 16.9167C5.33039 16.5278 4.53512 15.9931 3.85401 15.3125C3.17289 14.6319 2.63817 13.8367 2.24984 12.9267C1.86151 12.0167 1.66706 11.0411 1.66651 10C1.66595 8.95889 1.86039 7.98333 2.24984 7.07333C2.63928 6.16333 3.17401 5.36805 3.85401 4.6875C4.53401 4.00694 5.32928 3.47222 6.23984 3.08333C7.15039 2.69444 8.12595 2.5 9.1665 2.5C9.31928 2.5 9.46178 2.50361 9.594 2.51083C9.72623 2.51805 9.86845 2.53528 10.0207 2.5625C10.2568 2.5625 10.4548 2.6425 10.6148 2.8025C10.7748 2.9625 10.8546 3.16028 10.854 3.39583C10.8534 3.63139 10.7734 3.82944 10.614 3.99C10.4546 4.15055 10.2568 4.23028 10.0207 4.22916C9.86789 4.22916 9.72539 4.21861 9.59317 4.1975C9.46095 4.17639 9.31873 4.16611 9.1665 4.16666C7.52762 4.16666 6.14567 4.72916 5.02067 5.85416C3.89567 6.97916 3.33317 8.36111 3.33317 10C3.33317 11.6389 3.89567 13.0208 5.02067 14.1458C6.14567 15.2708 7.52762 15.8333 9.1665 15.8333C10.8054 15.8333 12.1873 15.2708 13.3123 14.1458C14.4373 13.0208 14.9998 11.6389 14.9998 10C14.9998 9.76389 15.0798 9.56611 15.2398 9.40667C15.3998 9.24722 15.5976 9.16722 15.8332 9.16667C16.0687 9.16611 16.2668 9.24611 16.4273 9.40667C16.5879 9.56722 16.6676 9.765 16.6665 10C16.6665 11.0417 16.4721 12.0175 16.0832 12.9275C15.6943 13.8375 15.1596 14.6325 14.479 15.3125C13.7984 15.9925 13.0032 16.5272 12.0932 16.9167C11.1832 17.3061 10.2076 17.5006 9.1665 17.5Z" fill="currentColor"/></svg>
                        </div>
                        <div class="flex flex-col">
                            <div class="text-sm font-semibold text-darkslategray mb-1">Dibuat pada</div>
                            <div class="text-sm text-slategray">${formatDateTime(created_at)}</div>
                        </div>
                    </div>
                    ` : ''}
                    ${updated_at ? `
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M2.021 10.3345C2.95608 12.7238 5.28066 14.4167 8.00016 14.4167C11.5439 14.4167 14.4168 11.5437 14.4168 8M13.9793 5.6655C13.0454 3.27558 10.7202 1.58333 8.00016 1.58333C4.45641 1.58333 1.5835 4.45625 1.5835 8M6.25016 10.3333H1.5835V15M14.4168 1V5.66667H9.75016" stroke="currentColor" stroke-width="2"/></svg>
                        </div>
                        <div class="flex flex-col">
                            <div class="text-sm font-semibold text-darkslategray mb-1">Terakhir diubah</div>
                            <div class="text-sm text-slategray">${formatDateTime(updated_at)}</div>
                        </div>
                    </div>
                    ` : ''}
                </div>
            </div>
            ` : ''}
        `;

    // Initialize SR slider
    if (document.getElementById('sr-slider-content')) {
        // Pass monitoring data to the slider
        const monitoringData = window.monitoringDataFromServer || [];
        window.srSlider.init(monitoringData);
    }

    // Show modal
    modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    /**
     * Close Pohon Modal
     */
    window.closePohonModal = () => {
        const modal = document.getElementById('pohonModal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';

            // Cleanup SR slider
            if (window.srSlider) {
                window.srSlider.destroy();
            }
        }
    };

    /**
     * Open Delete Confirmation Modal
     */
    window.openDeletePohonModal = function(dataPohonManualId) {
        if (!dataPohonManualId) {
            console.error('Invalid data_pohon_manual_id:', dataPohonManualId);
            return;
        }
        
        window.closePohonModal();
        
        setTimeout(() => {
            window.dispatchEvent(
                new CustomEvent('open-modal', { 
                    detail: `confirm-pohon-deletion-${dataPohonManualId}` 
                })
            );
        }, 100);
    };

    // Close modal when clicking outside
    document.addEventListener('click', (e) => {
        const modal = document.getElementById('pohonModal');
        if (modal && !modal.classList.contains('hidden') && e.target === modal) {
            window.closePohonModal();
        }
    });

    // Close modal with ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const modal = document.getElementById('pohonModal');
            if (modal && !modal.classList.contains('hidden')) {
                window.closePohonModal();
            }
        }
    });
});