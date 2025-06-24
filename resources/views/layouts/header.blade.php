<nav x-data="{ open: false }" class="w-full relative bg-gainsboro border-solid border-darkslategray border-b-[1px] border-r-[0px] border-l-[0px] border-t-[0px] box-border h-[78px] flex flex-row items-center justify-between py-[19px] px-5 text-left text-xl text-white">
    <!-- Logo -->
    <div class="w-[191px] h-[37px] flex flex-row items-center justify-center">
        <a class="w-[191px] relative h-[37px] flex">
            <x-main.application-logo class="w-[41px] h-[37px] object-cover" />
        </a>
    </div>

    <!-- Desktop Right Side Menu -->
    <div class="hidden md:flex md:items-center">
        <div class="flex items-center gap-4">
            <!-- Profile Link -->
            <a type="button" href="{{ route('profile.edit') }}" class="functional-button w-[118px] rounded-lg bg-darkslategray h-9 flex flex-row items-center justify-between py-[15px] px-5 box-border gap-0 text-sm text-white font-outfit no-underline hover:bg-slategray-200">
                <div class="relative leading-5 font-medium">Profile</div>
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </a>
            
            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-main.primary-button class="w-[118px] rounded-lg bg-darkslategray h-9 flex flex-row items-center justify-between py-[15px] px-5 box-border gap-0 text-sm text-white">
                    <span>Logout</span>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14 8V6C14 5.46957 13.7893 4.96086 13.4142 4.58579C13.0391 4.21071 12.5304 4 12 4H5C4.46957 4 3.96086 4.21071 3.58579 4.58579C3.21071 4.96086 3 5.46957 3 6V18C3 18.5304 3.21071 19.0391 3.58579 19.4142C3.96086 19.7893 4.46957 20 5 20H12C12.5304 20 13.0391 19.7893 13.4142 19.4142C13.7893 19.0391 14 18.5304 14 18V16" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9 12H21M21 12L18 9M21 12L18 15" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </x-main.primary-button>
            </form>
        </div>
    </div>

    <!-- Mobile menu button -->
    <div class="flex items-center md:hidden">
        <button @click="sidebarOpen = !sidebarOpen" class="inline-flex items-center justify-center p-2 rounded-md border-solid border-[1px] text-white hover:text-darkslategray bg-darkslategray hover:bg-white focus:outline-none transition duration-150 ease-in-out">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{'hidden': sidebarOpen, 'inline-flex': !sidebarOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{'hidden': !sidebarOpen, 'inline-flex': sidebarOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</nav>