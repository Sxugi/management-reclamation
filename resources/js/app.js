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

import './components/lahan/map-component.js';
import './components/plot/map-plot-component.js';
import './components/dokumentasi/date-filter.js';
import './components/dokumentasi/file-upload.js';
import './components/gudang/panel-filter.js';
import './components/gudang/detail-modal.js';

// Start Turbo
Turbo.start()

// Set up Alpine.js
window.Alpine = Alpine

document.addEventListener("turbo:load", () => {
    Alpine.initTree(document.body);
});

document.addEventListener("turbo:before-cache", () => {
    document.querySelectorAll('[x-data]').forEach(el => {
        if (el.__x && el.__x.$data) {
            if ('open' in el.__x.$data) el.__x.$data.open = false;
            if ('show' in el.__x.$data) el.__x.$data.show = false;
            if ('sidebarOpen' in el.__x.$data) el.__x.$data.sidebarOpen = false;
        }
    });
});

Alpine.start()