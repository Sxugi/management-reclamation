import Alpine from 'alpinejs';
import * as Turbo from '@hotwired/turbo';

import maplibregl from 'maplibre-gl';
import 'maplibre-gl/dist/maplibre-gl.css';

import MaplibreGeocoder from '@maplibre/maplibre-gl-geocoder';
import '@maplibre/maplibre-gl-geocoder/dist/maplibre-gl-geocoder.css';

import MapboxDraw from '@mapbox/mapbox-gl-draw';
import '@mapbox/mapbox-gl-draw/dist/mapbox-gl-draw.css';

import * as turf from '@turf/turf';

MapboxDraw.constants.classes.CANVAS  = 'maplibregl-canvas';
MapboxDraw.constants.classes.CONTROL_BASE  = 'maplibregl-ctrl';
MapboxDraw.constants.classes.CONTROL_PREFIX = 'maplibregl-ctrl-';
MapboxDraw.constants.classes.CONTROL_GROUP = 'maplibregl-ctrl-group';
MapboxDraw.constants.classes.ATTRIBUTION = 'maplibregl-ctrl-attrib';

window.maplibregl = maplibregl;
window.MaplibreGeocoder = MaplibreGeocoder;
window.MapboxDraw = MapboxDraw;
window.turf = turf;

import './components/map-component.js';
import './components/map-plot-component.js';
import './components/date-filter.js';
import './components/file-upload.js';

// Start Turbo
Turbo.start()

// Set up Alpine.js
window.Alpine = Alpine

// Event listener for when Turbo loads a new page
document.addEventListener("turbo:load", () => {
    console.log('Turbo: Page loaded');
    
    // Restart all Alpine components on the page
    Alpine.initTree(document.body);
    
    // 1. Check if map components exist and restart them if needed
    if (window.mapComponent && document.getElementById('map')) {
        console.log('Re-initializing map component');
        // Map component will restart automatically through Alpine
    }
    
    if (window.mapPlotComponent && document.getElementById('plot-map')) {
        console.log('Re-initializing plot map component');
        // Plot map component will restart automatically through Alpine
    }
    
    // 2. Check for search/geocoder components
    if (document.querySelector('#geocoder-input')) {
        console.log('Re-initializing search components');
        // Search features will restart automatically through Alpine
    }
    
    // 3. Check for coordinate input forms
    if (document.querySelector('input[name="longitude"]') || document.querySelector('input[name="latitude"]')) {
        console.log('Re-initializing coordinate forms');
    }
    
    // 4. Find and prepare modal components
    document.querySelectorAll('[x-data*="modal"]').forEach(modal => {
        // Modals handle their own events automatically
    });
});

// Clean up before Turbo caches the page (important for performance)
document.addEventListener("turbo:before-cache", () => {
    console.log('Turbo: Cleaning up before cache');
    
    // Reset all dropdown and UI states
    document.querySelectorAll('[x-data]').forEach(el => {
        if (el.__x && el.__x.$data) {
            // Close any open dropdowns
            if ('open' in el.__x.$data) {
                el.__x.$data.open = false;
            }
            // Close any open sidebars
            if ('sidebarOpen' in el.__x.$data) {
                el.__x.$data.sidebarOpen = false;
            }
            // Close any open modals
            if ('show' in el.__x.$data) {
                el.__x.$data.show = false;
            }
        }
    });
    
    // Clean up map instances if they exist
    if (window.map && typeof window.map.remove === 'function') {
        console.log('Cleaning up map instance');
        // Map cleanup will be handled by the map component itself
    }
    
    // Clear temporary search results
    document.querySelectorAll('.search-results').forEach(el => {
        if (el.__x && el.__x.$data && 'searchResults' in el.__x.$data) {
            el.__x.$data.searchResults = [];
        }
    });
});

// Prevent double form submissions
document.addEventListener("turbo:submit-start", () => {
    console.log('Form submission started');
    // Temporarily disable all submit buttons
    document.querySelectorAll('button[type="submit"]').forEach(btn => {
        btn.disabled = true;
    });
});

document.addEventListener("turbo:submit-end", () => {
    console.log('Form submission ended');
    // Re-enable all submit buttons
    document.querySelectorAll('button[type="submit"]').forEach(btn => {
        btn.disabled = false;
    });
});

// Handle network errors
document.addEventListener("turbo:fetch-request-error", (event) => {
    console.error('Turbo fetch error:', event.detail);
});

// Start Alpine after Turbo is set up
Alpine.start()