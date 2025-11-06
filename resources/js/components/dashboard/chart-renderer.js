import Chart from 'chart.js/auto';

class DashboardChartRenderer {
    constructor(dataService) {
        this.dataService = dataService;
        this.chart = null;
        this.isRendering = false;
        this.chartColors = [
            '#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6',
            '#06b6d4', '#84cc16', '#f97316', '#ec4899', '#6366f1'
        ];
        this.init();
    }

    init() {
        console.log('Chart renderer initialized');
    }

    async renderChart(canvasId, view, period) {
        // Prevent multiple simultaneous renders
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
            // Always destroy existing chart first
            this.destroyExistingChart();

            console.log('Rendering chart:', { view, period });

            switch (view) {
                case 'overall':
                    await this.renderOverallChart(canvas, period);
                    break;
                case 'indicator':
                    await this.renderIndicatorChart(canvas, period);
                    break;
                default:
                    this.renderNoDataChart(canvas, 'Unknown view type');
            }
        } catch (err) {
            console.error('Error rendering chart:', err);
            this.renderErrorChart(canvas);
        } finally {
            this.isRendering = false;
        }
    }

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

    // Overall chart methods
    async renderOverallChart(canvas, period) {
        const showIndividualBlocks = this.shouldShowIndividualBlocks();
        
        if (showIndividualBlocks) {
            await this.renderOverallWithIndividualBlocks(canvas, period);
        } else {
            await this.renderOverallAverage(canvas, period);
        }
    }

    shouldShowIndividualBlocks() {
        const checkbox = document.getElementById('show-individual-blocks');
        return checkbox?.checked || false;
    }

    async renderOverallAverage(canvas, period) {
        try {
            console.log('Rendering overall average chart for period:', period);
            const data = await this.dataService.loadHistoricalProgress(period);
            
            if (!data || data.length === 0) {
                this.renderNoDataChart(canvas, 'Tidak ada data progres untuk periode ini');
                return;
            }

            const chartData = this.prepareOverallAverageData(data);
            const options = this.getOverallAverageChartOptions();
            
            this.chart = new Chart(canvas, {
                type: 'line',
                data: chartData,
                options: options
            });

            console.log('Overall average chart rendered successfully');
        } catch (error) {
            console.error('Error rendering overall average chart:', error);
            throw error;
        }
    }

    prepareOverallAverageData(data) {
        const labels = (data || []).map((d) =>
            new Date(d.date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' })
        );
        const values = (data || []).map((d) => Number(d.avg_percent) || 0);

        return {
            labels,
            datasets: [{
                label: 'Rata-rata Progres (%)',
                data: values,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,0.08)',
                fill: true,
                tension: 0.3,
                pointRadius: 4,
                borderWidth: 3,
            }]
        };
    }

    getOverallAverageChartOptions() {
        return {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        title: (ctx) => `Tanggal: ${ctx[0].label}`,
                        label: (ctx) => `Rata-rata: ${ctx.parsed.y}%`,
                    },
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { callback: (v) => `${v}%` },
                },
            },
        };
    }

    async renderOverallWithIndividualBlocks(canvas, period) {
        try {
            console.log('Rendering individual blocks chart for period:', period);
            const progressData = await this.dataService.loadProgressData();
            
            if (!progressData || progressData.length === 0) {
                this.renderNoDataChart(canvas, 'Tidak ada data blok');
                return;
            }

            const datasets = await this.prepareIndividualBlocksData(progressData, period);
            const labels = this.generateDateLabels(period);

            this.chart = new Chart(canvas, {
                type: 'line',
                data: { labels, datasets },
                options: this.getIndividualBlocksChartOptions()
            });

            console.log('Individual blocks chart rendered successfully');
        } catch (error) {
            console.error('Error rendering individual blocks chart:', error);
            throw error;
        }
    }

    async prepareIndividualBlocksData(progressData, period) {
        const datasets = [];
        const labels = this.generateDateLabels(period);

        for (let i = 0; i < progressData.length; i++) {
            const block = progressData[i];
            let values = [];

            try {
                const blockHistorical = await this.dataService.loadBlockHistorical(block.plot_id, period);
                if (Array.isArray(blockHistorical) && blockHistorical.length) {
                    values = blockHistorical.map((d) => Number(d.percent) || 0);
                } else {
                    values = labels.map(() => Number(block.percent || 0));
                }
            } catch (err) {
                console.warn(`Could not load historical data for ${block.nama_plot}:`, err);
                values = labels.map(() => Number(block.percent || 0));
            }

            datasets.push({
                label: block.nama_plot || `Plot ${block.plot_id}`,
                data: values,
                borderColor: this.chartColors[i % this.chartColors.length],
                backgroundColor: `${this.chartColors[i % this.chartColors.length]}20`,
                fill: false,
                tension: 0.3,
                pointRadius: 3,
                borderWidth: 2,
            });
        }

        return datasets;
    }

    getIndividualBlocksChartOptions() {
        return {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: { usePointStyle: true, padding: 12, font: { size: 11 } },
                },
                tooltip: { 
                    mode: 'index', 
                    intersect: false, 
                    callbacks: { title: (ctx) => `Tanggal: ${ctx[0].label}` } 
                },
            },
            scales: { 
                y: { 
                    beginAtZero: true, 
                    max: 100, 
                    ticks: { callback: (v) => `${v}%` } 
                } 
            },
            interaction: { mode: 'index', intersect: false },
        };
    }

    generateDateLabels(period) {
        const days = this.getPeriodDays(period);
        const labels = [];
        
        for (let i = days - 1; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            labels.push(date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }));
        }
        
        return labels;
    }

    getPeriodDays(period) {
        const periodMap = {
            '7days': 7,
            '30days': 30,
            '90days': 90,
            '1year': 365
        };
        return periodMap[period] || 30;
    }

    // Indicator chart methods
    async renderIndicatorChart(canvas, period) {
        const indicatorId = this.getSelectedIndicatorId();
        
        if (!indicatorId) {
            this.renderNoDataChart(canvas, 'Pilih indikator terlebih dahulu');
            return;
        }

        try {
            console.log('Rendering indicator chart for indicator:', indicatorId);
            await this.setupEnhancedIndicatorControls(indicatorId);
            
            const selectedBlockId = this.getSelectedBlockId();
            const weightMethod = 'target_weighted';
            const plotId = selectedBlockId && selectedBlockId !== 'all' ? parseInt(selectedBlockId, 10) : null;

            const data = await this.dataService.loadEnhancedIndicatorProgress(indicatorId, period, plotId, weightMethod);
            
            if (!data || data.error) {
                this.renderNoDataChart(canvas, data?.error || 'Error loading indicator data');
                return;
            }

            if (!Array.isArray(data.data) || data.data.length === 0) {
                const message = data.summary?.message || 'Tidak ada data untuk indikator ini dalam periode yang dipilih';
                this.renderNoDataChart(canvas, message);
                return;
            }

            this.renderIndicatorChartByViewType(canvas, data);
            console.log('Indicator chart rendered successfully');
        } catch (error) {
            console.error('Error rendering enhanced indicator chart:', error);
            this.renderNoDataChart(canvas, 'Terjadi kesalahan saat memuat data');
        }
    }

    getSelectedIndicatorId() {
        const indicatorSelector = document.getElementById('indicator-selector');
        return indicatorSelector?.value;
    }

    getSelectedBlockId() {
        const blockSelector = document.getElementById('block-selector');
        return blockSelector?.value;
    }

    renderIndicatorChartByViewType(canvas, data) {
        switch (data.view_type) {
            case 'weighted_percentage_overall':
                this.renderEnhancedWeightedOverallChart(canvas, data);
                break;
            case 'specific_block_percentage':
                this.renderEnhancedSpecificBlockChart(canvas, data);
                break;
            default:
                console.warn('Unknown view type:', data.view_type);
                this.renderNoDataChart(canvas, 'View type tidak dikenali');
        }
    }

    // Enhanced indicator chart methods
    async setupEnhancedIndicatorControls(indicatorId) {
        const blockSelector = document.getElementById('block-selector');
        if (!blockSelector) {
            console.warn('Block selector not found');
            return;
        }

        try {
            const currentSelection = blockSelector.value;
            const blocks = await this.dataService.loadEnhancedBlocksForIndicator(indicatorId);
            
            this.populateBlockSelector(blockSelector, blocks, currentSelection);
        } catch (err) {
            console.error('Error setting up enhanced indicator controls:', err);
            blockSelector.classList.add('hidden');
        }
    }

    populateBlockSelector(selector, blocks, currentSelection) {
        selector.innerHTML = '<option value="all">Semua Blok</option>';

        if (Array.isArray(blocks) && blocks.length > 0) {
            blocks.forEach((block) => {
                const opt = document.createElement('option');
                opt.value = block.plot_id;
                opt.textContent = block.nama_plot;
                
                if (!block.has_data || !block.has_target) {
                    opt.disabled = true;
                    opt.style.color = '#9ca3af';
                }
                
                selector.appendChild(opt);
            });
            
            this.restoreBlockSelection(selector, currentSelection);
            selector.classList.remove('hidden');
        } else {
            selector.classList.add('hidden');
        }
    }

    restoreBlockSelection(selector, currentSelection) {
        if (currentSelection && currentSelection !== 'all') {
            const optionExists = Array.from(selector.options).some(opt => opt.value === currentSelection);
            if (optionExists) {
                selector.value = currentSelection;
            }
        }
    }

    renderEnhancedWeightedOverallChart(canvas, data) {
        const chartData = this.prepareWeightedOverallChartData(data);
        
        this.chart = new Chart(canvas, {
            type: 'line',
            data: chartData,
            options: this.getWeightedOverallChartOptions(data)
        });
    }

    prepareWeightedOverallChartData(data) {
        const labels = (data.data || []).map((d) =>
            new Date(d.date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' })
        );
        const percentageValues = (data.data || []).map((d) => Number(d.weighted_percentage) || 0);

        return {
            labels,
            datasets: [{
                label: `Progress ${data.indicator?.label || ''}`,
                data: percentageValues,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,0.08)',
                fill: true,
                tension: 0.3,
                pointRadius: 4,
                borderWidth: 3,
            }]
        };
    }

    getWeightedOverallChartOptions(data) {
        return {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        title: (ctx) => `Tanggal: ${ctx[0].label}`,
                        label: (ctx) => {
                            const idx = ctx.dataIndex;
                            const dp = data.data?.[idx] || {};
                            const percentage = ctx.parsed.y;
                            const status = this.getProgressStatusForPercentage(percentage);
                            
                            return [
                                `Progress: ${percentage.toFixed(1)}%`,
                                `Status: ${status.label}`,
                                `Blok Aktif: ${dp.active_blocks ?? 0} dari ${dp.total_blocks ?? 0}`,
                                `Total Pencapaian: ${dp.total_achievement ?? 0} ${data.indicator?.satuan ?? ''}`,
                            ];
                        },
                    },
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { callback: (v) => `${v}%` },
                },
            },
        };
    }

    renderEnhancedSpecificBlockChart(canvas, data) {
        const chartData = this.prepareSpecificBlockChartData(data);
        
        this.chart = new Chart(canvas, {
            type: 'line',
            data: chartData,
            options: this.getSpecificBlockChartOptions(data)
        });
    }

    prepareSpecificBlockChartData(data) {
        const labels = (data.data || []).map((d) =>
            new Date(d.date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' })
        );
        const percentageValues = (data.data || []).map((d) => Number(d.percentage) || 0);

        return {
            labels,
            datasets: [{
                label: `${data.block?.nama_plot || 'Blok'} - Progress`,
                data: percentageValues,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,0.08)',
                fill: true,
                tension: 0.3,
                pointRadius: 4,
                borderWidth: 3,
            }]
        };
    }

    getSpecificBlockChartOptions(data) {
        return {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        title: (ctx) => `${ctx[0].label} - ${data.block?.nama_plot || ''}`,
                        label: (ctx) => {
                            const dp = data.data?.[ctx.dataIndex] || {};
                            const percentage = ctx.parsed.y;
                            const actualValue = dp.cumulative_value ?? 0;
                            const targetValue = data.block?.target_value ?? 0;
                            const remaining = Math.max(targetValue - actualValue, 0);
                            
                            const lines = [
                                `Progress: ${percentage.toFixed(1)}%`,
                                `Pencapaian: ${actualValue} ${data.indicator?.satuan || ''}`,
                                `Target: ${targetValue} ${data.indicator?.satuan || ''}`,
                            ];
                            
                            if (targetValue > 0) {
                                lines.push(remaining > 0 ? `Sisa: ${remaining.toFixed(2)} ${data.indicator?.satuan || ''}` : 'Target Tercapai!');
                            } else {
                                lines.push('Tidak ada target yang ditetapkan');
                            }
                            
                            return lines;
                        },
                    },
                },
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    max: 100, 
                    ticks: { callback: (v) => `${v}%` } 
                },
            },
        };
    }

    // Utility methods
    getProgressStatusForPercentage(percentage) {
        const p = Number(percentage || 0);
        if (p >= 100) return { label: 'Target Tercapai!' };
        if (p >= 75) return { label: 'Hampir Selesai' };
        if (p >= 50) return { label: 'Separuh Jalan' };
        if (p >= 25) return { label: 'Dalam Progress' };
        if (p > 0) return { label: 'Baru Dimulai' };
        return { label: 'Belum Dimulai' };
    }

    // Error handling methods
    renderNoDataChart(canvas, message) {
        if (!canvas) return;
        
        const ctx = canvas.getContext('2d');
        if (!ctx) return;

        // Clear and style canvas
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = '#9ca3af';
        ctx.font = '16px system-ui, -apple-system, sans-serif';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        
        // Draw message in center
        const centerX = canvas.width / 2;
        const centerY = canvas.height / 2;
        ctx.fillText(message || 'Tidak ada data', centerX, centerY);

        console.log('No data chart rendered:', message);
    }

    renderErrorChart(canvas) {
        this.renderNoDataChart(canvas, 'Gagal memuat grafik');
    }

    // Cleanup methods
    destroy() {
        console.log('Destroying chart renderer');
        
        this.destroyExistingChart();
        
        // Clear any existing progress lists
        const containers = document.querySelectorAll('.progress-list-container');
        containers.forEach((c) => c.remove());
        
        // Show canvas if hidden
        const canvas = document.getElementById('main-chart');
        if (canvas) {
            canvas.style.display = 'block';
        }

        // Reset state
        this.isRendering = false;
        this.dataService = null;
    }
}

export { DashboardChartRenderer };