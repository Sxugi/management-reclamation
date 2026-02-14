class DashboardDataService {
    constructor(lahanId) {
        this.lahanId = lahanId;
        this.cache = {};
        this.cacheTimers = {};
        this.init();
    }

    init() {
        console.log('Dashboard data service initialized for lahan:', this.lahanId);
    }

    // Stats data methods
    async loadStats() {
        const cacheKey = `stats_${this.lahanId}`;
        
        if (this.cache[cacheKey]) {
            return this.cache[cacheKey];
        }

        try {
            const res = await fetch(`/lahan/${this.lahanId}/dashboard/data?type=stats`);
            if (!res.ok) throw new Error('Failed to load stats');
            
            const stats = await res.json();
            this.setCache(cacheKey, stats, 5 * 60 * 1000);
            
            return stats;
        } catch (error) {
            console.error('Error loading stats:', error);
            throw error;
        }
    }

    // Progress data methods
    async loadProgressData() {
        const cacheKey = `progress_${this.lahanId}`;
        
        if (this.cache[cacheKey]) {
            return this.cache[cacheKey];
        }

        try {
            const res = await fetch(`/lahan/${this.lahanId}/dashboard/data?type=progress-per-blok`);
            if (!res.ok) throw new Error('Failed to load progress data');
            
            const progressData = await res.json();
            this.setCache(cacheKey, progressData, 5 * 60 * 1000);
            
            return progressData;
        } catch (error) {
            console.error('Error loading progress data:', error);
            throw error;
        }
    }

    // Indicator data methods
    async loadAllIndicators() {
        const cacheKey = 'all_indicators';
        
        if (this.cache[cacheKey]) {
            return this.cache[cacheKey];
        }

        try {
            const res = await fetch(`/lahan/${this.lahanId}/dashboard/indicators?type=all`);
            if (!res.ok) throw new Error('Failed to load all indicators');
            
            const indicators = await res.json();
            this.setCache(cacheKey, indicators, 30 * 60 * 1000);
            
            return indicators;
        } catch (error) {
            console.error('Error loading indicators:', error);
            throw error;
        }
    }

    // Specific indicator methods
    async loadSpecificIndicatorProgress(indicatorId, period = '30days') {
        const cacheKey = `specific_indicator_${this.lahanId}_${indicatorId}_${period}`;
        
        if (this.cache[cacheKey]) {
            return this.cache[cacheKey];
        }

        try {
            const res = await fetch(`/lahan/${this.lahanId}/dashboard/indicators?type=specific&indicator_id=${indicatorId}&period=${period}`);
            if (!res.ok) throw new Error('Failed to fetch specific indicator data');
            
            const data = await res.json();
            this.setCache(cacheKey, data, 10 * 60 * 1000);
            
            return data;
        } catch (error) {
            console.error('Error loading specific indicator progress:', error);
            throw error;
        }
    }

    // Historical data methods
    async loadHistoricalProgress(period = '30days') {
        const cacheKey = `historical_${this.lahanId}_${period}`;
        
        if (this.cache[cacheKey]) {
            return this.cache[cacheKey];
        }

        try {
            const res = await fetch(`/lahan/${this.lahanId}/dashboard/historical?type=progress&period=${period}`);
            if (!res.ok) throw new Error('Failed to fetch historical data');
            
            const data = await res.json();
            this.setCache(cacheKey, data, 10 * 60 * 1000);
            
            return data;
        } catch (error) {
            console.error('Error loading historical progress:', error);
            throw error;
        }
    }

    // Block historical data methods
    async loadBlockHistorical(plotId, period = '30days') {
        const cacheKey = `block_historical_${plotId}_${period}`;
        
        if (this.cache[cacheKey]) {
            return this.cache[cacheKey];
        }

        try {
            const res = await fetch(`/lahan/${this.lahanId}/dashboard/historical?type=block&plot_id=${plotId}&period=${period}`);
            if (!res.ok) throw new Error('No historical data for block');
            
            const data = await res.json();
            this.setCache(cacheKey, data, 10 * 60 * 1000);
            
            return data;
        } catch (error) {
            console.error('Error loading block historical data:', error);
            throw error;
        }
    }

    // Enhanced indicator methods
    async loadEnhancedIndicatorProgress(indicatorId, period, plotId = null, weightMethod = 'target_weighted') {
        const cacheKey = `enhanced_indicator_${indicatorId}_${period}_${plotId || 'all'}_${weightMethod}`;
        
        if (this.cache[cacheKey]) {
            return this.cache[cacheKey];
        }

        try {
            const params = this.buildEnhancedIndicatorParams(indicatorId, period, plotId, weightMethod);
            
            const res = await fetch(`/lahan/${this.lahanId}/dashboard/indicators?${params}`, {
                method: 'GET',
                headers: this.getRequestHeaders()
            });
            
            if (!res.ok) {
                throw new Error(`HTTP ${res.status}: ${res.statusText}`);
            }
            
            const data = await res.json();
            this.setCache(cacheKey, data, 10 * 60 * 1000);
            
            return data;
        } catch (error) {
            console.error('Error loading enhanced indicator progress:', error);
            throw error;
        }
    }

    async loadEnhancedBlocksForIndicator(indicatorId) {
        const cacheKey = `enhanced_blocks_${indicatorId}`;
        
        if (this.cache[cacheKey]) {
            return this.cache[cacheKey];
        }

        try {
            const params = new URLSearchParams({
                type: 'enhanced-blocks',
                indicator_id: indicatorId
            });

            const res = await fetch(`/lahan/${this.lahanId}/dashboard/indicators?${params}`, {
                method: 'GET',
                headers: this.getRequestHeaders()
            });
            
            if (!res.ok) {
                throw new Error(`HTTP ${res.status}: ${res.statusText}`);
            }
            
            const data = await res.json();
            this.setCache(cacheKey, data, 15 * 60 * 1000);
            
            return data;
        } catch (error) {
            console.error('Error loading enhanced blocks for indicator:', error);
            return [];
        }
    }

    /**
     * Load planted trees distribution (simple count by category)
     */
    async loadPlantedTreesDistribution() {
        const cacheKey = `planted_trees_${this.lahanId}`;
        
        if (this.cache[cacheKey]) {
            return this.cache[cacheKey];
        }

        try {
            const res = await fetch(`/lahan/${this.lahanId}/dashboard/planted-trees`);
            if (!res.ok) throw new Error('Failed to load planted trees');
            
            const data = await res.json();
            this.setCache(cacheKey, data, 10 * 60 * 1000);
            
            return data;
        } catch (error) {
            console.error('Error loading planted trees:', error);
            throw error;
        }
    }

    // Helper methods
    buildEnhancedIndicatorParams(indicatorId, period, plotId, weightMethod) {
        const params = new URLSearchParams({
            type: 'enhanced',
            indicator_id: indicatorId,
            period: period,
            weight_method: weightMethod
        });
        
        if (plotId) {
            params.append('plot_id', plotId);
        }

        return params;
    }

    getRequestHeaders() {
        return {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };
    }

    // Cache management methods
    setCache(key, data, ttl) {
        this.cache[key] = data;
        
        if (this.cacheTimers[key]) {
            clearTimeout(this.cacheTimers[key]);
        }
        
        this.cacheTimers[key] = setTimeout(() => {
            delete this.cache[key];
            delete this.cacheTimers[key];
        }, ttl);
    }

    clearCache(pattern = null) {
        if (pattern) {
            this.clearCacheByPattern(pattern);
        } else {
            this.clearAllCache();
        }
    }

    clearCacheByPattern(pattern) {
        Object.keys(this.cache).forEach(key => {
            if (key.includes(pattern)) {
                if (this.cacheTimers[key]) {
                    clearTimeout(this.cacheTimers[key]);
                    delete this.cacheTimers[key];
                }
                delete this.cache[key];
            }
        });
    }

    clearAllCache() {
        Object.values(this.cacheTimers).forEach(timer => clearTimeout(timer));
        this.cache = {};
        this.cacheTimers = {};
    }

    destroy() {
        console.log('Destroying dashboard data service');
        this.clearAllCache();
    }
}

export { DashboardDataService };
