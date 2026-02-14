import maplibregl from 'maplibre-gl';

class DashboardMapHandler {
    constructor(lahanId, mapElementId = 'maplibre-map') {
        this.lahanId = lahanId;
        this.mapElementId = mapElementId;
        this.map = null;
        this.lahanCenter = null;
        this.cachedMapData = null;
        this.mapState = {
            popupAdded: false,
            dataLoaded: false,
            isLoadingData: false
        };

        this.activePopup = null;
        this.activePlotId = null;
        this.isSwitchingSelection = false;
    }

    async initialize() {
        try {
            console.log('Initializing dashboard map for lahan:', this.lahanId);
            await this.loadLahanCenter();
            this.initMap();
            return this.map; 
        } catch (error) {
            console.error('Map initialization error:', error);
            throw error;
        }
    }

    /**
     * Load plot polygon data and add to map
     */
    async loadLahanCenter() {
        try {
            // Get map data which now includes lahan center coordinates
            const mapRes = await fetch(`/lahan/${this.lahanId}/dashboard/data?type=map-data`);
            if (mapRes.ok) {
                const mapData = await mapRes.json();
                
                // Check if lahan has center coordinates
                if (mapData.lahan_center?.has_coordinates) {
                    this.lahanCenter = {
                        lng: mapData.lahan_center.longitude,
                        lat: mapData.lahan_center.latitude,
                        zoom: 16 // Close zoom for lahan center
                    };
                    console.log('Using lahan center coordinates:', this.lahanCenter);
                    
                    // Store map data for later use to avoid duplicate fetch
                    this.cachedMapData = mapData;
                    return;
                }
                
                // Fallback: calculate center from plot polygons
                if (mapData.features && mapData.features.length > 0) {
                    this.lahanCenter = this.calculateGeojsonCenter(mapData);
                    console.log('Using calculated polygon center:', this.lahanCenter);
                    this.cachedMapData = mapData;
                    return;
                }
            }
            
            // Final fallback
            this.setDefaultCenter();
            
        } catch (err) {
            console.warn('Could not load lahan center, using default:', err);
            this.setDefaultCenter();
        }
    }

    /**
     * Fallback method to calculate center from plot polygons
     */
    async calculateCenterFromPolygons() {
        try {
            const mapRes = await fetch(`/lahan/${this.lahanId}/dashboard/data?type=map-data`);
            if (mapRes.ok) {
                const geojson = await mapRes.json();
                if (geojson.features && geojson.features.length > 0) {
                    this.lahanCenter = this.calculateGeojsonCenter(geojson);
                    console.log('Using calculated polygon center:', this.lahanCenter);
                    return;
                }
            }
            this.setDefaultCenter();
        } catch (err) {
            console.warn('Could not calculate center from polygons:', err);
            this.setDefaultCenter();
        }
    }

    /**
     * Set default center for Indonesia
     */
    setDefaultCenter() {
        this.lahanCenter = { 
            lng: 118.0149, 
            lat: -2.5489, 
            zoom: 5 
        };
        console.log('Using default Indonesia center:', this.lahanCenter);
    }

    /**
     * Initialize MapLibre map with lahan center
     */
    initMap() {
        const el = document.getElementById(this.mapElementId);
        if (!el) {
            throw new Error(`Map element ${this.mapElementId} not found`);
        }

        const center = this.lahanCenter || { lng: 118.0149, lat: -2.5489, zoom: 5 };
        
        console.log('Creating map with center:', center);
        
        this.map = new maplibregl.Map({
            container: this.mapElementId,
            style: this.getHybridStyle(),
            center: [center.lng, center.lat],
            zoom: center.zoom,
            maxZoom: 18,
            fadeDuration: 0,
            prefetchZoomDelta: 0,
            transformRequest: (url, resourceType) => ({ url })
        });

        this.addMapControls();
        this.addScaleControl();
        this.bindMapEvents();
    }

    /**
     * Add map navigation controls
     */
    addMapControls() {
        this.map.addControl(new maplibregl.NavigationControl({
            showCompass: true,
            showZoom: true
        }));
    }

    /**     
     * Add scale control to map
     */
    addScaleControl() {
        this.map.addControl(new maplibregl.ScaleControl({
            maxWidth: 100,
            unit: 'metric'
        }), 'bottom-left');
    }

    /**
     * Bind map event handlers
     */
    bindMapEvents() {
        this.map.on('load', () => {
            console.log('Map loaded, loading plot data...');
            if (!this.mapState.dataLoaded && !this.mapState.isLoadingData) {
                this.loadMapData();
            }
        });

        this.map.on('error', (e) => {
            console.error('Map error:', e);
        });

        this.bindMapMovementEvents();
    }

    /**
     * Bind map movement events (no reload on movement)
     */
    bindMapMovementEvents() {
        this.map.on('movestart', () => {
            console.log('Map movement started - no data reload');
        });

        this.map.on('moveend', () => {
            console.log('Map movement ended - no data reload');
        });

        this.map.on('zoomstart', () => {
            console.log('Map zoom started - no data reload');
        });

        this.map.on('zoomend', () => {
            console.log('Map zoom ended - no data reload');
        });
    }

    /**
     * Load plot polygon data and add to map
     */
    async loadMapData() {
        if (!this.map || this.mapState.dataLoaded || this.mapState.isLoadingData) {
            console.log('Skipping map data load - already loaded or loading');
            return;
        }
        
        this.mapState.isLoadingData = true;
        console.log('Loading plot polygon data...');
        
        try {
            const geojson = await this.fetchMapData();

            if (!geojson || !geojson.features || geojson.features.length === 0) {
                console.warn('No plot features found');
                this.updateProgressSummary([]);
                return;
            }

            this.processFeatures(geojson);
            this.addMapLayers(geojson);
            this.fitMapToPolygons(geojson);
            this.addMapPopup();
            
            // Only fit to polygons if we don't have lahan center coordinates
            if (!this.hasLahanCenterCoordinates()) {
                this.fitMapToPolygons(geojson);
            }
            
            this.updateProgressSummary(geojson.features);

            this.mapState.dataLoaded = true;
            console.log('Plot data loaded successfully');
        } catch (err) {
            console.error('Error loading plot data:', err);
            this.updateProgressSummary([]);
        } finally {
            this.mapState.isLoadingData = false;
        }
    }

    /**
     * Check if we have lahan center coordinates (not calculated from polygons)
     */
    hasLahanCenterCoordinates(mapData = null) {
        // If we have map data, check the lahan_center property
        if (mapData?.lahan_center?.has_coordinates) {
            return true;
        }
        
        // Otherwise check our stored center
        return this.lahanCenter && 
            this.lahanCenter.lng !== 118.0149 && 
            this.lahanCenter.lat !== -2.5489 &&
            this.lahanCenter.zoom === 16; // This indicates it came from lahan table
    }

    /**
     * Fetch plot polygon data from backend
     */
    async fetchMapData() {
        const res = await fetch(`/lahan/${this.lahanId}/dashboard/data?type=map-data`);
        if (!res.ok) {
            throw new Error(`HTTP ${res.status}: ${await res.text()}`);
        }
        return await res.json();
    }

    /**
     * Process polygon features and calculate centers
     */
    processFeatures(geojson) {
        console.log('Processing plot features...');
        geojson.features.forEach((feature, index) => {
            if (this.isValidPolygonFeature(feature)) {
                const center = this.getPolygonCenter(feature.geometry.coordinates[0]);
                if (center) {
                    feature.properties._calculatedCenter = center;
                }
            }
        });
    }

    /**
     * Check if feature is a valid polygon
     */
    isValidPolygonFeature(feature) {
        return feature.geometry && 
               feature.geometry.type === 'Polygon' && 
               feature.geometry.coordinates && 
               feature.geometry.coordinates[0];
    }

    /**
     * Update progress summary display
     */
    updateProgressSummary(features) {
        const progressSummary = document.getElementById('progress-summary');
        if (!progressSummary) return;

        if (!features || features.length === 0) {
            this.showEmptyProgressSummary(progressSummary);
            return;
        }

        const stats = this.calculateSummaryStats(features);
        this.renderProgressSummary(progressSummary, stats);
        
        console.log('Progress summary updated:', stats);
    }

    /**
     * Show empty state for progress summary
     */
    showEmptyProgressSummary(container) {
        container.innerHTML = `
            <div class="text-center py-4">
                <div class="text-darkslategray text-sm">Belum ada data plot</div>
            </div>
        `;
    }

    /**
     * Calculate statistics from plot features
     */
    calculateSummaryStats(features) {
        let totalProgress = 0;
        const blocks = [];

        features.forEach(feature => {
            const props = feature.properties;
            const progress = parseFloat(props.progress_percent) || 0;
            const blockName = props.nama_plot || `Blok ${feature.id}`;
            
            blocks.push({
                name: blockName,
                progress: Math.round(progress)
            });
            
            totalProgress += progress;
        });

        const avgProgress = features.length > 0 ? (totalProgress / features.length) : 0;

        return {
            blocks: blocks,
            totalProgress: Math.round(avgProgress),
            totalBlocks: features.length
        };
    }

    /**
     * Render progress summary HTML
     */
    renderProgressSummary(container, stats) {
        container.innerHTML = `
            <div class="flex items-center justify-center gap-6 text-sm flex-wrap">
                ${stats.blocks.map(block => `
                    <div class="text-center">
                        <div class="text-slategray-100 font-medium">${block.name}</div>
                        <div class="font-semibold text-darkslategray">${block.progress}%</div>
                    </div>
                `).join(`
                    <div class="text-gray-400 font-bold">
                        <svg width="1" height="28" viewBox="0 0 1 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <line x1="0.5" y1="-2.18557e-08" x2="0.500001" y2="27.7076" stroke="#27374D"/>
                        </svg>
                    </div>
                `)}
                
                <div class="text-gray-400 font-bold">
					<svg width="1" height="28" viewBox="0 0 1 28" fill="none" xmlns="http://www.w3.org/2000/svg">
						<line x1="0.5" y1="-2.18557e-08" x2="0.500001" y2="27.7076" stroke="#27374D"/>
					</svg>
				</div>
                
                <div class="text-center">
                    <div class="text-slategray-100 font-medium">Total</div>
                    <div class="font-semibold text-darkslategray">${stats.totalProgress}%</div>
                </div>
            </div>
        `;
    }

    /**
     * Add map layers for plot polygons
     */
    addMapLayers(geojson) {
        this.addMapSource(geojson);
        this.addFillLayer();
        this.addOutlineLayer();
    }

    /**
     * Add or update map source with plot data
     */
    addMapSource(geojson) {
        if (this.map.getSource('plots-source')) {
            this.map.getSource('plots-source').setData(geojson);
        } else {
            this.map.addSource('plots-source', {
                type: 'geojson',
                data: geojson
            });
        }
    }

    /**
     * Add polygon fill layer
     */
    addFillLayer() {
        if (!this.map.getLayer('plots-fill')) {
            this.map.addLayer({
                id: 'plots-fill',
                type: 'fill',
                source: 'plots-source',
                paint: {
                    'fill-color': [
                        'step',
                        ['get', 'progress_percent'],
                        '#ef4444',
                        50, '#f59e0b',
                        75, '#3b82f6',
                        100, '#22c55e'
                    ],
                    'fill-opacity': 0.2
                }
            });
        }
    }

    /**
     * Add polygon outline layer
     */
    addOutlineLayer() {
        if (!this.map.getLayer('plots-outline')) {
            this.map.addLayer({
                id: 'plots-outline',
                type: 'line',
                source: 'plots-source',
                paint: {
                    'line-color': [
                        'step',
                        ['get', 'progress_percent'],
                        '#f03',
                        50, '#f59e0b',
                        75, '#3b82f6',
                        100, '#22c55e'
                    ],
                    'line-width': 3
                }
            }, 'plots-fill');
        }
    }

    /**
     * Add interactive popup functionality
     */
    addMapPopup() {
        if (this.mapState.popupAdded) return;

        const popup = new maplibregl.Popup({
            closeButton: false,
            closeOnClick: true
        });

        this.bindPopupEvents(popup);
        this.bindCursorEvents();

        this.mapState.popupAdded = true;
    }

    /**
     * Bind popup click events
     */
    bindPopupEvents(popup) {
        this.map.on('click', 'plots-fill', (e) => {
            this.handlePlotClick(e, popup);
        });
    }

    /**
     * Bind cursor hover events
     */
    bindCursorEvents() {
        this.map.on('mouseenter', 'plots-fill', () => {
            this.map.getCanvas().style.cursor = 'pointer';
        });

        this.map.on('mouseleave', 'plots-fill', () => {
            this.map.getCanvas().style.cursor = '';
        });
    }

    // Update map visuals based on active selection
    updateMapVisuals() {
        if (!this.map) return;

        // Define color expressions
        const progressColorExpression = [
            'step',
            ['get', 'progress_percent'],
            '#ef4444', 50, '#f59e0b', 75, '#3b82f6', 100, '#22c55e'
        ];

        // Define styles based on active selection
        const fillColor = this.activePlotId 
            ? ['case', ['==', ['get', 'plot_id'], this.activePlotId], '#d946ef', progressColorExpression]
            : progressColorExpression;

        const fillOpacity = this.activePlotId 
            ? ['case', ['==', ['get', 'plot_id'], this.activePlotId], 0.6, 0.2]
            : 0.2;

        const lineColor = this.activePlotId 
            ? ['case', ['==', ['get', 'plot_id'], this.activePlotId], '#d946ef', progressColorExpression]
            : progressColorExpression;

        const lineWidth = this.activePlotId 
            ? ['case', ['==', ['get', 'plot_id'], this.activePlotId], 4, 2]
            : 2; // Default line width

        // Apply styles to layers
        if (this.map.getLayer('plots-fill')) {
            this.map.setPaintProperty('plots-fill', 'fill-color', fillColor);
            this.map.setPaintProperty('plots-fill', 'fill-opacity', fillOpacity);
        }
        if (this.map.getLayer('plots-outline')) {
            this.map.setPaintProperty('plots-outline', 'line-color', lineColor);
            this.map.setPaintProperty('plots-outline', 'line-width', lineWidth);
        }
    }

    /**
     * Handle plot click and show popup
     */
    handlePlotClick(e) {
        if (!e.features || e.features.length === 0) return;
        
        const feature = e.features[0];
        const properties = feature.properties;
        const plotId = properties.plot_id;
        const coordinates = this.getPolygonCenter(feature.geometry.coordinates[0]) || e.lngLat;

        if (this.activePlotId === plotId) return;

        this.isSwitchingSelection = true; // Prevent visual reset during popup switch
        if (this.activePopup) {
            this.activePopup.remove(); // Close existing popup
        }
        this.isSwitchingSelection = false;

        this.activePlotId = plotId;

        this.updateMapVisuals();

        // Create popup content
        const popupNode = document.createElement('div');

        // Set popup HTML content
        popupNode.innerHTML = this.createPopupContent(properties);

        // Bind close button event
        const closeBtn = popupNode.querySelector('button'); 
        if(closeBtn) {
            closeBtn.onclick = (e) => {
                e.preventDefault(); 
                e.stopPropagation();
                if (this.activePopup) this.activePopup.remove(); 
            };
        }

        const popup = new maplibregl.Popup({
            closeButton: false,
            closeOnClick: true,
            maxWidth: '300px'
        })
        .setLngLat(coordinates)
        .setDOMContent(popupNode)
        .addTo(this.map);

        this.activePopup = popup;

        popup.once('close', () => {
            if (!this.isSwitchingSelection) {
                this.activePlotId = null;
                this.activePopup = null;
                this.updateMapVisuals(); // Reset visuals
            }
        });
    }

    /**
     * Create popup HTML content
     */
    createPopupContent(properties) {
        const progressPercent = Math.round(properties.progress_percent || 0);
        const status = properties.status === 'completed' ? 'Selesai' : 'Dalam Proses';
        const progressColor = this.getProgressColor(progressPercent);
        const plotName = properties.nama_plot || '-';
        const truncatedName = plotName.length > 12 ? plotName.substring(0, 12) + '...' : plotName;

        return `
            <div class="plot-popup-content p-2 relative">
                <button class="absolute cursor-pointer top-3 right-1 w-4 h-4 bg-transparent text-red-500 flex items-center justify-center text-lg font-bold transition-all duration-200 hover:scale-110">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <h3 class="text-lg font-bold mb-2 text-gray-800 pr-10" title="${plotName}">${truncatedName}</h3>
                <div class="space-y-2 mb-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Luas Area:</span>
                        <span class="font-semibold text-blue-700">${properties.luas_area || '-'} Ha</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Progres:</span>
                        <span class="font-semibold ${progressColor}">${progressPercent}%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Status:</span>
                        <span class="text-sm font-medium ${progressPercent >= 100 ? 'text-green-600' : 'text-blue-600'}">${status}</span>
                    </div>
                </div>
                <div class="mt-2 text-center">
                    <a href="/plot/${properties.plot_id}" 
                       class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded shadow transition no-underline"
                       aria-label="Lihat detail plot ${plotName}">
                        Lihat Detail
                    </a>
                </div>
            </div>
        `;
    }

    /**
     * Get color class based on progress percentage
     */
    getProgressColor(percent) {
        if (percent >= 100) return 'text-green-600';
        if (percent >= 75) return 'text-blue-600';
        if (percent >= 50) return 'text-amber-600';
        return 'text-red-600';
    }

    /**
     * Calculate center from GeoJSON features
     */
    calculateGeojsonCenter(geojson) {
        if (!geojson || !geojson.features || geojson.features.length === 0) {
            return { lng: 118.0149, lat: -2.5489, zoom: 5 };
        }

        const bounds = new maplibregl.LngLatBounds();
        
        geojson.features.forEach(feature => {
            if (this.isValidPolygonFeature(feature)) {
                feature.geometry.coordinates[0].forEach(coord => {
                    if (Array.isArray(coord) && coord.length >= 2) {
                        bounds.extend([coord[0], coord[1]]);
                    }
                });
            }
        });

        if (!bounds.isEmpty()) {
            const center = bounds.getCenter();
            return { lng: center.lng, lat: center.lat, zoom: 16 };
        }
        
        return { lng: 118.0149, lat: -2.5489, zoom: 5 };
    }

    /**
     * Get polygon center point
     */
    getPolygonCenter(coordinates) {
        if (!coordinates || !Array.isArray(coordinates) || coordinates.length === 0) {
            return null;
        }
        
        let totalLng = 0;
        let totalLat = 0;
        let count = 0;
        
        const coordsToProcess = coordinates.length > 1 && 
            coordinates[0][0] === coordinates[coordinates.length - 1][0] && 
            coordinates[0][1] === coordinates[coordinates.length - 1][1] 
            ? coordinates.slice(0, -1)
            : coordinates;
        
        coordsToProcess.forEach(coord => {
            if (Array.isArray(coord) && coord.length >= 2) {
                totalLng += coord[0];
                totalLat += coord[1];
                count++;
            }
        });
        
        if (count === 0) return null;
        
        return [totalLng / count, totalLat / count];
    }

    /**
     * Fit map to show all polygons (fallback method)
     */
    fitMapToPolygons(geojson) {
        if (!geojson || !geojson.features || geojson.features.length === 0) return;

        const bounds = new maplibregl.LngLatBounds();

        geojson.features.forEach(feature => {
            if (this.isValidPolygonFeature(feature)) {
                feature.geometry.coordinates[0].forEach(coord => {
                    if (Array.isArray(coord) && coord.length >= 2) {
                        bounds.extend([coord[0], coord[1]]);
                    }
                });
            }
        });

        if (!bounds.isEmpty()) {
            this.map.fitBounds(bounds, { padding: 50 });
        }
    }

    /**
     * Get hybrid style configuration
     */
    getHybridStyle() {
        return {
            version: 8,
            sources: {
                'esri-imagery': {
                    type: 'raster',
                    tiles: ['https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'],
                    tileSize: 256,
                    attribution: 'Tiles &copy; Esri'
                },
                'esri-transportation': {
                    type: 'raster',
                    tiles: ['https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Transportation/MapServer/tile/{z}/{y}/{x}'],
                    tileSize: 256
                },
                'esri-labels': {
                    type: 'raster',
                    tiles: ['https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}'],
                    tileSize: 256
                }
            },
            layers: [
                {
                    id: 'esri-imagery',
                    type: 'raster',
                    source: 'esri-imagery',
                    minzoom: 0,
                    maxzoom: 22
                },
                {
                    id: 'esri-transportation',
                    type: 'raster',
                    source: 'esri-transportation',
                    minzoom: 0,
                    maxzoom: 22
                },
                {
                    id: 'esri-labels',
                    type: 'raster',
                    source: 'esri-labels',
                    minzoom: 0,
                    maxzoom: 22
                }
            ]
        };
    }

    /**
     * Clean up and destroy map instance
     */
    destroy() {
        console.log('Destroying dashboard map handler');
        
        if (this.map) {
            this.map.remove();
            this.map = null;
        }
        
        this.mapState = {
            popupAdded: false,
            dataLoaded: false,
            isLoadingData: false
        };
    }
}

export { DashboardMapHandler };
