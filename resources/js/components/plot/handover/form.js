/**
 * Configuration constants for the handover form
 */
const CONFIG = {
    MAX_FILE_SIZE: {
        SURAT: 10, // MB
        PETA: 20   // MB
    },
    GEOCODING: {
        API_URL: 'https://nominatim.openstreetmap.org/reverse',
        LANGUAGE: 'id',
        ZOOM_LEVEL: 18
    },
    SELECTORS: {
        FORM: '[data-handover-form]',
        SURAT_INPUT: '#surat-files',
        PETA_INPUT: '#peta-files',
        SURAT_PREVIEW: '#surat-preview',
        PETA_PREVIEW: '#peta-preview',
        LOCATION_BTN: '#fetch-location-btn',
        LOCATION_FIELD: '#lokasi',
        LOCATION_STATUS: '#location-status',
        POLYGON_INPUT: '#plot-polygon'
    }
};

/**
 * Initialize form functionalities on page load
 */
document.addEventListener('turbo:load', initializeHandoverForm);

function initializeHandoverForm() {
    const form = document.querySelector(CONFIG.SELECTORS.FORM);
    if (!form) return;

    initializeFileUploads();
    initializeLocationAutoFill();
}

/**
 * File Upload Handlers
 */
function initializeFileUploads() {
    const suratInput = document.querySelector(CONFIG.SELECTORS.SURAT_INPUT);
    const petaInput = document.querySelector(CONFIG.SELECTORS.PETA_INPUT);
    
    if (suratInput) {
        suratInput.addEventListener('change', (e) => {
            handleFileSelection(e.target, CONFIG.SELECTORS.SURAT_PREVIEW, CONFIG.MAX_FILE_SIZE.SURAT);
        });
    }
    
    if (petaInput) {
        petaInput.addEventListener('change', (e) => {
            handleFileSelection(e.target, CONFIG.SELECTORS.PETA_PREVIEW, CONFIG.MAX_FILE_SIZE.PETA);
        });
    }
}

/**
 * Handle file selection and render previews
 */
function handleFileSelection(input, previewSelector, maxSizeMB) {
    const files = Array.from(input.files);
    renderFilePreview(files, previewSelector, maxSizeMB, input.id);
}

function renderFilePreview(files, previewSelector, maxSizeMB, inputId) {
    const previewContainer = document.querySelector(previewSelector);
    if (!previewContainer) return;

    previewContainer.innerHTML = '';
    const maxSizeBytes = maxSizeMB * 1024 * 1024;

    files.forEach((file, index) => {
        const fileItem = createFilePreviewItem(file, maxSizeBytes, inputId, index, previewSelector);
        previewContainer.appendChild(fileItem);
    });
}

function createFilePreviewItem(file, maxSizeBytes, inputId, index, previewSelector) {
    const isValid = file.size <= maxSizeBytes;
    const fileSize = formatFileSize(file.size);
    
    const div = document.createElement('div');
    div.className = `flex items-center justify-between p-3 rounded-lg border ${
        isValid ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200'
    }`;
    
    div.innerHTML = `
        <div class="flex items-center gap-3 flex-1 min-w-0">
            ${getFileIcon(isValid)}
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">${escapeHtml(file.name)}</p>
                <p class="text-xs ${isValid ? 'text-green-600' : 'text-red-600'}">
                    ${fileSize} ${isValid ? '' : '- Ukuran file terlalu besar!'}
                </p>
            </div>
        </div>
        <button 
            type="button" 
            onclick="removeFileFromInput('${inputId}', ${index}, '${previewSelector}')" 
            class="text-red-500 hover:text-red-700 p-1"
            aria-label="Hapus file"
        >
            ${getDeleteIcon()}
        </button>
    `;
    
    return div;
}

function getFileIcon(isValid) {
    const colorClass = isValid ? 'text-green-600' : 'text-red-600';
    return `
        <svg class="w-5 h-5 flex-shrink-0 ${colorClass}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
    `;
}

function getDeleteIcon() {
    return `
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    `;
}

/**
 * Remove a file from the input element and update the preview
 */
window.removeFileFromInput = function(inputId, indexToRemove, previewSelector) {
    const input = document.getElementById(inputId);
    if (!input) return;

    const dataTransfer = new DataTransfer();
    const files = Array.from(input.files);
    
    files.forEach((file, index) => {
        if (index !== indexToRemove) {
            dataTransfer.items.add(file);
        }
    });
    
    input.files = dataTransfer.files;
    input.dispatchEvent(new Event('change', { bubbles: true }));
};

/** 
 * Format file size in human-readable form
 */
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return `${Math.round((bytes / Math.pow(k, i)) * 100) / 100} ${sizes[i]}`;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

/**
 * Location Auto-Fill Handlers
 */
function initializeLocationAutoFill() {
    const fetchButton = document.querySelector(CONFIG.SELECTORS.LOCATION_BTN);
    const locationField = document.querySelector(CONFIG.SELECTORS.LOCATION_FIELD);
    
    if (!fetchButton) return;

    fetchButton.addEventListener('click', (e) => {
        e.preventDefault();
        fetchLocationFromPolygon();
    });

    // Auto-fill on load if field is empty
    if (locationField && !locationField.value.trim()) {
        fetchLocationFromPolygon();
    }
}

/** 
 * Fetch location data based on polygon input
 */
async function fetchLocationFromPolygon() {
    const elements = getLocationElements();
    if (!elements.polygonInput || !elements.locationField) return;

    const polygonData = elements.polygonInput.value;
    if (!polygonData) {
        updateLocationStatus('Polygon tidak ditemukan', 'error');
        return;
    }

    try {
        setLoadingState(elements.button, true);
        updateLocationStatus('Mengambil lokasi...', 'loading');

        const coordinates = parsePolygonCoordinates(polygonData);
        if (!coordinates || coordinates.length === 0) {
            throw new Error('Format polygon tidak valid');
        }

        const centerPoint = calculateCentroid(coordinates);
        const locationData = await reverseGeocode(centerPoint);
        
        elements.locationField.value = locationData.display_name;
        updateLocationStatus(
            `✓ Lokasi berhasil diisi (${centerPoint.lat.toFixed(6)}, ${centerPoint.lng.toFixed(6)})`,
            'success'
        );

    } catch (error) {
        console.error('Error fetching location:', error);
        updateLocationStatus(`✗ ${error.message}. Silakan isi manual.`, 'error');
    } finally {
        setLoadingState(elements.button, false);
    }
}

/** 
 * Get DOM elements related to location auto-fill
 */
function getLocationElements() {
    return {
        polygonInput: document.querySelector(CONFIG.SELECTORS.POLYGON_INPUT),
        locationField: document.querySelector(CONFIG.SELECTORS.LOCATION_FIELD),
        statusElement: document.querySelector(CONFIG.SELECTORS.LOCATION_STATUS),
        button: document.querySelector(CONFIG.SELECTORS.LOCATION_BTN)
    };
}

/**
 * Set loading state for the fetch button
 */
function setLoadingState(button, isLoading) {
    if (!button) return;
    
    button.disabled = isLoading;
    button.textContent = isLoading ? 'Memuat...' : 'Isi Otomatis';
}

/**
 * Update location status message
 */
function updateLocationStatus(message, type) {
    const statusElement = document.querySelector(CONFIG.SELECTORS.LOCATION_STATUS);
    if (!statusElement) return;

    const statusClasses = {
        loading: 'text-xs text-blue-500',
        success: 'text-xs text-green-600',
        error: 'text-xs text-red-500'
    };

    statusElement.textContent = message;
    statusElement.className = statusClasses[type] || 'text-xs text-gray-500';
}

/** 
 * Reverse geocode to get location details from coordinates
 */
async function reverseGeocode(centerPoint) {
    const url = new URL(CONFIG.GEOCODING.API_URL);
    url.searchParams.append('format', 'json');
    url.searchParams.append('lat', centerPoint.lat);
    url.searchParams.append('lon', centerPoint.lng);
    url.searchParams.append('zoom', CONFIG.GEOCODING.ZOOM_LEVEL);
    url.searchParams.append('addressdetails', '1');

    const response = await fetch(url, {
        headers: {
            'Accept-Language': CONFIG.GEOCODING.LANGUAGE
        }
    });

    if (!response.ok) {
        throw new Error('Gagal mengambil data lokasi');
    }

    const data = await response.json();
    
    if (!data || !data.display_name) {
        throw new Error('Lokasi tidak ditemukan');
    }

    return data;
}

/**
 * Parse polygon coordinates from various formats
 */
function parsePolygonCoordinates(polygonString) {
    const parsers = [
        parseGeoJSON,
        parsePostGISWKT,
        parseCoordinateArray
    ];

    for (const parser of parsers) {
        try {
            const result = parser(polygonString);
            if (result && result.length > 0) {
                return result;
            }
        } catch (error) {
            continue;
        }
    }

    console.error('Could not parse polygon format:', polygonString);
    return null;
}

function parseGeoJSON(polygonString) {
    const parsed = JSON.parse(polygonString);
    
    // Standard GeoJSON Polygon
    if (parsed.type === 'Polygon' && parsed.coordinates) {
        return parsed.coordinates[0].map(coord => ({
            lng: coord[0],
            lat: coord[1]
        }));
    }
    
    // GeoJSON FeatureCollection
    if (parsed.type === 'FeatureCollection' && parsed.features?.length > 0) {
        const feature = parsed.features[0];
        if (feature.geometry?.type === 'Polygon') {
            return feature.geometry.coordinates[0].map(coord => ({
                lng: coord[0],
                lat: coord[1]
            }));
        }
    }
    
    // Array of coordinate objects
    if (Array.isArray(parsed) && parsed.length > 0) {
        const first = parsed[0];
        
        if (first.lat !== undefined && first.lng !== undefined) {
            return parsed;
        }
        
        if (first.latitude !== undefined && first.longitude !== undefined) {
            return parsed.map(p => ({ 
                lat: p.latitude, 
                lng: p.longitude 
            }));
        }
    }
    
    return null;
}

function parsePostGISWKT(polygonString) {
    // PostGIS WKT format: SRID=4326;POLYGON((lng lat, lng lat, ...))
    if (!polygonString.includes('POLYGON')) {
        return null;
    }

    const coordsMatch = polygonString.match(/POLYGON\s*\(\(([^)]+)\)\)/i);
    if (!coordsMatch) {
        return null;
    }

    const coordPairs = coordsMatch[1].split(',');
    return coordPairs
        .map(pair => {
            const [lng, lat] = pair.trim().split(/\s+/).map(parseFloat);
            return { lat, lng };
        })
        .filter(coord => !isNaN(coord.lat) && !isNaN(coord.lng));
}

function parseCoordinateArray(polygonString) {
    if (!polygonString.trim().startsWith('[')) {
        return null;
    }

    // Remove SRID if present
    const cleanString = polygonString.replace(/SRID=\d+;?/gi, '').trim();
    
    // Extract coordinate pairs: [[lng, lat], ...]
    const coordMatches = cleanString.match(/\[([^\]]+)\]/g);
    if (!coordMatches) {
        return null;
    }

    return coordMatches
        .map(match => {
            const values = match
                .replace(/[\[\]]/g, '')
                .split(',')
                .map(v => parseFloat(v.trim()));
            
            if (values.length >= 2) {
                return { lng: values[0], lat: values[1] };
            }
            return null;
        })
        .filter(coord => coord && !isNaN(coord.lat) && !isNaN(coord.lng));
}

/** 
 * Calculate centroid of polygon coordinates
 */
function calculateCentroid(coordinates) {
    const sum = coordinates.reduce((acc, coord) => ({
        lat: acc.lat + parseFloat(coord.lat),
        lng: acc.lng + parseFloat(coord.lng)
    }), { lat: 0, lng: 0 });

    const count = coordinates.length;
    
    return {
        lat: sum.lat / count,
        lng: sum.lng / count
    };
}