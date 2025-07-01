@props(['initialPoints' => [], 'lahan' => null, 'plot' => null])
<div class="self-stretch rounded-2xl flex flex-col items-center justify-start text-darkgray">
    <div class="self-stretch flex flex-row items-center justify-between gap-1 mb-6">
        <div class="flex-1 flex flex-col items-start justify-start gap-1 text-lg text-gray">
            <div class="self-stretch relative leading-7 font-semibold text-darkslategray font-outfit">Input Koordinat</div>
            <div class="self-stretch relative text-sm leading-5 text-slategray font-outfit">Minimal 3 titik koordinat diperlukan untuk membuat polygon.</div>
        </div>
        <x-main.primary-button type="button" @click="addPoint()">
            <div class="relative leading-5 font-medium">Tambah Titik</div>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
        </x-main.primary-button>
    </div>

    <!-- Points Container -->
    <div class="w-full">
        <template x-for="(point, index) in points" :key="index">
            <div class="self-stretch flex flex-col items-start justify-start p-6">
                <div class="self-stretch h-11 flex flex-row items-center justify-center">
                    <div class="flex-1 flex flex-row items-center justify-start gap-1.5">
                        <!-- Point Number -->
                        <b class="w-[33px] relative text-2xl leading-5 flex text-darkslategray-100 text-center items-center justify-center h-11 shrink-0" x-text="index + 1"></b>
                        
                        <!-- Latitude Input -->
                        <div class="flex-1 rounded-lg bg-white border-lightgray border-solid border-[1px] box-border h-11 overflow-hidden flex flex-row items-center justify-start">
                            <x-main.text-input type="text" 
                                x-model="point.lat" 
                                class="w-full h-full focus:outline-none"
                                placeholder="Latitude"
                                @input="if(point.lat && point.lng && !isNaN(point.lat) && !isNaN(point.lng)) updateMapFromInputs()" />
                        </div>

                        <!-- Longitude Input -->
                        <div class="flex-1 rounded-lg bg-white border-lightgray border-solid border-[1px] box-border h-11 overflow-hidden flex flex-row items-center justify-start">
                            <x-main.text-input type="text" 
                                x-model="point.lng" 
                                class="w-full h-full focus:outline-none"
                                placeholder="Longitude"
                                @input="if(point.lat && point.lng && !isNaN(point.lat) && !isNaN(point.lng)) updateMapFromInputs()" />
                        </div>
                        
                        <!-- Delete Button -->
                        <button type="button" @click="removePoint(index)" class="border-none bg-transparent cursor-pointer">
                            <svg width="20" height="20" viewBox="0 0 25 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.54464 1.50254L7.14286 2.3125H1.78571C0.797991 2.3125 0 3.12246 0 4.125C0 5.12754 0.797991 5.9375 1.78571 5.9375H23.2143C24.202 5.9375 25 5.12754 25 4.125C25 3.12246 24.202 2.3125 23.2143 2.3125H17.8571L17.4554 1.50254C17.154 0.885156 16.5346 0.5 15.8594 0.5H9.14062C8.4654 0.5 7.84598 0.885156 7.54464 1.50254ZM23.2143 7.75H1.78571L2.96875 26.9512C3.05804 28.3842 4.22991 29.5 5.64174 29.5H19.3583C20.7701 29.5 21.942 28.3842 22.0312 26.9512L23.2143 7.75Z" fill="#F24822"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Submit Button -->
    <div class="flex justify-center p-6">
        <x-main.primary-button type="submit">
            <div class="relative leading-5 font-medium">
                {{ isset($plot) ? 'Update' : 'Save' }}
            </div>
        </x-main.primary-button>
    </div>
</div>