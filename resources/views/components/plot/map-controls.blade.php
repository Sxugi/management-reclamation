<!-- Map Container -->
<div class="self-stretch rounded-2xl overflow-hidden flex flex-col items-center justify-start">
    <div id="plot-map" 
        class="w-full h-[394px] relative" 
        x-data="mapPlotComponent({
            polygons: window.plotData || [],
            longitude: {{ $lahan->location->getX() ?? 'null' }},
            latitude: {{ $lahan->location->getY() ?? 'null' }},
            enableDraw: {{ $enableDraw ? 'true' : 'false' }},
        })"
        x-init="
            $nextTick(() => {
                polygons = window.plotData || [];
            });
            
            document.addEventListener('turbo:before-visit', () => {
                window.plotData = null;
            });
        "
        >
        
        <!-- Map Controls -->
        <div class="absolute top-2 left-2 bg-white rounded-md shadow-sm p-2 z-10">
            <x-main.primary-button type="button" @click="changeBasemap('arcgis_hybrid')" 
                x-bind:class="currentBasemap === 'arcgis_hybrid' ? 'bg-slategray-200' : ''">
                ArcGIS Hybrid
            </x-main.primary-button>
            <x-main.primary-button type="button" @click="changeBasemap('osm')" 
                x-bind:class="currentBasemap === 'osm' ? 'bg-slategray-200' : ''">
                OpenStreetMap
            </x-main.primary-button>
        </div>
    </div>
</div>

@if(isset($plot))
    <script>
        window.plotData = @json($plot instanceof \Illuminate\Support\Collection ? $plot : [$plot]);
    </script>
@endif