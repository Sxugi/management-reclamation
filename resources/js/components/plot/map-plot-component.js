document.addEventListener('alpine:init', () => {
    // Register the map component with polygon support and fixed center
    Alpine.data('mapPlotComponent', (initialData = {}) => ({
        map: null,
        draw: null, 
        polygonData: initialData.polygons || [],
        coordinates: {
            lng: initialData.longitude ? parseFloat(initialData.longitude) : null,
            lat: initialData.latitude ? parseFloat(initialData.latitude) : null
        },
        currentBasemap: 'arcgis_hybrid',
        selectedPolygonId: null,
        enableDraw: initialData.enableDraw || false,

        // Initialize the map when the component loads
        init() {
            this.initMap();
        },
        
        // Set up the map with fixed center
        initMap() {
            console.log('Initializing map with polygon support and fixed center...');

            console.log('polygonData:', this.polygonData);
            
            if (!window.maplibregl) {
                console.error('MapLibre GL JS is not loaded!');
                return;
            }
            
            const maplibregl = window.maplibregl;
            
            // Default center if no coordinates
            let centerLng = 118.0149; 
            let centerLat = -2.5489;
            let initialZoom = 5;
            
            if (this.coordinates.lng !== null && this.coordinates.lat !== null) {
                centerLng = this.coordinates.lng;
                centerLat = this.coordinates.lat;
                initialZoom = 16;
            }
            
            // Create the map with fixed center
            this.map = new maplibregl.Map({
                container: 'plot-map',
                style: this.getHybridStyle(),
                center: [centerLng, centerLat], 
                maxZoom: 18,
                zoom: initialZoom,
                fadeDuration: 0,
                prefetchZoomDelta: 0,
                transformRequest: (url, resourceType) => {
                    return { url };
                }
            });

            // Add only zoom controls without pan controls
            this.map.addControl(new maplibregl.NavigationControl({
                showCompass: true,
                showZoom: true
            }));
            
            // Initialize drawing tools when map loads
            this.map.on('load', () => {
                this.initDrawTools();
                this.loadExistingPolygons();
            });

            // Listen for manual coordinate changes from input fields
            window.addEventListener('update-polygon-from-inputs', (e) => {
                if (e.detail && e.detail.geometry && e.detail.geometry.coordinates) {
                    if (this.draw) {                       
                        // Add the updated feature
                        this.draw.add(e.detail);

                        // Calculate luas_area
                        const luas_area = this.calculatePolygonArea(e.detail.geometry.coordinates);

                        // Update form with the new luas_area
                        window.dispatchEvent(new CustomEvent('polygon-updated', {
                            detail: {
                                id: e.detail.id,
                                coordinates: e.detail.geometry.coordinates,
                                luas_area: luas_area.toFixed(2)
                            }
                        }));
                    }
                }
            });
        },
        
        // Initialize drawing tools
        initDrawTools() {
            const MapboxDraw = window.MapboxDraw;
            
            // Configure draw with polygon support
            this.draw = new MapboxDraw({
                displayControlsDefault: false,
                controls: this.enableDraw
                    ? { polygon: true, trash: true }
                    : {},
                styles: this.getDrawStyles()
            });
            
            // Add the draw tools to the map
            this.map.addControl(this.draw);

            // Listen for drawing events
            this.map.on('draw.create', (e) => {
                const features = this.draw.getAll();

                if (features.features.length > 1) {
                    const newFeature = e.features[0];
                    
                    // Delete all features
                    this.draw.deleteAll();
                    
                    // Add back only the new feature
                    this.draw.add(newFeature);
                }

                const feature = e.features[0];
                this.handlePolygonCreated(feature);
            });
            
            this.map.on('draw.update', (e) => {
                const feature = e.features[0];
                this.handlePolygonUpdated(feature);
            });
            
            this.map.on('draw.delete', (e) => {
                const feature = e.features[0];
                this.handlePolygonDeleted(feature);
            });
            
            this.map.on('draw.selectionchange', (e) => {
                if (e.features.length > 0) {
                    const selectedFeature = e.features[0];
                    this.selectedPolygonId = selectedFeature.id;
                    
                    // Show the edit button
                    const editButton = document.getElementById('editButton');
                    if (editButton) {
                        editButton.classList.remove('hidden');
                        editButton.classList.add('flex');
                        
                        // Update the href with the selected polygon ID
                        const baseUrl = editButton.getAttribute('data-base-url') || editButton.href.split('?')[0];
                        editButton.href = baseUrl + '?id=' + this.selectedPolygonId;
                    }
                } else {
                    this.selectedPolygonId = null;
                    
                    // Hide the edit button
                    const editButton = document.getElementById('editButton');
                    if (editButton) {
                        editButton.classList.add('hidden');
                        editButton.classList.remove('flex');
                    }
                }
            });
        },
        
        // Reset view to center if somehow the map gets moved
        resetViewToCenter() {
            if (!this.map) return;
            if (this.coordinates.lng !== null && this.coordinates.lat !== null) {
                this.map.jumpTo({
                    center: [this.coordinates.lng, this.coordinates.lat],
                    zoom: 15
                });
            }
        },
        
        // Load existing polygons from initialData
        loadExistingPolygons() {
            if (!this.polygonData || !this.polygonData.length) {
                console.log('No polygon data to load');
                return;
            }
            
            const maplibregl = window.maplibregl;
            
            // Create a single GeoJSON collection for all plots
            const geojsonData = {
                type: 'FeatureCollection',
                features: this.polygonData.map(plot => {
                    return {
                        type: 'Feature',
                        id: plot.plot_id,
                        properties: {
                            plot_id: plot.plot_id,
                            nama_plot: plot.nama_plot,
                            luas_area: plot.luas_area,
                        },
                        geometry: {
                            type: 'Polygon',
                            coordinates: plot.polygon.coordinates
                        }
                    };
                })
            };

            if (this.enableDraw) {
                this.draw.add(geojsonData);
            } else {
                // Add single source for all plots
                this.map.addSource('plots-source', {
                    type: 'geojson',
                    data: geojsonData,
                });

                console.log('Loaded existing polygons:', geojsonData);
            
                // Add fill layer
                this.map.addLayer({
                    id: 'plots-fill',
                    type: 'fill',
                    source: 'plots-source',
                    paint: {
                        'fill-color': '#3388ff',
                        'fill-opacity': 0.2
                    }
                });
                
                // Add outline layer
                this.map.addLayer({
                    id: 'plots-outline',
                    type: 'line',
                    source: 'plots-source',
                    paint: {
                        'line-color': '#3388ff',
                        'line-width': 3
                    }
                }, 'plots-fill');
                
                // Create a popup but don't add it to the map yet
                const popup = new maplibregl.Popup({
                    closeButton: true,
                    closeOnClick: true
                });

                // Add click event for the polygons (use hitbox for better detection)
                this.map.on('click', 'plots-fill', (e) => {
                    if (e.features.length > 0) {
                        // Get clicked feature
                        const feature = e.features[0];
                        const properties = feature.properties;
                        const plotId = properties.plot_id;

                        let coordinates;
                        coordinates = this.getPolygonCenter(
                            feature.geometry.coordinates[0]
                        );
                        
                        // Create popup HTML content with better formatting
                        const html = `
                            <div class="plot-popup-content">
                                <h3 class="text-lg font-bold mb-2">${properties.nama_plot}</h3>
                                <div class="flex justify-between mb-2">
                                    <span>Luas Area:</span>
                                    <span class="font-semibold">${properties.luas_area} Ha</span>
                                </div>
                                <div class="mt-2 text-center">
                                    <a href="/plot/${plotId}/edit" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        `;
                        
                        // Set popup position and content
                        popup.setLngLat(coordinates)
                            .setHTML(html)
                            .addTo(this.map);
                    }
                });

                // Change cursor to pointer when over a plot
                this.map.on('mouseenter', 'plots-fill', () => {
                    this.map.getCanvas().style.cursor = 'pointer';
                });

                // Remove hover state when leaving the layer
                this.map.on('mouseleave', 'plots-fill', () => {
                    this.map.getCanvas().style.cursor = '';
                });
            }
            
            // Fit map to show all polygons
            this.fitMapToPolygons();
        },
        
        // Get the center point of a polygon
        getPolygonCenter(coordinates) {
            if (!coordinates || !Array.isArray(coordinates) || coordinates.length === 0) {
                return null;
            }
            
            let totalLng = 0;
            let totalLat = 0;
            let count = 0;
            
            // Skip the last coordinate if it's identical to the first (closing the polygon)
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
        },
        
        // Fit map to show all polygons
        fitMapToPolygons() {
            if (!this.polygonData || !this.polygonData.length) return;

            const maplibregl = window.maplibregl;
            const bounds = new maplibregl.LngLatBounds();

            this.polygonData.forEach(plot => {
                // Pastikan format GeoJSON Polygon
                if (
                    plot.polygon &&
                    plot.polygon.coordinates &&
                    Array.isArray(plot.polygon.coordinates) &&
                    Array.isArray(plot.polygon.coordinates[0])
                ) {
                    // Ambil ring pertama (umumnya outline polygon)
                    plot.polygon.coordinates[0].forEach(coord => {
                        if (Array.isArray(coord) && coord.length >= 2) {
                            bounds.extend([coord[0], coord[1]]);
                        }
                    });
                }
            });

            if (bounds.isEmpty()) return; // Jangan fit jika bounds kosong
            this.map.fitBounds(bounds, { padding: 50 });
        },
        
        // Handle polygon creation
        handlePolygonCreated(feature) {
            // Calculate luas_area for the new polygon
            const luas_area = this.calculatePolygonArea(feature.geometry.coordinates);

            const polygonData = {
                nama_plot: 'Blok A',
                coordinates: feature.geometry.coordinates,
                luas_area: luas_area.toFixed(2),
                lahan_id: document.getElementById('lahan_id')?.value || null,
            };
            
            window.dispatchEvent(new CustomEvent('polygon-created', {
                detail: {
                    id: feature.id,
                    data: polygonData
                }
            }));
        },
        
        // Handle polygon updates
        handlePolygonUpdated(feature) {
            // Recalculate luas_area
            const luas_area = this.calculatePolygonArea(feature.geometry.coordinates);

            // Dispatch update event
            this.$dispatch('polygon-updated', {
                id: feature.id,
                coordinates: feature.geometry.coordinates,
                luas_area: luas_area.toFixed(2),
                nama_plot: feature.properties.nama_plot
            });
        },
        
        // Handle polygon deletion
        handlePolygonDeleted(feature) {
            this.$dispatch('polygon-deleted', {
                id: feature.id,
            });
        },
        
        // Calculate area of polygon in Hectares
        calculatePolygonArea(coordinates) {
            // Use turf.js for accurate area calculation
            if (window.turf) {
                const polygon = {
                    type: 'Feature',
                    geometry: {
                        type: 'Polygon',
                        coordinates: coordinates
                    }
                };
                
                // Get area in square meters
                const areaInSquareMeters = window.turf.area(polygon);
                
                // Convert to hectares (1 hectare = 10,000 square meters)
                const areaInHectares = areaInSquareMeters / 10000;
                
                return areaInHectares;
            } else {
                console.warn("Turf.js tidak tersedia. Area tidak dihitung.");
                return 0;
            }
        },
        
        // Get custom styles for the draw tools
        getDrawStyles() {
            return [
                // Fill styles
                {
                    'id': 'gl-draw-polygon-fill-inactive',
                    'type': 'fill',
                    'filter': ['all', ['==', 'active', 'false'], ['==', '$type', 'Polygon']],
                    'paint': {
                        'fill-color': '#3388ff',
                        'fill-outline-color': '#3388ff',
                        'fill-opacity': 0.2
                    }
                },
                {
                    'id': 'gl-draw-polygon-fill-active',
                    'type': 'fill',
                    'filter': ['all', ['==', 'active', 'true'], ['==', '$type', 'Polygon']],
                    'paint': {
                        'fill-color': '#f03',
                        'fill-outline-color': '#f03',
                        'fill-opacity': 0.3
                    }
                },
                // Line styles
                {
                    'id': 'gl-draw-polygon-stroke-inactive',
                    'type': 'line',
                    'filter': ['all', ['==', 'active', 'false'], ['==', '$type', 'Polygon']],
                    'layout': {
                        'line-cap': 'round',
                        'line-join': 'round'
                    },
                    'paint': {
                        'line-color': '#3388ff',
                        'line-width': 2
                    }
                },
                {
                    'id': 'gl-draw-polygon-stroke-active',
                    'type': 'line',
                    'filter': ['all', ['==', 'active', 'true'], ['==', '$type', 'Polygon']],
                    'layout': {
                        'line-cap': 'round',
                        'line-join': 'round'
                    },
                    'paint': {
                        'line-color': '#f03',
                        'line-width': 3
                    }
                },
                // Vertex styles
                {
                    'id': 'gl-draw-polygon-midpoint',
                    'type': 'circle',
                    'filter': ['all', ['==', '$type', 'Point'], ['==', 'meta', 'midpoint']],
                    'paint': {
                        'circle-radius': 3,
                        'circle-color': '#fbb03b'
                    }
                },
                {
                    'id': 'gl-draw-polygon-and-line-vertex-inactive',
                    'type': 'circle',
                    'filter': ['all', ['==', 'meta', 'vertex'], ['==', '$type', 'Point']],
                    'paint': {
                        'circle-radius': 5,
                        'circle-color': '#fff'
                    }
                },
                {
                    'id': 'gl-draw-polygon-and-line-vertex-active',
                    'type': 'circle',
                    'filter': ['all', ['==', 'meta', 'vertex'], ['==', '$type', 'Point'], ['==', 'active', 'true']],
                    'paint': {
                        'circle-radius': 6,
                        'circle-color': '#fbb03b'
                    }
                }
            ];
        },
        
        // Get the style for the ArcGIS Hybrid basemap
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
        },
        
        // Change the basemap
        changeBasemap(basemapId) {
            // Store current view state and drawn features
            const center = this.map.getCenter();
            const zoom = this.map.getZoom();
            const features = this.draw ? this.draw.getAll() : null;
            
            // Handle ArcGIS Hybrid specially
            if (basemapId === 'arcgis_hybrid') {
                this.map.setStyle(this.getHybridStyle());
                this.currentBasemap = 'arcgis_hybrid';
                this.loadExistingPolygons();
            } else if (basemapId === 'osm') {
                // OpenStreetMap
                const osmStyle = {
                    version: 8,
                    sources: {
                        'raster-tiles': {
                            type: 'raster',
                            tiles: ['https://tile.openstreetmap.org/{z}/{x}/{y}.png'],
                            tileSize: 256,
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                        }
                    },
                    layers: [{
                        id: 'basemap',
                        type: 'raster',
                        source: 'raster-tiles',
                        minzoom: 0,
                        maxzoom: 22
                    }]
                };
                this.map.setStyle(osmStyle);
                this.currentBasemap = 'osm';
                this.loadExistingPolygons();
            }
            
            // Restore view state and features when style is loaded
            this.map.once('style.load', () => {
                // Restore view
                this.map.setCenter(center);
                this.map.setZoom(zoom);

                const maplibregl = window.maplibregl;
                
                // Re-add controls
                this.map.addControl(new maplibregl.NavigationControl({
                    showCompass: false,
                    visualizePitch: false
                }));
                
                this.initDrawTools();
                
                // Restore features if available
                if (features && features.features.length > 0) {
                    this.draw.set(features);
                }
            });
        }
    }));
});

document.addEventListener('alpine:init', () => {
    Alpine.data('koordinatInputData', (initialData = {}) => ({
        polygons: initialData.polygons || [],
        points: initialData.points || [
                    { lat: '', lng: '' },
                    { lat: '', lng: '' },
                    { lat: '', lng: '' }
                ],
        nama_plot: initialData.nama_plot || '',
        luas_area: initialData.luas_area || 0,
        polygonId: null,
        
        init() {
            // Load initial data if available
            this.loadInitialData();
            
            // Event listeners
            window.addEventListener('polygon-created', (e) => {
                if (e.detail && e.detail.data && e.detail.data.coordinates) {
                    this.polygonId = e.detail.id;
                    this.luas_area = e.detail.data.luas_area;
                    this.nama_plot = e.detail.data.nama_plot;

                    // Get coordinates from the first ring of the polygon
                    const coords = e.detail.data.coordinates[0];

                    const uniqueCoords = coords.length > 1 &&
                        coords[0][0] === coords[coords.length - 1][0] &&
                        coords[0][1] === coords[coords.length - 1][1]
                        ? coords.slice(0, -1)
                        : coords;
                    
                    // Update the input fields with coordinates from the map
                    this.points = uniqueCoords.map(coord => ({
                        lng: parseFloat(coord[0]),
                        lat: parseFloat(coord[1])
                    }));
                }
            });
            
            // Listen for polygon update events from the map
            window.addEventListener('polygon-updated', (e) => {
                if (e.detail && e.detail.coordinates) {
                    this.luas_area = e.detail.luas_area;

                    // Get coordinates from the first ring of the polygon
                    const coords = e.detail.coordinates[0];

                    const uniqueCoords = coords.length > 1 &&
                        coords[0][0] === coords[coords.length - 1][0] &&
                        coords[0][1] === coords[coords.length - 1][1]
                        ? coords.slice(0, -1)
                        : coords;
                    
                    // Update the input fields with coordinates from the map
                    this.points = uniqueCoords.map(coord => ({
                        lng: parseFloat(coord[0]),
                        lat: parseFloat(coord[1])
                    }));
                }
            });
            
            // Listen for polygon deletion events from the map
            window.addEventListener('polygon-deleted', () => {
                this.points = [
                    { lat: '', lng: '' },
                    { lat: '', lng: '' },
                    { lat: '', lng: '' }
                ];
                this.polygonId = null;
                this.luas_area = 0;
            });
        },
        
        // Function to load existing data for pre-populating the form
        loadInitialData() {
            if (!this.polygons || typeof this.polygons !== 'object') {
                console.warn('No polygon data found');
                return;
            }

            // Set polygon ID, name, and area
            this.polygonId = this.polygons.plot_id ?? null;
            this.nama_plot = this.polygons.nama_plot ?? '';
            this.luas_area = this.polygons.luas_area ?? 0;

            // Extract coordinates from the polygons object
            const coordinates = this.polygons?.polygon?.coordinates;

            if (Array.isArray(coordinates) && Array.isArray(coordinates[0])) {
                const coords = coordinates[0]; // Ambil ring pertama
                const uniqueCoords = coords.length > 1 &&
                    coords[0][0] === coords[coords.length - 1][0] &&
                    coords[0][1] === coords[coords.length - 1][1]
                    ? coords.slice(0, -1)
                    : coords;

                this.points = uniqueCoords.map(coord => ({
                    lng: parseFloat(coord[0]),
                    lat: parseFloat(coord[1])
                }));
            }

            // Ensure there are at least 3 points
            if (!Array.isArray(this.points) || this.points.length < 3) {
                this.points = [
                    { lat: '', lng: '' },
                    { lat: '', lng: '' },
                    { lat: '', lng: '' }
                ];
            }

            // Update map setelah delay
            setTimeout(() => this.updateMapFromInputs(), 500);
        },
        
        // Add a new empty input field
        addPoint() {
            this.points.push({ lat: '', lng: '' });
        },
        
        // Remove a specific input field
        removePoint(index) {
            if (this.points.length > 3) {
                this.points.splice(index, 1);
                this.updateMapFromInputs();
            } else {
                this.$dispatch('open-modal', 'point-error');
            }
        },
        
        // Update the map when input fields change
        updateMapFromInputs() {
            // Filter out incomplete points
            const validPoints = this.points.filter(p => 
                p.lat && p.lng && 
                !isNaN(parseFloat(p.lat)) && 
                !isNaN(parseFloat(p.lng))
            );
            
            // Need at least 3 points for a valid polygon
            if (validPoints.length >= 3) {
                // Convert to GeoJSON format (longitude first, then latitude)
                const coordinates = validPoints.map(p => [
                    parseFloat(p.lng),
                    parseFloat(p.lat)
                ]);
                
                // Close the polygon by duplicating the first point at the end
                if (coordinates.length >= 3) {
                    const first = coordinates[0];
                    const last = coordinates[coordinates.length - 1];

                    if (first[0] !== last[0] || first[1] !== last[1]) {
                        coordinates.push([...first]);
                    }
                }
                
                // Create a GeoJSON feature
                const feature = {
                    type: 'Feature',
                    properties: {},
                    id: this.polygonId, // Keep the existing ID if there is one
                    geometry: {
                        type: 'Polygon',
                        coordinates: [coordinates]
                    }
                };
                
                // Dispatch event for the map to handle
                window.dispatchEvent(new CustomEvent('update-polygon-from-inputs', {
                    detail: feature
                }));
            }
        }
    }));
});