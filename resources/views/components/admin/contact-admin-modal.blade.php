@props(['adminContact'])

<div x-data="{ show: false }"
     x-show="show"
     @open-modal.window="if ($event.detail === 'contact-admin-modal') show = true"
     @keydown.escape.window="show = false"
     style="display: none;"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm font-outfit"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">

    <div @click.away="show = false" 
         class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden transform transition-all"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95">

        <div class="flex items-center justify-between p-6 border-b border-gainsboro bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center shadow-md">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center self-stretch leading-7 font-semibold text-lg text-darkslategray font-outfit">Hubungi Admin</div>
                    <div class="text-sm text-black font-outfit">Bantuan teknis & sistem</div>
                </div>
            </div>
            
            <button @click="show = false" class="w-10 h-10 rounded-xl bg-white shadow-sm border border-gainsboro hover:bg-gray-100 flex items-center justify-center text-black transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Content --}}
        <div class="p-6">
            @if($adminContact)
                <div class="flex flex-col items-center justify-center text-center space-y-4">
                    
                    <div class="relative">
                        <div class="h-24 w-24 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center shadow-lg ring-4 ring-white">
                            @if($adminContact->avatar)
                                <img src="{{ asset('storage/' . $adminContact->avatar) }}" class="h-24 w-24 rounded-full object-cover">
                            @else
                                <span class="text-white text-3xl font-bold">{{ strtoupper(substr($adminContact->name, 0, 2)) }}</span>
                            @endif
                        </div>
                        <span class="absolute bottom-1 right-1 h-5 w-5 bg-green-500 border-4 border-white rounded-full"></span>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-darkslategray">{{ $adminContact->name }}</h3>
                        <p class="text-sm text-blue-600 font-medium bg-blue-50 px-3 py-1 rounded-full inline-block mt-1">Administrator System</p>
                    </div>

                    <div class="w-full space-y-3 mt-4">
                        @if(!empty($adminContact->phone))
                            @php
                                $phone = $adminContact->phone;
                                if(substr($phone, 0, 1) == '0') $phone = '62' . substr($phone, 1);
                            @endphp
                            <a href="https://wa.me/{{ $phone }}?text=Halo%20Admin,%20saya%20butuh%20bantuan%20terkait%20Sistem..." 
                               target="_blank"
                               class="flex items-center justify-center gap-3 w-full p-4 rounded-xl bg-[#25D366] hover:bg-[#20bd5a] text-white shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all group no-underline">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                <span class="font-bold text-lg">WhatsApp Admin</span>
                            </a>
                        @endif

                        <a href="mailto:{{ $adminContact->email }}" 
                           class="flex items-center justify-center gap-3 w-full p-4 rounded-xl bg-gray-50 hover:bg-gray-100 text-darkslategray border border-gainsboro transition-colors group no-underline">
                            <svg class="w-5 h-5 text-gray-500 group-hover:text-darkslategray" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span class="font-semibold">{{ $adminContact->email }}</span>
                        </a>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-gray-400 mb-2">Belum ada data Admin</div>
                </div>
            @endif
        </div>

        <div class="p-6 border-t border-gainsboro bg-gray-50 rounded-b-2xl flex justify-center">
            <button @click="show = false" class="text-slategray hover:text-darkslategray font-medium text-sm underline">
                Tutup Jendela
            </button>
        </div>
    </div>
</div>