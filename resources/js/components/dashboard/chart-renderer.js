import Chart from 'chart.js/auto';

/**
 * Dashboard Chart Renderer
 * Handles all chart rendering with empty states and error handling
 */
class DashboardChartRenderer {
    constructor(dataService) {
        this.dataService = dataService;
        this.chart = null;
        this.isRendering = false;
        this.resizeObserver = null;
        this.chartColors = [
            '#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6',
            '#06b6d4', '#84cc16', '#f97316', '#ec4899', '#6366f1'
        ];
        this.init();
    }

    init() {
        console.log('Chart renderer initialized');
    }

    /**
     * Main chart renderer - routes to specific chart types
     */
    async renderChart(canvasId, view, param) {
        if (this.isRendering) {
            console.log('Chart render already in progress, skipping...');
            return;
        }

        const canvas = document.getElementById(canvasId);
        if (!canvas) {
            console.warn('Chart canvas not found:', canvasId);
            return;
        }

        this.isRendering = true;

        try {
            this.destroyExistingChart();
            console.log('Rendering chart:', { view, param  });

            if (view === 'planted' && param === 'all') {
                this.updateLayoutMode('split');
            } else {
                this.updateLayoutMode('full');
            }

            switch (view) {
                case 'overall':
                    await this.renderOverallChart(canvas, param);
                    break;
                case 'indicator':
                    await this.renderIndicatorChart(canvas, param );
                    break;
                case 'planted':
                    await this.renderPlantedTreesChart(canvas, param);
                    break;
                default:
                    this.renderEmptyState(canvas, 'Unknown view type');
            }
        } catch (err) {
            console.error('Error rendering chart:', err);
            this.renderErrorState(canvas);
        } finally {
            this.isRendering = false;
        }
    }

    /**
     * Update layout mode based on chart type (split for planted, full for others)
     */
    updateLayoutMode(mode) {
        const chartContainer = document.getElementById('chart-container');
        const detailsContainer = document.getElementById('planted-details-container');

        if (!chartContainer || !detailsContainer) return;

        if (mode === 'split') {
            // Mode Split: Chart 2 column, Show detail
            chartContainer.classList.remove('lg:col-span-3');
            chartContainer.classList.add('lg:col-span-2');
            
            detailsContainer.classList.remove('hidden');
            detailsContainer.classList.add('flex');
        } else {
            // Mode Default: Chart full width, Hide detail
            chartContainer.classList.remove('lg:col-span-2');
            chartContainer.classList.add('lg:col-span-3');
            
            detailsContainer.classList.add('hidden');
            detailsContainer.classList.remove('flex');
            
            // Clear detail content when not in planted view
            const detailsContent = document.getElementById('planted-details');
            if(detailsContent) detailsContent.innerHTML = '';
        }

        // Trigger chart resize after layout change
        if (this.chart) {
            setTimeout(() => this.chart.resize(), 300);
        }
    }

    /**
     * Clean up existing chart
     */
    destroyExistingChart() {
        if (this.chart) {
            console.log('Destroying existing chart');
            try {
                this.chart.destroy();
            } catch (e) {
                console.warn('Error destroying chart:', e);
            } finally {
                this.chart = null;
            }
        }
    }

    /**
     * Render overall progress - single average or multiple block lines
     */
    async renderOverallChart(canvas, period) {
        const showIndividualBlocks = this.shouldShowIndividualBlocks();
        
        if (showIndividualBlocks) {
            await this.renderIndividualBlocksChart(canvas, period);
        } else {
            await this.renderAverageChart(canvas, period);
        }
    }

    /**
     * Check if user wants individual block lines
     */
    shouldShowIndividualBlocks() {
        const checkbox = document.getElementById('show-individual-blocks');
        return checkbox?.checked || false;
    }

    /**
     * Render site-wide average progress chart
     */
    async renderAverageChart(canvas, period) {
        try {
            const data = await this.dataService.loadHistoricalProgress(period);
            
            if (!this.hasValidData(data)) {
                this.renderEmptyState(canvas, 'new_site', period);
                return;
            }

            if (!this.hasNonZeroData(data, 'avg_percent')) {
                this.renderEmptyState(canvas, 'no_progress', period);
                return;
            }

            this.createLineChart(canvas, {
                data: this.prepareAverageData(data),
                options: this.getAverageChartOptions()
            });

        } catch (error) {
            console.error('Error rendering average chart:', error);
            this.renderErrorState(canvas);
        }
    }

    /**
     * Render individual block progress lines
     */
    async renderIndividualBlocksChart(canvas, period) {
        try {
            const progressData = await this.dataService.loadProgressData();
            
            if (!this.hasValidData(progressData)) {
                this.renderEmptyState(canvas, 'no_blocks');
                return;
            }

            const blocksWithProgress = progressData.filter(block => Number(block.percent) > 0);
            if (blocksWithProgress.length === 0) {
                this.renderEmptyState(canvas, 'blocks_no_progress', null, progressData.length);
                return;
            }

            const datasets = await this.prepareBlocksData(progressData, period);
            const labels = this.generateDateLabels(period);

            this.createLineChart(canvas, {
                data: { labels, datasets },
                options: this.getBlocksChartOptions()
            });

        } catch (error) {
            console.error('Error rendering blocks chart:', error);
            this.renderErrorState(canvas);
        }
    }

    /**
     * Render indicator-specific progress chart
     */
    async renderIndicatorChart(canvas, period) {
        const indicatorId = this.getSelectedValue('indicator-selector');
        
        if (!indicatorId || indicatorId === 'null') {
            this.renderEmptyState(canvas, 'select_indicator');
            return;
        }

        try {
            await this.setupIndicatorControls(indicatorId);
            
            const selectedBlockId = this.getSelectedValue('block-selector');
            const plotId = selectedBlockId && selectedBlockId !== 'all' ? parseInt(selectedBlockId, 10) : null;
            
            const data = await this.dataService.loadEnhancedIndicatorProgress(
                indicatorId, period, plotId, 'target_weighted'
            );
            
            if (!data || data.error) {
                this.renderEmptyState(canvas, 'indicator_error', null, null, data?.error);
                return;
            }

            if (!this.hasValidData(data.data)) {
                const emptyType = this.getIndicatorEmptyType(data);
                this.renderEmptyState(canvas, emptyType.type, null, null, emptyType.message, data);
                return;
            }

            if (!this.hasIndicatorProgress(data)) {
                this.renderEmptyState(canvas, 'indicator_no_progress', null, null, null, data);
                return;
            }

            this.renderIndicatorByType(canvas, data);

        } catch (error) {
            console.error('Error rendering indicator chart:', error);
            this.renderErrorState(canvas);
        }
    }

    /**
     * Render planted trees distribution chart (Donut)
     * Handles data loading, empty states, and triggering the sidebar render.
     */
    async renderPlantedTreesChart(canvas, category = 'all') {
        // Load data
        const plantedData = await this.dataService.loadPlantedTreesDistribution();
        
        // Check for empty data
        if (!plantedData || !plantedData.by_category || plantedData.by_category.length === 0) {
            this.renderEmptyState(canvas, 'no_planting_data');
            return;
        }

        // Prepare Chart Data (Switch logic inside here)
        const chartData = this.preparePlantedTreesChartData(plantedData, category);

        if (chartData.data.length === 0) {
             this.renderEmptyState(canvas, 'no_data_for_category', null, null, `No data for ${category}`);
             return;
        }

        // Create Chart
        this.createPlantedTreesDonutChart(canvas, chartData, category);

        // Render Sidebar Details
        this.renderPlantedTreesDetails(plantedData, category);
    }

    /**
     * Filter helper for category selection
     */
    filterPlantedDataByCategory(categoryData, category) {
        if (category === 'all') return categoryData;
        return categoryData.filter(c => c.kategori.toLowerCase() === category.toLowerCase());
    }

    /**
     * Prepare chart data based on selected category
     */
    preparePlantedTreesChartData(plantedData, selectedCategory) {
        let labels = [];
        let data = [];
        let backgroundColors = [];

        if (selectedCategory === 'all') {
            // --- MODE: ALL CATEGORIES ---
            const items = plantedData.by_category;
            
            labels = items.map(c => {
                const cleanName = this.formatLabel(c.kategori);
                return `${cleanName}: ${c.total_trees.toLocaleString('id-ID')} pohon`;
            });

            data = items.map(c => c.total_trees);
            backgroundColors = this.getCategoryColorsArray(items);

        } else {
            // --- MODE: SPECIFIC CATEGORY ---
            const speciesList = plantedData.by_species.filter(s => 
                s.kategori.toLowerCase() === selectedCategory.toLowerCase()
            );

            labels = speciesList.map(s => {
                const cleanName = this.formatLabel(s.nama);
                return `${cleanName}: ${s.total_trees.toLocaleString('id-ID')} btg`;
            });

            data = speciesList.map(s => s.total_trees);
            
            backgroundColors = speciesList.map((_, index) => 
                this.chartColors[index % this.chartColors.length]
            );
        }

        return { labels, data, backgroundColors };
    }

    /**
     * Map category names to specific hex colors
     */
    getCategoryColorsArray(data) {
        const colorMap = {
            'PIONIR': '#10b981',    // Emerald
            'LOKAL': '#3b82f6',     // Blue
            'MPTS': '#f59e0b',      // Amber
            'COVER_CROP': '#8b5cf6' // Purple
        };
        return data.map(c => colorMap[c.kategori.toUpperCase()] || '#6b7280');
    }

    /**
     * Helper to get Tailwind class colors for UI elements
     */
    getCategoryColor(categoryName) {
        if (!categoryName) return 'bg-gray-500';
        
        const tailwindMap = {
            'PIONIR': 'bg-emerald-500',
            'LOKAL': 'bg-blue-500',
            'MPTS': 'bg-amber-500',
            'COVER_CROP': 'bg-purple-500'
        };
        
        return tailwindMap[categoryName.toUpperCase()] || 'bg-gray-500';
    }

    /**
     * Helper to format text (remove underscores, capitalize words)
     */
    formatLabel(text) {
        if (!text) return '';
        return text
            .toString()
            .replace(/_/g, ' ') // Replace underscore with space
            .toLowerCase()
            .replace(/\b\w/g, c => c.toUpperCase()); // Capitalize first letter of each word
    }

    /**
     * Create the Donut Chart with "Center Text" plugin
     */
    createPlantedTreesDonutChart(canvas, chartData, selectedCategory) {
        const self = this;

        const centerTextPlugin = {
            id: 'centerText',
            beforeDraw: function(chart) {
                if (chart.config.type !== 'doughnut') return;
                
                const { ctx, chartArea: { top, bottom, left, right, width, height } } = chart;
        
                ctx.save();
                
                // Calculate Total
                const total = chartData.data.reduce((a, b) => a + b, 0);
                
                // Labels
                let subLabel = "Pohon Tanam"; 

                if (selectedCategory !== 'all') {
                    subLabel = "Total (Btg)";
                }
                
                // Draw Big Number
                const fontSizeBig = (height / 100).toFixed(2);
                ctx.font = `bold ${fontSizeBig}em 'Outfit', sans-serif`;
                ctx.fillStyle = "#1f2937"; 
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                
                const centerX = (left + right) / 2;
                const centerY = (top + bottom) / 2;
                
                ctx.fillText(total.toLocaleString('id-ID'), centerX, centerY - (height * 0.05));
                
                // Draw Label
                const fontSizeSmall = (height / 280).toFixed(2);
                ctx.font = `500 ${fontSizeSmall}em 'Outfit', sans-serif`;
                ctx.fillStyle = "#9ca3af";
                
                ctx.fillText(subLabel, centerX, centerY + (height * 0.10));

                // Draw Category Name (Optional styling)
                if (selectedCategory !== 'all') {
                    ctx.font = `bold ${(fontSizeSmall * 0.8).toFixed(2)}em 'Outfit', sans-serif`;
                    // Simple color logic
                    ctx.fillStyle = '#6b7280'; 
                }
                
                ctx.restore();
            }
        };

        if (this.chart) {
            this.chart.destroy();
        }

        this.chart = new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: chartData.labels,
                datasets: [{
                    data: chartData.data,
                    backgroundColor: chartData.backgroundColors,
                    borderWidth: 0, 
                    hoverOffset: 10
                }]
            },
            options: this.getPlantedTreesChartOptions(),
            plugins: [centerTextPlugin]
        });
    }

    /**
     * Configuration options for the Donut Chart
     */
    getPlantedTreesChartOptions() {
        return {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            layout: { padding: 20 },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        font: { size: 11, family: "'Outfit', sans-serif" },
                        usePointStyle: true,
                        boxWidth: 8,
                        generateLabels: (chart) => {
                            const data = chart.data;
                            if (data.labels.length && data.datasets.length) {
                                return data.labels.map((label, i) => {
                                    const text = label.split(':')[0]; 
                                    const meta = chart.getDatasetMeta(0);
                                    const style = meta.controller.getStyle(i);
                                    
                                    return {
                                        text: text,
                                        fillStyle: style.backgroundColor,
                                        strokeStyle: style.borderColor,
                                        lineWidth: style.borderWidth,
                                        hidden: isNaN(data.datasets[0].data[i]) || meta.data[i].hidden,
                                        index: i
                                    };
                                });
                            }
                            return [];
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.95)',
                    titleColor: '#1f2937',
                    bodyColor: '#4b5563',
                    borderColor: '#e5e7eb',
                    borderWidth: 1,
                    padding: 12,
                    callbacks: {
                        label: (context) => {
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            
                            // Simplified label parsing
                            let rawLabel = context.label || '';
                            let name = rawLabel.split(':')[0];

                            return ` ${name}: ${value.toLocaleString('id-ID')} btg (${percentage}%)`;
                        }
                    }
                }
            }
        };
    }

    /**
     * Render the sidebar list with Expandable Categories
     */
    renderPlantedTreesDetails(plantedData, selectedCategory) {
        const detailsContainer = document.getElementById('planted-details');
        if (!detailsContainer) return;

        const speciesByCategory = this.groupSpeciesByCategory(plantedData.by_species || []);
        let listHtml = '';
        
        plantedData.by_category.forEach(category => {
            const isExpanded = selectedCategory !== 'all' && selectedCategory === category.kategori.toLowerCase();
            const species = speciesByCategory[category.kategori] || [];
            
            const catBgClass = this.getCategoryColor(category.kategori).replace('bg-', 'text-'); 
            const catKey = category.kategori.toLowerCase();

            listHtml += `
                <div class="group bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden transition-all hover:shadow-md mb-3">
                    <button class="w-full p-3 flex items-center justify-between bg-white hover:bg-gray-50 transition-colors"
                            onclick="togglePlantedCategory('${catKey}')">
                        
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-[10px] font-bold ${catBgClass}">
                                ${category.kategori.substring(0, 2)}
                            </div>
                            <div class="text-left">
                                <div class="font-bold text-gray-700 text-xs">${category.kategori}</div>
                                <div class="text-[10px] text-gray-400">${category.species_count} Species</div>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <div class="text-right">
                                 <div class="font-bold text-gray-800 text-xs">
                                    ${category.total_trees.toLocaleString('id-ID')} btg
                                </div>
                            </div>
                            <svg id="${catKey}-icon" 
                                class="w-4 h-4 text-gray-400 transition-transform duration-200 ${isExpanded ? 'rotate-180' : ''}" 
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    
                    <div id="${catKey}-detail" 
                        class="${isExpanded ? '' : 'hidden'} bg-gray-50 border-t border-gray-100 p-2 space-y-1">
                        
                        ${species.length > 0 ? species.map(s => `
                            <div class="flex items-center justify-between py-1.5 px-2 rounded hover:bg-white transition-colors border border-transparent hover:border-gray-200">
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-gray-300"></div>
                                    <span class="text-gray-600 text-xs font-medium">${s.nama}</span>
                                </div>
                                <span class="text-gray-500 text-[10px] font-mono">
                                    ${s.total_trees.toLocaleString('id-ID')} btg
                                </span>
                            </div>
                        `).join('') : '<div class="text-[10px] text-center text-gray-400 py-1">No detail available</div>'}
                        
                    </div>
                </div>
            `;
        });
        
        detailsContainer.innerHTML = listHtml;
    }
    
    /**
     * Helper: Group linear species array into object by category
     */
    groupSpeciesByCategory(speciesArray) {
        if (!Array.isArray(speciesArray)) return {};

        return speciesArray.reduce((acc, species) => {
            const cat = species.kategori;
            if (!cat) return acc;

            if (!acc[cat]) acc[cat] = [];
            acc[cat].push(species);
            return acc;
        }, {});
    }

    /**
     * Setup block selector for indicator
     */
    async setupIndicatorControls(indicatorId) {
        const blockSelector = document.getElementById('block-selector');
        if (!blockSelector) return;

        try {
            const blocks = await this.dataService.loadEnhancedBlocksForIndicator(indicatorId);
            this.populateBlockSelector(blockSelector, blocks);
        } catch (err) {
            console.error('Error setting up indicator controls:', err);
            blockSelector.classList.add('hidden');
        }
    }

    /**
     * Populate block selector dropdown
     */
    populateBlockSelector(selector, blocks) {
        const currentValue = selector.value;
        selector.innerHTML = '<option value="all">Semua Blok</option>';

        if (this.hasValidData(blocks)) {
            blocks.forEach(block => {
                const option = document.createElement('option');
                option.value = block.plot_id;
                option.textContent = block.nama_plot;
                
                if (!block.has_data || !block.has_target) {
                    option.disabled = true;
                    option.style.color = '#9ca3af';
                }
                
                selector.appendChild(option);
            });
            
            // Restore previous selection
            if (currentValue && this.hasOption(selector, currentValue)) {
                selector.value = currentValue;
            }
            
            selector.classList.remove('hidden');
        } else {
            selector.classList.add('hidden');
        }
    }

    /**
     * Route indicator chart by data type
     */
    renderIndicatorByType(canvas, data) {
        switch (data.view_type) {
            case 'weighted_percentage_overall':
                this.renderWeightedChart(canvas, data);
                break;
            case 'specific_block_percentage':
                this.renderBlockChart(canvas, data);
                break;
            default:
                this.renderEmptyState(canvas, 'unknown_type');
        }
    }

    /**
     * Render weighted overall indicator chart
     */
    renderWeightedChart(canvas, data) {
        this.createLineChart(canvas, {
            data: this.prepareIndicatorData(data, 'weighted_percentage'),
            options: this.getWeightedChartOptions(data)
        });
    }

    /**
     * Render specific block indicator chart
     */
    renderBlockChart(canvas, data) {
        this.createLineChart(canvas, {
            data: this.prepareIndicatorData(data, 'percentage'),
            options: this.getBlockChartOptions(data)
        });
    }

    /**
     * Prepare data for average chart
     */
    prepareAverageData(data) {
        const labels = this.formatDateLabels(data);
        const values = data.map(d => Number(d.avg_percent) || 0);

        return {
            labels,
            datasets: [{
                label: 'Rata-rata Progres (%)',
                data: values,
                ...this.getLineStyle('#3b82f6')
            }]
        };
    }

    /**
     * Prepare data for individual blocks
     */
    async prepareBlocksData(progressData, period) {
        const datasets = [];
        const labels = this.generateDateLabels(period);

        for (let i = 0; i < progressData.length; i++) {
            const block = progressData[i];
            const values = await this.getBlockHistoricalValues(block, period, labels);
            
            datasets.push({
                label: block.nama_plot || `Plot ${block.plot_id}`,
                data: values,
                ...this.getLineStyle(this.chartColors[i % this.chartColors.length], false)
            });
        }

        return datasets;
    }

    /**
     * Get historical values for a block
     */
    async getBlockHistoricalValues(block, period, labels) {
        try {
            const historical = await this.dataService.loadBlockHistorical(block.plot_id, period);
            if (this.hasValidData(historical)) {
                return historical.map(d => Number(d.percent) || 0);
            }
        } catch (err) {
            console.warn(`No historical data for ${block.nama_plot}`);
        }
        
        // Fallback to current value
        return labels.map(() => Number(block.percent || 0));
    }

    /**
     * Prepare indicator chart data
     */
    prepareIndicatorData(data, valueKey) {
        const labels = this.formatDateLabels(data.data);
        const values = data.data.map(d => Number(d[valueKey]) || 0);
        const label = data.view_type === 'specific_block_percentage' 
            ? `${data.block?.nama_plot || 'Blok'} - Progress`
            : `Progress ${data.indicator?.label || ''}`;

        return {
            labels,
            datasets: [{
                label,
                data: values,
                ...this.getLineStyle('#3b82f6')
            }]
        };
    }

    /**
     * Create Chart.js line chart
     */
    createLineChart(canvas, config) {
        this.chart = new Chart(canvas, {
            type: 'line',
            data: config.data,
            options: config.options
        });
    }

    /**
     * Get common line style
     */
    getLineStyle(color, filled = true) {
        return {
            borderColor: color,
            backgroundColor: filled ? `${color}20` : 'transparent',
            fill: filled,
            tension: 0.3,
            pointRadius: 4,
            borderWidth: 3
        };
    }

    /**
     * Get base chart options
     */
    getBaseChartOptions() {
        return {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { callback: v => `${v}%` }
                }
            }
        };
    }

    /**
     * Get average chart options
     */
    getAverageChartOptions() {
        return {
            ...this.getBaseChartOptions(),
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        title: ctx => `Tanggal: ${ctx[0].label}`,
                        label: ctx => `Rata-rata: ${ctx.parsed.y}%`
                    }
                }
            }
        };
    }

    /**
     * Get blocks chart options
     */
    getBlocksChartOptions() {
        return {
            ...this.getBaseChartOptions(),
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: { usePointStyle: true, padding: 12, font: { size: 11 } }
                },
                tooltip: { 
                    mode: 'index', 
                    intersect: false,
                    callbacks: { title: ctx => `Tanggal: ${ctx[0].label}` }
                }
            },
            interaction: { mode: 'index', intersect: false }
        };
    }

    /**
     * Get weighted chart options with enhanced tooltips
     */
    getWeightedChartOptions(data) {
        return {
            ...this.getBaseChartOptions(),
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        title: ctx => `Tanggal: ${ctx[0].label}`,
                        label: ctx => {
                            const dataPoint = data.data?.[ctx.dataIndex] || {};
                            const percentage = ctx.parsed.y;
                            const status = this.getProgressStatus(percentage);
                            
                            return [
                                `Progress: ${percentage.toFixed(1)}%`,
                                `Status: ${status}`,
                                `Blok Aktif: ${dataPoint.active_blocks || 0} dari ${dataPoint.total_blocks || 0}`,
                                `Total: ${dataPoint.total_achievement || 0} ${data.indicator?.satuan || ''}`
                            ];
                        }
                    }
                }
            }
        };
    }

    /**
     * Get block chart options with target info
     */
    getBlockChartOptions(data) {
        return {
            ...this.getBaseChartOptions(),
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        title: ctx => `${ctx[0].label} - ${data.block?.nama_plot || ''}`,
                        label: ctx => {
                            const dataPoint = data.data?.[ctx.dataIndex] || {};
                            const percentage = ctx.parsed.y;
                            const actual = dataPoint.cumulative_value || 0;
                            const target = data.block?.target_value || 0;
                            const remaining = Math.max(target - actual, 0);
                            const unit = data.indicator?.satuan || '';
                            
                            const lines = [
                                `Progress: ${percentage.toFixed(1)}%`,
                                `Pencapaian: ${actual} ${unit}`,
                                `Target: ${target} ${unit}`
                            ];
                            
                            if (target > 0) {
                                lines.push(remaining > 0 ? `Sisa: ${remaining.toFixed(2)} ${unit}` : 'Target Tercapai!');
                            }
                            
                            return lines;
                        }
                    }
                }
            }
        };
    }

    /**
     * Main empty state renderer
     */
    renderEmptyState(canvas, type, period = null, count = null, message = null, data = null) {
        this.destroyExistingChart();
        this.prepareCanvas(canvas, (canvas, dimensions) => {
            const ctx = canvas.getContext('2d');
            if (!ctx) return;

            this.clearCanvas(canvas);
            
            switch (type) {
                case 'new_site':
                    this.drawNewSiteState(ctx, dimensions, period);
                    break;
                case 'no_progress':
                    this.drawNoProgressState(ctx, dimensions, period);
                    break;
                case 'no_blocks':
                    this.drawNoBlocksState(ctx, dimensions);
                    break;
                case 'blocks_no_progress':
                    this.drawBlocksNoProgressState(ctx, dimensions, count);
                    break;
                case 'select_indicator':
                    this.drawSelectIndicatorState(ctx, dimensions);
                    break;
                case 'indicator_error':
                    this.drawIndicatorErrorState(ctx, dimensions, message);
                    break;
                case 'indicator_no_target':
                    this.drawNoTargetState(ctx, dimensions, data);
                    break;
                case 'indicator_no_progress':
                    this.drawIndicatorNoProgressState(ctx, dimensions, data);
                    break;
                case 'indicator_no_data':
                    this.drawIndicatorNoDataState(ctx, dimensions, data);
                    break;
                case 'no_planting_data':
                    this.drawNoPlantingDataState(ctx, dimensions);
                    break;
                case 'no_data_for_category':
                    this.drawNoCategoryDataState(ctx, dimensions, message);
                    break;
                default:
                    this.drawGenericState(ctx, dimensions, message || 'Tidak ada data');
            }
        });
    }

    /**
     * Render error state
     */
    renderErrorState(canvas) {
        if (type instanceof Error) {
            error = type;
            type = 'generic';
        }

        this.destroyExistingChart();

        this.prepareCanvas(canvas, (canvas, dimensions) => {
            const ctx = canvas.getContext('2d');
            if (!ctx) return;

            this.clearCanvas(canvas);
            this.drawErrorState(ctx, dimensions);
        });
    }

    /**
     * Draw new site welcome state
     */
    drawNewSiteState(ctx, { centerX, centerY, width }, period) {
        this.drawIcon(ctx, centerX, centerY - 60, '🌱', '#e0f2fe', '#0369a1', 40);
        this.drawTitle(ctx, 'Belum Ada Progres', centerX, centerY - 5, width);
        this.drawSubtitle(ctx, 'Mulai menambahkan target dan aktivitas reklamasi', centerX, centerY + 25, width);
        this.drawSubtitle(ctx, 'untuk melihat grafik perkembangan', centerX, centerY + 45, width);
        this.drawHint(ctx, '💡 Tip: Tetapkan target terlebih dahulu di halaman Plot', centerX, centerY + 75, width);
    }

    /**
     * Draw no progress state
     */
    drawNoProgressState(ctx, { centerX, centerY, width }, period) {
        this.drawIcon(ctx, centerX, centerY - 50, '📊', '#f3f4f6', '#6b7280', 35);
        this.drawTitle(ctx, `Tidak Ada Data dalam ${this.getPeriodLabel(period)}`, centerX, centerY, width);
        this.drawSubtitle(ctx, 'Coba ubah periode waktu atau tambahkan', centerX, centerY + 30, width);
        this.drawSubtitle(ctx, 'data progres untuk periode ini', centerX, centerY + 50, width);
    }

    /**
     * Draw no blocks state
     */
    drawNoBlocksState(ctx, { centerX, centerY, width }) {
        this.drawIcon(ctx, centerX, centerY - 50, '🏗️', '#fef3c7', '#d97706', 35);
        this.drawTitle(ctx, 'Belum Ada Blok Lahan', centerX, centerY, width, '#92400e');
        this.drawSubtitle(ctx, 'Tambahkan blok lahan terlebih dahulu', centerX, centerY + 30, width, '#a16207');
        this.drawSubtitle(ctx, 'untuk mulai tracking progres', centerX, centerY + 50, width, '#a16207');
    }

    /**
     * Draw blocks without progress state
     */
    drawBlocksNoProgressState(ctx, { centerX, centerY, width }, count) {
        this.drawIcon(ctx, centerX, centerY - 50, '📈', '#e0f2fe', '#0369a1', 35);
        this.drawTitle(ctx, `${count} Blok Belum Memiliki Progres`, centerX, centerY, width, '#0c4a6e');
        this.drawSubtitle(ctx, 'Mulai menambahkan aktivitas reklamasi', centerX, centerY + 30, width, '#0369a1');
        this.drawSubtitle(ctx, 'pada masing-masing blok', centerX, centerY + 50, width, '#0369a1');
    }

    /**
     * Draw select indicator prompt
     */
    drawSelectIndicatorState(ctx, { centerX, centerY, width }) {
        this.drawIcon(ctx, centerX, centerY - 40, '📊', '#f3f4f6', '#6b7280', 30);
        this.drawTitle(ctx, 'Pilih Indikator untuk Analisis', centerX, centerY + 10, width);
        this.drawSubtitle(ctx, 'Gunakan "Chart Options" untuk memilih', centerX, centerY + 35, width);
        this.drawSubtitle(ctx, 'indikator yang ingin dianalisis', centerX, centerY + 55, width);
    }

    /**
     * Draw no target state
     */
    drawNoTargetState(ctx, { centerX, centerY, width }, data) {
        this.drawIcon(ctx, centerX, centerY - 60, '🎯', '#fef3c7', '#d97706', 40);
        this.drawTitle(ctx, 'Target Belum Ditetapkan', centerX, centerY - 10, width, '#92400e');
        
        let yOffset = centerY + 20;
        if (data?.indicator?.label) {
            this.drawSubtitle(ctx, `Indikator: ${data.indicator.label}`, centerX, yOffset, width, '#a16207');
            yOffset += 25;
        }
        
        this.drawInstructions(ctx, centerX, yOffset, width, [
            'Untuk melihat progres, silakan:',
            '1. Kunjungi halaman Plot',
            '2. Tetapkan target untuk indikator ini',
            '3. Mulai mencatat aktivitas reklamasi'
        ]);
    }

    /**
     * Draw indicator no progress state
     */
    drawIndicatorNoProgressState(ctx, { centerX, centerY, width }, data) {
        this.drawIcon(ctx, centerX, centerY - 50, '📈', '#dbeafe', '#3b82f6', 35);
        this.drawTitle(ctx, 'Belum Ada Progres', centerX, centerY, width, '#1e40af');
        
        let yOffset = centerY + 25;
        if (data?.indicator?.label) {
            this.drawSubtitle(ctx, data.indicator.label, centerX, yOffset, width, '#3730a3');
            yOffset += 25;
        }
        
        this.drawSubtitle(ctx, 'Target sudah ditetapkan, namun belum ada', centerX, yOffset, width, '#3730a3');
        this.drawSubtitle(ctx, 'aktivitas yang dicatat untuk periode ini', centerX, yOffset + 20, width, '#3730a3');
    }

    /**
     * Draw indicator no data state
     */
    drawIndicatorNoDataState(ctx, { centerX, centerY, width }, data) {
        this.drawIcon(ctx, centerX, centerY - 50, '📈', '#dbeafe', '#3b82f6', 35);
        this.drawTitle(ctx, 'Tidak Ada Data dalam Periode Ini', centerX, centerY, width, '#1e40af');
        
        let yOffset = centerY + 25;
        if (data?.indicator?.label) {
            this.drawSubtitle(ctx, `Indikator: ${data.indicator.label}`, centerX, yOffset, width, '#3730a3');
            yOffset += 20;
        }
        
        this.drawSubtitle(ctx, 'Coba ubah periode waktu atau', centerX, yOffset, width, '#3730a3');
        this.drawSubtitle(ctx, 'tambahkan data progres', centerX, yOffset + 20, width, '#3730a3');
    }

    /**
     * Draw no planting data state
     */
    drawNoPlantingDataState(ctx, { centerX, centerY, width }) {
        this.drawIcon(ctx, centerX, centerY - 60, '🌱', '#fef3c7', '#d97706', 40);
        this.drawTitle(ctx, 'Belum Ada Data Penanaman', centerX, centerY - 5, width, '#92400e');
        this.drawSubtitle(ctx, 'Belum ada aktivitas penanaman pohon', centerX, centerY + 25, width, '#a16207');
        this.drawSubtitle(ctx, 'yang tercatat untuk lahan ini', centerX, centerY + 45, width, '#a16207');
        this.drawHint(ctx, '💡 Tip: Mulai catat aktivitas penanaman pohon', centerX, centerY + 75, width, '#d97706');
    }

    /**
     * Draw no category data state (filtered category has no data)
     */
    drawNoCategoryDataState(ctx, { centerX, centerY, width }, message) {
        this.drawIcon(ctx, centerX, centerY - 50, '🔍', '#f3f4f6', '#6b7280', 35);
        this.drawTitle(ctx, 'Tidak Ada Data', centerX, centerY, width, '#374151');
        this.drawSubtitle(ctx, message || 'Tidak ada data untuk kategori ini', centerX, centerY + 30, width, '#6b7280');
        this.drawSubtitle(ctx, 'Coba pilih kategori lain atau "Semua Kategori"', centerX, centerY + 50, width, '#6b7280');
    }

    /**
     * Draw error state
     */
    drawErrorState(ctx, { centerX, centerY, width }) {
        this.drawIcon(ctx, centerX, centerY - 50, '⚠️', '#fee2e2', '#dc2626', 35);
        this.drawTitle(ctx, 'Gagal Memuat Data Chart', centerX, centerY, width, '#dc2626');
        this.drawSubtitle(ctx, 'Silakan refresh halaman atau coba lagi', centerX, centerY + 30, width, '#7f1d1d');
        this.drawSubtitle(ctx, 'Jika masalah berlanjut, hubungi administrator', centerX, centerY + 50, width, '#7f1d1d');
    }

    /**
     * Draw generic empty state
     */
    drawGenericState(ctx, { centerX, centerY, width }, message) {
        this.drawIcon(ctx, centerX, centerY - 30, '📊', '#f3f4f6', '#6b7280', 25);
        this.drawText(ctx, message, centerX, centerY + 15, this.getTextStyle(width, 16));
    }

    /**
     * Draw icon with background circle
     */
    drawIcon(ctx, x, y, icon, bgColor, iconColor, size) {
        // Background circle
        ctx.fillStyle = bgColor;
        ctx.beginPath();
        ctx.arc(x, y, size, 0, 2 * Math.PI);
        ctx.fill();
        
        // Icon
        ctx.fillStyle = iconColor;
        ctx.font = `${Math.floor(size * 0.8)}px system-ui`;
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText(icon, x, y);
    }

    /**
     * Draw title text
     */
    drawTitle(ctx, text, x, y, width, color = '#0c4a6e') {
        this.drawText(ctx, text, x, y, {
            ...this.getTextStyle(width, 18),
            color,
            fontWeight: 'bold'
        });
    }

    /**
     * Draw subtitle text
     */
    drawSubtitle(ctx, text, x, y, width, color = '#0369a1') {
        this.drawText(ctx, text, x, y, {
            ...this.getTextStyle(width, 14),
            color
        });
    }

    /**
     * Draw hint text
     */
    drawHint(ctx, text, x, y, width, color = '#0284c7') {
        this.drawText(ctx, text, x, y, {
            ...this.getTextStyle(width, 12),
            color
        });
    }

    /**
     * Draw instruction lines
     */
    drawInstructions(ctx, x, y, width, lines) {
        lines.forEach((line, index) => {
            const fontSize = index === 0 ? 14 : 12;
            this.drawText(ctx, line, x, y + (index * 20), {
                ...this.getTextStyle(width, fontSize),
                color: '#a16207'
            });
        });
    }

    /**
     * Draw text with style
     */
    drawText(ctx, text, x, y, style = {}) {
        const {
            color = '#374151',
            fontSize = 16,
            fontWeight = 'normal',
            fontFamily = 'system-ui, -apple-system, sans-serif',
            textAlign = 'center',
            textBaseline = 'middle'
        } = style;

        ctx.fillStyle = color;
        ctx.font = `${fontWeight} ${fontSize}px ${fontFamily}`;
        ctx.textAlign = textAlign;
        ctx.textBaseline = textBaseline;
        ctx.fillText(text, x, y);
    }

    /**
     * Check if data is valid and not empty
     */
    hasValidData(data) {
        return data && Array.isArray(data) && data.length > 0;
    }

    /**
     * Check if data has non-zero values
     */
    hasNonZeroData(data, key = 'value') {
        return this.hasValidData(data) && data.some(d => Number(d[key]) > 0);
    }

    /**
     * Check if indicator has progress data
     */
    hasIndicatorProgress(data) {
        if (!this.hasValidData(data.data)) return false;
        
        return data.data.some(d => {
            const value = data.view_type === 'weighted_percentage_overall'
                ? Number(d.weighted_percentage)
                : Number(d.percentage);
            return value > 0;
        });
    }

    /**
     * Get selected value from dropdown
     */
    getSelectedValue(elementId) {
        const element = document.getElementById(elementId);
        return element?.value;
    }

    /**
     * Check if option exists in select
     */
    hasOption(select, value) {
        return Array.from(select.options).some(opt => opt.value === value);
    }

    /**
     * Determine indicator empty state type
     */
    getIndicatorEmptyType(data) {
        if (data.summary?.message) {
            const message = data.summary.message.toLowerCase();
            
            if (message.includes('target')) {
                return { type: 'indicator_no_target', message: 'Tidak ada target yang ditetapkan' };
            }
            
            if (message.includes('blok')) {
                return { type: 'indicator_no_data', message: 'Blok belum memiliki data' };
            }
        }
        
        return { type: 'indicator_no_data', message: 'Tidak ada data dalam periode ini' };
    }

    /**
     * Get progress status text
     */
    getProgressStatus(percentage) {
        const p = Number(percentage) || 0;
        if (p >= 100) return 'Target Tercapai!';
        if (p >= 75) return 'Hampir Selesai';
        if (p >= 50) return 'Separuh Jalan';
        if (p >= 25) return 'Dalam Progress';
        if (p > 0) return 'Baru Dimulai';
        return 'Belum Dimulai';
    }

    /**
     * Get period label in Indonesian
     */
    getPeriodLabel(period) {
        const labels = {
            '7days': '7 Hari Terakhir',
            '30days': '30 Hari Terakhir',
            '90days': '90 Hari Terakhir',
            '1year': '1 Tahun Terakhir'
        };
        return labels[period] || 'Periode yang Dipilih';
    }

    /**
     * Generate date labels for period
     */
    generateDateLabels(period) {
        const days = { '7days': 7, '30days': 30, '90days': 90, '1year': 365 }[period] || 30;
        const labels = [];
        
        for (let i = days - 1; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            labels.push(date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }));
        }
        
        return labels;
    }

    /**
     * Format date labels from data
     */
    formatDateLabels(data) {
        return data.map(d => 
            new Date(d.date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' })
        );
    }

    /**
     * Get responsive text style
     */
    getTextStyle(width, baseFontSize) {
        return {
            fontSize: Math.min(baseFontSize, width / 30)
        };
    }

    /**
     * Prepare canvas for drawing
     */
    prepareCanvas(canvas, callback) {
        if (!canvas) return;
        
        this.setCanvasSize(canvas);
        
        if (callback) {
            requestAnimationFrame(() => {
                const dimensions = this.getCanvasDimensions(canvas);
                callback(canvas, dimensions);
            });
        }
    }

    /**
     * Set canvas size for high-DPI displays
     */
    setCanvasSize(canvas) {
        const container = canvas.parentElement;
        if (!container) return;

        canvas.style.height = '0px'; 
        
        // Get container dimensions
        const rect = container.getBoundingClientRect();
        
        // Set canvas size
        canvas.style.width = '100%';
        canvas.style.height = '100%';
        
        const dpr = window.devicePixelRatio || 1;
        canvas.width = rect.width * dpr;
        canvas.height = rect.height * dpr;
        
        const ctx = canvas.getContext('2d');
        if (ctx) {
            ctx.scale(dpr, dpr);
        }
        
        canvas._logicalWidth = rect.width;
        canvas._logicalHeight = rect.height;
    }

    /**
     * Get canvas dimensions
     */
    getCanvasDimensions(canvas) {
        const width = canvas._logicalWidth || canvas.offsetWidth || 800;
        const height = canvas._logicalHeight || canvas.offsetHeight || 400;
        
        return {
            width,
            height,
            centerX: width / 2,
            centerY: height / 2
        };
    }

    /**
     * Clear canvas content
     */
    clearCanvas(canvas) {
        const ctx = canvas.getContext('2d');
        if (!ctx) return;
        
        const { width, height } = this.getCanvasDimensions(canvas);
        ctx.clearRect(0, 0, width, height);
    }

    /**
     * Setup chart resizing
     */
    setupChartResizing(canvas) {
        if (!this.resizeObserver) {
            this.resizeObserver = new ResizeObserver(() => {
                if (this.chart && canvas.id === 'main-chart') {
                    this.chart.resize();
                }
            });
        }
        
        const container = canvas.parentElement;
        if (container) {
            this.resizeObserver.observe(container);
        }
    }

    /**
     * Clean up and destroy renderer
     */
    destroy() {
        console.log('Destroying chart renderer');
        
        this.destroyExistingChart();
        
        if (this.resizeObserver) {
            this.resizeObserver.disconnect();
            this.resizeObserver = null;
        }
        
        document.querySelectorAll('.progress-list-container').forEach(c => c.remove());
        
        const canvas = document.getElementById('main-chart');
        if (canvas) {
            canvas.style.display = 'block';
        }

        this.isRendering = false;
        this.dataService = null;
    }
}

export { DashboardChartRenderer };