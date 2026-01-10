<nav x-data="{ open: false }" class="w-full relative bg-gainsboro border-solid border-darkslategray border-b-[1px] border-r-[0px] border-l-[0px] border-t-[0px] box-border h-[78px] flex flex-row items-center justify-between py-[19px] px-5 text-left text-xl text-white">
    <!-- Logo -->
    <div class="w-[191px] h-[37px] flex flex-row items-center justify-center">
        <a href="{{ route('lahan.index') }}" class="w-[191px] relative h-[37px] flex">
            <x-main.application-logo class="w-[41px] h-[37px] object-cover" />
        </a>
    </div>

    <!-- Desktop Right Side Menu -->
    <div class="sm:flex sm:items-center">
        <div class="flex items-center gap-4">
            <x-main.user-dropdown />
        </div>
    </div>
</nav>