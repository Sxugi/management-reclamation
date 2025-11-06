import maplibregl from 'maplibre-gl';

class DashboardMapHandler {
    constructor(lahanId, mapElementId = 'maplibre-map') {
        this.lahanId = lahanId;
        this.mapElementId = mapElementId;
        this.map = null;
        this.lahanCenter = null;
        this.mapState = {
            popupAdded: false,
            dataLoaded: false,
            isLoadingData: false
        };
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

    // Map initialization methods
    async loadLahanCenter() {
        try {
            const mapRes = await fetch(`/lahan/${this.lahanId}/dashboard/data?type=map-data`);
            if (mapRes.ok) {
                const geojson = await mapRes.json();
                if (geojson.features && geojson.features.length > 0) {
                    this.lahanCenter = this.calculateGeojsonCenter(geojson);
                    return;
                }
            }
            this.setDefaultCenter();
        } catch (err) {
            console.warn('Could not load lahan center, using default:', err);
            this.setDefaultCenter();
        }
    }

    setDefaultCenter() {
        this.lahanCenter = { lng: 118.0149, lat: -2.5489, zoom: 5 };
    }

    initMap() {
        const el = document.getElementById(this.mapElementId);
        if (!el) {
            throw new Error(`Map element ${this.mapElementId} not found`);
        }

        const center = this.lahanCenter || { lng: 118.0149, lat: -2.5489, zoom: 5 };
        
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
        this.bindMapEvents();
    }

    addMapControls() {
        this.map.addControl(new maplibregl.NavigationControl({
            showCompass: true,
            showZoom: true
        }));
    }

    bindMapEvents() {
        this.map.on('load', () => {
            console.log('Map loaded, loading data...');
            if (!this.mapState.dataLoaded && !this.mapState.isLoadingData) {
                this.loadMapData();
            }
        });

        this.map.on('error', (e) => {
            console.error('Map error:', e);
        });

        this.bindMapMovementEvents();
    }

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

    // Data loading methods
    async loadMapData() {
        if (!this.map || this.mapState.dataLoaded || this.mapState.isLoadingData) {
            console.log('Skipping map data load - already loaded or loading');
            return;
        }
        
        this.mapState.isLoadingData = true;
        console.log('Loading map data for the first time...');
        
        try {
            const geojson = await this.fetchMapData();

            if (!geojson || !geojson.features || geojson.features.length === 0) {
                console.warn('No features found in map data');
                this.updateProgressSummary([]);
                return;
            }

            this.processFeatures(geojson);
            this.addMapLayers(geojson);
            this.addMapPopup();
            this.fitMapToPolygons(geojson);
            this.updateProgressSummary(geojson.features);

            this.mapState.dataLoaded = true;
            console.log('Map data loaded successfully - will not reload on map interactions');
        } catch (err) {
            console.error('Error loading map data:', err);
            this.updateProgressSummary([]);
        } finally {
            this.mapState.isLoadingData = false;
        }
    }

    async fetchMapData() {
        const res = await fetch(`/lahan/${this.lahanId}/dashboard/data?type=map-data`);
        if (!res.ok) {
            throw new Error(`HTTP ${res.status}: ${await res.text()}`);
        }
        return await res.json();
    }

    processFeatures(geojson) {
        console.log('Processing features and calculating centers...');
        geojson.features.forEach((feature, index) => {
            if (this.isValidPolygonFeature(feature)) {
                const center = this.getPolygonCenter(feature.geometry.coordinates[0]);
                console.log(`Feature ${index} (${feature.properties?.nama_plot}) center:`, center);
                
                if (center) {
                    feature.properties._calculatedCenter = center;
                }
            }
        });
    }

    isValidPolygonFeature(feature) {
        return feature.geometry && 
               feature.geometry.type === 'Polygon' && 
               feature.geometry.coordinates && 
               feature.geometry.coordinates[0];
    }

    // Progress summary methods
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

    showEmptyProgressSummary(container) {
        container.innerHTML = `
            <div class="text-center py-4">
                <div class="text-darkslategray text-sm">Belum ada data plot</div>
            </div>
        `;
    }

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

    // Map layer methods
    addMapLayers(geojson) {
        this.addMapSource(geojson);
        this.addFillLayer();
        this.addOutlineLayer();
    }

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

    addFillLayer() {
        if (!this.map.getLayer('plots-fill')) {
            this.map.addLayer({
                id: 'plots-fill',
                type: 'fill',
                source: 'plots-source',
                paint: {
                    'fill-color': '#3388ff',
                    'fill-opacity': 0.2
                }
            });
        }
    }

    addOutlineLayer() {
        if (!this.map.getLayer('plots-outline')) {
            this.map.addLayer({
                id: 'plots-outline',
                type: 'line',
                source: 'plots-source',
                paint: {
                    'line-color': '#3388ff',
                    'line-width': 3
                }
            }, 'plots-fill');
        }
    }

    // Popup methods
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

    bindPopupEvents(popup) {
        this.map.on('click', 'plots-fill', (e) => {
            this.handlePlotClick(e, popup);
        });
    }

    bindCursorEvents() {
        this.map.on('mouseenter', 'plots-fill', () => {
            this.map.getCanvas().style.cursor = 'pointer';
        });

        this.map.on('mouseleave', 'plots-fill', () => {
            this.map.getCanvas().style.cursor = '';
        });
    }

    handlePlotClick(e, popup) {
        if (!e.features || e.features.length === 0) return;
        
        const feature = e.features[0];
        const properties = feature.properties;
        const coordinates = this.getPolygonCenter(feature.geometry.coordinates[0]) || e.lngLat;
        
        const html = this.createPopupContent(properties);
        popup.setLngLat(coordinates).setHTML(html).addTo(this.map);
    }

    createPopupContent(properties) {
        const progressPercent = Math.round(properties.progress_percent || 0);
        const status = properties.status === 'completed' ? 'Selesai' : 'Dalam Proses';
        const progressColor = this.getProgressColor(progressPercent);
        const plotName = properties.nama_plot || '-';
        const truncatedName = plotName.length > 12 ? plotName.substring(0, 12) + '...' : plotName;

        return `
            <div class="plot-popup-content p-2 relative">
                <button class="absolute cursor-pointer top-3 right-1 w-4 h-4 bg-transparent text-red-500 flex items-center justify-center text-lg font-bold transition-all duration-200 hover:scale-110" 
                        onclick="document.querySelector('.maplibregl-popup').remove()">
                    ×
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

    getProgressColor(percent) {
        if (percent >= 100) return 'text-green-600';
        if (percent >= 75) return 'text-blue-600';
        if (percent >= 50) return 'text-amber-600';
        return 'text-red-600';
    }

    // Utility methods
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
