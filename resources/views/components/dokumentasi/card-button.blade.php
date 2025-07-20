<div class="flex flex-row items-center justify-center gap-3 p-4 mt-auto">
    <a href="{{ route('lahan.dokumentasi.show',  [$lahan, $doc]) }}" 
        class="rounded-lg bg-darkslategray overflow-hidden flex flex-row items-center justify-center py-3 px-4 gap-2 !text-white no-underline hover:bg-slategray-200">
        <span class="text-leading-5 font-medium">Lihat Detail</span>
    </a>
    
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" 
                class="flex border-none fill-none bg-darkslategray hover:bg-slategray-200 rounded-lg p-3 items-center"
                title="More Options">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 11.25C10.4973 11.25 10.9742 11.0525 11.3258 10.7008C11.6775 10.3492 11.875 9.87228 11.875 9.375C11.875 8.87772 11.6775 8.40081 11.3258 8.04917C10.9742 7.69754 10.4973 7.5 10 7.5C9.50272 7.5 9.02581 7.69754 8.67417 8.04917C8.32254 8.40081 8.125 8.87772 8.125 9.375C8.125 9.87228 8.32254 10.3492 8.67417 10.7008C9.02581 11.0525 9.50272 11.25 10 11.25ZM1.875 11.25C2.37228 11.25 2.84919 11.0525 3.20083 10.7008C3.55246 10.3492 3.75 9.87228 3.75 9.375C3.75 8.87772 3.55246 8.40081 3.20083 8.04917C2.84919 7.69754 2.37228 7.5 1.875 7.5C1.37772 7.5 0.900806 7.69754 0.549175 8.04917C0.197544 8.40081 0 8.87772 0 9.375C0 9.87228 0.197544 10.3492 0.549175 10.7008C0.900806 11.0525 1.37772 11.25 1.875 11.25ZM18.125 11.25C18.6223 11.25 19.0992 11.0525 19.4508 10.7008C19.8025 10.3492 20 9.87228 20 9.375C20 8.87772 19.8025 8.40081 19.4508 8.04917C19.0992 7.69754 18.6223 7.5 18.125 7.5C17.6277 7.5 17.1508 7.69754 16.7992 8.04917C16.4475 8.40081 16.25 8.87772 16.25 9.375C16.25 9.87228 16.4475 10.3492 16.7992 10.7008C17.1508 11.0525 17.6277 11.25 18.125 11.25Z" fill="white"/>
            </svg>
        </button>

        <div x-show="open" 
                @click.away="open = false"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute right-0 bottom-full mb-2 w-48 bg-darkslategray rounded-md shadow-sm z-50 border border-gray-200">
            <div class="py-1">
                <div class="inline">
                    <a href="{{ route('lahan.dokumentasi.edit', [$lahan, $doc]) }}" 
                    class="self-stretch px-4 py-2 text-sm rounded-md !text-white hover:bg-slategray-200 flex items-center gap-2 no-underline">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.83301 5.83333H4.99967C4.55765 5.83333 4.13372 6.00892 3.82116 6.32148C3.5086 6.63404 3.33301 7.05797 3.33301 7.49999V15C3.33301 15.442 3.5086 15.8659 3.82116 16.1785C4.13372 16.4911 4.55765 16.6667 4.99967 16.6667H12.4997C12.9417 16.6667 13.3656 16.4911 13.6782 16.1785C13.9907 15.8659 14.1663 15.442 14.1663 15V14.1667" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M13.3333 4.16666L15.8333 6.66666M16.9875 5.4875C17.3157 5.15929 17.5001 4.71415 17.5001 4.25C17.5001 3.78585 17.3157 3.3407 16.9875 3.0125C16.6593 2.68429 16.2142 2.49991 15.75 2.49991C15.2858 2.49991 14.8407 2.68429 14.5125 3.0125L7.5 10V12.5H10L16.9875 5.4875Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Edit
                    </a>
                </div>
                @if($doc->image_path)
                    <div class="inline">
                        <a href="{{ Storage::url($doc->image_path) }}" 
                        target="_blank"
                        class="self-stretch px-4 py-2 text-sm rounded-md !text-white hover:bg-slategray-200 flex items-center gap-2 no-underline">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.33398 3.33334C3.33398 2.89131 3.50958 2.46739 3.82214 2.15483C4.1347 1.84227 4.55862 1.66667 5.00065 1.66667H11.6673C11.8883 1.66672 12.1002 1.75455 12.2565 1.91084L16.4232 6.07751C16.5794 6.23375 16.6673 6.44568 16.6673 6.66667V16.6667C16.6673 17.1087 16.4917 17.5326 16.1792 17.8452C15.8666 18.1577 15.4427 18.3333 15.0007 18.3333H5.00065C4.55862 18.3333 4.1347 18.1577 3.82214 17.8452C3.50958 17.5326 3.33398 17.1087 3.33398 16.6667V3.33334ZM14.6557 6.66667L11.6673 3.67834V6.66667H14.6557ZM10.0007 3.33334H5.00065V16.6667H15.0007V8.33334H10.834C10.613 8.33334 10.401 8.24554 10.2447 8.08926C10.0884 7.93298 10.0007 7.72102 10.0007 7.5V3.33334ZM10.0007 10C10.2217 10 10.4336 10.0878 10.5899 10.2441C10.7462 10.4004 10.834 10.6123 10.834 10.8333V11.6667H11.6673C11.8883 11.6667 12.1003 11.7545 12.2566 11.9107C12.4129 12.067 12.5007 12.279 12.5007 12.5C12.5007 12.721 12.4129 12.933 12.2566 13.0893C12.1003 13.2455 11.8883 13.3333 11.6673 13.3333H10.834V14.1667C10.834 14.3877 10.7462 14.5996 10.5899 14.7559C10.4336 14.9122 10.2217 15 10.0007 15C9.77964 15 9.56768 14.9122 9.4114 14.7559C9.25512 14.5996 9.16732 14.3877 9.16732 14.1667V13.3333H8.33398C8.11297 13.3333 7.90101 13.2455 7.74473 13.0893C7.58845 12.933 7.50065 12.721 7.50065 12.5C7.50065 12.279 7.58845 12.067 7.74473 11.9107C7.90101 11.7545 8.11297 11.6667 8.33398 11.6667H9.16732V10.8333C9.16732 10.6123 9.25512 10.4004 9.4114 10.2441C9.56768 10.0878 9.77964 10 10.0007 10Z" fill="white"/>
                            </svg>
                            Download Gambar
                        </a>
                    </div>
                @endif
                <button x-data="" 
                        x-on:click.prevent="$dispatch('open-modal', 'confirm-dokumentasi-deletion-{{ $doc->dokumentasi_id }}'); open = false"
                        class="w-full px-4 py-2 text-sm rounded-md text-white hover:bg-slategray-200 flex items-center gap-2 no-underline font-outfit border-none bg-transparent">
                    <svg width="18" height="18" viewBox="0 0 15 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.52679 1.0877L4.28571 1.5625H1.07143C0.478795 1.5625 0 2.0373 0 2.625C0 3.2127 0.478795 3.6875 1.07143 3.6875H13.9286C14.5212 3.6875 15 3.2127 15 2.625C15 2.0373 14.5212 1.5625 13.9286 1.5625H10.7143L10.4732 1.0877C10.2924 0.725781 9.92076 0.5 9.51562 0.5H5.48438C5.07924 0.5 4.70759 0.725781 4.52679 1.0877ZM13.9286 4.75H1.07143L1.78125 16.0059C1.83482 16.8459 2.53795 17.5 3.38504 17.5H11.615C12.4621 17.5 13.1652 16.8459 13.2187 16.0059L13.9286 4.75Z" fill="white"/>
                    </svg>
                    Hapus Dokumentasi
                </button>
            </div>
        </div>
    </div>
</div>