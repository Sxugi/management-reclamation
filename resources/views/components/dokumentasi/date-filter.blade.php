<div class="flex flex-row items-start justify-start gap-3">
    <form method="GET" action="{{ route('lahan.dokumentasi.index', $lahan) }}" id="dateFilterForm">
        <div class="rounded-lg bg-white border-lightgray border-solid border-[1px] overflow-hidden flex flex-row items-center justify-center py-3 px-4 gap-2 text-darkslategray-100 cursor-pointer" 
             data-date-filter-toggle 
             onclick="toggleDatePicker()">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6.66667 1.66667V5" stroke="#374151" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M13.3333 1.66667V5" stroke="#374151" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M2.5 8.33333H17.5" stroke="#374151" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M17.5 3.33333H2.5C1.39543 3.33333 0.5 4.22876 0.5 5.33333V16.6667C0.5 17.7712 1.39543 18.6667 2.5 18.6667H17.5C18.6046 18.6667 19.5 17.7712 19.5 16.6667V5.33333C19.5 4.22876 18.6046 3.33333 17.5 3.33333Z" stroke="#374151" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <div class="relative leading-5 font-medium" id="dateRangeDisplay">
                @if(request('start_date') && request('end_date'))
                    {{ \Carbon\Carbon::parse(request('start_date'))->format('d M') }} - {{ \Carbon\Carbon::parse(request('end_date'))->format('d M Y') }}
                @else
                    Pilih Tanggal
                @endif
            </div>
        </div>

        <div id="datePicker" class="absolute z-50 mt-2 p-4 bg-white border border-gray-300 rounded-lg shadow-lg hidden" style="min-width: 300px;">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-main.input-label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</x-main.input-label>
                    <x-main.text-input type="date" 
                           name="start_date" 
                           id="start_date" 
                           value="{{ request('start_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                </div>
                <div>
                    <x-main.input-label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</x-main.input-label>
                    <x-main.text-input type="date" 
                           name="end_date" 
                           id="end_date" 
                           value="{{ request('end_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                </div>
            </div>
            <div class="flex justify-between mt-4">
                <x-main.primary-button type="button" 
                        onclick="clearDateFilter()" 
                        class="px-4 py-2">
                    Clear
                </x-main.primary-button>
                <div class="space-x-2">
                    <x-main.primary-button type="button" 
                            onclick="closeDatePicker()" 
                            class="px-4 py-2">
                        Cancel
                    </x-main.primary-button>
                    <x-main.primary-button type="submit" 
                            class="px-4 py-2">
                        Apply
                    </x-main.primary-button>
                </div>
            </div>
        </div>
    </form>
</div>
