<x-main-layout>
    <x-slot name="header">
        <div class="flex flex-row items-center justify-between gap-4">
            <h2 class="text-xl font-bold text-darkslategray font-outfit">Plot Lahan</h2>
            <div class="self-stretch flex flex-row items-center justify-start gap-1.5 text-left text-sm text-slategray font-outfit">
                <a type="button" href="{{ route('lahan.index') }}" class="relative leading-5 text-darkslategray no-underline visited:text-darkslategray">List Lahan</a>
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.83333 12.6667L10 8.5L5.83333 4.33333" stroke="#667085" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <a type="button" href="{{ route('lahan.plot.index', $lahan->lahan_id) }}" class="relative leading-5 text-darkslategray no-underline visited:text-darkslategray">Plot Lahan</a>
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5.83333 12.6667L10 8.5L5.83333 4.33333" stroke="#667085" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div class="relative leading-5 text-darkslategray-200 font-medium">Mengedit Plot Lahan</div>
            </div>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-6">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg sm:rounded-lg">
                <div class="p-6">
                    <div class="self-stretch flex flex-row items-center justify-start gap-1 mb-6">
                        <div class="flex-1 flex flex-col items-start justify-start gap-1 text-lg text-gray">
                            <div class="self-stretch relative leading-7 font-semibold text-darkslategray font-outfit">Area Lahan Reklamasi</div>
                            <div class="self-stretch relative text-sm leading-5 text-slategray font-outfit">Plot lahan menjadi beberapa bagian.</div>
                        </div>
                        <a href=" {{ route('lahan.plot.index', $lahan->lahan_id) }} " class="rounded-lg bg-darkslategray overflow-hidden flex flex-row items-center justify-center py-3 px-4 gap-2 !text-white no-underline hover:bg-slategray-200">
                            <span class="relative leading-5 font-medium font-outfit">Back</span>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.99994 10L3.29294 10.707L2.58594 10L3.29294 9.29303L3.99994 10ZM20.9999 18C20.9999 18.2652 20.8946 18.5196 20.707 18.7071C20.5195 18.8947 20.2652 19 19.9999 19C19.7347 19 19.4804 18.8947 19.2928 18.7071C19.1053 18.5196 18.9999 18.2652 18.9999 18H20.9999ZM8.29294 15.707L3.29294 10.707L4.70694 9.29303L9.70694 14.293L8.29294 15.707ZM3.29294 9.29303L8.29294 4.29303L9.70694 5.70703L4.70694 10.707L3.29294 9.29303ZM3.99994 9.00003H13.9999V11H3.99994V9.00003ZM20.9999 16V18H18.9999V16H20.9999ZM13.9999 9.00003C15.8565 9.00003 17.6369 9.73753 18.9497 11.0503C20.2624 12.363 20.9999 14.1435 20.9999 16H18.9999C18.9999 14.6739 18.4732 13.4022 17.5355 12.4645C16.5978 11.5268 15.326 11 13.9999 11V9.00003Z" fill="white"/>
                            </svg>
                        </a>
                    </div>
                    
                    <!-- Map Container -->
                    <x-plot.map-controls :plot="$plot" :lahan="$lahan" :enableDraw="true"/>
                    </div>
                </div>

                <div x-data="koordinatInputData({   
                    polygons: {{ json_encode($plot) }},
                    longitude: {{ $lahan->location->getX() ?? 'null' }},
                    latitude: {{ $lahan->location->getY() ?? 'null' }},
                    })" 
                    class="flex flex-col gap-6">
                
                    <form 
                        method="POST" 
                        action="{{ route('plot.update', $plot->plot_id) }}"
                        class="w-full">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="lahan_id" value="{{ $lahan->lahan_id }}" />
                        <input type="hidden" name="polygon" :value="JSON.stringify([points.map(p => [parseFloat(p.lng), parseFloat(p.lat)])])" />
                        
                        <div class="flex flex-col gap-6">
                            <div class="bg-white overflow-hidden shadow-sm rounded-lg sm:rounded-lg">
                                <div class="p-6">
                                    <x-plot.input-info :plot="$plot"/>
                                </div>
                            </div>

                            <div class="bg-white overflow-hidden shadow-sm rounded-lg sm:rounded-lg">
                                <div class="p-6">
                                    <x-plot.input-koordinat :plot="$plot"/>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-main-layout>