<div class="mx-auto w-full rounded-2xl bg-white border border-gainsboro shadow-sm">
    <form 
        x-data="formData()" 
        method="POST" 
        action="{{ isset($lahan) ? route('lahan.update', $lahan->lahan_id) : route('lahan.store') }}" 
        class="w-full"
    >
        @csrf
        @if(isset($lahan))
            @method('PUT')
        @endif
        
        <div class="p-4 sm:p-6 flex flex-col gap-3 lg:gap-6 justify-between">
            <x-lahan.form-fields :lahan="$lahan ?? null" />

            <!-- Hidden Coordinate Fields with PostGIS handling -->
            @php
                $longitude = old('longitude');
                $latitude = old('latitude');
                
                if (!$longitude && isset($lahan) && $lahan->location) {
                    // Extract coordinates from PostGIS Point
                    $coordinates = DB::selectOne("SELECT ST_X(location) as lng, ST_Y(location) as lat FROM lahan WHERE lahan_id = ?", [$lahan->lahan_id]);
                    $longitude = $coordinates->lng ?? '';
                    $latitude = $coordinates->lat ?? '';
                }
            @endphp
            
            <input type="hidden" id="longitude" name="longitude" value="{{ $longitude }}" />
            <input type="hidden" id="latitude" name="latitude" value="{{ $latitude }}" />
        </div>
        
        <!-- Save Button -->
        <div class="flex items-center justify-center px-4 py-4 sm:px-6 sm:pb-6">
            <x-main.primary-button 
                type="submit" 
                class="bg-darkslategray-300 hover:bg-opacity-90"
            >
                {{ isset($lahan) ? 'Update' : 'Save' }}
            </x-main.primary-button>
        </div>
    </form>
</div>