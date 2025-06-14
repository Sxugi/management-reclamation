<div class="p-4 border-t border-gainsboro">
    <div class="flex flex-wrap gap-2">
        <x-main.primary-button 
            type="button" 
            @click="changeBasemap('arcgis_hybrid')" 
            x-bind:class="currentBasemap === 'arcgis_hybrid' ? 'bg-slategray-200' : ''">
            ArcGIS Hybrid
        </x-main.primary-button>
        <x-main.primary-button 
            type="button" 
            @click="changeBasemap('osm')" 
            x-bind:class="currentBasemap === 'osm' ? 'bg-slategray-200' : ''">
            OpenStreetMap
        </x-main.primary-button>
    </div>
    <p class="mt-3 text-sm text-gray-600">
        <template x-if="coordinates.lng">
            <span>Koordinat terpilih: <span class="font-medium" x-text="`${coordinates.lng.toFixed(6)}, ${coordinates.lat.toFixed(6)}`"></span></span>
        </template>
        <template x-if="!coordinates.lng">
            <span>Klik pada peta untuk memilih lokasi lahan</span>
        </template>
    </p>
</div>