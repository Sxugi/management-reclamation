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
        @if(!request()->routeIs(['*.create', '*.edit']))
        <div class="absolute bottom-10 right-2 bg-white/90 backdrop-blur-md p-1.5 rounded-lg shadow-lg border border-gray-200 z-10 text-sm max-w-3xs font-outfit">
            <h4 class="font-bold text-gray-700 mb-2 border-b pb-1 text-xs uppercase tracking-wider">Keterangan</h4>
            
            <div class="flex items-center mb-2">
                <div class="w-5 h-5 mr-1 border-2 border-[#3388ff] bg-[#3388ff]/20 rounded-sm"></div>
                <span class="text-gray-600 text-xs font-medium">Area Plot</span>
            </div>

            <div class="flex items-center">
                <div class="w-5 h-5 mr-1 border-2 border-[#f03] bg-[#f03]/30 rounded-sm"></div>
                <span class="text-gray-600 text-xs font-medium">Plot Terpilih</span>
            </div>
        </div>
        @endif
    </div>
</div>

@if(isset($plot))
    <script>
        window.plotData = @json($plot instanceof \Illuminate\Support\Collection ? $plot : [$plot]);
    </script>
@endif