@php
    // Get admin contact info
    $adminContact = \App\Models\User::where('role', 'admin')->first();
@endphp

<div class="relative font-outfit" x-data="{ open: false }" x-cloak>
    <!-- Trigger Button -->
    <button @click="open = !open" 
            class="flex items-center border border-solid sm:border-darkslategray sm:bg-almostgray bg-transparent border-transparent gap-3 rounded-xl sm:hover:bg-white hover:bg-opacity-5 px-3 py-2 transition-all duration-200 group flex-shrink-0 cursor-pointer">
        
        <!-- Avatar -->
        <div class="relative">
            @if(auth()->user()->avatar)
                <img class="h-10 w-10 rounded-full object-cover ring-2 ring-transparent group-hover:ring-darkslategray group-hover:ring-opacity-20 transition-all duration-200" 
                     src="{{ asset('storage/' .auth()->user()->avatar) }}" 
                     alt="{{ auth()->user()->name }}">
            @else
                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center ring-2 ring-transparent group-hover:ring-darkslategray group-hover:ring-opacity-20 transition-all duration-200">
                    <span class="text-white text-sm font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </span>
                </div>
            @endif
        </div>

        <!-- User Info (Hidden on mobile) -->
        <div class="hidden sm:block text-left">
            <p class="text-sm font-semibold text-darkslategray group-hover:text-opacity-80 transition-colors truncate max-w-3xs">
                {{ auth()->user()->name }}
            </p>
            <p class="text-xs text-gray-500 truncate max-w-3xs">
                {{ ucfirst(auth()->user()->role ?? 'User') }}
            </p>
        </div>

        <!-- Chevron Icon -->
        <svg class="w-4 h-4 text-darkslategray transition-transform duration-200 hidden sm:block"
             :class="open ? 'rotate-180' : ''"
             fill="none" 
             stroke="currentColor" 
             viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-1 w-72 rounded-xl bg-white shadow-xl border border-darkslategray overflow-hidden z-50"
         style="display:  none;">
        
        <!-- User Info Header -->
        <div class="px-4 py-4 bg-almostgray border-b border-darkslategray">
            <div class="flex items-center gap-3">
                @if(auth()->user()->avatar)
                    <img class="h-12 w-12 rounded-full object-cover" 
                         src="{{ asset('storage/' .auth()->user()->avatar) }}" 
                         alt="{{ auth()->user()->name }}">
                @else
                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                        <span class="text-white font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </span>
                    </div>
                @endif
                
                <div class="flex-1 min-w-0">
                    <div class="flex flex-row justify-between items-center gap-2">
                        <div>
                            <p class="text-sm font-semibold text-darkslategray truncate">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-xs text-gray-600 truncate">
                                {{ auth()->user()->email }}
                            </p>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-white text-darkslategray">
                            {{ ucfirst(auth()->user()->role ?? 'User') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Items -->
        <div>
            <!-- Profile Link -->
            <a href="{{ route('profile.edit') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm text-darkslategray hover:bg-blue-50 transition-colors no-underline group">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 group-hover:bg-blue-200 transition-colors">
                    <svg width="18" height="18" viewBox="0 0 18 18" class="text-blue-600" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 7.5C9.34472 7.5 9.68606 7.4321 10.0045 7.30018C10.323 7.16827 10.6124 6.97491 10.8562 6.73116C11.0999 6.4874 11.2933 6.19802 11.4252 5.87954C11.5571 5.56106 11.625 5.21972 11.625 4.875C11.625 4.53028 11.5571 4.18894 11.4252 3.87046C11.2933 3.55198 11.0999 3.2626 10.8562 3.01884C10.6124 2.77509 10.323 2.58173 10.0045 2.44982C9.68606 2.3179 9.34472 2.25 9 2.25C8.30381 2.25 7.63613 2.52656 7.14384 3.01884C6.65156 3.51113 6.375 4.17881 6.375 4.875C6.375 5.57119 6.65156 6.23887 7.14384 6.73116C7.63613 7.22344 8.30381 7.5 9 7.5ZM2.25 15.3V15.75H15.75V15.3C15.75 13.62 15.75 12.78 15.423 12.138C15.1354 11.5735 14.6765 11.1146 14.112 10.827C13.47 10.5 12.63 10.5 10.95 10.5H7.05C5.37 10.5 4.53 10.5 3.888 10.827C3.32354 11.1146 2.86462 11.5735 2.577 12.138C2.25 12.78 2.25 13.62 2.25 15.3Z" fill="currentColor" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-medium">Profile Settings</p>
                    <p class="text-xs text-gray-500">Manage your account</p>
                </div>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-darkslategray group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>

            @if(auth()->user()->role === 'user')
                <button @click="open = false; $dispatch('open-modal', 'contact-admin-modal')" 
                class="w-full flex items-center gap-3 px-4 py-3 text-sm text-darkslategray hover:bg-green-50 transition-colors cursor-pointer group text-left">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-green-100 group-hover:bg-green-200 transition-colors">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="text-green-600" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium">Hubungi Admin</p>
                        <p class="text-xs text-gray-500">Butuh bantuan?</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-darkslategray group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            @endif

            <!-- Settings Link (Optional) -->
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" 
                class="flex items-center gap-3 px-4 py-3 text-sm text-darkslategray hover:bg-purple-50 transition-colors no-underline group">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-purple-100 group-hover:bg-purple-200 transition-colors">
                        <svg width="18" height="18" viewBox="0 0 18 18" class="text-purple-600" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M9.00883 6.86602C8.48324 6.86602 7.99105 7.06992 7.6184 7.44258C7.2475 7.81523 7.04184 8.30742 7.04184 8.83301C7.04184 9.35859 7.2475 9.85078 7.6184 10.2234C7.99105 10.5943 8.48324 10.8 9.00883 10.8C9.53441 10.8 10.0266 10.5943 10.3993 10.2234C10.7702 9.85078 10.9758 9.35859 10.9758 8.83301C10.9758 8.30742 10.7702 7.81523 10.3993 7.44258C10.2172 7.25916 10.0006 7.11374 9.7619 7.01477C9.52321 6.91579 9.26723 6.86523 9.00883 6.86602ZM16.2563 11.0057L15.1067 10.023C15.1612 9.68906 15.1893 9.34805 15.1893 9.00879C15.1893 8.66953 15.1612 8.32676 15.1067 7.99453L16.2563 7.01191C16.3431 6.93757 16.4053 6.83855 16.4345 6.72803C16.4637 6.6175 16.4586 6.5007 16.4198 6.39316L16.4039 6.34746C16.0876 5.46274 15.6135 4.64266 15.0047 3.92695L14.9731 3.89004C14.8992 3.80312 14.8006 3.74064 14.6905 3.71083C14.5804 3.68102 14.4638 3.68528 14.3561 3.72305L12.9288 4.23105C12.4014 3.79863 11.8143 3.45762 11.178 3.22031L10.902 1.72793C10.8812 1.6155 10.8266 1.51207 10.7456 1.43138C10.6646 1.35069 10.561 1.29656 10.4485 1.27617L10.401 1.26738C9.48695 1.10215 8.52367 1.10215 7.60961 1.26738L7.56215 1.27617C7.44964 1.29656 7.346 1.35069 7.265 1.43138C7.18399 1.51207 7.12945 1.6155 7.10863 1.72793L6.8309 3.22734C6.20059 3.46653 5.61342 3.80672 5.09242 4.23457L3.65453 3.72305C3.54689 3.68498 3.43022 3.68056 3.32001 3.71039C3.2098 3.74022 3.11128 3.80288 3.03754 3.89004L3.0059 3.92695C2.3982 4.64341 1.92426 5.46329 1.60668 6.34746L1.59086 6.39316C1.51176 6.61289 1.5768 6.85898 1.75434 7.01191L2.91801 8.00508C2.86352 8.33555 2.83715 8.67305 2.83715 9.00703C2.83715 9.34453 2.86352 9.68203 2.91801 10.009L1.75785 11.0021C1.67101 11.0765 1.60885 11.1755 1.57965 11.286C1.55045 11.3966 1.55559 11.5134 1.59437 11.6209L1.6102 11.6666C1.92836 12.5508 2.3977 13.3682 3.00941 14.0871L3.04105 14.124C3.11498 14.2109 3.2135 14.2734 3.32364 14.3032C3.43379 14.333 3.55038 14.3288 3.65805 14.291L5.09594 13.7795C5.61977 14.2102 6.20336 14.5512 6.83441 14.7867L7.11215 16.2861C7.13297 16.3986 7.18751 16.502 7.26851 16.5827C7.34952 16.6634 7.45316 16.7175 7.56566 16.7379L7.61313 16.7467C8.53618 16.9128 9.48148 16.9128 10.4045 16.7467L10.452 16.7379C10.5645 16.7175 10.6681 16.6634 10.7491 16.5827C10.8301 16.502 10.8847 16.3986 10.9055 16.2861L11.1815 14.7938C11.8178 14.5547 12.4049 14.2154 12.9323 13.783L14.3596 14.291C14.4672 14.3291 14.5839 14.3335 14.6941 14.3037C14.8043 14.2738 14.9029 14.2112 14.9766 14.124L15.0082 14.0871C15.62 13.3646 16.0893 12.5508 16.4075 11.6666L16.4233 11.6209C16.4989 11.4029 16.4338 11.1586 16.2563 11.0057ZM9.00883 11.9232C7.30199 11.9232 5.91859 10.5398 5.91859 8.83301C5.91859 7.12617 7.30199 5.74277 9.00883 5.74277C10.7157 5.74277 12.0991 7.12617 12.0991 8.83301C12.0991 10.5398 10.7157 11.9232 9.00883 11.9232Z" fill="evenodd"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium">Admin Config</p>
                        <p class="text-xs text-gray-500">System configuration</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-darkslategray group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            @endif
        </div>

        <!-- Divider -->
        <div class="border-t border-darkslategray"></div>

        <!-- Logout -->
        <div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors group">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 group-hover:bg-red-200 transition-colors">
                        <svg width="18" height="18" viewBox="0 0 16 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.5 6V4.5C10.5 4.10218 10.342 3.72064 10.0607 3.43934C9.77936 3.15804 9.39782 3 9 3H3.75C3.35218 3 2.97064 3.15804 2.68934 3.43934C2.40804 3.72064 2.25 4.10218 2.25 4.5V13.5C2.25 13.8978 2.40804 14.2794 2.68934 14.5607C2.97064 14.842 3.35218 15 3.75 15H9C9.39782 15 9.77936 14.842 10.0607 14.5607C10.342 14.2794 10.5 13.8978 10.5 13.5V12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6.75 9H15.75M15.75 9L13.5 6.75M15.75 9L13.5 11.25" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex-1 text-left">
                        <p class="font-medium">Logout</p>
                        <p class="text-xs text-red-400">Sign out of your account</p>
                    </div>
                </button>
            </form>
        </div>
    </div>

    @if($adminContact)
        <x-admin.contact-admin-modal :adminContact="$adminContact" />
    @endif
</div>