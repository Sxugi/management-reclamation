<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-0 w-full">
            <a href="{{ route('lahan.index') }}" class="inline-flex items-center px-4 py-2 bg-darkslategray-300 rounded-lg font-outfit text-sm text-white leading-5 font-medium hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                <span>Back</span>
            </a>
            <h2 class="text-xl sm:text-2xl font-medium text-darkslategray-300 font-outfit">Input Data Lahan</h2>
            <div class="hidden sm:block w-[100px]">
                <!-- Placeholder to maintain flex spacing -->
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Map Column -->
                <div 
                    x-data="mapComponent()"
                    class="w-full rounded-2xl bg-white border border-gainsboro shadow-sm overflow-hidden"
                >
                    <!-- Search control -->
                    <div class="search-box bg-white rounded-md shadow-md p-2 relative">
                        <div class="relative">
                            <input 
                                type="text" 
                                id="geocoder-input"
                                placeholder="Cari lokasi..." 
                                class="w-full py-2 pl-3 pr-10 rounded-md text-gray-700 focus:outline-none border border-gray-300 focus:border-darkslategray focus:ring-darkslategray"
                                x-ref="searchInput"
                                @keydown.enter.prevent="searchLocation($event.target.value)"
                            />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <button 
                                    type="button"
                                    class="text-gray-400 hover:text-gray-600" 
                                    @click="searchLocation($refs.searchInput.value)"
                                    x-bind:disabled="isSearching"
                                >
                                    <template x-if="!isSearching">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </template>
                                    <template x-if="isSearching">
                                        <svg class="w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </template>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div 
                        x-show="searchResults.length > 0" 
                        x-transition
                        class="search-results absolute z-50 mt-1 w-full bg-white rounded-md shadow-lg max-h-60 overflow-y-auto"
                        style="display: none;"
                    >
                        <template x-for="(result, index) in searchResults" :key="index">
                            <div 
                                class="p-2 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0"
                                @click="selectSearchResult(result)"
                            >
                                <div x-text="result.display_name" class="text-sm"></div>
                                <div class="text-xs text-gray-500" x-text="`${result.lat}, ${result.lon}`"></div>
                            </div>
                        </template>
                    </div>
                    <!-- Map Container -->
                    <div style="width: 100%; height: 400px;" id="map"></div>

                    <!-- Map Controls -->
                    <div class="p-4 border-t border-gainsboro">
                        <div class="flex flex-wrap gap-2">
                            <button type="button" @click="changeBasemap('arcgis_hybrid')" class="px-3 py-1.5 text-sm font-medium rounded-md bg-darkslategray-300 text-white hover:bg-opacity-90 transition">
                                ArcGIS Hybrid
                            </button>
                            <button type="button" @click="changeBasemap('arcgis')" class="px-3 py-1.5 text-sm font-medium rounded-md bg-gray-200 text-gray-800 hover:bg-gray-300 transition">
                                Satelit
                            </button>
                            <button type="button" @click="changeBasemap('osm')" class="px-3 py-1.5 text-sm font-medium rounded-md bg-gray-200 text-gray-800 hover:bg-gray-300 transition">
                                OpenStreetMap
                            </button>
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
                </div>

                <!-- Form Column -->
                <div class="mx-auto w-full rounded-2xl bg-white border border-gainsboro shadow-sm">
                    <form 
                        x-data="formData()" 
                        method="POST" 
                        action="{{ route('lahan.store') }}" 
                        class="w-full"
                    >
                        @csrf
                        
                        <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
                            <!-- Nama Lahan -->
                            <div class="space-y-1.5">
                                <x-main.input-label for="nama_lahan" :value="__('Nama Lahan')" class="text-darkslategray-200" />
                                <div>
                                    <x-main.text-input 
                                        id="nama_lahan"
                                        name="nama_lahan"
                                        type="text"
                                        class="block w-full"
                                        :value="old('nama_lahan')"
                                        required
                                        placeholder="Masukkan nama lahan"
                                    />
                                </div>
                                <x-main.input-error :messages="$errors->get('nama_lahan')" class="mt-2" />
                            </div>

                            <!-- Luas Lahan -->
                            <div class="space-y-1.5">
                                <x-main.input-label for="luas_lahan" :value="__('Luas Lahan (ha)')" class="text-darkslategray-200" />
                                <div>
                                    <x-main.text-input
                                        id="luas_lahan"
                                        name="luas_lahan"
                                        type="number"
                                        step="0.01"
                                        class="block w-full"
                                        :value="old('luas_lahan')"
                                        required
                                        placeholder="0.00"
                                    />
                                </div>
                                <x-main.input-error :messages="$errors->get('luas_lahan')" class="mt-2" />
                            </div>

                            <!-- Periode Tahun -->
                            <div class="space-y-1.5">
                                <x-main.input-label :value="__('Periode Tahun')" class="text-darkslategray-200" />
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- Tahun Awal -->
                                    <div>
                                        <select
                                            name="tahun_awal"
                                            id="tahun_awal"
                                            class="border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm block w-full"
                                            required
                                        >
                                            <option value="" disabled {{ old('tahun_awal') ? '' : 'selected' }}>Tahun Awal</option>
                                            @for ($year = date('Y') - 5; $year <= date('Y') + 5; $year++)
                                                <option value="{{ $year }}" {{ old('tahun_awal') == $year ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endfor
                                        </select>
                                        <x-main.input-error :messages="$errors->get('tahun_awal')" class="mt-2" />
                                    </div>
                                    
                                    <!-- Tahun Akhir -->
                                    <div>
                                        <select
                                            name="tahun_akhir"
                                            id="tahun_akhir"
                                            class="border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm block w-full"
                                            required
                                        >
                                            <option value="" disabled {{ old('tahun_akhir') ? '' : 'selected' }}>Tahun Akhir</option>
                                            @for ($year = date('Y'); $year <= date('Y') + 10; $year++)
                                                <option value="{{ $year }}" {{ old('tahun_akhir') == $year ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endfor
                                        </select>
                                        <x-main.input-error :messages="$errors->get('tahun_akhir')" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <!-- PIC Reklamasi -->
                            <div class="space-y-1.5">
                                <x-main.input-label for="pic_reklamasi" :value="__('PIC Reklamasi')" class="text-darkslategray-200" />
                                <div>
                                    <x-main.text-input
                                        id="pic_reklamasi"
                                        name="pic_reklamasi"
                                        type="text"
                                        class="block w-full"
                                        :value="old('pic_reklamasi')"
                                        required
                                        placeholder="Masukkan nama PIC"
                                    />
                                </div>
                                <x-main.input-error :messages="$errors->get('pic_reklamasi')" class="mt-2" />
                            </div>

                            <!-- Hidden Coordinate Fields -->
                            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}" />
                            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}" />
                            <x-main.input-error :messages="$errors->get('longitude')" class="mt-2" />
                            <x-main.input-error :messages="$errors->get('latitude')" class="mt-2" />
                        </div>
                        
                        <!-- Save Button -->
                        <div class="flex items-center justify-center px-4 py-4 sm:px-6 sm:pb-6">
                            <x-main.primary-button 
                                type="submit" 
                                class="bg-darkslategray-300 hover:bg-opacity-90 w-full sm:w-auto"
                                x-bind:disabled="!isValidForm"
                            >
                                Save
                            </x-main.primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>