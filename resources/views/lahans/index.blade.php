<x-app-layout>
    <x-slot name="header">
        <div class="w-full relative flex flex-row items-center justify-between gap-0 text-left text-[22px] text-darkslategray">
            <div class="w-[201.4px] relative inline-block shrink-0 font-outfit">List Lahan</div>
            <a href="{{ route('lahans.create') }}" class="shadow-[0px_1px_2px_rgba(16,_24,_40,_0.05)] rounded-lg bg-darkslategray overflow-hidden flex flex-row items-center justify-center py-3 px-4 gap-2 text-sm text-white font-outfit hover:bg-slategray-200 transition-colors duration-150">
                <div class="relative leading-5 font-medium">Tambah Lahan</div>
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="w-full relative flex flex-col items-center justify-between gap-4 text-base text-white font-inter">
                @forelse($lahans as $lahan)
                <!-- Responsive Lahan Card -->
                <div class="self-stretch rounded-[20px] bg-darkslategray flex flex-col md:flex-row items-start md:items-center justify-between py-[15px] px-4 md:px-6 gap-4 md:gap-0 w-full">
                    <!-- Logo/Icon Space - Hidden on mobile to save space -->
                    <div class="hidden md:block w-[110px] relative rounded-[20px] bg-darkslategray h-[60px] relative">
                        <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center">
                            <!-- Replace with your actual logo -->
                            <span class="text-2xl font-bold">{{ substr($lahan->nama_lahan, 0, 1) }}</span>
                        </div>
                    </div>
                    
                    <!-- Land Name -->
                    <div class="w-full md:w-[146px] relative">
                        <b class="block md:absolute md:top-[0px] md:left-[0px] w-full md:w-[146px]">Nama Lahan</b>
                        <div class="block md:absolute md:top-[26px] md:left-[0px] text-[15px] w-full md:w-[142px] truncate">
                            {{ $lahan->nama_lahan }}
                        </div>
                    </div>
                    
                    <!-- Land Area -->
                    <div class="w-full md:w-[127px] relative">
                        <b class="block md:absolute md:top-[0px] md:left-[0px] w-full md:w-24">Luas Lahan</b>
                        <div class="block md:absolute md:top-[26px] md:left-[0px] text-[15px] w-full md:w-[127px]">
                            {{ number_format($lahan->luas_lahan, 2) }} ha
                        </div>
                    </div>
                    
                    <!-- Year Period -->
                    <div class="w-full md:w-[195px] relative">
                        <b class="block md:absolute md:top-[0px] md:left-[0px] w-full md:w-[190px]">Periode Tahun</b>
                        <div class="block md:absolute md:top-[26px] md:left-[0px] text-[15px] w-full md:w-[195px]">
                            {{ $lahan->tahun_awal }} - {{ $lahan->tahun_akhir }}
                        </div>
                    </div>
                    
                    <!-- PIC Reclamation -->
                    <div class="w-full md:w-[182px] relative">
                        <b class="block md:absolute md:top-[0px] md:left-[0px] w-full md:w-[182px]">PIC Reklamasi</b>
                        <div class="block md:absolute md:top-[26px] md:left-[0px] text-[15px] w-full md:w-[92px] truncate">
                            {{ $lahan->pic_reklamasi }}
                        </div>
                    </div>
                    
                    <!-- Status Reclamation - Using a placeholder status -->
                    <div class="w-full md:w-[182px] relative">
                        <b class="block md:absolute md:top-[0px] md:left-[0px] w-full md:w-[182px]">Status Reklamasi</b>
                        <div class="block md:absolute md:top-[22px] md:left-[0px] w-full md:w-[135px] flex flex-col items-start justify-start text-sm font-outfit">
                            <div class="self-stretch rounded-lg bg-slategray h-7 flex flex-row items-center justify-center py-2 px-3 box-border gap-3">
                                <div class="flex-1 flex flex-row items-center justify-start">
                                    <div class="flex-1 relative leading-5 font-medium">Aktif</div>
                                </div>
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- View Details Button and Actions -->
                    <div class="flex flex-row items-center justify-between w-full md:w-auto gap-2">
                        <div class="w-full md:w-[146px] relative">
                            <a href="{{ route('lahans.show', $lahan->lahan_id) }}" class="w-full shadow-[0px_1px_2px_rgba(16,_24,_40,_0.05)] rounded-lg bg-slategray border-slategray border-solid border-[1px] overflow-hidden flex flex-row items-center justify-center py-3 px-4">
                                <div class="w-[97px] relative font-medium inline-block text-[15px]">View Details</div>
                            </a>
                        </div>
                        
                        <div class="dropdown relative">
                            <button class="p-1 hover:bg-slategray-300 rounded-full">
                                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                </svg>
                            </button>
                            <div class="dropdown-menu hidden absolute right-0 mt-2 bg-white rounded-md shadow-lg z-10 text-black">
                                <a href="{{ route('lahans.edit', $lahan->lahan_id) }}" class="block px-4 py-2 hover:bg-gray-100 text-sm">Edit</a>
                                <form action="{{ route('lahans.destroy', $lahan->lahan_id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100 text-sm text-red-500" 
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus lahan ini?')">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <!-- Empty State -->
                <div class="self-stretch rounded-[20px] bg-darkslategray flex flex-col items-center justify-center py-8 px-6 w-full">
                    <p class="text-lg">Belum ada data lahan. Silakan tambahkan lahan baru.</p>
                    <a href="{{ route('lahans.create') }}" class="mt-4 shadow-[0px_1px_2px_rgba(16,_24,_40,_0.05)] rounded-lg bg-slategray overflow-hidden flex flex-row items-center justify-center py-3 px-4 gap-2 text-sm">
                        <div class="relative leading-5 font-medium">Tambah Lahan Baru</div>
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>