<x-main-layout>
    <x-slot name="header">
        <div class="flex flex-row items-center justify-between gap-4">
            <h2 class="text-xl font-bold text-darkslategray font-outfit">Dokumentasi</h2>
            <div class="self-stretch flex flex-row items-center justify-start gap-1.5 text-left text-sm text-slategray font-outfit">
                <a type="button" href="{{ route('lahan.index') }}" class="relative leading-5 text-darkslategray no-underline visited:text-darkslategray">List Lahan</a>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.83333 12.6667L10 8.5L5.83333 4.33333" stroke="#667085" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                <a type="button" href="{{ route('lahan.dokumentasi.index', $lahan) }}" class="relative leading-5 text-darkslategray no-underline visited:text-darkslategray">Dokumentasi</a>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.83333 12.6667L10 8.5L5.83333 4.33333" stroke="#667085" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                <div class="relative leading-5 text-darkslategray-200 font-medium">Menampilkan Dokumentasi</div>
            </div>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm rounded-lg sm:rounded-lg">
        <div class="p-6">
            <div class="self-stretch flex flex-row items-center justify-start gap-1 mb-6">
                <div class="flex-1 flex flex-col items-start justify-start gap-1 text-lg text-gray">
                    <div class="flex flex-col items-start justify-start">
                        <div class="self-stretch relative leading-7 font-bold mb-1">
                            {{ $dokumentasi->nama }}
                        </div>
                        <div class="flex items-center text-sm text-gray-500 space-x-4">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16 2C16.4142 2 16.75 2.33579 16.75 2.75V3.75H18.5C19.7426 3.75 20.75 4.75736 20.75 6V19C20.75 20.2426 19.7426 21.25 18.5 21.25H5.5C4.25736 21.25 3.25 20.2426 3.25 19V6C3.25 4.75736 4.25736 3.75 5.5 3.75H7.25V2.75C7.25 2.33579 7.58579 2 8 2C8.41421 2 8.75 2.33579 8.75 2.75V3.75H15.25V2.75C15.25 2.33579 15.5858 2 16 2ZM4.75 19C4.75 19.4142 5.08579 19.75 5.5 19.75H18.5C18.9142 19.75 19.25 19.4142 19.25 19V9.75H4.75V19ZM5.5 5.25C5.08579 5.25 4.75 5.58579 4.75 6V8.25H19.25V6C19.25 5.58579 18.9142 5.25 18.5 5.25H5.5Z" fill="currentColor"/>
                                </svg>
                                {{ $dokumentasi->created_at->format('d M Y') }}
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $dokumentasi->created_at->format('H:i') }}
                            </span>
                        </div>
                    </div>
                </div>
                <a href=" {{ route('lahan.dokumentasi.index', [$lahan]) }} " class="rounded-lg bg-darkslategray overflow-hidden flex flex-row items-center justify-center py-3 px-4 gap-2 !text-white no-underline hover:bg-slategray-200">
                    <span class="relative leading-5 font-medium">Back</span>
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.99994 10L3.29294 10.707L2.58594 10L3.29294 9.29303L3.99994 10ZM20.9999 18C20.9999 18.2652 20.8946 18.5196 20.707 18.7071C20.5195 18.8947 20.2652 19 19.9999 19C19.7347 19 19.4804 18.8947 19.2928 18.7071C19.1053 18.5196 18.9999 18.2652 18.9999 18H20.9999ZM8.29294 15.707L3.29294 10.707L4.70694 9.29303L9.70694 14.293L8.29294 15.707ZM3.29294 9.29303L8.29294 4.29303L9.70694 5.70703L4.70694 10.707L3.29294 9.29303ZM3.99994 9.00003H13.9999V11H3.99994V9.00003ZM20.9999 16V18H18.9999V16H20.9999ZM13.9999 9.00003C15.8565 9.00003 17.6369 9.73753 18.9497 11.0503C20.2624 12.363 20.9999 14.1435 20.9999 16H18.9999C18.9999 14.6739 18.4732 13.4022 17.5355 12.4645C16.5978 11.5268 15.326 11 13.9999 11V9.00003Z" fill="white"/>
                    </svg>
                </a>
            </div>
            <x-dokumentasi.show-fields :lahan="$lahan" :dokumentasi="$dokumentasi" />
        </div>
    </div>
</x-main-layout>