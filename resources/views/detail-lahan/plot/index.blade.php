<x-main-layout>
    <x-slot name="header">
        <div class="flex flex-row items-center justify-between gap-4">
            <h2 class="text-xl font-bold text-darkslategray font-outfit">Plot Lahan</h2>
            <div class="self-stretch flex flex-row items-center justify-start gap-1.5 text-left text-sm text-slategray font-outfit">
                <a type="button" href="{{ route('lahan.index') }}" class="relative leading-5 text-darkslategray no-underline visited:text-darkslategray">List Lahan</a>
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.83333 12.6667L10 8.5L5.83333 4.33333" stroke="#667085" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div class="relative leading-5 text-darkslategray-200 font-medium">Plot Lahan</div>
            </div>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg sm:rounded-lg">
                <div class="p-6">
                    <div class="self-stretch flex flex-row items-center justify-start gap-1 mb-6">
                        <div class="flex-1 flex flex-col items-start justify-start gap-1 text-lg text-gray">
                            <div class="self-stretch relative leading-7 font-semibold">Area Lahan Reklamasi</div>
                            <div class="self-stretch relative text-sm leading-5 text-slategray">Plot lahan menjadi beberapa bagian.</div>
                        </div>
                        <a href=" {{ route('lahan.plot.create', $lahan->lahan_id) }} " class="rounded-lg bg-darkslategray overflow-hidden flex flex-row items-center justify-center py-3 px-4 gap-2 !text-white no-underline hover:bg-slategray-200">
                            <span class="relative leading-5 font-medium">Add</span>
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 4.24951C10.4142 4.24951 10.75 4.58534 10.75 4.99951V9.24951H15.001L15.0771 9.25342C15.4553 9.29177 15.7508 9.61128 15.751 9.99951C15.751 10.3879 15.4554 10.7072 15.0771 10.7456L15.001 10.7495H10.75V15.0005L10.7461 15.0767C10.7077 15.4549 10.3884 15.7505 10 15.7505C9.61173 15.7504 9.29227 15.4548 9.25391 15.0767L9.25 15.0005V10.7495H5C4.58579 10.7495 4.25 10.4137 4.25 9.99951C4.25015 9.58543 4.58588 9.24951 5 9.24951H9.25V4.99951C9.25004 4.5854 9.58591 4.24962 10 4.24951Z" fill="white"/>
                            </svg>
                        </a>
                    </div>

                    <x-plot.map-controls :plot="$plot ?? []" :lahan="$lahan" :enableDraw="false"/>
                    <x-plot.list :plot="$plot ?? []" :lahan="$lahan ?? []"/>
                </div>
            </div>
        </div>
    </div>
</x-main-layout>