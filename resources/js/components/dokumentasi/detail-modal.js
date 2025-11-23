document.addEventListener('turbo:load', () => {
    window.openDokumentasiModal = (data) => {
        const modal = document.getElementById('dokumentasiInfoModal');
        const content = document.getElementById('infoModalContent');
        const editButton = document.getElementById('editButton');
        const deleteButton = document.getElementById('deleteButton');

        if (!modal || !content) return;

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

        // Build static information section
        const staticHtml = `
            <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-lg space-y-4 mb-6">
                <h3 class="text-lg font-semibold text-darkslategray mb-4 flex items-center gap-2">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="width="16" height="16"" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16 2C16.4142 2 16.75 2.33579 16.75 2.75V3.75H18.5C19.7426 3.75 20.75 4.75736 20.75 6V19C20.75 20.2426 19.7426 21.25 18.5 21.25H5.5C4.25736 21.25 3.25 20.2426 3.25 19V6C3.25 4.75736 4.25736 3.75 5.5 3.75H7.25V2.75C7.25 2.33579 7.58579 2 8 2C8.41421 2 8.75 2.33579 8.75 2.75V3.75H15.25V2.75C15.25 2.33579 15.5858 2 16 2ZM4.75 19C4.75 19.4142 5.08579 19.75 5.5 19.75H18.5C18.9142 19.75 19.25 19.4142 19.25 19V9.75H4.75V19ZM5.5 5.25C5.08579 5.25 4.75 5.58579 4.75 6V8.25H19.25V6C19.25 5.58579 18.9142 5.25 18.5 5.25H5.5Z" fill="currentColor"/>
                        </svg>
                    </div>
                    Informasi Data Dokumentasi
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slategray uppercase tracking-wide mb-2">Nama Dokumentasi</label>
                        <div class="bg-gray-50 border border-gainsboro rounded-lg p-2">
                            <div class="text-sm font-medium text-darkslategray">${data.nama}</div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slategray uppercase tracking-wide mb-2">Tanggal</label>
                        <div class="bg-gray-50 border border-gainsboro rounded-lg p-2">
                            <div class="text-sm font-medium text-darkslategray">${formatDate(data.created_at)}</div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Build image section (single image only)
        const imgSrc = data.image_path;
        const downloadUrl = data.download_url || imgSrc;
        const title = data.nama;

        const imgHtml = imgSrc ? `
            <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-lg mb-6">
                <h3 class="text-lg font-semibold text-darkslategray mb-4 flex items-center gap-2">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-purple-600">
                            <path d="M21 15V16.2C21 17.8802 21 18.7202 20.673 19.362C20.3854 19.9265 19.9265 20.3854 19.362 20.673C18.7202 21 17.8802 21 16.2 21H7.8C6.11984 21 5.27976 21 4.63803 20.673C4.07354 20.3854 3.6146 19.9265 3.32698 19.362C3 18.7202 3 17.8802 3 16.2V7.8C3 6.11984 3 5.27976 3.32698 4.63803C3.6146 4.07354 4.07354 3.6146 4.63803 3.32698C5.27976 3 6.11984 3 7.8 3H12M21 15L18 12M21 15L18 18M15 3H21M21 3V9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    Dokumentasi
                </h3>
                <div class="relative group rounded-lg overflow-hidden mb-3">
                    <div class="w-full max-h-[420px] overflow-hidden rounded-lg">
                        <img src="${imgSrc}" 
                             alt="${title}" 
                             class="w-full h-full object-contain"
                             onerror="this.src='${window.DEFAULT_PLACEHOLDER_IMAGE || '/images/default-placeholder.png'}'"/>
                    </div>
                    
                    <!-- Overlay on hover -->
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/50 transition-all duration-300 flex items-center justify-center rounded-lg">
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col items-center gap-3">
                            <!-- View button -->
                            <button type="button" 
                                    onclick="window.openDokumentasiImageModal('${imgSrc}', '${title}')"
                                    class="flex items-center font-semibold text-xs bg-white text-gray-800 py-2 px-4 gap-1.5 rounded-lg shadow-lg hover:bg-gray-100 transition-colors border-none cursor-pointer">
                                <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18.125 16.7188L13.2781 11.8711C14.0592 10.7977 14.4794 9.50407 14.4781 8.17656C14.4781 4.70195 11.6512 1.875 8.17656 1.875C4.70195 1.875 1.875 4.70195 1.875 8.17656C1.875 11.6512 4.70195 14.4781 8.17656 14.4781C9.50407 14.4794 10.7977 14.0592 11.8711 13.2781L16.7188 18.125L18.125 16.7188ZM8.17656 12.4879C7.32375 12.488 6.49007 12.2351 5.78095 11.7614C5.07183 11.2877 4.51913 10.6143 4.19274 9.82638C3.86635 9.0385 3.78093 8.17152 3.94728 7.33509C4.11364 6.49867 4.5243 5.73035 5.12733 5.12733C5.73035 4.5243 6.49867 4.11364 7.33509 3.94728C8.17152 3.78093 9.0385 3.86635 9.82638 4.19274C10.6143 4.51913 11.2877 5.07183 11.7614 5.78095C12.2351 6.49007 12.488 7.32375 12.4879 8.17656C12.4865 9.31959 12.0319 10.4154 11.2236 11.2236C10.4154 12.0319 9.31959 12.4865 8.17656 12.4879Z" fill="currentColor"/>
                                </svg>
                                Lihat
                            </button>
                            
                            <!-- Download button -->
                            <a href="${downloadUrl}" 
                               download="${title}"
                               class="flex items-center font-semibold text-xs bg-blue-600 text-white py-2 px-4 gap-1.5 rounded-lg shadow-lg hover:bg-blue-700 transition-colors no-underline">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21 15V16.2C21 17.8802 21 18.7202 20.673 19.362C20.3854 19.9265 19.9265 20.3854 19.362 20.673C18.7202 21 17.8802 21 16.2 21H7.8C6.11984 21 5.27976 21 4.63803 20.673C4.07354 20.3854 3.6146 19.9265 3.32698 19.362C3 18.7202 3 17.8802 3 16.2V15M17 10L12 15M12 15L7 10M12 15V3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Download
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        ` : `
            <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-lg mb-6 text-center text-slategray">
                <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-sm">Tidak ada gambar dokumentasi</p>
            </div>
        `;

        // Build description section
        const descHtml = data.deskripsi ? `
            <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-lg mb-6">
                <h3 class="text-lg font-semibold text-darkslategray mb-4 flex items-center gap-2">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-orange-600">
                            <path d="M9 5H7C5.89543 5 5 5.89543 5 7V19C5 20.1046 5.89543 21 7 21H17C18.1046 21 19 20.1046 19 19V7C19 5.89543 18.1046 5 17 5H15M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5M9 5V7H15V5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 13H15M9 17H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    Deskripsi
                </h3>
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <div class="text-sm text-darkslategray whitespace-pre-wrap leading-relaxed">${data.deskripsi}</div>
                </div>
            </div>
        ` : '';

        // Build log section
        const logHtml = `
            <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-lg">
                <h3 class="text-lg font-semibold text-darkslategray mb-4 flex items-center gap-2">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
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

        // Populate modal content
        content.innerHTML = `
            ${staticHtml}
            ${imgHtml}
            ${descHtml ? descHtml : ''}
            ${logHtml}
        `;

        // Setup edit button
        if (editButton && data.edit_url) {
            editButton.onclick = () => {
                window.location.href = data.edit_url;
            };
        }

        // Setup delete button
        if (deleteButton) {
            if (data.dokumentasi_id) {
                deleteButton.onclick = () => {
                    window.closeDokumentasiModal();
                    window.dispatchEvent(
                        new CustomEvent('open-modal', { detail: `confirm-dokumentasi-deletion-${data.dokumentasi_id}` })
                    );
                };
                deleteButton.classList.remove('hidden');
            } else {
                deleteButton.classList.add('hidden');
                deleteButton.onclick = null;
            }
        }

        modal.classList.remove('hidden');
    };

    window.closeDokumentasiModal = () => {
        const modal = document.getElementById('dokumentasiInfoModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    };

    window.openDokumentasiImageModal = (imageSrc, imageName = '') => {
        const imageModal = document.getElementById('imageModal');
        const img = document.getElementById('modalImg');
        
        if (img && imageModal) {
            img.src = imageSrc;
            img.alt = imageName;
            imageModal.classList.remove('hidden');
        }
    };

    window.closeDokumentasiImageModal = () => {
        const imageModal = document.getElementById('imageModal');
        if (imageModal) {
            imageModal.classList.add('hidden');
        }
    };

    // Close modals on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            window.closeDokumentasiModal();
            window.closeDokumentasiImageModal();
        }
    });

    // Close modal on backdrop click
    document.addEventListener('click', (e) => {
        const modal = document.getElementById('dokumentasiInfoModal');
        const imageModal = document.getElementById('imageModal');
        
        if (modal && !modal.classList.contains('hidden') && e.target === modal) {
            window.closeDokumentasiModal();
        }
        
        if (imageModal && !imageModal.classList.contains('hidden') && e.target === imageModal) {
            window.closeDokumentasiImageModal();
        }
    });
});