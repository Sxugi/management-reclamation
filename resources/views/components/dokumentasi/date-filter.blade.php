<div class="flex flex-row items-start justify-start gap-2">
    <form method="GET" action="{{ route('lahan.dokumentasi.index', $lahan) }}" id="dateFilterForm">
        <div class="rounded-lg bg-white border-lightgray border-solid border-[1px] overflow-hidden flex flex-row items-center justify-center py-3 px-4 gap-2 text-darkslategray-100 cursor-pointer" 
             data-date-filter-toggle 
             onclick="toggleDatePicker()">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M13.332 1.54199C13.7462 1.54199 14.082 1.87778 14.082 2.29199V3H15.415C16.5195 3 17.4149 3.89558 17.415 5V15.834C17.4147 16.9383 16.5194 17.834 15.415 17.834H4.58203C3.47783 17.8338 2.58238 16.9381 2.58203 15.834V5C2.58221 3.89569 3.47772 3.00018 4.58203 3H5.91504V2.29199C5.91504 1.878 6.25112 1.54234 6.66504 1.54199C7.07925 1.54199 7.41504 1.87778 7.41504 2.29199V3H12.582V2.29199C12.582 1.87789 12.918 1.54217 13.332 1.54199ZM4.08203 15.834C4.08238 16.1097 4.30626 16.3338 4.58203 16.334H15.415C15.691 16.334 15.9147 16.1098 15.915 15.834V8.25H4.08203V15.834ZM4.58203 4.5C4.30615 4.50018 4.08221 4.72412 4.08203 5V6.75H15.915V5C15.9149 4.72401 15.6911 4.5 15.415 4.5H4.58203Z" fill="#344054"/>
            </svg>
            <div class="relative leading-5 font-medium" id="dateRangeDisplay">
                @if(request('startDate') && request('endDate'))
                    {{ \Carbon\Carbon::parse(request('startDate'))->format('d M') }} - {{ \Carbon\Carbon::parse(request('endDate'))->format('d M Y') }}
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
                           name="startDate" 
                           id="startDate" 
                           value="{{ request('startDate') }}"
                           class="border rounded p-2 text-sm"/>
                </div>
                <div>
                    <x-main.input-label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</x-main.input-label>
                    <x-main.text-input type="date" 
                           name="endDate" 
                           id="endDate" 
                           value="{{ request('endDate') }}"
                           class="border rounded p-2 text-sm"/>
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
