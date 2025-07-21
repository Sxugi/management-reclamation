document.addEventListener('alpine:init', () => {
    // Register the map component
    Alpine.data('mapComponent', (initialData = {}) => ({
        map: null,
        marker: null,
        geocoder: null,
        coordinates: {
            lng: initialData.longitude ? parseFloat(initialData.longitude) : null,
            lat: initialData.latitude ? parseFloat(initialData.latitude) : null
        },
        currentBasemap: 'arcgis_hybrid',
        searchResults: [],
        isSearching: false,
        
        // Initialize the map when the component loads
        init() {
            // Use nextTick to ensure the DOM element exists
            this.$nextTick(() => {
                this.initMap();
            });
        },
        
        // Set up the map
        initMap() {
            console.log('Initializing map...');
            
            // Check if MapLibre is available
            if (!window.maplibregl) {
                console.error('MapLibre GL JS is not loaded!');
                return;
            }
            
            const maplibregl = window.maplibregl;
            
            // Default center if no coordinates
            let centerLng = 118.0149; 
            let centerLat = -2.5489;
            let initialZoom = 5;
            
            // Use existing coordinates if available
            if (this.coordinates.lng !== null && this.coordinates.lat !== null) {
                centerLng = this.coordinates.lng;
                centerLat = this.coordinates.lat;
                initialZoom = 14;
            }
            
            // Create the map with ArcGIS Hybrid basemap
            this.map = new maplibregl.Map({
                container: 'map',
                style: this.getHybridStyle(),
                center: [centerLng, centerLat], 
                zoom: initialZoom,
                maxZoom: 18,
                fadeDuration: 0,
                prefetchZoomDelta: 0,
                transformRequest: (url, resourceType) => {
                    return { url };
                }
            });
            
            // Add navigation controls
            this.map.addControl(new maplibregl.NavigationControl());
            
            // Add initial marker if coordinates exist
            if (this.coordinates.lng !== null && this.coordinates.lat !== null) {
                this.marker = new maplibregl.Marker()
                    .setLngLat([this.coordinates.lng, this.coordinates.lat])
                    .addTo(this.map);
            }
            
            // Add click handler for selecting coordinates
            this.map.on('click', (e) => {
                const lngLat = e.lngLat;
                console.log(`Selected coordinates: ${lngLat.lng}, ${lngLat.lat}`);
                
                this.updateMarkerAndCoordinates(lngLat.lng, lngLat.lat);
            });
        },
        
        // Update marker position and coordinates
        updateMarkerAndCoordinates(lng, lat) {
            // Update marker
            if (this.marker) this.marker.remove();
            
            const maplibregl = window.maplibregl;
            this.marker = new maplibregl.Marker()
                .setLngLat([lng, lat])
                .addTo(this.map);
            
            // Update coordinates in this component
            this.coordinates = {
                lng: lng,
                lat: lat
            };
            
            // Dispatch to the form component
            this.$dispatch('coordinates-updated', {
                lng: lng,
                lat: lat
            });
        },
        
        // Search for a location using Nominatim
        searchLocation(query) {
            if (!query || query.trim() === '') return;
            
            this.isSearching = true;
            this.searchResults = [];
            
            // Create URL with parameters
            const params = new URLSearchParams({
                q: query,
                format: 'json',
                addressdetails: 1,
                limit: 5
            });
            
            // Use Nominatim API
            fetch(`https://nominatim.openstreetmap.org/search?${params.toString()}`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                this.searchResults = data;
                this.isSearching = false;
            })
            .catch(error => {
                console.error('Error searching for location:', error);
                this.isSearching = false;
            });
        },
        
        // Select a search result
        selectSearchResult(result) {
            // Clear search results
            this.searchResults = [];
            
            // Extract coordinates
            const lng = parseFloat(result.lon);
            const lat = parseFloat(result.lat);
            
            // Update marker and coordinates
            this.updateMarkerAndCoordinates(lng, lat);
            
            // Fly to the location
            this.map.flyTo({
                center: [lng, lat],
                zoom: 14,
                essential: true
            });
            
            // Clear the search input
            if (this.$refs && this.$refs.searchInput) {
                this.$refs.searchInput.value = '';
            }
        },
        
        // Get the bounding box for a search result (useful for zooming to fit)
        getBoundsFromResult(result) {
            if (result.boundingbox) {
                return [
                    [parseFloat(result.boundingbox[2]), parseFloat(result.boundingbox[0])],
                    [parseFloat(result.boundingbox[3]), parseFloat(result.boundingbox[1])]
                ];
            }
            return null;
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
            // Store current view state
            const center = this.map.getCenter();
            const zoom = this.map.getZoom();
            const markerLocation = this.marker?.getLngLat();
            
            // Handle ArcGIS Hybrid specially
            if (basemapId === 'arcgis_hybrid') {
                this.map.setStyle(this.getHybridStyle());
                this.currentBasemap = 'arcgis_hybrid';
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
            }
            
            // Restore view state when style is loaded
            this.map.once('style.load', () => {
                this.map.setCenter(center);
                this.map.setZoom(zoom);
                this.map.addControl(new maplibregl.NavigationControl());
                
                // Restore marker if it existed
                if (markerLocation) {
                    this.marker = new maplibregl.Marker()
                        .setLngLat([markerLocation.lng, markerLocation.lat])
                        .addTo(this.map);
                }
            });
        }
    }));
    
    Alpine.data('formData', () => ({
        longitude: '',
        latitude: '',
        isValidForm: false,
        
        init() {
            // Get initial values if they exist (for validation)
            const lngInput = document.getElementById('longitude');
            const latInput = document.getElementById('latitude');
            
            if (lngInput && latInput) {
                this.longitude = lngInput.value;
                this.latitude = latInput.value;
                this.checkFormValidity();
            }

            window.addEventListener('coordinates-updated', (event) => {
                // Custom events put their data in the detail property
                const coordinates = event.detail;
                console.log('Form received coordinates:', coordinates);
                
                this.longitude = coordinates.lng;
                this.latitude = coordinates.lat;
                
                // Update hidden inputs
                document.getElementById('longitude').value = coordinates.lng;
                document.getElementById('latitude').value = coordinates.lat;
                
                this.checkFormValidity();
            });
        },
        
        checkFormValidity() {
            this.isValidForm = Boolean(this.longitude && this.latitude);
        }
    }));
});