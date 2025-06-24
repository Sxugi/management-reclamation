<div class="absolute top-4 left-4 z-10 w-64 md:w-80">
    <div class="search-box bg-transparent bg-opacity-90 rounded-md shadow-md">
        <div class="relative">
            <x-main.text-input 
                type="text" 
                id="geocoder-input"
                placeholder="Cari lokasi..." 
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
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.125 16.7188L13.2781 11.8711C14.0592 10.7977 14.4794 9.50407 14.4781 8.17656C14.4781 4.70195 11.6512 1.875 8.17656 1.875C4.70195 1.875 1.875 4.70195 1.875 8.17656C1.875 11.6512 4.70195 14.4781 8.17656 14.4781C9.50407 14.4794 10.7977 14.0592 11.8711 13.2781L16.7188 18.125L18.125 16.7188ZM8.17656 12.4879C7.32375 12.488 6.49007 12.2351 5.78095 11.7614C5.07183 11.2877 4.51913 10.6143 4.19274 9.82638C3.86635 9.0385 3.78093 8.17152 3.94728 7.33509C4.11364 6.49867 4.5243 5.73035 5.12733 5.12733C5.73035 4.5243 6.49867 4.11364 7.33509 3.94728C8.17152 3.78093 9.0385 3.86635 9.82638 4.19274C10.6143 4.51913 11.2877 5.07183 11.7614 5.78095C12.2351 6.49007 12.488 7.32375 12.4879 8.17656C12.4865 9.31959 12.0319 10.4154 11.2236 11.2236C10.4154 12.0319 9.31959 12.4865 8.17656 12.4879Z" fill="#27374D"/>
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
        class="search-results mt-1 self-stretch bg-white rounded-md shadow-lg max-h-60 overflow-y-auto"
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
</div>