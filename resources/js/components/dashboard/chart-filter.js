class DashboardChartFilter {
    constructor() {
        this.isOpen = false;
        this.isInitialized = false;
        this.observers = new Map();
        this.eventHandlers = new Map();
        this.syncTimeouts = new Map();
        
        this.handleOutsideClickBound = this.handleOutsideClick.bind(this);
    }

    init() {
        console.log('Initializing dashboard chart filter');
        this.setup();
    }

    setup() {
        if (this.isInitialized) {
            console.log('Chart filter already initialized, refreshing...');
            this.refresh();
            return;
        }

        this.bindEventListeners();
        this.setupObservers();
        this.isInitialized = true;
        console.log('Chart filter setup completed');
    }

    refresh() {
        this.syncIndicatorOptions();
        this.syncWithMainControls();
    }

    // Panel control methods
    toggle() {
        const panel = document.getElementById('dashboardChartFilterPanel');
        if (!panel) return;

        if (this.isOpen) {
            this.close();
        } else {
            this.open();
        }
    }

    open() {
        const panel = document.getElementById('dashboardChartFilterPanel');
        if (!panel) return;

        panel.classList.remove('hidden');
        this.isOpen = true;
        this.syncWithMainControls();
        
        // Delay to avoid immediate outside click
        setTimeout(() => {
            document.addEventListener('click', this.handleOutsideClickBound);
        }, 100);
    }

    close() {
        const panel = document.getElementById('dashboardChartFilterPanel');
        if (panel) panel.classList.add('hidden');
        
        this.isOpen = false;
        document.removeEventListener('click', this.handleOutsideClickBound);
    }

    handleOutsideClick(event) {
        const panel = document.getElementById('dashboardChartFilterPanel');
        const toggleBtn = event.target.closest('[data-chart-filter-toggle]');
        
        if (panel && !panel.contains(event.target) && !toggleBtn) {
            this.close();
        }
    }

    // Event binding methods
    bindEventListeners() {
        this.clearEventHandlers();

        const eventMappings = [
            { filterId: 'chart-view-filter', mainId: 'chart-view', handler: this.handleChartViewChange.bind(this) },
            { filterId: 'chart-period-filter', mainId: 'chart-period', handler: this.handlePeriodChange.bind(this) },
            { filterId: 'show-individual-blocks-filter', mainId: 'show-individual-blocks', handler: this.handleIndividualBlocksChange.bind(this), type: 'checkbox' },
            { filterId: 'indicator-selector-filter', mainId: 'indicator-selector', handler: this.handleIndicatorChange.bind(this) },
            { filterId: 'block-selector-filter', mainId: 'block-selector', handler: this.handleBlockChange.bind(this) }
        ];

        eventMappings.forEach(({ filterId, handler }) => {
            const element = document.getElementById(filterId);
            if (element) {
                element.addEventListener('change', handler);
                this.eventHandlers.set(filterId, { element, handler });
            }
        });

        console.log('Event listeners bound for', this.eventHandlers.size, 'elements');
    }

    clearEventHandlers() {
        this.eventHandlers.forEach(({ element, handler }) => {
            element.removeEventListener('change', handler);
        });
        this.eventHandlers.clear();
    }

    // Event handlers
    handleChartViewChange(e) {
        const view = e.target.value;
        console.log('Chart view changed to:', view);
        
        this.updateMainControl('chart-view', view);
        this.toggleConditionalControls(view);
    }

    handlePeriodChange(e) {
        const period = e.target.value;
        console.log('Period changed to:', period);
        
        this.updateMainControl('chart-period', period);
    }

    handleIndividualBlocksChange(e) {
        const checked = e.target.checked;
        console.log('Individual blocks changed to:', checked);
        
        this.updateMainControl('show-individual-blocks', checked, 'checkbox');
    }

    handleIndicatorChange(e) {
        const indicatorId = e.target.value;
        console.log('Indicator changed to:', indicatorId);
        
        // Show/hide helper text
        const helperDiv = document.getElementById('indicator-helper');
        if (helperDiv) {
            if (indicatorId) {
                helperDiv.classList.add('hidden');
            } else {
                helperDiv.classList.remove('hidden');
            }
        }
        
        this.updateMainControl('indicator-selector', indicatorId);
        this.toggleBlockSelector(indicatorId);
    }

    handleBlockChange(e) {
        const blockId = e.target.value;
        console.log('Block changed to:', blockId);
        
        this.updateMainControl('block-selector', blockId);
    }

    // Main control update method
    updateMainControl(mainId, value, type = 'value') {
        const mainElement = document.getElementById(mainId);
        if (!mainElement) return;

        if (type === 'checkbox') {
            mainElement.checked = value;
        } else {
            mainElement.value = value;
        }
        
        mainElement.dispatchEvent(new Event('change'));
    }

    // Observer setup methods
    setupObservers() {
        this.clearObservers();
        
        this.createObserver('indicator-selector', () => {
            console.log('Main indicator options changed');
            this.syncIndicatorOptions();
        });

        this.createObserver('block-selector', () => {
            console.log('Main block options changed');
            this.syncBlockOptions();
        });
    }

    createObserver(elementId, callback) {
        const element = document.getElementById(elementId);
        if (!element) return;

        const observer = new MutationObserver((mutations) => {
            const hasChildChanges = mutations.some(m => m.type === 'childList');
            if (hasChildChanges) {
                this.debounce(`observer_${elementId}`, callback, 100);
            }
        });

        observer.observe(element, { childList: true, subtree: true });
        this.observers.set(elementId, observer);
    }

    clearObservers() {
        this.observers.forEach(observer => observer.disconnect());
        this.observers.clear();
    }

    // Synchronization methods
    syncWithMainControls() {
        const syncMappings = [
            { main: 'chart-view', filter: 'chart-view-filter' },
            { main: 'chart-period', filter: 'chart-period-filter' },
            { main: 'show-individual-blocks', filter: 'show-individual-blocks-filter', type: 'checkbox' },
            { main: 'indicator-selector', filter: 'indicator-selector-filter' },
            { main: 'block-selector', filter: 'block-selector-filter' }
        ];

        syncMappings.forEach(({ main, filter, type }) => {
            const mainElement = document.getElementById(main);
            const filterElement = document.getElementById(filter);
            
            if (mainElement && filterElement) {
                if (type === 'checkbox') {
                    filterElement.checked = mainElement.checked;
                } else {
                    filterElement.value = mainElement.value;
                }
            }
        });

        // Sync options and update conditional controls
        this.syncIndicatorOptions();
        this.updateConditionalControlsFromMain();
    }

    syncIndicatorOptions() {
        const mainIndicator = document.getElementById('indicator-selector');
        const filterIndicator = document.getElementById('indicator-selector-filter');
        
        if (!mainIndicator || !filterIndicator) return;

        const mainOptions = mainIndicator.querySelectorAll('option');
        if (mainOptions.length > 1) {
            filterIndicator.innerHTML = mainIndicator.innerHTML;
            filterIndicator.value = mainIndicator.value;
            console.log('Indicator options synced:', mainOptions.length, 'options');
        }
    }

    syncBlockOptions() {
        const mainBlock = document.getElementById('block-selector');
        const filterBlock = document.getElementById('block-selector-filter');
        
        if (!mainBlock || !filterBlock) return;

        const mainOptions = mainBlock.querySelectorAll('option');
        if (mainOptions.length > 0) {
            filterBlock.innerHTML = mainBlock.innerHTML;
            filterBlock.value = mainBlock.value;
            console.log('Block options synced:', mainOptions.length, 'options');
        }
    }

    // Conditional controls methods
    updateConditionalControlsFromMain() {
        const currentView = document.getElementById('chart-view-filter')?.value;
        if (currentView) {
            this.toggleConditionalControls(currentView);
            
            if (currentView === 'indicator') {
                const indicatorId = document.getElementById('indicator-selector-filter')?.value;
                this.toggleBlockSelector(indicatorId);
            }
        }
    }

    toggleConditionalControls(view) {
        const overallControls = document.getElementById('overall-controls');
        const indicatorControls = document.getElementById('indicator-controls');

        if (view === 'overall') {
            overallControls?.classList.remove('hidden');
            indicatorControls?.classList.add('hidden');
        } else if (view === 'indicator') {
            overallControls?.classList.add('hidden');
            indicatorControls?.classList.remove('hidden');
        }
    }

    toggleBlockSelector(indicatorId) {
        const blockContainer = document.getElementById('block-selector-container');
        
        if (!blockContainer) return;

        if (indicatorId && indicatorId !== '') {
            blockContainer.classList.remove('hidden');
        } else {
            blockContainer.classList.add('hidden');
        }
    }

    // Reset method
    reset() {
        if (this.isResetting) return;
        this.isResetting = true;

        console.log('Resetting chart filter to defaults');

        try {
            // Single reset configuration
            const resetConfig = {
                'chart-view': 'overall',
                'chart-period': '30days',
                'show-individual-blocks': false,
                'indicator-selector': '',
                'block-selector': 'all'
            };

            // Reset both filter and main controls using the same values
            Object.entries(resetConfig).forEach(([mainId, value]) => {
                const filterId = `${mainId}-filter`;
                
                // Reset filter element
                const filterElement = document.getElementById(filterId);
                if (filterElement) {
                    if (typeof value === 'boolean') {
                        filterElement.checked = value;
                    } else {
                        filterElement.value = value;
                    }
                }

                // Reset main control element
                const mainElement = document.getElementById(mainId);
                if (mainElement) {
                    if (typeof value === 'boolean') {
                        mainElement.checked = value;
                    } else {
                        mainElement.value = value;
                    }
                }
            });

            // Update UI
            this.toggleConditionalControls('overall');

            // Trigger chart update
            this.debounce('reset_chart_update', () => {
                const mainChartView = document.getElementById('chart-view');
                if (mainChartView) {
                    mainChartView.dispatchEvent(new Event('change'));
                }
            }, 100);

            console.log('Chart filter reset completed');
        } catch (error) {
            console.error('Error during reset:', error);
        } finally {
            this.isResetting = false;
        }
    }

    // Utility methods
    debounce(key, func, wait) {
        if (this.syncTimeouts.has(key)) {
            clearTimeout(this.syncTimeouts.get(key));
        }

        const timeout = setTimeout(() => {
            this.syncTimeouts.delete(key);
            func();
        }, wait);

        this.syncTimeouts.set(key, timeout);
    }

    clearTimeouts() {
        this.syncTimeouts.forEach(timeout => clearTimeout(timeout));
        this.syncTimeouts.clear();
    }

    // Cleanup methods
    destroy() {
        console.log('Destroying chart filter');
        
        this.clearEventHandlers();
        this.clearObservers();
        this.clearTimeouts();
        this.close();
        
        this.isInitialized = false;
    }
}

window.toggleDashboardChartFilter = function() {
    window.dashboardInstance?.chartFilter?.toggle();
};

window.closeDashboardChartFilter = function() {
    window.dashboardInstance?.chartFilter?.close();
};

window.resetDashboardChartFilter = function() {
    window.dashboardInstance?.chartFilter?.reset();
};

export { DashboardChartFilter };