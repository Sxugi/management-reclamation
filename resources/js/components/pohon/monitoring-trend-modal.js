import Chart from 'chart.js/auto';

/**
 * Global state for chart instances
 * Stored outside to survive Turbo navigation
 */
const chartInstances = [];

document.addEventListener("turbo:load", () => {    
    /**
     * Open the trend modal and load data
     * 
     * @param {number} jenisPohonId - Tree species ID
     * @param {number|null} lahanId - Land/Lahan ID (optional, fallback to global)
     */
    window.openTrendModal = async (jenisPohonId, lahanId = null) => {        
        const modal = document.getElementById('trendModal');
        if (!modal) {
            console.error('❌ Modal element not found');
            alert('Error: Modal tidak ditemukan. Silakan refresh halaman.');
            return;
        }
        
        // Validate and parse parameters
        jenisPohonId = parseInt(jenisPohonId);
        lahanId = lahanId ? parseInt(lahanId) : null;
        
        if (!jenisPohonId || isNaN(jenisPohonId)) {
            console.error('❌ Invalid jenis_pohon_id:', jenisPohonId);
            alert('Error: ID jenis pohon tidak valid');
            return;
        }
        
        // Get effective lahan ID (from parameter or global variable)
        const effectiveLahanId = lahanId || window.currentLahanId;
        
        if (!effectiveLahanId) {
            console.error('❌ No lahan ID available');
            alert('Error: ID lahan tidak tersedia');
            return;
        }
        
        // Show modal
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Show loading state
        showLoadingState();
        
        // Fetch and display data
        await loadTrendData(jenisPohonId, effectiveLahanId);
    };

    /**
     * Close the modal and cleanup resources
     */
    window.closeTrendModal = () => {        
        const modal = document.getElementById('trendModal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
        
        // Cleanup charts to prevent memory leaks
        destroyAllCharts();
    };

    /**
     * Retry loading data (called from error state button)
     */
    window.retryLoadTrend = () => {        
        const modal = document.getElementById('trendModal');
        if (!modal || modal.classList.contains('hidden')) {
            console.error('❌ Modal not open');
            return;
        }
        
        // Re-fetch using stored parameters (would need to store them)
        // For now, just show error
        alert('Please close and reopen the modal to retry');
    };

    /**
     * Export data functionality (placeholder)
     */
    window.exportTrendData = () => {
        alert('Export feature coming soon!\n\nData akan dapat diexport ke Excel atau PDF.');
    };

    // Close modal when clicking outside
    document.addEventListener('click', (e) => {
        const modal = document.getElementById('trendModal');
        if (modal && !modal.classList.contains('hidden') && e.target === modal) {
            window.closeTrendModal();
        }
    });

    // Close modal with ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const modal = document.getElementById('trendModal');
            if (modal && !modal.classList.contains('hidden')) {
                window.closeTrendModal();
            }
        }
    });
});

/**
 * Show loading spinner
 */
function showLoadingState() {
    document.getElementById('trendLoading')?.classList.remove('hidden');
    document.getElementById('trendContent')?.classList.add('hidden');
    document.getElementById('trendError')?.classList.add('hidden');
}

/**
 * Show error message
 */
function showErrorState() {
    document.getElementById('trendLoading')?.classList.add('hidden');
    document.getElementById('trendContent')?.classList.add('hidden');
    document.getElementById('trendError')?.classList.remove('hidden');
}

/**
 * Show main content
 */
function showContentState() {
    document.getElementById('trendLoading')?.classList.add('hidden');
    document.getElementById('trendContent')?.classList.remove('hidden');
    document.getElementById('trendError')?.classList.add('hidden');
}

/**
 * Fetch trend data from API
 * 
 * @param {number} jenisPohonId - Tree species ID
 * @param {number} lahanId - Land ID
 */
async function loadTrendData(jenisPohonId, lahanId) {    
    try {
        const url = `/lahan/${lahanId}/monitoring-trend?jenis_pohon_id=${jenisPohonId}`;
            
        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });
                
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        const data = await response.json();

        if (!data.success) {
            throw new Error(data.message || 'Failed to load trend data');
        }
        
        // Update UI
        updateModalTitle(data);
        renderTrendContent(data);
        showContentState();
    } catch (error) {
        console.error('❌ Error loading trend data:', error);
        
        // Show error message in modal
        const errorMessageEl = document.getElementById('errorMessage');
        if (errorMessageEl) {
            errorMessageEl.textContent = error.message;
        }
        
        showErrorState();
    }
}

/**
 * Update modal title and subtitle
 * 
 * @param {Object} data - API response data
 */
function updateModalTitle(data) {
    const title = document.getElementById('trendModalTitle');
    const subtitle = document.getElementById('trendModalSubtitle');
    
    if (title) {
        title.textContent = `Trend Survival Rate - ${data.jenis_pohon.nama}`;
    }
    
    if (subtitle) {
        const stats = data.trend_data.overall_stats;
        subtitle.textContent = `${data.lahan.nama} • ${stats?.total_plots || 0} Plot • ${stats?.total_monitoring || 0} Records`;
    }
}

/**
 * Render main content area
 * 
 * @param {Object} data - API response data
 */
function renderTrendContent(data) {
    const container = document.getElementById('trendContent');
    if (!container) {
        console.error('❌ Content container not found');
        return;
    }
    
    // Check if data exists
    if (!data.trend_data.has_data || !data.trend_data.plots || data.trend_data.plots.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12">
                <div class="text-6xl mb-4">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-gray-500 text-lg">Belum ada data monitoring untuk jenis pohon ini</p>
            </div>
        `;
        return;
    }
    
    // Build HTML
    const html = `
        ${renderOverallStats(data.trend_data.overall_stats)}
        ${renderPlotTrends(data.trend_data.plots)}
    `;
    
    container.innerHTML = html;
    
    // Destroy old charts BEFORE creating new ones
    destroyAllCharts();
    
    // Initialize charts after DOM is ready
    setTimeout(() => {
        initializeCharts(data.trend_data.plots);
    }, 100);
}

/**
 * Render overall statistics section
 */
function renderOverallStats(stats) {
    if (!stats) return '';
    
    return `
        <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl p-6 mb-6 border border-blue-100">
            <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <span>Statistik Keseluruhan</span>
            </h4>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                ${renderStatCard('Total Monitoring', stats.total_monitoring, 'gray')}
                ${renderStatCard('Rata-rata SR', `${stats.average_sr}%`, 'blue')}
                ${renderStatCard('SR Tertinggi', `${stats.best_sr}%`, 'green')}
                ${renderStatCard('SR Terendah', `${stats.worst_sr}%`, 'red')}
            </div>
        </div>
    `;
}

/**
 * Render a single stat card
 */
function renderStatCard(label, value, color) {
    const colorClasses = {
        gray: 'text-gray-800',
        blue: 'text-blue-600',
        green: 'text-green-600',
        red: 'text-red-600'
    };
    
    return `
        <div class="bg-white rounded-lg p-4 shadow-sm">
            <div class="text-xs text-gray-500 mb-1">${label}</div>
            <div class="text-2xl font-bold ${colorClasses[color]}">${value}</div>
        </div>
    `;
}

/**
 * Render plot trends section
 */
function renderPlotTrends(plots) {
    if (!plots || plots.length === 0) return '';
    
    return `
        <div class="space-y-6">
            <h4 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <span>Trend Per Plot</span>
            </h4>
            ${plots.map((plot, index) => renderPlotCard(plot, index)).join('')}
        </div>
    `;
}

/**
 * Render individual plot card with chart and records
 */
function renderPlotCard(plot, index) {
    const trendInfo = getTrendInfo(plot.trend);
    const statusInfo = getSRStatusInfo(plot.latest_sr);
    const periodDisplay = formatPeriodDisplay(plot.period_months, plot.period_days);
    const plantAgeRange = getPlantAgeRange(plot.records);
    
    return `
        <div class="border border-gainsboro rounded-lg overflow-hidden shadow-sm">
            <!-- Header -->
            <div class="bg-gray-50 px-4 py-2 border-b border-gainsboro">
                <div class="flex items-center justify-between">
                    <div>
                        <h5 class="text-base font-bold text-gray-800">${plot.plot_name}</h5>
                        <p class="text-sm text-gray-600">${plot.total_planted.toLocaleString('id-ID')} batang ditanam</p>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold ${getSRColorClass(plot.latest_sr)}">${plot.latest_sr}%</div>
                        <div class="text-xs ${getStatusColorClass(statusInfo.color)}">
                            ${statusInfo.icon} ${statusInfo.label}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Trend Summary -->
            <div class="px-6 py-4 ${getTrendBgClass(trendInfo.bgColor)} border-b border-gainsboro">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="text-2xl">${trendInfo.icon}</div>
                        <div>
                            <div class="text-sm font-semibold text-gray-800">${trendInfo.label}</div>
                            <div class="text-xs text-gray-600">
                                ${plot.oldest_sr}% → ${plot.latest_sr}% 
                                (${plot.sr_change > 0 ? '+' : ''}${plot.sr_change.toFixed(2)}%)
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-semibold text-gray-800">${plot.monitoring_count} Monitoring</div>
                        <div class="text-xs text-gray-500">
                            📅 Periode: ${periodDisplay}
                        </div>
                        ${plantAgeRange ? `
                            <div class="text-xs text-gray-500">
                                🌱 Umur Pohon: ${plantAgeRange}
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
            
            <!-- Chart Section -->
            <div class="p-6 bg-white">
                <h6 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <span>Grafik Trend</span>
                </h6>
                <div style="position: relative; height: 250px;">
                    <canvas id="plotChart${index}"></canvas>
                </div>
            </div>
            
            <!-- Detailed Records Timeline -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gainsboro">
                <h6 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Riwayat Monitoring Detail</span>
                </h6>
                
                <div class="space-y-3 max-h-100 overflow-y-auto">
                    ${plot.records.map((record, idx) => {
                        const isLatest = idx === 0;
                        return renderMonitoringRecord(record, isLatest);
                    }).join('')}
                </div>
            </div>
        </div>
    `;
}

/**
 * Render individual monitoring record
 */
function renderMonitoringRecord(record, isLatest) {
    const plantAge = parseInt(record.umur_bulan) || 0;
    
    return `
        <div class="flex items-start gap-4 p-4 rounded-lg ${isLatest ? 'bg-blue-50 border-2 border-blue-200' : 'bg-white border border-gainsboro'}">
            <!-- Age Badge -->
            <div class="flex-shrink-0">
                <div class="w-16 h-16 rounded-full ${isLatest ? 'bg-blue-600' : 'bg-gray-400'} flex items-center justify-center text-white">
                    <div class="text-center">
                        <div class="text-lg font-bold">${plantAge}</div>
                        <div class="text-xs">bulan</div>
                    </div>
                </div>
            </div>
            
            <!-- Record Details -->
            <div class="flex-1">
                <!-- Header -->
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <div class="text-sm font-semibold text-gray-800">${record.tanggal_monitoring}</div>
                        <div class="text-xs text-gray-500">
                            ${record.is_sampling ? '📊 Sampling' : '📋 Full Census'} 
                            ${record.is_sampling ? `(${record.sampling_percentage}% dari total)` : ''}
                        </div>
                    </div>
                    ${isLatest ? '<span class="text-xs font-semibold text-blue-600 bg-blue-100 px-3 py-1 rounded-full">Latest</span>' : ''}
                </div>
                
                <!-- Stats Grid -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 mb-3">
                    ${renderRecordStatCard('SR', record.survival_rate + '%', getSRColorClass(record.survival_rate), isLatest)}
                    ${renderRecordStatCard('Hidup', record.hidup, 'text-green-600', isLatest)}
                    ${renderRecordStatCard('Mati', record.mati, 'text-red-600', isLatest)}
                    ${renderRecordStatCard('Total', record.total_surveyed, 'text-gray-700', isLatest)}
                </div>
                
                <!-- Plant Age Info -->
                ${plantAge > 0 ? `
                    <div class="text-xs text-gray-600 mb-2">
                        🌱 <span class="font-semibold">Umur Pohon saat monitoring:</span> ${plantAge} bulan
                    </div>
                ` : ''}
                
                <!-- Sampling Area -->
                ${record.luas_sampling ? `
                    <div class="text-xs text-gray-600 mb-2">
                        📐 <span class="font-semibold">Luas Sampling:</span> ${record.luas_sampling_formatted} ha
                    </div>
                ` : ''}
                
                <!-- Health Condition -->
                ${record.kondisi_kesehatan ? `
                    <div class="text-xs text-gray-600 mb-2">
                        💚 <span class="font-semibold">Kondisi:</span> ${record.kondisi_kesehatan}
                    </div>
                ` : ''}
                
                <!-- Mortality Cause -->
                ${record.penyebab_kematian ? `
                    <div class="text-xs bg-red-50 text-red-700 p-2 rounded border border-red-200 mb-2">
                        ⚠️ <span class="font-semibold">Penyebab Kematian:</span> ${record.penyebab_kematian}
                    </div>
                ` : ''}
                
                <!-- Notes -->
                ${record.catatan ? `
                    <div class="text-xs text-gray-600 italic bg-gray-50 p-2 rounded border border-gray-200">
                        💬 ${record.catatan}
                    </div>
                ` : ''}
            </div>
        </div>
    `;
}

/**
 *  Get plant age range from records
 */
function getPlantAgeRange(records) {
    if (!records || records.length === 0) {
        return null;
    }
    
    // Get all umur_bulan values
    const ages = records
        .map(r => parseInt(r.umur_bulan) || 0)
        .filter(age => age > 0);
    
    if (ages.length === 0) {
        return null;
    }
    
    const minAge = Math.min(...ages);
    const maxAge = Math.max(...ages);
    
    if (minAge === maxAge) {
        return `${minAge} bulan`;
    } else {
        return `${minAge}-${maxAge} bulan`;
    }
}

/**
 * Format period display
 */
function formatPeriodDisplay(months, days) {
    months = months || 0;
    days = days || 0;
    
    if (months >= 1) {
        return `${months} bulan`;
    } else if (days > 0) {
        return `${days} hari`;
    } else {
        return 'Monitoring tunggal';
    }
}

/**
 * Render a stat card within a monitoring record
 */
function renderRecordStatCard(label, value, colorClass, isLatest) {
    return `
        <div class="bg-white rounded-lg p-3 border ${isLatest ? 'border-blue-200' : 'border-gainsboro'} text-center">
            <div class="text-xs text-gray-500 mb-1">${label}</div>
            <div class="text-lg font-bold ${colorClass}">${value}</div>
        </div>
    `;
}

/**
 * Initialize all charts for plots
 */
function initializeCharts(plots) {
    if (typeof Chart === 'undefined') {
        console.error('❌ Chart.js not loaded!');
        return;
    }
    
    plots.forEach((plot, index) => {
        createPlotChart(plot, index);
    });
}

/**
 * Create a chart for a single plot
 */
function createPlotChart(plot, index) {
    const canvas = document.getElementById(`plotChart${index}`);
    if (!canvas) {
        console.warn(`⚠️ Canvas #plotChart${index} not found`);
        return;
    }
    
    const ctx = canvas.getContext('2d');
    const recordsChronological = [...plot.records].reverse();
    const labels = recordsChronological.map(r => r.tanggal_monitoring);
    const srData = recordsChronological.map(r => r.survival_rate);
    
    try {
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Survival Rate (%)',
                    data: srData,
                    borderColor: 'rgb(37, 99, 235)',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (context) => `SR: ${context.parsed.y.toFixed(2)}%`
                        }
                    }
                },
                scales: {
                    y: {
                        min: 0,
                        max: 100,
                        title: { display: true, text: 'Survival Rate (%)' },
                        ticks: { callback: (value) => value + '%' }
                    },
                    x: {
                        title: { display: true, text: 'Tanggal Monitoring' }
                    }
                }
            }
        });
        
        chartInstances.push(chart);        
    } catch (error) {
        console.error(`❌ Error creating chart ${index}:`, error);
    }
}

/**
 * Destroy all chart instances
 * Prevents memory leaks and infinite growth bug
 */
function destroyAllCharts() {
    if (chartInstances.length === 0) return;
        
    chartInstances.forEach((chart, index) => {
        try {
            chart.destroy();
        } catch (error) {
            console.error(`❌ Error destroying chart ${index}:`, error);
        }
    });
    
    chartInstances.length = 0; // Clear array
}

function getSRColorClass(sr) {
    if (sr >= 90) return 'text-green-600';
    if (sr >= 80) return 'text-blue-600';
    if (sr >= 70) return 'text-yellow-600';
    if (sr >= 60) return 'text-orange-600';
    return 'text-red-600';
}

function getSRStatusInfo(sr) {
    if (sr >= 90) return { label: 'Sangat Baik', color: 'green', icon: '🌟' };
    if (sr >= 80) return { label: 'Baik', color: 'blue', icon: '✅' };
    if (sr >= 70) return { label: 'Cukup', color: 'yellow', icon: '⚠️' };
    if (sr >= 60) return { label: 'Kurang', color: 'orange', icon: '⚡' };
    return { label: 'Buruk', color: 'red', icon: '❌' };
}

function getStatusColorClass(color) {
    const map = {
        green: 'text-green-600',
        blue: 'text-blue-600',
        yellow: 'text-yellow-600',
        orange: 'text-orange-600',
        red: 'text-red-600'
    };
    return map[color] || 'text-gray-600';
}

function getTrendInfo(trend) {
    const map = {
        'improving': { label: 'Trend Membaik', icon: '📈', bgColor: 'green' },
        'stable': { label: 'Trend Stabil', icon: '➡️', bgColor: 'blue' },
        'declining_slight': { label: 'Trend Menurun (Ringan)', icon: '📉', bgColor: 'yellow' },
        'declining_significant': { label: 'Trend Menurun (Signifikan)', icon: '⚠️', bgColor: 'orange' },
        'declining_critical': { label: 'Trend Menurun (Kritis)', icon: '🚨', bgColor: 'red' },
        'insufficient_data': { label: 'Data Tidak Cukup', icon: '❓', bgColor: 'gray' }
    };
    return map[trend] || map['insufficient_data'];
}

function getTrendBgClass(bgColor) {
    const map = {
        green: 'bg-green-50',
        blue: 'bg-blue-50',
        yellow: 'bg-yellow-50',
        orange: 'bg-orange-50',
        red: 'bg-red-50',
        gray: 'bg-gray-50'
    };
    return map[bgColor] || 'bg-gray-50 border-gray-100';
}

/**
 * Cleanup before Turbo caches page
 * Prevents stale charts from being cached
 */
document.addEventListener('turbo:before-cache', () => {
    destroyAllCharts();
});