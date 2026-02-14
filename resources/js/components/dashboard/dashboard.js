import { DashboardMapHandler } from './map-handler.js';
import { DashboardDataService } from './data-service.js';
import { DashboardChartRenderer } from './chart-renderer.js';
import { DashboardChartFilter } from './chart-filter.js';


class ReclamationDashboard {
    constructor(config) {
        this.config = config;
        this.lahanId = config.lahanId;
        this.mapHandler = null;
        this.dataService = null;
        this.chartRenderer = null;
        this.chartFilter = null;
        this.isLoading = false;
        this.progressBlocks = [];
        this.currentBlockIndex = 0;
        this.autoCycleInterval = null;
        this.autoCycleDelay = 5000;
        this.isUserInteracting = false;
        this.isPaused = false;

        this.init();
    }

    async init() {
        try {
            console.log('Initializing dashboard for lahan:', this.lahanId);
            
            // Initialize services
            this.dataService = new DashboardDataService(this.lahanId);
            this.chartRenderer = new DashboardChartRenderer(this.dataService);
            this.mapHandler = new DashboardMapHandler(this.lahanId);
            this.chartFilter = new DashboardChartFilter();

            // Initialize components
            await this.mapHandler.initialize();
            await this.loadDashboardData();
            await this.chartRenderer.renderChart('main-chart', 'overall', '30days');
            const filterPanel = document.getElementById('dashboardChartFilterPanel');
            if (filterPanel) {
                this.chartFilter = new DashboardChartFilter();
                await this.chartFilter.init();
            }
            this.bindEvents();
            this.setupProgressHoverInteraction();
            
            console.log('Dashboard initialized successfully');
        } catch (e) {
            console.error('Dashboard init error:', e);
        }
    }

    async loadDashboardData() {
        if (this.isLoading) return;
        
        this.isLoading = true;
        console.log('Loading dashboard data...');
        
        try {
            const [stats, progressData, indicators] = await Promise.all([
                this.dataService.loadStats(),
                this.dataService.loadProgressData(),
                this.dataService.loadAllIndicators()
            ]);

            this.updateStatsCards(stats);
            this.updateProgressBlockSlider(progressData);
            this.updateIndicatorSelector(indicators);

            console.log('Dashboard data loaded successfully');
        } catch (err) {
            console.error('Error loading dashboard data:', err);
        } finally {
            this.isLoading = false;
        }
    }

    updateStatsCards(stats) {
        try {
            // Jumlah Blok Lahan
            this.updateElement('jumlah-blok', stats.jumlah_blok_lahan ?? '-');
            
            // Total Luas Area
            const totalLuas = stats.total_luas_area;
            this.updateElement('total-luas-area', 
                totalLuas ? `${this.formatDecimal(totalLuas, 2)} ha` : '- ha'
            );
            
            // Progres Hari Ini (DELTA)
            this.updateDailyProgress(stats.progres_hari_ini);
            
            // Aktivitas Terakhir
            const aktivitasTerakhir = stats.aktivitas_terakhir ?? 0;
            this.updateElement('aktivitas-terakhir', 
                this.formatRelativeTime(aktivitasTerakhir)
            );

            // Total Bibit Ditanam
            const totalBibit = stats.revegetasi?.total_bibit_ditanam;
            this.updateElement('total-bibit', 
                totalBibit ? `${this.formatNumber(totalBibit)} btg` : '- btg'
            );

            // Total Berat Benih Cover Crops
            const beratBenih = stats.revegetasi?.total_berat_benih_cover_crops;
            this.updateElement('total-benih-cover', 
                beratBenih ? `${this.formatDecimal(beratBenih, 2)} kg` : '- kg'
            );
            
            // Area Bervegetasi
            const areaBervegetasi = stats.revegetasi?.total_area_bervegetasi;
            this.updateElement('area-bervegetasi', 
                areaBervegetasi ? `${this.formatDecimal(areaBervegetasi, 2)} ha` : '- ha'
            );

            // Persentase Area Bervegetasi (Coverage)
            const persentaseArea = stats.revegetasi?.persentase_area_bervegetasi;
            this.updateElement('persentase-area-bervegetasi', 
                persentaseArea !== undefined ? `${this.formatDecimal(persentaseArea, 1)}%` : '-%'
            );

            // Total Kompos/Organik
            const totalKompos = stats.input_resources?.total_kompos;
            this.updateElement('total-kompos', 
                totalKompos ? `${this.formatNumber(totalKompos)} kg` : '- kg'
            );
            
            // Total Pupuk Anorganik
            const totalPupuk = stats.input_resources?.total_pupuk_anorganik;
            this.updateElement('total-pupuk', 
                totalPupuk ? `${this.formatNumber(totalPupuk)} kg` : '- kg'
            );

            // Total Aktivitas Pemeliharaan
            const totalAktivitas = stats.maintenance?.total_aktivitas_pemeliharaan;
            this.updateElement('total-aktivitas', 
                totalAktivitas !== undefined ? `${totalAktivitas} kali` : '- kali'
            );

            // Total Tanaman Disulam
            const totalTanamanDisulam = stats.maintenance?.total_tanaman_disulam;
            this.updateElement('total-tanaman-disulam', 
                totalTanamanDisulam !== undefined ? `${totalTanamanDisulam} batang` : '- batang'
            );
            
            // Tinggi Tanaman Rata-rata
            const tinggiRata = stats.monitoring?.tinggi_tanaman_rata;
            this.updateElement('tinggi-rata', 
                tinggiRata ? `${Math.round(tinggiRata)} cm` : '-'
            );

            // Survival Rate
            const survivalRate = stats.monitoring?.survival_rate_rata;
            this.updateElement('survival-rate', 
                survivalRate ? `${this.formatDecimal(survivalRate, 1)}%` : '-'
            );
        } catch (e) {
            console.error('Error updating stats cards:', e);
            this.showStatsError();
        }
    }

    updateDailyProgress(dailyProgress) {
        if (!dailyProgress) {
            this.updateElement('progres-hari-ini', '-%');
            this.updateElement('progres-message', 'Memuat...');
            return;
        }

        const delta = dailyProgress.delta_percent || 0;
        const progressEl = document.getElementById('progres-hari-ini');
        const messageEl = document.getElementById('progres-message');
        const badgeEl = document.getElementById('activity-badge');
        const detailEl = document.getElementById('today-total');

        if (!progressEl) {
            console.warn('Progress element not found');
            return;
        }

        // Format delta with sign
        const sign = delta > 0 ? '+' : (delta < 0 ? '' : '');
        const displayValue = `${sign}${Math.abs(delta).toFixed(1)}%`;
        
        // Determine color based on delta
        const colorClass = delta > 0 
            ? 'text-green-600' 
            : (delta < 0 ? 'text-red-600' : 'text-gray-400');
        
        // Update main value
        progressEl.textContent = displayValue;
        progressEl.className = `stat-value-large ${colorClass}`;
        
        // Update message
        if (messageEl) {
            messageEl.textContent = dailyProgress.message || 'Tidak ada perubahan';
        }
        
        // Update activity badge
        if (badgeEl) {
            if (dailyProgress.has_activity_today) {
                badgeEl.classList.remove('hidden');
                badgeEl.innerHTML = `
                    <span class="inline-block w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse mr-1"></span>
                    ${dailyProgress.activity_count || 0} Aktivitas
                `;
            } else {
                badgeEl.classList.add('hidden');
            }
        }
        
        // Update detail (total progress) for hover
        if (detailEl && dailyProgress.today_total !== undefined) {
            detailEl.textContent = dailyProgress.today_total.toFixed(1);
        }
    }

    updateProgressBlockSlider(progressData) {
        this.progressBlocks = Array.isArray(progressData) ? progressData : [];
        this.currentBlockIndex = 0;
        
        const contentContainer = document.getElementById('progress-block-content');
        const dotsContainer = document.getElementById('progress-dots');
        
        if (!contentContainer || !dotsContainer) return;

        if (this.progressBlocks.length === 0) {
            this.showEmptyProgressState(contentContainer, dotsContainer);
            return;
        }

        this.generateThreeDotNavigation();
        this.renderCurrentBlock();

        if (this.progressBlocks.length > 1) {
            this.startAutoCycle();
        }
    }

    showEmptyProgressState(contentContainer, dotsContainer) {
        contentContainer.innerHTML = `
            <div class="flex items-center justify-center py-4">
                <div class="text-center">
                    <div class="text-gray-500 text-sm mb-1">Belum ada data progres</div>
                    <div class="text-gray-400 text-xs">Silakan tambahkan data progres terlebih dahulu</div>
                </div>
            </div>
        `;
        dotsContainer.innerHTML = '';
        this.stopAutoCycle();
    }

    generateThreeDotNavigation() {
        const dotsContainer = document.getElementById('progress-dots');
        if (!dotsContainer) return;

        const totalBlocks = this.progressBlocks.length;
        
        if (totalBlocks === 0) {
            dotsContainer.innerHTML = '';
            return;
        }

        if (totalBlocks === 1) {
            dotsContainer.innerHTML = '<div class="dot active"></div>';
            return;
        }

        if (totalBlocks === 2) {
            const dotsHTML = this.progressBlocks.map((_, index) => {
                const isActive = index === this.currentBlockIndex;
                return `<button class="dot ${isActive ? 'active' : ''}" data-index="${index}"></button>`;
            }).join('');
            
            dotsContainer.innerHTML = dotsHTML;
            this.bindDotClickEvents();
            return;
        }

        // For 3+ blocks, show left-center-right pattern
        const leftIndex = (this.currentBlockIndex - 1 + totalBlocks) % totalBlocks;
        const rightIndex = (this.currentBlockIndex + 1) % totalBlocks;

        dotsContainer.innerHTML = `
            <button class="dot" data-index="${leftIndex}"></button>
            <div class="dot active"></div>
            <button class="dot" data-index="${rightIndex}"></button>
        `;
        
        this.bindDotClickEvents();
    }

    bindDotClickEvents() {
        const dotButtons = document.querySelectorAll('.dot[data-index]');
        dotButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const index = parseInt(e.target.getAttribute('data-index'));
                if (!isNaN(index)) {
                    this.goToBlock(index, true);
                }
            });
        });
    }

    goToBlock(index, isUserInitiated = true) {
        if (index >= 0 && index < this.progressBlocks.length) {
            if (isUserInitiated) {
                this.handleUserInteraction();
            }

            const oldIndex = this.currentBlockIndex;
            this.currentBlockIndex = index;
            
            const contentContainer = document.getElementById('progress-block-content');
            if (contentContainer && oldIndex !== index) {
                contentContainer.classList.add('slide-transition');
                
                setTimeout(() => {
                    if (contentContainer && contentContainer.classList) {
                        contentContainer.classList.remove('slide-transition');
                    }
                }, 300);
            }
            
            this.animateDotsTransition(oldIndex, index);
            
            setTimeout(() => {
                this.renderCurrentBlock();
            }, 150);
        }
    }

    handleUserInteraction() {
        this.isUserInteracting = true;
        setTimeout(() => { 
            this.isUserInteracting = false; 
        }, 3000);
    }

    animateDotsTransition(oldIndex, newIndex) {
        const dotsContainer = document.getElementById('progress-dots');
        if (!dotsContainer || this.progressBlocks.length <= 2) {
            setTimeout(() => this.generateThreeDotNavigation(), 0);
            return;
        }

        this.animateThreeDotTransition(oldIndex, newIndex, dotsContainer);
    }

    animateThreeDotTransition(oldIndex, newIndex, container) {
        const totalBlocks = this.progressBlocks.length;
        const isMovingRight = (newIndex === (oldIndex + 1) % totalBlocks) || 
                             (oldIndex === totalBlocks - 1 && newIndex === 0);

        // Get current dots
        const dots = container.querySelectorAll('.dot');
        if (dots.length !== 3) {
          this.generateThreeDotNavigation();
          return;
        }

        const [leftDot, centerDot, rightDot] = dots;

        // Apply sliding animation
        if (isMovingRight) {
          // Moving right: everything slides left, new dot comes from right
          this.animateDotSlideRight(leftDot, centerDot, rightDot, container);
        } else {
          // Moving left: everything slides right, new dot comes from left
          this.animateDotSlideLeft(leftDot, centerDot, rightDot, container);
        }
    }

    animateDotSlideRight(leftDot, centerDot, rightDot, container) {
        // Moving right: everything slides left, new dot comes from right
        leftDot.style.transform = 'translateX(-24px) scale(0.5)';
        leftDot.style.opacity = '0';
        
        centerDot.style.transform = 'translateX(-24px) scale(0.67)';
        centerDot.style.backgroundColor = '#d1d5db';
        
        rightDot.style.transform = 'translateX(-24px) scale(1.5)';
        rightDot.style.backgroundColor = '#2563eb';

        // After animation, regenerate dots
        setTimeout(() => {
            this.generateThreeDotNavigation();
            this.resetDotStyles(container);
        }, 300);
    }

    animateDotSlideLeft(leftDot, centerDot, rightDot, container) {
        // Moving left: everything slides right, new dot comes from left
        rightDot.style.transform = 'translateX(24px) scale(0.5)';
        rightDot.style.opacity = '0';
        
        centerDot.style.transform = 'translateX(24px) scale(0.67)';
        centerDot.style.backgroundColor = '#d1d5db';
        
        leftDot.style.transform = 'translateX(24px) scale(1.5)';
        leftDot.style.backgroundColor = '#2563eb';

        // After animation, regenerate dots
        setTimeout(() => {
            this.generateThreeDotNavigation();
            this.resetDotStyles(container);
        }, 300);
    }

    resetDotStyles(container) {
        const dots = container.querySelectorAll('.dot');
        dots.forEach(dot => {
            dot.style.transform = '';
            dot.style.opacity = '';
            dot.style.backgroundColor = '';
        });
    }

    renderCurrentBlock() {
        const contentContainer = document.getElementById('progress-block-content');
        if (!contentContainer || this.progressBlocks.length === 0) return;

        const currentBlock = this.progressBlocks[this.currentBlockIndex];
        if (!currentBlock) return;

        const percent = Math.round(currentBlock.percent || 0);
        const status = currentBlock.status === 'selesai' ? 'Selesai' : 'Dalam Proses';

        const html = `
            <div class="block-progress-card cursor-pointer hover:bg-gray-50 transition-colors rounded-lg p-3 border border-gainsboro" 
                onclick="window.location.href='/plot/${currentBlock.plot_id}'">
                <div class="flex justify-between items-center">
                    <div class="flex-1">
                        <h5 class="text-sm font-bold text-darkslategray mb-1">${currentBlock.nama_plot}</h5>
                        <div class="flex items-center gap-2 text-xs text-gray-600 mb-2">
                            <span>${currentBlock.luas_area} Ha</span>
                            <span>•</span>
                            <span>${status}</span>
                        </div>
                        
                        <div class="mb-2">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs text-gray-600">Progres</span>
                                <span class="text-xs font-bold text-red-600">${percent}%</span>
                            </div>
                            <div class="w-full bg-gray-300 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full bg-red-500 transition-all duration-300" style="width: ${Math.min(percent, 100)}%"></div>
                            </div>
                        </div>

                        <div class="flex items-center text-blue-600 text-xs font-medium hover:text-blue-800 transition-colors">
                            Lihat Detail
                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3 text-right">
                        <div class="text-2xl font-bold text-red-600">${percent}%</div>
                        <div class="text-xs text-gray-500">${percent >= 100 ? 'Selesai' : 'Progress'}</div>
                    </div>
                </div>
            </div>
        `;

        contentContainer.innerHTML = html;
    }

    updateIndicatorSelector(indicators) {
        const selector = document.getElementById('indicator-selector');
        if (!selector) return;

        selector.innerHTML = '<option value="">Pilih Indikator...</option>';

        if (Array.isArray(indicators)) {
            indicators.forEach(indicator => {
                const option = document.createElement('option');
                option.value = indicator.indikator_id;
                option.textContent = indicator.label || indicator.nama;
                selector.appendChild(option);
            });
        }
    }

    bindEvents() {
        this.bindChartViewSelector();
        this.bindIndividualBlocksToggle();
        this.bindTreeCategorySelector();
        this.bindPeriodSelector();
        this.bindIndicatorSelector();
        this.bindBlockSelector();
        this.bindProgressHoverEvents();
    }

    bindChartViewSelector() {
        const viewSelector = document.getElementById('chart-view');
        if (!viewSelector) return;

        viewSelector.addEventListener('change', (e) => {
            const view = e.target.value;
            this.handleViewChange(view);
        });
    }

    bindIndividualBlocksToggle() {
        const individualBlocksToggle = document.getElementById('show-individual-blocks');
        if (!individualBlocksToggle) return;

        individualBlocksToggle.addEventListener('change', () => {
            const view = document.getElementById('chart-view')?.value || 'overall';
            if (view === 'overall') {
                const period = document.getElementById('chart-period')?.value || '30days';
                this.chartRenderer.renderChart('main-chart', view, period);
            }
        });
    }

    bindPeriodSelector() {
        const periodSelector = document.getElementById('chart-period');
        if (!periodSelector) return;

        periodSelector.addEventListener('change', () => {
            this.dataService.clearCache('historical');
            this.dataService.clearCache('specific_indicator');
            
            const view = document.getElementById('chart-view')?.value || 'overall';
            const period = periodSelector.value;
            this.chartRenderer.renderChart('main-chart', view, period);
        });
    }

    bindIndicatorSelector() {
        const indicatorSelector = document.getElementById('indicator-selector');
        if (!indicatorSelector) return;

        indicatorSelector.addEventListener('change', async () => {
            this.handleIndicatorChange(indicatorSelector);
        });
    }

    bindTreeCategorySelector() {
        const treeCategorySelector = document.getElementById('tree-category');
        if (!treeCategorySelector) return;

        treeCategorySelector.addEventListener('change', async () => {
            const view = document.getElementById('chart-view')?.value;
            if (view === 'planted') {
                const category = treeCategorySelector.value;
                await this.chartRenderer.renderChart('main-chart', 'planted', category);
            }
        });
    }

    bindBlockSelector() {
        const blockSelector = document.getElementById('block-selector');
        if (!blockSelector) return;

        blockSelector.addEventListener('change', async () => {
            this.handleBlockSelectorChange(blockSelector);
        });
    }

    bindProgressHoverEvents() {
        const progressSection = document.getElementById('progress-block-content');
        if (!progressSection) return;

        progressSection.addEventListener('mouseenter', () => {
            this.handleUserInteraction();
        });
    }

    handleViewChange(view) {
        const indicatorSelector = document.getElementById('indicator-selector');
        const blockSelector = document.getElementById('block-selector');
        const individualBlocksToggle = document.getElementById('individual-blocks-toggle');
        const periodSelector = document.getElementById('chart-period');
        const treeCategorySelector = document.getElementById('tree-category');
        const chartTitle = document.getElementById('chart-title');
        const plantedDetails = document.getElementById('planted-details');
        
        // Update title based on view
        if (chartTitle) {
            const titles = {
                'overall': 'Grafik Progres Keseluruhan',
                'indicator': 'Grafik Progres Indikator',
                'planted': 'Sebaran Pohon Tertanam per Kategori',
            };
            chartTitle.textContent = titles[view] || 'Grafik Progres';
        }

        // Show/hide selectors based on view
        this.toggleElementVisibility(indicatorSelector, view === 'indicator');
        this.toggleElementVisibility(blockSelector, view === 'indicator');
        this.toggleElementVisibility(individualBlocksToggle, view === 'overall');
        this.toggleElementVisibility(periodSelector, view !== 'planted');
        this.toggleElementVisibility(treeCategorySelector, view === 'planted'); 
        this.toggleElementVisibility(plantedDetails, view === 'planted'); 

        // Render appropriate chart
        if (view === 'planted') {
            const category = treeCategorySelector?.value || 'all';
            this.chartRenderer.renderChart('main-chart', 'planted', category);
        } else {
            const period = periodSelector?.value || '30days';
            this.chartRenderer.renderChart('main-chart', view, period);
        }
    }

    async handleIndicatorChange(indicatorSelector) {
        const chartTitle = document.getElementById('chart-title');
        const selectedOption = indicatorSelector.options[indicatorSelector.selectedIndex];
        
        if (chartTitle && selectedOption && selectedOption.value) {
            chartTitle.textContent = `Grafik Progres - ${selectedOption.text}`;
        } else if (chartTitle) {
            chartTitle.textContent = 'Grafik Progres Indikator';
        }
        
        this.dataService.clearCache('enhanced_indicator');
        this.dataService.clearCache('enhanced_blocks');
        
        const period = document.getElementById('chart-period')?.value || '30days';
        await this.chartRenderer.renderChart('main-chart', 'indicator', period);
    }

    async handleBlockSelectorChange(blockSelector) {
        const selectedValue = blockSelector.value;
        console.log('Block selector changed to:', selectedValue);
        
        const view = document.getElementById('chart-view')?.value;
        if (view === 'indicator') {
            this.dataService.clearCache('enhanced_indicator');
            
            const period = document.getElementById('chart-period')?.value || '30days';
            
            setTimeout(async () => {
                await this.chartRenderer.renderChart('main-chart', 'indicator', period);
            }, 100);
        }
    }

    toggleElementVisibility(element, show) {
        if (!element) return;
        
        if (show) {
            element.classList.remove('hidden');
        } else {
            element.classList.add('hidden');
        }
    }

    startAutoCycle() {
        if (this.autoCycleInterval || this.progressBlocks.length <= 1) return;
        
        this.autoCycleInterval = setInterval(() => {
            if (!this.isUserInteracting && !this.isPaused) {
                this.nextBlockAuto();
            }
        }, this.autoCycleDelay);
    }

    stopAutoCycle() {
        if (this.autoCycleInterval) {
            clearInterval(this.autoCycleInterval);
            this.autoCycleInterval = null;
        }
    }

    nextBlockAuto() {
        const nextIndex = (this.currentBlockIndex + 1) % this.progressBlocks.length;
        this.goToBlock(nextIndex, false);
    }

    destroy() {
        console.log('Destroying dashboard instance');
        
        this.stopAutoCycle();
        
        if (this.chartRenderer) {
            this.chartRenderer.destroy();
            this.chartRenderer = null;
        }
        
        if (this.mapHandler) {
            this.mapHandler.destroy();
            this.mapHandler = null;
        }

        if (this.dataService) {
            this.dataService.destroy();
            this.dataService = null;
        }

        if (this.chartFilter) {
            this.chartFilter.destroy();
            this.chartFilter = null;
        }
        
        window.dashboardInstance = null;
    }

    // Helper Methods
    updateElement(elementId, value) {
        const element = document.getElementById(elementId);
        if (element) {
            element.textContent = value;
        } else {
            console.warn(`Element not found: ${elementId}`);
        }
    }

    formatNumber(num) {
        if (num === null || num === undefined || isNaN(num)) {
            return '0';
        }
        return new Intl.NumberFormat('id-ID').format(Math.round(num));
    }

    formatDecimal(num, decimals = 2) {
        if (num === null || num === undefined || isNaN(num)) {
            return '0';
        }
        return parseFloat(num).toFixed(decimals);
    }

    formatRelativeTime(dateString) {
        if (!dateString) {
            return '-';
        }
        
        try {
            const date = new Date(dateString);
            const now = new Date();
            
            // Reset time to midnight for accurate day calculation
            const dateOnly = new Date(date.getFullYear(), date.getMonth(), date.getDate());
            const nowOnly = new Date(now.getFullYear(), now.getMonth(), now.getDate());
            
            const diffMs = nowOnly - dateOnly;
            const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));
            
            // Format based on difference
            if (diffDays === 0) {
                return 'Hari ini';
            } else if (diffDays === 1) {
                return 'Kemarin';
            } else if (diffDays < 7) {
                return `${diffDays} hari lalu`;
            } else if (diffDays < 30) {
                const weeks = Math.floor(diffDays / 7);
                return `${weeks} minggu lalu`;
            } else if (diffDays < 365) {
                const months = Math.floor(diffDays / 30);
                return `${months} bulan lalu`;
            } else {
                // For old dates, show actual date
                return date.toLocaleDateString('id-ID', { 
                    day: 'numeric', 
                    month: 'short',
                    year: date.getFullYear() !== now.getFullYear() ? 'numeric' : undefined
                });
            }
        } catch (e) {
            console.error('Error formatting date:', dateString, e);
            return '-';
        }
    }

    showStatsError() {
        const errorElements = [
            'jumlah-blok', 'total-luas-area', 'progres-hari-ini', 'blok-selesai',
            'total-bibit', 'area-bervegetasi', 'survival-rate',
            'total-kompos', 'total-pupuk',
            'total-aktivitas', 'tinggi-rata', 'persentase-area-bervegetasi', 'aktivitas-terakhir'
        ];
        
        errorElements.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.textContent = '⚠️';
                el.title = 'Error memuat data';
            }
        });
    }

    /**
     * Setup hover interactions for progress card (show total on hover)
     */
    setupProgressHoverInteraction() {
        const progressCard = document.getElementById('progres-hari-ini')?.parentElement;
        const detailEl = document.getElementById('progres-detail');
        
        if (!progressCard || !detailEl) return;
        
        progressCard.addEventListener('mouseenter', () => {
            detailEl.classList.remove('hidden');
        });
        
        progressCard.addEventListener('mouseleave', () => {
            detailEl.classList.add('hidden');
        });
    }
}

// Initialize dashboard on page load
class DashboardManager {
    constructor() {
        this.dashboardInstance = null;
        this.bindEvents();
    }

    bindEvents() {
        document.addEventListener('turbo:load', () => {
            this.initializeDashboard();
        });

        document.addEventListener('turbo:before-cache', () => {
            this.destroyDashboard();
        });
    }

    initializeDashboard() {
        const mapContainer = document.getElementById('maplibre-map');
        
        if (window.dashboardConfig && mapContainer) {
            console.log('Initializing dashboard with config:', window.dashboardConfig);
            
            if (this.dashboardInstance) {
                this.dashboardInstance.destroy();
            }
            
            this.dashboardInstance = new ReclamationDashboard(window.dashboardConfig);
            window.dashboardInstance = this.dashboardInstance;
        }
    }

    destroyDashboard() {
        if (this.dashboardInstance) {
            this.dashboardInstance.destroy();
            this.dashboardInstance = null;
        }
    }
}

// Initialize dashboard manager
document.addEventListener('DOMContentLoaded', () => {
    window.dashboardManager = new DashboardManager();
});

export default ReclamationDashboard;