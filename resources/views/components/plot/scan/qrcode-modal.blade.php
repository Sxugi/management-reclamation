@props(['plot'])

<div x-data="{ 
    open: false,
    downloading: false,
    async handleDownload() {
        this.downloading = true;
        try {
            await window.downloadQrCode('{{ $plot->plot_id }}', '{{ \Str::slug($plot->nama_plot) }}');
        } catch (error) {
            console.error(error);
        } finally {
            this.downloading = false;
        }
    }
}" class="inline-block">
    <!-- Button Trigger -->
    <button 
        @click="open = true"
        type="button" 
        class="rounded-lg bg-white border border-gray-300 overflow-hidden flex flex-row items-center justify-center py-3 px-4 gap-2 text-darkslategray hover:bg-gray-50 cursor-pointer"
    >
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="2.5" y="2.5" width="6" height="6" stroke="currentColor" stroke-width="1.5" fill="none"/>
            <rect x="11.5" y="2.5" width="6" height="6" stroke="currentColor" stroke-width="1.5" fill="none"/>
            <rect x="2.5" y="11.5" width="6" height="6" stroke="currentColor" stroke-width="1.5" fill="none"/>
            <rect x="4" y="4" width="3" height="3" fill="currentColor"/>
            <rect x="13" y="4" width="3" height="3" fill="currentColor"/>
            <rect x="4" y="13" width="3" height="3" fill="currentColor"/>
            <rect x="11.5" y="11.5" width="2" height="2" fill="currentColor"/>
            <rect x="14.5" y="11.5" width="3" height="2" fill="currentColor"/>
            <rect x="11.5" y="14.5" width="2" height="3" fill="currentColor"/>
            <rect x="14.5" y="14.5" width="3" height="3" fill="currentColor"/>
        </svg>
        <span class="relative leading-5 font-medium">Lihat QR Code</span>
    </button>

    <!-- Modal -->
    <div 
        x-show="open"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <!-- Background overlay -->
        <div 
            x-show="open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="open = false"
            class="fixed inset-0 bg-gray-500/75 transition-opacity"
        ></div>

        <!-- Modal container -->
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <!-- Modal panel -->
            <div 
                x-show="open"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                @click.stop
                class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6"
            >
                <!-- Close button -->
                <div class="absolute right-0 top-0 pr-4 pt-4">
                    <button
                        @click="open = false"
                        type="button"
                        class="w-10 h-10 rounded-xl bg-white shadow-sm border border-gainsboro hover:bg-gray-500 flex items-center justify-center text-slategray hover:text-white transition-all duration-200"
                    >
                        <span class="sr-only">Close</span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="sm:flex sm:flex-col sm:items-center">
                    <!-- Modal Header -->
                    <div class="text-center w-full mb-4">
                        <h3 class="text-lg leading-6 font-semibold text-gray-900 font-outfit">
                            QR Code Plot {{ $plot->nama_plot }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            UUID: {{ $plot->uuid }}
                        </p>
                    </div>

                    <!-- QR Code Display -->
                    <div class="mt-4 bg-white p-6 rounded-lg border-2 border-gray-200 flex flex-col items-center">
                        <div id="qrcode-{{ $plot->plot_id }}" class="mb-4 bg-white p-4 rounded">
                            {!! QrCode::size(250)->generate(route('public.plot.scan', $plot->uuid)) !!}
                        </div>
                        <div class="text-xs text-gray-600 font-mono break-all text-center max-w-xs">
                            {{ $plot->uuid }}
                        </div>
                        <div class="mt-3 text-xs text-gray-500 text-center">
                            Scan untuk akses informasi plot
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 flex flex-col sm:flex-row gap-3 w-full">
                        <button
                            @click="handleDownload()"
                            :disabled="downloading"
                            type="button"
                            class="flex-1 inline-flex justify-center items-center rounded-lg border border-transparent shadow-sm px-4 py-3 bg-darkslategray text-base font-medium text-white hover:bg-slategray-200 focus:outline-none sm:text-sm gap-2 disabled:opacity-50 disabled:cursor-not-allowed transition-opacity cursor-pointer"
                        >
                            <svg x-show="!downloading" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.5 12.5V15.8333C17.5 16.2754 17.3244 16.6993 17.0118 17.0118C16.6993 17.3244 16.2754 17.5 15.8333 17.5H4.16667C3.72464 17.5 3.30072 17.3244 2.98816 17.0118C2.67559 16.6993 2.5 16.2754 2.5 15.8333V12.5M5.83333 8.33333L10 12.5M10 12.5L14.1667 8.33333M10 12.5V2.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <svg x-show="downloading" x-cloak class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="downloading ? 'Downloading...' : 'Download QR Code (PNG)'"></span>
                        </button>
                        <button
                            @click="open = false"
                            type="button"
                            class="flex-1 inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-3 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:text-sm cursor-pointer"
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>