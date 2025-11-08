<div class="relative inline-block">
    <a class="rounded-lg bg-darkslategray overflow-hidden flex flex-row items-center justify-center py-3 px-4 gap-2 !text-white no-underline hover:bg-slategray-200 cursor-pointer"
        data-chart-filter-toggle
        onclick="toggleDashboardChartFilter()">
        <span class="relative leading-5 font-medium">Chart Options</span>
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7.91699 10.7754C9.49286 10.7754 10.812 11.8731 11.1523 13.3457H17.708C18.1219 13.346 18.4578 13.6818 18.458 14.0957C18.458 14.5098 18.122 14.8454 17.708 14.8457H11.1523C10.8122 16.3185 9.49308 17.417 7.91699 17.417C6.3411 17.4168 5.0226 16.3183 4.68262 14.8457H2.29102L2.21484 14.8418C1.83657 14.8035 1.54004 14.4841 1.54004 14.0957C1.54022 13.7075 1.83668 13.3879 2.21484 13.3496L2.29102 13.3457H4.68262C5.02284 11.8733 6.34133 10.7756 7.91699 10.7754ZM7.91699 12.2754C6.91159 12.2756 6.09668 13.0912 6.09668 14.0967C6.09714 15.1018 6.91187 15.9167 7.91699 15.917C8.92232 15.917 9.73782 15.1019 9.73828 14.0967C9.73828 13.0911 8.92261 12.2754 7.91699 12.2754ZM12.083 2.58301C13.6588 2.58322 14.9772 3.68177 15.3174 5.1543H17.707L17.7832 5.1582C18.1615 5.19654 18.457 5.51592 18.457 5.9043C18.4568 6.29252 18.1614 6.61208 17.7832 6.65039L17.707 6.6543H15.3174C14.9773 8.1268 13.6588 9.2244 12.083 9.22461C10.507 9.22461 9.18787 8.12694 8.84766 6.6543H2.29004C1.87595 6.6543 1.54024 6.31834 1.54004 5.9043C1.54004 5.49008 1.87583 5.1543 2.29004 5.1543H8.84766C9.18793 3.68162 10.5071 2.58301 12.083 2.58301ZM12.083 4.08301C11.0775 4.08301 10.2619 4.89883 10.2617 5.9043C10.262 6.90971 11.0775 7.72461 12.083 7.72461C13.0883 7.72436 13.9031 6.90955 13.9033 5.9043C13.9031 4.89898 13.0883 4.08325 12.083 4.08301Z" fill="white"/>
        </svg>
    </a>

    <div id="dashboardChartFilterPanel" class="absolute right-0 z-50 mt-2 p-4 bg-white border border-gray-300 rounded-lg shadow-lg hidden" style="min-width:350px;">
        <div class="space-y-3">
            <div>
                <h4 class="text-sm font-medium text-darkslategray mb-3">Basic Settings</h4>
                
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <x-main.input-label for="chart-view-filter" class="block text-sm font-medium text-gray-700 mb-1">Chart View</x-main.input-label>
                        <select id="chart-view-filter" class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit flex-1 leading-5 bg-white">
                            <option value="overall">Overall</option>
                            <option value="indicator">Indikator</option>
                        </select>
                    </div>

                    <div>
                        <x-main.input-label for="chart-period-filter" class="block text-sm font-medium text-gray-700 mb-1">Periode Waktu</x-main.input-label>
                        <select id="chart-period-filter" class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit flex-1 leading-5 bg-white">
                            <option value="7days">7 Hari</option>
                            <option value="30days" selected>30 Hari</option>
                            <option value="90days">90 Hari</option>
                            <option value="1year">1 Tahun</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Overall Mode Controls -->
            <div id="overall-controls" class="bg-blue-50 rounded-lg p-3">
                <h4 class="text-sm font-medium text-darkslategray mb-3">Overall View Settings</h4>
                
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="show-individual-blocks-filter" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                    <x-main.input-label for="show-individual-blocks-filter" class="text-sm font-medium text-gray-700 mb-0">Blok Individu</x-main.input-label>
                </div>
                <p class="text-xs text-gray-500 mt-1">Menampilkan progres untuk setiap blok secara terpisah</p>
            </div>

            <!-- Indicator Mode Controls -->
            <div id="indicator-controls" class="bg-green-50 rounded-lg p-3 hidden">
                <h4 class="text-sm font-medium text-darkslategray mb-3">Indikator View Settings</h4>
                
                <div class="space-y-3">
                    <div>
                        <x-main.input-label for="indicator-selector-filter" class="block text-sm font-medium text-gray-700 mb-1">Indikator</x-main.input-label>
                        <select id="indicator-selector-filter" class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit flex-1 leading-5 bg-white">
                            <option value="">Pilih Indikator</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Pilih indikator untuk lihat progres indikator</p>
                        <div id="indicator-helper" class="mt-2 p-2 bg-blue-50 border border-blue-200 rounded text-xs text-blue-700 hidden">
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <div class="font-medium">Tips for Indicator Analysis:</div>
                                    <ul class="mt-1 list-disc list-inside space-y-1">
                                        <li>Pastikan indikator memiliki target yang sudah ditetapkan</li>
                                        <li>Data progres harus sudah diinput untuk periode yang dipilih</li>
                                        <li>Pilih blok spesifik untuk analisis detail per area</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="block-selector-container" class="hidden">
                        <x-main.input-label for="block-selector-filter" class="block text-sm font-medium text-gray-700 mb-1">Blok</x-main.input-label>
                        <select id="block-selector-filter" class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit flex-1 leading-5 bg-white">
                            <option value="all">Select Option</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Menampilkan progres indikator untuk spesifik blok</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between pt-2">
                <x-main.primary-button type="button" onclick="resetDashboardChartFilter()" class="px-4 py-2">
                    Reset
                </x-main.primary-button>
                <x-main.primary-button type="button" onclick="closeDashboardChartFilter()" class="px-4 py-2">
                    Close
                </x-main.primary-button>
            </div>
        </div>
    </div>
</div>
