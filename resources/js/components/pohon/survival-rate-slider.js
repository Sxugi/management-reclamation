/**
 * Survival Rate Slider Component
 * Shows monitoring data per plot with auto-cycling slider
 */
class SurvivalRateSlider {
    constructor() {
        this.monitoringData = [];
        this.currentIndex = 0;
        this.autoInterval = null;
        this.AUTO_CYCLE_DURATION = 5000; // 5 seconds
        this.lastUserInteraction = null;
        this.USER_INTERACTION_PAUSE = 10000; // 10 seconds pause after user interaction
    }

    /**
     * Initialize slider with monitoring data
     */
    init(monitoringData) {
        this.monitoringData = Array.isArray(monitoringData) ? monitoringData : [];
        this.currentIndex = 0;
        
        const contentContainer = document.getElementById('sr-slider-content');
        const dotsContainer = document.getElementById('sr-dots');
        
        if (!contentContainer || !dotsContainer) {
            console.warn('SR Slider containers not found');
            return;
        }

        if (this.monitoringData.length === 0) {
            this.showEmptyState(contentContainer, dotsContainer);
            return;
        }

        this.generateDots();
        this.renderCurrent();

        if (this.monitoringData.length > 1) {
            this.startAutoCycle();
            this.bindHoverEvents();
        }
    }

    /**
     * Show empty state when no data
     */
    showEmptyState(contentContainer, dotsContainer) {
        contentContainer.innerHTML = `
            <div class="flex flex-col items-center justify-center py-8 text-gray-400">
                <svg class="w-16 h-16 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-sm font-medium">Belum ada data monitoring</p>
                <p class="text-xs mt-1">Data monitoring akan muncul setelah progres monitoring diinput</p>
            </div>
        `;
        dotsContainer.innerHTML = '';
    }

    /**
     * Generate navigation dots (max 3 visible)
     */
    generateDots() {
        const dotsContainer = document.getElementById('sr-dots');
        if (!dotsContainer) return;

        const totalItems = this.monitoringData.length;
        
        if (totalItems <= 1) {
            dotsContainer.innerHTML = '';
            return;
        }

        // Show max 3 dots
        const visibleDots = Math.min(totalItems, 3);
        
        let html = '';
        for (let i = 0; i < visibleDots; i++) {
            const isActive = i === this.getVisibleDotIndex();
            html += `
                <button 
                    onclick="window.srSlider.goTo(${this.getActualIndex(i)}, true)"
                    class="w-2 h-2 rounded-full transition-all duration-200 ${
                        isActive 
                            ? 'bg-purple-600 w-6' 
                            : 'bg-gray-300 hover:bg-purple-300'
                    }"
                    aria-label="Go to item ${i + 1}">
                </button>
            `;
        }

        dotsContainer.innerHTML = html;
    }

    /**
     * Get visible dot index (for 3-dot system)
     */
    getVisibleDotIndex() {
        const total = this.monitoringData.length;
        if (total <= 3) return this.currentIndex;
        
        // For more than 3 items, keep active dot in middle
        if (this.currentIndex === 0) return 0;
        if (this.currentIndex === total - 1) return 2;
        return 1;
    }

    /**
     * Get actual data index from visible dot index
     */
    getActualIndex(dotIndex) {
        const total = this.monitoringData.length;
        if (total <= 3) return dotIndex;
        
        if (this.currentIndex === 0) return dotIndex;
        if (this.currentIndex === total - 1) return total - 3 + dotIndex;
        return this.currentIndex - 1 + dotIndex;
    }

    /**
     * Render current slide
     */
    renderCurrent() {
        const contentContainer = document.getElementById('sr-slider-content');
        if (!contentContainer || this.monitoringData.length === 0) return;

        const data = this.monitoringData[this.currentIndex];
        if (!data) return;

        const survivalRate = this.calculateSR(data);
        const srClass = this.getSRClass(survivalRate);
        const srColor = this.getSRColor(survivalRate);

        const html = `
            <div class="slide-transition rounded-lg border border-gainsboro hover:shadow-md transition-all duration-200">
                <!-- Header -->
                <div class="bg-gradient-to-r ${srColor.gradient} p-4 rounded-t-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/90 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 ${srColor.icon}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-white font-bold text-base">${data.plot_name || 'Plot'}</h4>
                                <div class="flex items-center gap-2 text-white/80 text-xs mt-0.5">
                                    <span>${data.luas_area || '-'} ha</span>
                                    <span>•</span>
                                    <span>${data.jenis_pohon_nama || '-'}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- SR Badge -->
                        <div class="text-right">
                            <div class="text-3xl font-bold text-white">${survivalRate}%</div>
                            <div class="text-xs text-white/80">Survival Rate</div>
                        </div>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-4 bg-white rounded-b-lg">
                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs text-gray-600 font-medium">Health Status</span>
                            <span class="text-xs font-bold ${srColor.text}">${srClass}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="${srColor.bar} h-2 rounded-full transition-all duration-500" style="width: ${survivalRate}%"></div>
                        </div>
                    </div>

                    <!-- Stats Grid -->
                    <div class="grid grid-cols-3 gap-3">
                        <div class="text-center p-2 bg-gray-50 rounded-lg">
                            <div class="text-xs text-gray-500 mb-1">Ditanam</div>
                            <div class="text-base font-bold text-gray-700">${data.realisasi_progres.toLocaleString('id-ID')}</div>
                            <div class="text-xs text-gray-400">btg</div>
                        </div>
                        
                        <div class="text-center p-2 bg-green-50 rounded-lg">
                            <div class="text-xs text-green-600 mb-1">Hidup</div>
                            <div class="text-base font-bold text-green-700">${data.hidup.toLocaleString('id-ID')}</div>
                            <div class="text-xs text-green-400">btg</div>
                        </div>
                        
                        <div class="text-center p-2 bg-red-50 rounded-lg">
                            <div class="text-xs text-red-600 mb-1">Mati</div>
                            <div class="text-base font-bold text-red-700">${data.mati.toLocaleString('id-ID')}</div>
                            <div class="text-xs text-red-400">btg</div>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <div class="flex items-center justify-between text-xs text-gray-600">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>Umur: <strong>${data.umur_bulan || '-'} bulan</strong></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>${data.tanggal_monitoring || '-'}</span>
                            </div>
                        </div>
                    </div>

                    ${data.penyebab_kematian ? `
                        <div class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded text-xs">
                            <span class="font-semibold text-yellow-800">⚠️ Penyebab Kematian:</span>
                            <span class="text-yellow-700">${data.penyebab_kematian}</span>
                        </div>
                    ` : ''}
                </div>
            </div>
        `;

        contentContainer.innerHTML = html;
    }

    /**
     * Calculate Survival Rate
     */
    calculateSR(data) {
        const total = (data.hidup || 0) + (data.mati || 0);
        if (total === 0) return 0;
        return Math.round((data.hidup / total) * 100);
    }

    /**
     * Get SR classification
     */
    getSRClass(sr) {
        if (sr >= 90) return 'Sangat Baik';
        if (sr >= 80) return 'Baik';
        if (sr >= 70) return 'Sedang';
        if (sr >= 60) return 'Kurang';
        return 'Buruk';
    }

    /**
     * Get SR color scheme
     */
    getSRColor(sr) {
        if (sr >= 90) return {
            gradient: 'from-green-500 to-emerald-600',
            bar: 'bg-green-500',
            text: 'text-green-600',
            icon: 'text-green-600'
        };
        if (sr >= 80) return {
            gradient: 'from-blue-500 to-cyan-600',
            bar: 'bg-blue-500',
            text: 'text-blue-600',
            icon: 'text-blue-600'
        };
        if (sr >= 70) return {
            gradient: 'from-yellow-500 to-amber-600',
            bar: 'bg-yellow-500',
            text: 'text-yellow-600',
            icon: 'text-yellow-600'
        };
        if (sr >= 60) return {
            gradient: 'from-orange-500 to-red-600',
            bar: 'bg-orange-500',
            text: 'text-orange-600',
            icon: 'text-orange-600'
        };
        return {
            gradient: 'from-red-500 to-rose-600',
            bar: 'bg-red-500',
            text: 'text-red-600',
            icon: 'text-red-600'
        };
    }

    /**
     * Navigate to specific index
     */
    goTo(index, isUserInitiated = false) {
        if (index < 0 || index >= this.monitoringData.length) return;
        if (index === this.currentIndex) return;

        if (isUserInitiated) {
            this.handleUserInteraction();
        }

        const oldIndex = this.currentIndex;
        this.currentIndex = index;

        // Animate transition
        const contentContainer = document.getElementById('sr-slider-content');
        if (contentContainer) {
            contentContainer.classList.add('fade-transition');
            setTimeout(() => {
                if (contentContainer && contentContainer.classList) {
                    contentContainer.classList.remove('fade-transition');
                }
            }, 300);
        }

        this.generateDots();
        
        setTimeout(() => {
            this.renderCurrent();
        }, 150);
    }

    /**
     * Auto cycle through items
     */
    startAutoCycle() {
        this.stopAutoCycle();
        
        this.autoInterval = setInterval(() => {
            if (this.shouldPauseAutoCycle()) return;
            
            const nextIndex = (this.currentIndex + 1) % this.monitoringData.length;
            this.goTo(nextIndex, false);
        }, this.AUTO_CYCLE_DURATION);
    }

    /**
     * Stop auto cycle
     */
    stopAutoCycle() {
        if (this.autoInterval) {
            clearInterval(this.autoInterval);
            this.autoInterval = null;
        }
    }

    /**
     * Handle user interaction (pause auto-cycle)
     */
    handleUserInteraction() {
        this.lastUserInteraction = Date.now();
    }

    /**
     * Check if should pause auto-cycle
     */
    shouldPauseAutoCycle() {
        if (!this.lastUserInteraction) return false;
        return (Date.now() - this.lastUserInteraction) < this.USER_INTERACTION_PAUSE;
    }

    /**
     * Bind hover events to pause auto-cycle
     */
    bindHoverEvents() {
        const contentContainer = document.getElementById('sr-slider-content');
        if (!contentContainer) return;

        contentContainer.addEventListener('mouseenter', () => {
            this.handleUserInteraction();
        });

        contentContainer.addEventListener('touchstart', () => {
            this.handleUserInteraction();
        }, { passive: true });
    }

    /**
     * Destroy slider (cleanup)
     */
    destroy() {
        this.stopAutoCycle();
        this.monitoringData = [];
        this.currentIndex = 0;
    }
}

// Initialize global instance
document.addEventListener('turbo:load', () => {
    window.srSlider = new SurvivalRateSlider();
});

// Cleanup on page change
document.addEventListener('turbo:before-cache', () => {
    if (window.srSlider) {
        window.srSlider.destroy();
    }
});