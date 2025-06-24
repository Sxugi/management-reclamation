import Alpine from 'alpinejs';

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

window.Alpine = Alpine;
Alpine.start();


