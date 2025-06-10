@props(['lahan', 'index'])

<div class="rounded-xl bg-darkslategray text-white shadow">
    <!-- Card for mobile view: stacked layout -->
    <div class="block md:hidden">
        <div class="p-4 space-y-4">
            <!-- Land Name -->
            <div>
                <x-lahan.info-label>Nama Lahan</x-lahan.info-label>
                <div class="font-medium text-gray-300">{{ $lahan->nama_lahan }}</div>
            </div>
            
            <!-- Two column grid for info -->
            <div class="grid grid-cols-2 gap-2">
                <!-- Land Area -->
                <div>
                    <x-lahan.info-label>Luas Lahan</x-lahan.info-label>
                    <div>{{ number_format($lahan->luas_lahan, 2) }} ha</div>
                </div>
                
                <!-- Year Period -->
                <div>
                    <x-lahan.info-label>Periode Tahun</x-lahan.info-label>
                    <div>{{ $lahan->tahun_awal }} - {{ $lahan->tahun_akhir }}</div>
                </div>
                
                <!-- PIC Reclamation -->
                <div>
                    <x-lahan.info-label>PIC Reklamasi</x-lahan.info-label>
                    <div class="truncate">{{ $lahan->pic_reklamasi }}</div>
                </div>
                
                <!-- Status -->
                <div>
                    <x-lahan.info-label>Status Reklamasi</x-lahan.info-label>
                    <x-lahan.status-dropdown :status="$lahan->status ?? 'Aktif'" :lahan="$lahan" />
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center justify-between pt-2">
                <x-lahan.details-button :lahan="$lahan" />
                <x-lahan.action-menu :lahan="$lahan" />
            </div>
        </div>
    </div>
    
    <!-- Card for desktop: row layout -->
    <div class="hidden md:flex items-center py-4 px-6 gap-3">
        <!-- Icon/Initial -->
        <div class="w-12 h-12 rounded-full bg-slategray flex-shrink-0 flex items-center justify-center mr-6border border-white border-solid border-[1px] rounded-lg">
            <span class="text-xl font-bold">{{ $index }}</span>
        </div>
        
        <!-- Info grid -->
        <div class="grid grid-cols-6 flex-1 gap-6 flex flex-row items-center justify-between">
            <div>
                <x-lahan.info-label>Nama Lahan</x-lahan.info-label>
                <x-lahan.info>{{ $lahan->nama_lahan }}</x-lahan.info>
            </div>
            
            <div>
                <x-lahan.info-label>Luas Lahan</x-lahan.info-label>
                <x-lahan.info>{{ number_format($lahan->luas_lahan, 2) }} ha</x-lahan.info>
            </div>
            
            <div>
                <x-lahan.info-label>Periode Tahun</x-lahan.info-label>
                <x-lahan.info>{{ $lahan->tahun_awal }} - {{ $lahan->tahun_akhir }}</x-lahan.info>
            </div>
            
            <div>
                <x-lahan.info-label>PIC Reklamasi</x-lahan.info-label>
                <x-lahan.info class="truncate">{{ $lahan->pic_reklamasi }}</x-lahan.info>
            </div>

            <div>
                <x-lahan.info-label>Status Reklamasi</x-lahan.info-label>
                <x-lahan.status-dropdown :status="$lahan->status ?? 'Aktif'" :lahan="$lahan" />
            </div>

            <div>
                <x-lahan.details-button :lahan="$lahan" />
            </div>
        </div>

        <div>
            <x-lahan.action-menu :lahan="$lahan" />
        </div>
    </div>
</div>