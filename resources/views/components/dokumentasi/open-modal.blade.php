<div class="flex flex-col items-center justify-start">
    <div class="w-full rounded-xl overflow-hidden bg-gray-50 border border-gray-200 shadow-sm">
        @if($dokumentasi->image_path)
            <div class="relative group">
                <img src="{{ Storage::url($dokumentasi->image_path) }}" 
                        alt="{{ $dokumentasi->nama }}" 
                        class="w-full h-auto object-cover rounded-xl transition-transform duration-300 group-hover:scale-105"
                        onerror="this.src='{{ asset('images/default-placeholder.png') }}'">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 rounded-xl flex items-center justify-center">
                    <button onclick="openImageModal()" 
                            class="flex flex-row items-center font-bold text-sm border-none opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-white text-darkslategray-200 py-3 px-4 gap-2 rounded-lg shadow-lg hover:bg-gray-100">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.125 16.7188L13.2781 11.8711C14.0592 10.7977 14.4794 9.50407 14.4781 8.17656C14.4781 4.70195 11.6512 1.875 8.17656 1.875C4.70195 1.875 1.875 4.70195 1.875 8.17656C1.875 11.6512 4.70195 14.4781 8.17656 14.4781C9.50407 14.4794 10.7977 14.0592 11.8711 13.2781L16.7188 18.125L18.125 16.7188ZM8.17656 12.4879C7.32375 12.488 6.49007 12.2351 5.78095 11.7614C5.07183 11.2877 4.51913 10.6143 4.19274 9.82638C3.86635 9.0385 3.78093 8.17152 3.94728 7.33509C4.11364 6.49867 4.5243 5.73035 5.12733 5.12733C5.73035 4.5243 6.49867 4.11364 7.33509 3.94728C8.17152 3.78093 9.0385 3.86635 9.82638 4.19274C10.6143 4.51913 11.2877 5.07183 11.7614 5.78095C12.2351 6.49007 12.488 7.32375 12.4879 8.17656C12.4865 9.31959 12.0319 10.4154 11.2236 11.2236C10.4154 12.0319 9.31959 12.4865 8.17656 12.4879Z" fill="#27374D"/>
                        </svg>
                        View Detail Image
                    </button>
                </div>
            </div>
        @else
            <div class="w-full h-64 lg:h-80 flex items-center justify-center bg-gray-100 rounded-xl">
                <div class="text-center text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm">No image available</p>
                </div>
            </div>
        @endif
    </div>
</div>

@if($dokumentasi->image_path)
<div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <button onclick="closeImageModal()" 
                class="absolute rounded-lg border-none text-darkslategray-200 hover:text-white hover:bg-gray-600 transition-colors m-2 p-2 right-0">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <img src="{{ Storage::url($dokumentasi->image_path) }}" 
             alt="{{ $dokumentasi->nama }}" 
             class="max-w-full max-h-full object-contain rounded-lg">
    </div>
</div>
@endif