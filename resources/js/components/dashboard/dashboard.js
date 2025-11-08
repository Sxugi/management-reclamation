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
            const elements = {
                'jumlah-blok': stats.jumlah_blok_lahan ?? '-',
                'total-progres': (Math.round(stats.total_progres_reklamasi || 0)) + '%',
                'progres-hari-ini': (Math.round(stats.progres_hari_ini?.today_percent || 0)) + '%',
                'blok-selesai': stats.jumlah_blok_selesai ?? 0
            };

            Object.entries(elements).forEach(([id, value]) => {
                const el = document.getElementById(id);
                if (el) el.textContent = value;
            });
        } catch (e) {
            console.warn('Error updating stats cards:', e);
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
        const chartTitle = document.getElementById('chart-title');
        const periodSelector = document.getElementById('chart-period');
        
        // Update title based on view
        if (chartTitle) {
            const titles = {
                'overall': 'Grafik Progres Keseluruhan',
                'indicator': 'Grafik Progres Indikator',
            };
            chartTitle.textContent = titles[view] || 'Grafik Progres';
        }

        // Show/hide selectors based on view
        this.toggleElementVisibility(indicatorSelector, view === 'indicator');
        this.toggleElementVisibility(blockSelector, view === 'indicator');
        this.toggleElementVisibility(individualBlocksToggle, view === 'overall');
        this.toggleElementVisibility(periodSelector, true);

        const period = document.getElementById('chart-period')?.value || '30days';
        this.chartRenderer.renderChart('main-chart', view, period);
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