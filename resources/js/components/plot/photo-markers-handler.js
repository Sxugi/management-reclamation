export default {
    /**
     * Group markers by progres_id
     */
    groupByProgresId(markers) {
        const groups = {};
        
        markers.forEach(marker => {
            const progresId = marker.progres_id;
            
            if (!groups[progresId]) {
                groups[progresId] = {
                    progres_id: progresId,
                    kategori: marker.kategori,
                    kategori_label: marker.kategori_label,
                    jenis_aktivitas: marker.jenis_aktivitas,
                    jenis_aktivitas_label: marker.jenis_aktivitas_label,
                    tanggal_kegiatan: marker.tanggal_kegiatan,
                    detail_info: marker.detail_info,
                    photos: [],
                    latitude: marker.latitude,
                    longitude: marker.longitude,
                };
            }
            
            groups[progresId].photos.push({
                progres_dokumentasi_id: marker.progres_dokumentasi_id,
                image_url: marker.image_url,
                image_path: marker.image_path,
                created_at: marker.created_at,
                latitude: marker.latitude,
                longitude: marker.longitude,
            });
        });
        
        return Object.values(groups);
    },

    /**
     * Cluster progres by location proximity
     */
    clusterByLocation(progresGroups, threshold) {
        const clusters = [];
        const used = new Set();
        
        progresGroups.forEach((progres, index) => {
            if (used.has(index)) return;
            
            const cluster = {
                latitude: progres.latitude,
                longitude: progres.longitude,
                progres_items: [progres],
            };
            
            used.add(index);
            
            // Find nearby progres
            progresGroups.forEach((other, otherIndex) => {
                if (used.has(otherIndex)) return;
                
                const distance = Math.sqrt(
                    Math.pow(progres.latitude - other.latitude, 2) +
                    Math.pow(progres.longitude - other.longitude, 2)
                );
                
                if (distance < threshold) {
                    cluster.progres_items.push(other);
                    used.add(otherIndex);
                }
            });
            
            // Calculate cluster center
            if (cluster.progres_items.length > 1) {
                const avgLat = cluster.progres_items.reduce((sum, p) => sum + p.latitude, 0) / cluster.progres_items.length;
                const avgLng = cluster.progres_items.reduce((sum, p) => sum + p.longitude, 0) / cluster.progres_items.length;
                cluster.latitude = avgLat;
                cluster.longitude = avgLng;
            }
            
            clusters.push(cluster);
        });
        
        return clusters;
    },

    /**
     * Build single photo popup HTML
     */
    buildSinglePhotoPopup(photo, kategoriLabel, aktivitasLabel, detailInfoHtml, tanggalKegiatan) {
        return `
            <div class="photo-popup-content">
                <div class="relative">
                    <button class="btn-close-popup-photo absolute cursor-pointer top-1.5 right-1.5 w-6 h-6 bg-black/60 hover:bg-red-500 text-white flex items-center justify-center transition-all duration-200 hover:scale-110 z-10" style="border: none; outline: none; border-radius: 4px;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    
                    <img src="${photo.image_url}" 
                        alt="Dokumentasi" 
                        class="w-full h-32 object-cover cursor-pointer"
                        onclick="window.open('${photo.image_url}', '_blank')"
                        loading="lazy"
                    />
                </div>
                <div class="p-2">
                    <div class="flex justify-center mb-1">
                        <span class="inline-block px-1.5 py-0.5 text-xs font-semibold bg-blue-100 text-blue-700 rounded">
                            ${kategoriLabel}
                        </span>
                    </div>
                    
                    <h4 class="text-xs font-bold text-gray-800 mb-1.5 leading-tight">
                        ${aktivitasLabel}
                    </h4>
                    
                    ${detailInfoHtml ? `
                        <div class="mb-1.5 pb-1.5 border-b border-gray-200">
                            ${detailInfoHtml}
                        </div>
                    ` : ''}
                    
                    <div class="text-xs text-gray-500 space-y-0.5">
                        ${tanggalKegiatan ? `
                            <p class="truncate">📅 ${tanggalKegiatan}</p>
                        ` : ''}
                        <p class="font-mono text-xs truncate">📍 ${photo.latitude.toFixed(4)}, ${photo.longitude.toFixed(4)}</p>
                    </div>
                </div>
            </div>
        `;
    },

    /**
     * Build cluster popup HTML with navigation
     */
    buildClusterPopup(cluster, formatKategoriLabel, formatAktivitasLabel) {
        const progresCount = cluster.progres_items.length;
        
        let slidesHtml = '';
        cluster.progres_items.forEach((progres, index) => {
            const kategoriLabel = formatKategoriLabel(progres.kategori);
            const aktivitasLabel = formatAktivitasLabel(progres.jenis_aktivitas);
            const photoCount = progres.photos.length;
            
            // Build detail info
            let detailInfoHtml = '';
            if (progres.detail_info && progres.detail_info.length > 0) {
                detailInfoHtml = `<div class="grid grid-cols-2 gap-x-2 gap-y-1 text-xs">`;
                progres.detail_info.forEach(item => {
                    detailInfoHtml += `
                        <div class="text-gray-500 truncate">${item.label}:</div>
                        <div class="font-medium text-gray-700 text-right truncate">${item.value}</div>
                    `;
                });
                detailInfoHtml += `</div>`;
            }
            
            // Build photos
            let photosHtml = '';
            if (photoCount === 1) {
                photosHtml = `
                    <img src="${progres.photos[0].image_url}" 
                         alt="Dokumentasi" 
                         class="w-full h-32 object-cover cursor-pointer"
                         onclick="window.open('${progres.photos[0].image_url}', '_blank')"
                         loading="lazy"
                    />
                `;
            } else if (photoCount <= 4) {
                photosHtml = `<div class="grid grid-cols-2 gap-1 p-1 bg-whitesmoke">`;
                progres.photos.forEach((photo, photoIdx) => {
                    photosHtml += `
                        <img src="${photo.image_url}" 
                             alt="Foto ${photoIdx + 1}" 
                             class="w-full h-16 object-cover cursor-pointer rounded hover:opacity-90 transition"
                             onclick="window.open('${photo.image_url}', '_blank')"
                             loading="lazy"
                        />
                    `;
                });
                photosHtml += `</div>`;
            } else {
                photosHtml = `<div class="grid grid-cols-2 gap-1 p-1 bg-whitesmoke">`;
                progres.photos.slice(0, 3).forEach((photo, photoIdx) => {
                    photosHtml += `
                        <img src="${photo.image_url}" 
                             alt="Foto ${photoIdx + 1}" 
                             class="w-full h-16 object-cover cursor-pointer rounded hover:opacity-90 transition"
                             onclick="window.open('${photo.image_url}', '_blank')"
                             loading="lazy"
                        />
                    `;
                });
                photosHtml += `
                    <button class="open-detail-modal w-full h-16 bg-gradient-to-br from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white flex flex-col items-center justify-center rounded transition group" 
                            data-progres-id="${progres.progres_id}"
                            style="border: none; outline: none;">
                        <svg class="w-6 h-6 mb-1 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span class="text-xs font-bold">Lihat Detail</span>
                    </button>
                `;
                photosHtml += `</div>`;
            }
            
            slidesHtml += `
                <div class="progres-slide ${index === 0 ? '' : 'hidden'}" data-index="${index}">
                    <div class="relative">
                        ${photosHtml}
                        ${photoCount > 1 ? `
                            <div class="absolute top-2 left-2 bg-black/70 text-white text-xs px-2 py-1 rounded font-medium">
                                ${photoCount} foto
                            </div>
                        ` : ''}
                    </div>
                    
                    <div class="p-2">
                        <div class="flex justify-center mb-1">
                            <span class="inline-block px-1.5 py-0.5 text-xs font-semibold bg-blue-100 text-blue-700 rounded">
                                ${kategoriLabel}
                            </span>
                        </div>
                        
                        <h4 class="text-xs font-bold text-gray-800 mb-1.5 leading-tight">
                            ${aktivitasLabel}
                        </h4>
                        
                        ${detailInfoHtml ? `
                            <div class="mb-1.5 pb-1.5 border-b border-gray-200">
                                ${detailInfoHtml}
                            </div>
                        ` : ''}
                        
                        <div class="text-xs text-gray-500 space-y-0.5">
                            ${progres.tanggal_kegiatan ? `
                                <p class="truncate">📅 ${progres.tanggal_kegiatan}</p>
                            ` : ''}
                            <p class="font-mono text-xs truncate">📍 ${progres.latitude.toFixed(5)}, ${progres.longitude.toFixed(5)}</p>
                        </div>
                    </div>
                </div>
            `;
        });
        
        return `
            <div class="photo-popup-content relative">
                <button class="btn-close-popup-photo absolute cursor-pointer top-2 right-2 w-6 h-6 bg-black/60 hover:bg-red-500 text-white flex items-center justify-center transition-all duration-200 hover:scale-110 z-30 rounded" style="border: none; outline: none;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                
                <div class="slides-container">
                    ${slidesHtml}
                </div>
                
                ${progresCount > 1 ? `
                    <div class="flex items-center justify-between p-2 bg-gray-50 border-t">
                        <button class="nav-prev px-2 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600 disabled:opacity-50" style="border: none;">
                            ← Prev
                        </button>
                        <span class="text-xs text-gray-600 font-medium">
                            <span class="current-slide">1</span> / ${progresCount}
                        </span>
                        <button class="nav-next px-2 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600 disabled:opacity-50" style="border: none;">
                            Next →
                        </button>
                    </div>
                ` : ''}
            </div>
        `;
    },

    /**
     * Initialize cluster navigation (slides and modal links)
     */
    initClusterNavigation(popupNode, cluster, activePhotoPopup) {
        const slides = popupNode.querySelectorAll('.progres-slide');
        const prevBtn = popupNode.querySelector('.nav-prev');
        const nextBtn = popupNode.querySelector('.nav-next');
        const counter = popupNode.querySelector('.current-slide');
        
        // Handle "Open Detail Modal" buttons
        const modalBtns = popupNode.querySelectorAll('.open-detail-modal');
        modalBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const progresId = btn.dataset.progresId;
                
                // Try to find existing modal trigger
                const allModalTriggers = document.querySelectorAll('[onclick*="openDetailModal"]');
                const matchingTrigger = Array.from(allModalTriggers).find(el => {
                    const onclickAttr = el.getAttribute('onclick');
                    return onclickAttr && onclickAttr.includes(`"progres_id":${progresId}`);
                });
                
                if (matchingTrigger) {
                    if (activePhotoPopup) {
                        activePhotoPopup.remove();
                    }
                    matchingTrigger.click();
                } else {
                    console.warn('Modal trigger not found for progres_id:', progresId);
                    // Dispatch custom event as fallback
                    window.dispatchEvent(new CustomEvent('open-progres-modal', {
                        detail: { progres_id: progresId }
                    }));
                }
            });
        });
        
        if (slides.length <= 1) return;
        
        let currentIndex = 0;
        
        const updateSlide = (newIndex) => {
            slides.forEach(slide => slide.classList.add('hidden'));
            slides[newIndex].classList.remove('hidden');
            
            if (counter) counter.textContent = newIndex + 1;
            if (prevBtn) prevBtn.disabled = newIndex === 0;
            if (nextBtn) nextBtn.disabled = newIndex === slides.length - 1;
            
            currentIndex = newIndex;
        };
        
        if (prevBtn) {
            prevBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (currentIndex > 0) updateSlide(currentIndex - 1);
            });
        }
        
        if (nextBtn) {
            nextBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (currentIndex < slides.length - 1) updateSlide(currentIndex + 1);
            });
        }
        
        const handleKeydown = (e) => {
            if (e.key === 'ArrowLeft' && currentIndex > 0) {
                updateSlide(currentIndex - 1);
            } else if (e.key === 'ArrowRight' && currentIndex < slides.length - 1) {
                updateSlide(currentIndex + 1);
            }
        };
        
        document.addEventListener('keydown', handleKeydown);
        
        if (activePhotoPopup) {
            activePhotoPopup.on('close', () => {
                document.removeEventListener('keydown', handleKeydown);
            });
        }
        
        updateSlide(0);
    },

    /**
     * Label formatters
     */
    formatKategoriLabel(kategori) {
        const labels = {
            'perbaikan_tanah': 'Perbaikan Tanah',
            'cover_crops': 'Cover Crops',
            'penanaman_pohon': 'Penanaman Pohon',
            'pemeliharaan': 'Pemeliharaan',
            'monitoring': 'Monitoring'
        };
        return labels[kategori] || kategori;
    },

    formatAktivitasLabel(aktivitas) {
        const labels = {
            'aplikasi_kompos': 'Aplikasi Kompos',
            'aplikasi_pupuk_dasar': 'Pupuk Dasar',
            'pengapuran': 'Pengapuran',
            'penanaman_cover_crops': 'Tanam LCC',
            'penanaman_pionir': 'Pohon Pionir',
            'penanaman_lokal': 'Pohon Lokal',
            'penanaman_mpts': 'MPTS',
            'penyiangan': 'Penyiangan',
            'pemupukan': 'Pemupukan',
            'pengendalian_hama': 'Pengendalian Hama',
            'penyulaman': 'Penyulaman',
            'monitoring_survival_rate': 'Monitoring SR',
            'monitoring_pertumbuhan': 'Monitoring Pertumbuhan'
        };
        return labels[aktivitas] || aktivitas;
    }
};