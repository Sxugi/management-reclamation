document.addEventListener("turbo:load", () => {
    // Check if modal elements exist before setting up functions
    const modal = document.getElementById('infoModal');
    const content = document.getElementById('infoModalContent');

    window.openInfoModal = (data) => {
        // Double check elements exist
        const modal = document.getElementById('infoModal');
        const content = document.getElementById('infoModalContent');
        const editButton = document.getElementById('editButton');

        if (!modal || !content) {
            console.error('Modal elements not found');
            return;
        }

        // Generate current date time (real-time)
        const getCurrentDateTime = () => {
            const now = new Date();
            const utc = new Date(now.getTime() + (now.getTimezoneOffset() * 60000));
            return utc.toISOString().slice(0, 19).replace('T', ' ');
        };

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

        // Get status badge class
        const getStatusBadgeClass = (status) => {
            switch(status) {
                case 'Tersedia':
                    return 'bg-green-100 text-green-800 border border-green-200';
                case 'Kosong':
                    return 'bg-orange-100 text-orange-800 border border-orange-200';
                case 'Rusak':
                    return 'bg-red-100 text-red-800 border border-red-200';
                case 'Digunakan':
                    return 'bg-yellow-100 text-yellow-800 border border-yellow-200';
                default:
                    return 'bg-gray-100 text-gray-800 border border-gray-200';
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

        // Build rich HTML content
        content.innerHTML = `
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-md">
                    <h3 class="text-lg font-semibold text-darkslategray mb-4 flex items-center gap-2">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-blue-600">
                                <path d="M20 7L12 3L4 7M20 7L12 11M20 7V17L12 21M12 11L4 7M12 11V21M4 7V17L12 21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        Informasi Barang
                    </h3>
                    <div class="space-y-4">
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

                <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-md">
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
                            <label class="block text-xs font-semibold text-slategray uppercase tracking-wide mb-2">Status Barang</label>
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium ${getStatusBadgeClass(data.status_barang)}">
                                    <span class="mr-2">${getStatusIcon(data.status_barang)}</span>
                                    ${data.status_barang || '-'}
                                </span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slategray uppercase tracking-wide mb-2">Lokasi Penyimpanan</label>
                            <div class="bg-gray-50 border border-gainsboro rounded-lg p-2">
                                <div class="text-sm font-medium text-darkslategray">${data.lokasi_penyimpanan || '-'}</div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slategray uppercase tracking-wide mb-2">Tanggal Masuk</label>
                            <div class="bg-gray-50 border border-gainsboro rounded-lg p-2">
                                <div class="text-sm font-medium text-darkslategray">${formatDate(data.tanggal_masuk)}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            ${data.catatan ? `
                <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-md">
                    <h3 class="text-lg font-semibold text-darkslategray mb-4 flex items-center gap-2">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-orange-600">
                                <path d="M9 5H7C5.89543 5 5 5.89543 5 7V19C5 20.1046 5.89543 21 7 21H17C18.1046 21 19 20.1046 19 19V7C19 5.89543 18.1046 5 17 5H15M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5M9 5V7H15V5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9 13H15M9 17H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </div>
                        Catatan
                    </h3>
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-2">
                        <div class="text-sm text-darkslategray whitespace-pre-wrap leading-relaxed">${data.catatan}</div>
                    </div>
                </div>
            ` : ''}
        
            <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-md">
                <h3 class="text-lg font-semibold text-darkslategray mb-4 flex items-center gap-2">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-purple-600">
                            <path d="M8 2V6M16 2V6M3 10H21M5 4H19C20.1046 4 21 4.89543 21 6V20C21 21.1046 20.1046 22 19 22H5C3.89543 22 3 21.1046 3 20V6C3 4.89543 3.89543 4 5 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    Log
                </h3>
                <div class="flex flex-col items-start justify-start gap-6">
                    <div class="flex flex-row items-start space-x-4">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-green-600">
                                <path d="M12 4V12L16 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <div class="text-sm font-semibold text-darkslategray mb-1">Dibuat pada</div>
                            <div class="text-sm text-slategray">${formatDateTime(data.created_at)}</div>
                        </div>
                    </div>
                    <div class="flex flex-row items-start space-x-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-blue-600">
                                <path d="M12 20H21L19 18L17 20L15 18L13 20H12M12 4V12M12 4L8 8M12 4L16 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
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

        // Set edit button URL if available
        if (editButton && data.edit_url) {
            editButton.onclick = () => {
                window.location.href = data.edit_url;
            };
        } else if (editButton) {
            editButton.style.display = 'none';
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