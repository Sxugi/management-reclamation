<div 
    x-data="mapComponent({
        longitude: '{{ $longitude ?? null }}',
        latitude: '{{ $latitude ?? null }}'
    })"
    class="w-full rounded-2xl bg-white border border-gainsboro shadow-sm overflow-hidden relative"
>
    <!-- Map Container -->
    <div style="width: 100%; height: 400px;" id="map"></div>
    <x-lahan.search-box />
    <x-lahan.map-controls />
</div>