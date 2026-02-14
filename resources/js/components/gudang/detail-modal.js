document.addEventListener("turbo:load", () => {
    window.openInfoModal = (data) => {
        // Check elements exist
        const modal = document.getElementById('infoModal');
        const content = document.getElementById('infoModalContent');
        const editButton = document.getElementById('editButton');
        const deleteButton = document.getElementById('deleteButton');

        if (!modal || !content) {
            console.error('Modal elements not found');
            return;
        }

        // Format date time for display
        const formatDateTime = (dateString) => {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                timeZoneName: 'short'
            });
        };

        // Format date only
        const formatDate = (dateString) => {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        };

        // Get transaction badge HTML
        const getTransactionBadge = (type) => {
            if (type === 'MASUK') {
                return '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200"><span class="mr-1">⬇️</span> Barang Masuk</span>';
            } else if (type === 'KELUAR') {
                return '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200"><span class="mr-1">⬆️</span> Barang Keluar</span>';
            }
            return '-';
        };

        // Get status badge class
        const getStatusBadgeClass = (status) => {
            switch(status) {
                case 'Tersedia':
                    return 'bg-green-100 text-xs text-green-800 border border-green-200';
                case 'Kosong':
                    return 'bg-orange-100 text-xs text-orange-800 border border-orange-200';
                case 'Rusak':
                    return 'bg-red-100 text-xs text-red-800 border border-red-200';
                case 'Digunakan':
                    return 'bg-yellow-100 text-xs text-yellow-800 border border-yellow-200';
                default:
                    return 'bg-gray-100  text-xs text-gray-800 border border-gray-200';
            }
        };

        // Get status icon
        const getStatusIcon = (status) => {
            switch(status) {
                case 'Tersedia':
                    return '✅';
                case 'Kosong':
                    return '❌';
                case 'Rusak':
                    return '⚠️';
                case 'Digunakan':
                    return '🔧';
                default:
                    return '📦';
            }
        };

        const getLabelLocation = (type) => {
            if (type === 'MASUK') {
                return 'Lokasi Penyimpanan';
            } else if (type === 'KELUAR') {
                return 'Lokasi Tujuan';
            }
            return '-';
        };

        // Build rich HTML content
        content.innerHTML = `
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Informasi Barang -->
                <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-lg">
                    <h3 class="text-lg font-semibold text-darkslategray mb-4 flex items-center gap-2">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-blue-600">
                                <path d="M20 7L12 3L4 7M20 7L12 11M20 7V17L12 21M12 11L4 7M12 11V21M4 7V17L12 21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        Informasi Barang
                    </h3>
                    <div class="space-y-4">
                        ${data. sku ? `
                        <div>
                            <label class="block text-xs font-semibold text-slategray uppercase tracking-wide mb-2">SKU / Kode</label>
                            <div class="bg-gray-50 border border-gainsboro rounded-lg p-2">
                                <div class="text-sm font-mono font-medium text-darkslategray">${data.sku}</div>
                            </div>
                        </div>
                        ` : ''}
                        <div>
                            <label class="block text-xs font-semibold text-slategray uppercase tracking-wide mb-2">Nama Barang</label>
                            <div class="bg-gray-50 border border-gainsboro rounded-lg p-2">
                                <div class="text-sm font-medium text-darkslategray">${data.nama_barang || '-'}</div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slategray uppercase tracking-wide mb-2">Jenis Barang</label>
                            <div class="bg-gray-50 border border-gainsboro rounded-lg p-2">
                                <div class="text-sm font-medium text-darkslategray">${data.jenis_barang || '-'}</div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slategray uppercase tracking-wide mb-2">Jumlah</label>
                            <div class="bg-gray-50 border border-gainsboro rounded-lg p-2">
                                <div class="text-sm font-medium text-darkslategray">${data.jumlah_barang || 0} ${data.satuan || 'unit'}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status & Lokasi -->
                <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-lg">
                    <h3 class="text-lg font-semibold text-darkslategray mb-4 flex items-center gap-2">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-green-600">
                                <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        Status & Lokasi
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slategray uppercase tracking-wide mb-2">Jenis Transaksi</label>
                            <div class="flex items-center">
                                ${getTransactionBadge(data.jenis_transaksi)}
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slategray uppercase tracking-wide mb-2">Kondisi Barang</label>
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${getStatusBadgeClass(data.status_barang)}">
                                    <span class="mr-2">${getStatusIcon(data.status_barang)}</span>
                                    ${data.status_barang || '-'}
                                </span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slategray uppercase tracking-wide mb-2">${getLabelLocation(data.jenis_transaksi)}</label>
                            <div class="bg-gray-50 border border-gainsboro rounded-lg p-2">
                                <div class="text-sm font-medium text-darkslategray">${data.lokasi_penyimpanan || '-'}</div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slategray uppercase tracking-wide mb-2">Tanggal Transaksi</label>
                            <div class="bg-gray-50 border border-gainsboro rounded-lg p-2">
                                <div class="text-sm font-medium text-darkslategray">${formatDate(data.tanggal_masuk)}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            ${data.catatan ? `
                <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-lg">
                    <h3 class="text-lg font-semibold text-darkslategray mb-4 flex items-center gap-2">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-orange-600">
                                <path d="M9 5H7C5.89543 5 5 5.89543 5 7V19C5 20.1046 5.89543 21 7 21H17C18.1046 21 19 20.1046 19 19V7C19 5.89543 18.1046 5 17 5H15M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5M9 5V7H15V5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9 13H15M9 17H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </div>
                        Catatan
                    </h3>
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                        <div class="text-sm text-darkslategray whitespace-pre-wrap leading-relaxed">${data.catatan}</div>
                    </div>
                </div>
            ` : ''}
        
            <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-lg">
                <h3 class="text-lg font-semibold text-darkslategray mb-4 flex items-center gap-2">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                        <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8.6665 14C7.13317 14 5.79717 13.4918 4.6585 12.4753C3.51984 11.4589 2.86695 10.1893 2.69984 8.66667H4.0665C4.22206 9.82222 4.73606 10.7778 5.6085 11.5333C6.48095 12.2889 7.50028 12.6667 8.6665 12.6667C9.9665 12.6667 11.0694 12.214 11.9752 11.3087C12.8809 10.4033 13.3336 9.30044 13.3332 8C13.3327 6.69956 12.8801 5.59689 11.9752 4.692C11.0703 3.78711 9.96739 3.33422 8.6665 3.33333C7.89984 3.33333 7.18317 3.51111 6.5165 3.86667C5.84984 4.22222 5.28873 4.71111 4.83317 5.33333H6.6665V6.66667H2.6665V2.66667H3.99984V4.23333C4.5665 3.52222 5.25828 2.97222 6.07517 2.58333C6.89206 2.19444 7.75584 2 8.6665 2C9.49984 2 10.2805 2.15844 11.0085 2.47533C11.7365 2.79222 12.3698 3.21978 12.9085 3.758C13.4472 4.29622 13.8749 4.92956 14.1918 5.658C14.5087 6.38644 14.6669 7.16711 14.6665 8C14.6661 8.83289 14.5078 9.61356 14.1918 10.342C13.8758 11.0704 13.4481 11.7038 12.9085 12.242C12.3689 12.7802 11.7356 13.208 11.0085 13.5253C10.2814 13.8427 9.50073 14.0009 8.6665 14ZM10.5332 10.8L7.99984 8.26667V4.66667H9.33317V7.73333L11.4665 9.86667L10.5332 10.8Z" fill="currentColor"/>
                        </svg>
                    </div>
                    Log
                </h3>
                <div class="flex flex-col items-start justify-start gap-4">
                    <div class="flex flex-row items-start space-x-4">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.99984 9.66667L12.0832 11.75C12.2359 11.9028 12.3123 12.0972 12.3123 12.3333C12.3123 12.5694 12.2359 12.7639 12.0832 12.9167C11.9304 13.0694 11.7359 13.1458 11.4998 13.1458C11.2637 13.1458 11.0693 13.0694 10.9165 12.9167L8.58317 10.5833C8.49984 10.5 8.43734 10.4064 8.39567 10.3025C8.354 10.1986 8.33317 10.0908 8.33317 9.97917V6.66666C8.33317 6.43055 8.41317 6.23278 8.57317 6.07333C8.73317 5.91389 8.93095 5.83389 9.1665 5.83333C9.40206 5.83278 9.60012 5.91278 9.76067 6.07333C9.92123 6.23389 10.0009 6.43166 9.99984 6.66666V9.66667ZM14.9998 5H13.3332C13.0971 5 12.8993 4.92028 12.7398 4.76083C12.5804 4.60139 12.5004 4.40333 12.4998 4.16666C12.4993 3.93 12.5793 3.73222 12.7398 3.57333C12.9004 3.41444 13.0982 3.33444 13.3332 3.33333H14.9998V1.66666C14.9998 1.43055 15.0798 1.23278 15.2398 1.07333C15.3998 0.913887 15.5976 0.833887 15.8332 0.833331C16.0687 0.832776 16.2668 0.912776 16.4273 1.07333C16.5879 1.23389 16.6676 1.43166 16.6665 1.66666V3.33333H18.3332C18.5693 3.33333 18.7673 3.41333 18.9273 3.57333C19.0873 3.73333 19.1671 3.93111 19.1665 4.16666C19.1659 4.40222 19.0859 4.60028 18.9265 4.76083C18.7671 4.92139 18.5693 5.00111 18.3332 5H16.6665V6.66666C16.6665 6.90278 16.5865 7.10083 16.4265 7.26083C16.2665 7.42083 16.0687 7.50055 15.8332 7.5C15.5976 7.49944 15.3998 7.41944 15.2398 7.26C15.0798 7.10055 14.9998 6.90278 14.9998 6.66666V5ZM9.1665 17.5C8.12484 17.5 7.14928 17.3056 6.23984 16.9167C5.33039 16.5278 4.53512 15.9931 3.85401 15.3125C3.17289 14.6319 2.63817 13.8367 2.24984 12.9267C1.86151 12.0167 1.66706 11.0411 1.66651 10C1.66595 8.95889 1.86039 7.98333 2.24984 7.07333C2.63928 6.16333 3.17401 5.36805 3.85401 4.6875C4.53401 4.00694 5.32928 3.47222 6.23984 3.08333C7.15039 2.69444 8.12595 2.5 9.1665 2.5C9.31928 2.5 9.46178 2.50361 9.594 2.51083C9.72623 2.51805 9.86845 2.53528 10.0207 2.5625C10.2568 2.5625 10.4548 2.6425 10.6148 2.8025C10.7748 2.9625 10.8546 3.16028 10.854 3.39583C10.8534 3.63139 10.7734 3.82944 10.614 3.99C10.4546 4.15055 10.2568 4.23028 10.0207 4.22916C9.86789 4.22916 9.72539 4.21861 9.59317 4.1975C9.46095 4.17639 9.31873 4.16611 9.1665 4.16666C7.52762 4.16666 6.14567 4.72916 5.02067 5.85416C3.89567 6.97916 3.33317 8.36111 3.33317 10C3.33317 11.6389 3.89567 13.0208 5.02067 14.1458C6.14567 15.2708 7.52762 15.8333 9.1665 15.8333C10.8054 15.8333 12.1873 15.2708 13.3123 14.1458C14.4373 13.0208 14.9998 11.6389 14.9998 10C14.9998 9.76389 15.0798 9.56611 15.2398 9.40667C15.3998 9.24722 15.5976 9.16722 15.8332 9.16667C16.0687 9.16611 16.2668 9.24611 16.4273 9.40667C16.5879 9.56722 16.6676 9.765 16.6665 10C16.6665 11.0417 16.4721 12.0175 16.0832 12.9275C15.6943 13.8375 15.1596 14.6325 14.479 15.3125C13.7984 15.9925 13.0032 16.5272 12.0932 16.9167C11.1832 17.3061 10.2076 17.5006 9.1665 17.5Z" fill="currentColor"/>
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <div class="text-sm font-semibold text-darkslategray mb-1">Dibuat pada</div>
                            <div class="text-sm text-slategray">${formatDateTime(data.created_at)}</div>
                        </div>
                    </div>
                    <div class="flex flex-row items-start space-x-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.021 10.3345C2.95608 12.7238 5.28066 14.4167 8.00016 14.4167C11.5439 14.4167 14.4168 11.5437 14.4168 8M13.9793 5.6655C13.0454 3.27558 10.7202 1.58333 8.00016 1.58333C4.45641 1.58333 1.5835 4.45625 1.5835 8M6.25016 10.3333H1.5835V15M14.4168 1V5.66667H9.75016" stroke="currentColor" stroke-width="2"/>
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <div class="text-sm font-semibold text-darkslategray mb-1">Terakhir diubah</div>
                            <div class="text-sm text-slategray">${formatDateTime(data.updated_at)}</div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        if (editButton) {            
            if (data.can_update && data.edit_url) {
                editButton.disabled = false
                editButton.onclick = () => {
                    window.location.href = data.edit_url;
                };
            } else {
                editButton.disabled = true;
                editButton.onclick = null;
            }
        }

        if (deleteButton) {
            if (data.can_delete && data.id) {
                if (data.id) {
                    deleteButton.disabled = false
                    deleteButton.onclick = () => {
                        window.closeInfoModal();
                        window.dispatchEvent(
                            new CustomEvent('open-modal', { detail: `confirm-gudang-deletion-${data.id}` })
                        );
                    };
                    deleteButton.classList.remove('hidden');
                } else {
                    deleteButton.classList.add('hidden');
                    deleteButton.onclick = null;
                }
            } else {
                deleteButton.disabled = true;
                deleteButton.onclick = null;
            }
        }

        modal.classList.remove('hidden');
    };

    window.closeInfoModal = () => {
        const modal = document.getElementById('infoModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    };

    // Close modal when clicking outside - with element check
    const modalElement = document.getElementById('infoModal');
    if (modalElement) {
        modalElement.addEventListener('click', (e) => {
            if (e.target === e.currentTarget) {
                window.closeInfoModal();
            }
        });
    }

    // Close modal with Escape key - with safer check
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const modal = document.getElementById('infoModal');
            if (modal && !modal.classList.contains('hidden')) {
                window.closeInfoModal();
            }
        }
    });
});