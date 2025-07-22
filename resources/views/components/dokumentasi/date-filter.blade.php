<div class="flex flex-row items-start justify-start gap-2">
    <form method="GET" action="{{ route('lahan.dokumentasi.index', $lahan) }}" id="dateFilterForm">
        <div class="rounded-lg bg-white border-lightgray border-solid border-[1px] overflow-hidden flex flex-row items-center justify-center py-3 px-4 gap-2 text-darkslategray-100 cursor-pointer" 
             data-date-filter-toggle 
             onclick="toggleDatePicker()">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16 2C16.4142 2 16.75 2.33579 16.75 2.75V3.75H18.5C19.7426 3.75 20.75 4.75736 20.75 6V19C20.75 20.2426 19.7426 21.25 18.5 21.25H5.5C4.25736 21.25 3.25 20.2426 3.25 19V6C3.25 4.75736 4.25736 3.75 5.5 3.75H7.25V2.75C7.25 2.33579 7.58579 2 8 2C8.41421 2 8.75 2.33579 8.75 2.75V3.75H15.25V2.75C15.25 2.33579 15.5858 2 16 2ZM4.75 19C4.75 19.4142 5.08579 19.75 5.5 19.75H18.5C18.9142 19.75 19.25 19.4142 19.25 19V9.75H4.75V19ZM5.5 5.25C5.08579 5.25 4.75 5.58579 4.75 6V8.25H19.25V6C19.25 5.58579 18.9142 5.25 18.5 5.25H5.5Z" fill="#344054"/>
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
