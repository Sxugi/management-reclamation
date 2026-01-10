<nav x-data="{ open: false }" class="w-full bg-gainsboro border-solid border-darkslategray border-b-[1px] border-r-[0px] border-l-[0px] border-t-[0px] box-border h-[78px] flex flex-row items-center justify-between py-[19px] px-5 text-left text-xl text-white">
    <!-- Logo -->
    <div class="w-[191px] h-[37px] flex flex-row items-center justify-center">
        <a href="{{ route('lahan.index') }}" class="w-[191px] h-[37px] flex">
            <x-main.application-logo class="w-[41px] h-[37px] object-cover" />
        </a>
    </div>

    <!-- Desktop Right Side Menu -->
    <div class="hidden lg:flex lg:items-center">
        <div class="flex items-center gap-4">
            <x-main.user-dropdown />
        </div>
    </div>

    <!-- Mobile menu button -->
    <div class="flex items-center lg:hidden">
        <button @click="sidebarOpen = !sidebarOpen" class="inline-flex items-center justify-center p-2 rounded-lg border-solid border-[1px] text-white hover:text-darkslategray bg-darkslategray hover:bg-white focus:outline-none transition duration-150 ease-in-out">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{'hidden': sidebarOpen, 'inline-flex': !sidebarOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{'hidden': !sidebarOpen, 'inline-flex': sidebarOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</nav>