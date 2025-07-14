<div x-data="{ sidebarOpen: false, closeSidebar() { this.sidebarOpen = false; setTimeout(() => { $dispatch('close-sidebar'); }, 300); } }" x-init="$nextTick(() => sidebarOpen = true)">
    <div
        x-show="sidebarOpen"
        @click="closeSidebar()"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-20 bg-gray-600 bg-opacity-75 lg:hidden">
    </div>

    <div
        x-show="sidebarOpen"
        @click.outside="closeSidebar()"
        x-transition:enter="transition ease-in-out duration-300 transform"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in-out duration-300 transform"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 z-30 w-64 lg:hidden bg-white shadow-lg">
        @include('layouts.sidebar')
    </div>
</div>