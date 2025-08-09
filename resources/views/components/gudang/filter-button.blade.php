@props(['lahan'])

<div class="relative inline-block gap-2">
    <a class="rounded-lg bg-darkslategray overflow-hidden flex flex-row items-center justify-center py-3 px-4 gap-2 !text-white no-underline hover:bg-slategray-200 cursor-pointer"
        data-filter-panel-toggle
        onclick="toggleGudangFilterPanel()">
        <span class="relative leading-5 font-medium">Filter</span>
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7.91699 10.7754C9.49286 10.7754 10.812 11.8731 11.1523 13.3457H17.708C18.1219 13.346 18.4578 13.6818 18.458 14.0957C18.458 14.5098 18.122 14.8454 17.708 14.8457H11.1523C10.8122 16.3185 9.49308 17.417 7.91699 17.417C6.3411 17.4168 5.0226 16.3183 4.68262 14.8457H2.29102L2.21484 14.8418C1.83657 14.8035 1.54004 14.4841 1.54004 14.0957C1.54022 13.7075 1.83668 13.3879 2.21484 13.3496L2.29102 13.3457H4.68262C5.02284 11.8733 6.34133 10.7756 7.91699 10.7754ZM7.91699 12.2754C6.91159 12.2756 6.09668 13.0912 6.09668 14.0967C6.09714 15.1018 6.91187 15.9167 7.91699 15.917C8.92232 15.917 9.73782 15.1019 9.73828 14.0967C9.73828 13.0911 8.92261 12.2754 7.91699 12.2754ZM12.083 2.58301C13.6588 2.58322 14.9772 3.68177 15.3174 5.1543H17.707L17.7832 5.1582C18.1615 5.19654 18.457 5.51592 18.457 5.9043C18.4568 6.29252 18.1614 6.61208 17.7832 6.65039L17.707 6.6543H15.3174C14.9773 8.1268 13.6588 9.2244 12.083 9.22461C10.507 9.22461 9.18787 8.12694 8.84766 6.6543H2.29004C1.87595 6.6543 1.54024 6.31834 1.54004 5.9043C1.54004 5.49008 1.87583 5.1543 2.29004 5.1543H8.84766C9.18793 3.68162 10.5071 2.58301 12.083 2.58301ZM12.083 4.08301C11.0775 4.08301 10.2619 4.89883 10.2617 5.9043C10.262 6.90971 11.0775 7.72461 12.083 7.72461C13.0883 7.72436 13.9031 6.90955 13.9033 5.9043C13.9031 4.89898 13.0883 4.08325 12.083 4.08301Z" fill="white"/>
        </svg>
    </a>

    <div id="gudangFilterPanel" class="absolute right-0 z-50 mt-2 p-4 bg-white border border-gray-300 rounded-lg shadow-lg hidden">
        <form id="gudangFilterForm" method="GET" action="{{ route('lahan.gudang.index', $lahan->lahan_id) }}" class="space-y-3">
            <div class="flex justify-between gap-2">
                <div class="flex flex-col w-1/2">
                    <x-main.input-label for="startDate" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</x-main.input-label>
                    <x-main.text-input type="date" id="startDate" name="startDate"
                           value="{{ request('startDate') }}"
                           class="border rounded p-2 text-sm"/>
                </div>
                <div class="flex flex-col w-1/2">
                    <x-main.input-label for="endDate" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</x-main.input-label>
                    <x-main.text-input type="date" id="endDate" name="endDate"
                           value="{{ request('endDate') }}"
                           class="border rounded p-2 text-sm"/>
                </div>
            </div>

            <div>
                <x-main.input-label for="jenisBarang" class="block text-sm font-medium text-gray-700 mb-1">Jenis Barang</x-main.input-label>
                <select id="jenisBarang" name="jenisBarang" placeholder="Jenis Barang" class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit flex-1 leading-5 bg-transparent">
                    <option value="">Select Option</option>
                    <option value="Pupuk" @selected(request('jenisBarang') === 'Pupuk')>Pupuk</option>
                    <option value="Pestisida" @selected(request('jenisBarang') === 'Pestisida')>Pestisida</option>
                    <option value="Benih" @selected(request('jenisBarang') === 'Benih')>Benih</option>
                    <option value="Alat Pertanian" @selected(request('jenisBarang') === 'Alat Pertanian')>Alat Pertanian</option>
                    <option value="Lainnya" @selected(request('jenisBarang') === 'Lainnya')>Lainnya</option>
                </select>
            </div>

            <div>
                <x-main.input-label for="statusBarang" class="block text-sm font-medium text-gray-700 mb-1">Status Barang</x-main.input-label>
                <select id="statusBarang" name="statusBarang" class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit flex-1 leading-5 bg-transparent">
                    <option value="">Select Option</option>
                    <option value="Tersedia" @selected(request('statusBarang') === 'Tersedia')>Tersedia</option>
                    <option value="Kosong" @selected(request('statusBarang') === 'Kosong')>Kosong</option>
                    <option value="Rusak" @selected(request('statusBarang') === 'Rusak')>Rusak</option>
                    <option value="Digunakan" @selected(request('statusBarang') === 'Digunakan')>Digunakan</option>
                </select>
            </div>

            <div class="flex justify-between pt-2">
                <x-main.primary-button type="button" onclick="clearGudangFilters()" class="px-4 py-2">Clear</x-main.primary-button>
                <div class="flex gap-2">
                    <x-main.primary-button type="button" onclick="closeGudangFilterPanel()" class="px-4 py-2">Cancel</x-main.primary-button>
                    <x-main.primary-button type="submit" class="px-4 py-2">Apply</x-main.primary-button>
                </div>
            </div>
        </form>
    </div>
</div>
