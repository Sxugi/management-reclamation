document.addEventListener("turbo:load", () => {
    window.openDetailModal = (data) => {
        const modal = document.getElementById('plotInfoModal');
        const content = document.getElementById('infoModalContent');
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

        // Field statis
        const staticFields = [
            { label: 'Tanggal', value: formatDate(data.tanggal) || '-' },
            { label: 'Kategori', value: data.kategori?.label || '-' },
            { label: 'Jenis Aktivitas', value: data.jenis_aktivitas?.label || '-' },
        ];

        // Build HTML
        const staticHtml = `
            <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-lg space-y-4 mb-6">
                <h3 class="text-lg font-semibold text-darkslategray mb-4 flex items-center gap-2">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1.66699 7.99999C1.66699 11.682 4.65166 14.6667 8.33366 14.6667C12.0157 14.6667 15.0003 11.682 15.0003 7.99999C15.0003 4.31799 12.0157 1.33333 8.33366 1.33333C4.65166 1.33333 1.66699 4.31799 1.66699 7.99999ZM13.667 7.99999C13.667 9.41448 13.1051 10.771 12.1049 11.7712C11.1047 12.7714 9.74815 13.3333 8.33366 13.3333C6.91917 13.3333 5.56262 12.7714 4.56242 11.7712C3.56223 10.771 3.00033 9.41448 3.00033 7.99999C3.00033 6.58551 3.56223 5.22895 4.56242 4.22876C5.56262 3.22856 6.91917 2.66666 8.33366 2.66666C9.74815 2.66666 11.1047 3.22856 12.1049 4.22876C13.1051 5.22895 13.667 6.58551 13.667 7.99999ZM12.3337 7.99999C12.3344 8.52541 12.2314 9.04581 12.0304 9.53125C11.8293 10.0167 11.5343 10.4576 11.1623 10.8287L8.33366 7.99999V3.99999C9.39452 3.99999 10.4119 4.42142 11.1621 5.17157C11.9122 5.92171 12.3337 6.93913 12.3337 7.99999Z" fill="#1D2939"/>
                        </svg>
                    </div>
                    Informasi Data Progres Reklamasi
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    ${staticFields.map(f => `
                        <div>
                            <label class="block text-xs font-semibold text-slategray uppercase tracking-wide mb-2">${f.label}</label>
                            <div class="bg-gray-50 border border-gainsboro rounded-lg p-2">
                                <div class="text-sm font-medium text-darkslategray">${f.value}</div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;

        const dataInputHtml = data.field_values && data.field_values.length > 0
            ? `<div class="grid grid-cols-2 gap-4">
                ${data.field_values.map(fv => 
                    `<div>
                        <label class="block text-xs font-semibold text-slategray capitalize tracking-wide mb-2">${fv.label}</label>
                        <div class="bg-gray-50 border border-gainsboro rounded-lg p-2">
                            <div class="text-sm font-medium text-darkslategray">${fv.value}${fv.satuan ? ' ' + fv.satuan : ''}</div>
                        </div>
                    </div>`
                ).join('')}
            </div>`
            : `<div class="text-center py-8">
                <div class="text-gray-400 text-sm">Tidak ada data input</div>
            </div>`;
        
        const docsHtml = data.progres_dokumentasi && data.progres_dokumentasi.length > 0
            ? `<div class="bg-white border border-gainsboro rounded-xl p-6 shadow-lg overflow-hidden">
                    <h3 class="text-lg font-semibold text-darkslategray mb-4 flex items-center gap-2">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-purple-600">
                                <path d="M21 15V16.2C21 17.8802 21 18.7202 20.673 19.362C20.3854 19.9265 19.9265 20.3854 19.362 20.673C18.7202 21 17.8802 21 16.2 21H7.8C6.11984 21 5.27976 21 4.63803 20.673C4.07354 20.3854 3.6146 19.9265 3.32698 19.362C3 18.7202 3 17.8802 3 16.2V7.8C3 6.11984 3 5.27976 3.32698 4.63803C3.6146 4.07354 4.07354 3.6146 4.63803 3.32698C5.27976 3 6.11984 3 7.8 3H12M21 15L18 12M21 15L18 18M15 3H21M21 3V9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        Dokumentasi (${data.progres_dokumentasi.length} foto)
                    </h3>
                    
                    <!-- Grid layout for multiple images -->
                    <div class="grid ${data.progres_dokumentasi.length === 1 ? 'grid-cols-1' : data.progres_dokumentasi.length === 2 ? 'grid-cols-2' : data.progres_dokumentasi.length === 3 ? 'grid-cols-3' : 'grid-cols-2 lg:grid-cols-3'} gap-4">
                        ${data.progres_dokumentasi.map((doc, index) => `
                            <div class="relative group">
                                <!-- Image container -->
                                <div class="aspect-square rounded-xl overflow-hidden bg-gray-50 shadow-sm">
                                    <img src="${doc.image_path}"
                                        alt="${doc.nama || `Dokumentasi ${index + 1}`}"
                                        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                        onerror="this.src='${window.DEFAULT_PLACEHOLDER_IMAGE || '/images/default-placeholder.png'}'">
                                    
                                    <!-- Overlay on hover -->
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/50 transition-all duration-300 flex items-center justify-center">
                                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col items-center gap-2">
                                            <!-- View button -->
                                            <button onclick="openImageModal('${doc.image_path}', '${doc.nama || `Dokumentasi ${index + 1}`}')"
                                                    class="flex items-center font-semibold text-xs bg-white text-gray-800 py-2 px-3 gap-1.5 rounded-lg shadow-lg hover:bg-gray-100 transition-colors">
                                                <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M18.125 16.7188L13.2781 11.8711C14.0592 10.7977 14.4794 9.50407 14.4781 8.17656C14.4781 4.70195 11.6512 1.875 8.17656 1.875C4.70195 1.875 1.875 4.70195 1.875 8.17656C1.875 11.6512 4.70195 14.4781 8.17656 14.4781C9.50407 14.4794 10.7977 14.0592 11.8711 13.2781L16.7188 18.125L18.125 16.7188ZM8.17656 12.4879C7.32375 12.488 6.49007 12.2351 5.78095 11.7614C5.07183 11.2877 4.51913 10.6143 4.19274 9.82638C3.86635 9.0385 3.78093 8.17152 3.94728 7.33509C4.11364 6.49867 4.5243 5.73035 5.12733 5.12733C5.73035 4.5243 6.49867 4.11364 7.33509 3.94728C8.17152 3.78093 9.0385 3.86635 9.82638 4.19274C10.6143 4.51913 11.2877 5.07183 11.7614 5.78095C12.2351 6.49007 12.488 7.32375 12.4879 8.17656C12.4865 9.31959 12.0319 10.4154 11.2236 11.2236C10.4154 12.0319 9.31959 12.4865 8.17656 12.4879Z" fill="currentColor"/>
                                                </svg>
                                                Lihat
                                            </button>
                                            
                                            <!-- Download button (optional) -->
                                            <a href="${doc.image_path}" 
                                            download="${doc.nama || `dokumentasi-${index + 1}`}"
                                            class="flex items-center font-semibold text-xs bg-blue-600 text-white py-2 px-3 gap-1.5 rounded-lg shadow-lg hover:bg-blue-700 transition-colors">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M21 15V16.2C21 17.8802 21 18.7202 20.673 19.362C20.3854 19.9265 19.9265 20.3854 19.362 20.673C18.7202 21 17.8802 21 16.2 21H7.8C6.11984 21 5.27976 21 4.63803 20.673C4.07354 20.3854 3.6146 19.9265 3.32698 19.362C3 18.7202 3 17.8802 3 16.2V15M17 10L12 15M12 15L7 10M12 15V3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <!-- Image counter badge for multiple images -->
                                    ${data.progres_dokumentasi.length > 1 ? `
                                        <div class="absolute top-2 right-2 bg-black/70 text-white text-xs font-semibold px-2 py-1 rounded-full">
                                            ${index + 1}/${data.progres_dokumentasi.length}
                                        </div>
                                    ` : ''}
                                </div>
                                
                                <!-- Image title/description (if available) -->
                                ${doc.nama && doc.nama.trim() !== '' ? `
                                    <div class="mt-2">
                                        <p class="text-sm font-medium text-gray-800 truncate" title="${doc.nama}">
                                            ${doc.nama}
                                        </p>
                                    </div>
                                ` : ''}
                            </div>
                        `).join('')}
                    </div>
                    
                    <!-- Show all images button if more than 6 images -->
                    ${data.progres_dokumentasi.length > 6 ? `
                        <div class="mt-4 text-center">
                            <button onclick="showAllImages()" 
                                    class="text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors">
                                Lihat semua ${data.progres_dokumentasi.length} foto →
                            </button>
                        </div>
                    ` : ''}
                </div>`
            : '';

        const logHtml = `
            <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-lg flex flex-col justify-center h-full">
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

        content.innerHTML = `
            ${staticHtml}
            <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-lg mb-6">
                <h3 class="text-sm font-semibold mb-2">Detail Data Input</h3>
                ${dataInputHtml}
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
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-2">
                        <div class="text-sm text-darkslategray whitespace-pre-wrap leading-relaxed">${data.catatan}</div>
                    </div>
                </div>
            ` : ''}
            ${docsHtml}
            ${logHtml}
        `;

        document.getElementById('editButton').onclick = () => {
            if (data.edit_url) {
                window.location.href = data.edit_url;
            }
        };

        if (deleteButton) {
            if (data.progres_id) {
                deleteButton.onclick = () => {
                    window.closeDetailModal();
                    window.dispatchEvent(
                        new CustomEvent('open-modal', { detail: `confirm-progres-deletion-${data.progres_id}` })
                    );
                };
                deleteButton.classList.remove('hidden');
            } else {
                deleteButton.classList.add('hidden');
                deleteButton.onclick = null;
            }
        }

        window.openImageModal = function(imageSrc, imageName = '') {
            const imageModal = document.getElementById('imageModal');
            const img = document.getElementById('modalImg');
            const modalTitle = document.getElementById('modalImageTitle');
            
            if (img && imageModal) {
                img.src = imageSrc;
                
                // Update modal title if available
                if (modalTitle && imageName) {
                    modalTitle.textContent = imageName;
                }
                
                // If multiple images, add navigation
                if (data.progres_dokumentasi && data.progres_dokumentasi.length > 1) {
                    addImageNavigation(imageSrc);
                }
                
                imageModal.classList.remove('hidden');
            }
        }

        function addImageNavigation(currentImageSrc) {
            const currentIndex = data.progres_dokumentasi.findIndex(doc => doc.image_path === currentImageSrc);
            
            // Add navigation buttons to modal
            const navigationHtml = `
                <div class="absolute top-4 left-4 bg-black/50 text-white px-3 py-1 rounded-full text-sm">
                    ${currentIndex + 1} / ${data.progres_dokumentasi.length}
                </div>
                
                <button onclick="navigateImage('prev')" 
                        class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black/50 text-white p-2 rounded-full hover:bg-black/70 transition-colors">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                
                <button onclick="navigateImage('next')" 
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black/50 text-white p-2 rounded-full hover:bg-black/70 transition-colors">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            `;

            window.navigateImage = function(direction) {
                const img = document.getElementById('modalImg');
                const currentSrc = img.src;
                const currentIndex = data.progres_dokumentasi.findIndex(doc => doc.image_path.includes(currentSrc.split('/').pop()));
                
                let newIndex;
                if (direction === 'next') {
                    newIndex = (currentIndex + 1) % data.progres_dokumentasi.length;
                } else {
                    newIndex = currentIndex === 0 ? data.progres_dokumentasi.length - 1 : currentIndex - 1;
                }
                
                const newImage = data.progres_dokumentasi[newIndex];
                img.src = newImage.image_path;
                
                // Update title
                const modalTitle = document.getElementById('modalImageTitle');
                if (modalTitle) {
                    modalTitle.textContent = newImage.nama || `Dokumentasi ${newIndex + 1}`;
                }
                
                // Update counter
                const counter = document.querySelector('.absolute.top-4.left-4');
                if (counter) {
                    counter.textContent = `${newIndex + 1} / ${data.progres_dokumentasi.length}`;
                }
            }
            
            // Insert navigation into modal
            const modalContent = imageModal.querySelector('.relative');
            if (modalContent && !modalContent.querySelector('.absolute')) {
                modalContent.insertAdjacentHTML('beforeend', navigationHtml);
            }
        }

        window.closeImageModal = function() {
            document.getElementById('imageModal').classList.add('hidden');
        }

        modal.classList.remove('hidden');
    };

    window.closeDetailModal = () => {
        const modal = document.getElementById('plotInfoModal');
                if (modal) {
            modal.classList.add('hidden');
        }
    };

    document.addEventListener('click', (e) => {
        const modal = document.getElementById('plotInfoModal');
        if (modal && !modal.classList.contains('hidden') && e.target === modal) {
            window.closeDetailModal();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const modal = document.getElementById('plotInfoModal');
            if (modal && !modal.classList.contains('hidden')) {
                window.closeDetailModal();
            }
        }
    });
});