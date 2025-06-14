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