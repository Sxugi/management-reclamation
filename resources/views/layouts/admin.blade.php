<! DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="turbo-cache-control" content="no-cache">
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

        <title>@yield('title', 'Admin') - {{ config('app.name', 'Management Reclamation') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <style>
        body {
            margin:   0;
            padding: 0;
        }
        @media (min-width: 1024px) {
            .main-content {
                margin-left: 16rem;
            }
            .sidebar-spacer {
                min-width: 16rem;
                width: 16rem;
            }
        }
    </style>
    <body class="font-outfit antialiased bg-whitesmoke">
        <div class="min-h-full" x-data="{ mobileMenuOpen: false }">
            <div class="w-full min-h-screen">
                <div class="min-h-full bg-whitesmoke flex flex-row">
                    
                    <!-- Desktop Sidebar -->
                    <div class="hidden lg:block h-screen">
                        <div class="h-full w-64 fixed overflow-y-auto overflow-x-hidden bg-gainsboro border-darkslategray border-solid border-b-[0px] border-r-[1px] border-l-[0px] border-t-[0px] box-border flex flex-col items-start justify-start pt-8 px-5 pb-5 gap-7 text-left text-sm text-white font-outfit">

                            <!-- Home Button -->
                            <div class="self-stretch flex flex-col items-start justify-start">
                                <a href="{{ route('lahan.index') }}" class="self-stretch shadow-[0px_1px_2px_rgba(16,_24,_40,_0.05)] rounded-lg bg-darkslategray h-9 overflow-hidden flex flex-row items-center justify-start py-3.5 px-4 box-border gap-3 no-underline text-white hover:bg-white hover:text-darkslategray ease-in-out">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 19V10C4 9.68333 4.071 9.38333 4.213 9.1C4.355 8.81667 4.55067 8.58333 4.8 8.4L10.8 3.9C11.15 3.63333 11.55 3.5 12 3.5C12.45 3.5 12.85 3.63333 13.2 3.9L19.2 8.4C19.45 8.58333 19.646 8.81667 19.788 9.1C19.93 9.38333 20.0007 9.68333 20 10V19C20 19.55 19.804 20.021 19.412 20.413C19.02 20.805 18.5493 21.0007 18 21H15C14.7167 21 14.4793 20.904 14.288 20.712C14.0967 20.52 14.0007 20.2827 14 20V15C14 14.7167 13.904 14.4793 13.712 14.288C13.52 14.0967 13.2827 14.0007 13 14H11C10.7167 14 10.4793 14.096 10.288 14.288C10.0967 14.48 10.0007 14.7173 10 15V20C10 20.2833 9.904 20.521 9.712 20.713C9.52 20.905 9.28267 21.0007 9 21H6C5.45 21 4.97933 20.8043 4.588 20.413C4.19667 20.0217 4.00067 19.5507 4 19Z" fill="currentColor"/>
                                    </svg>
                                    <div class="relative leading-5 font-medium">Home</div>
                                </a>
                            </div>

                            <!-- Menu Section -->
                            <div class="self-stretch flex flex-col items-start justify-start gap-2.5">
                                <div class="self-stretch flex flex-col items-start justify-start gap-4">
                                    <div class="relative leading-5 uppercase text-xs text-darkslategray">MENU</div>
                                    
                                    <div class="self-stretch flex flex-col items-start justify-start gap-1 text-sm">
                                        <!-- Dashboard -->
                                        <a href="{{ route('admin.dashboard') }}"
                                        class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out
                                        {{ request()->routeIs('admin.dashboard') ? 'bg-white ! text-darkslategray' :  'bg-darkslategray text-white' }}">
                                            <div class="flex-1 flex flex-row items-center justify-start gap-3">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9 12.75C10.2426 12.75 11.25 13.7574 11.25 15V18.5C11.25 19.7426 10.2426 20.75 9 20.75H5.5C4.25737 20.75 3.25001 19.7426 3.25 18.5V15C3.25002 13.7574 4.25737 12.75 5.5 12.75H9ZM18.5 12.75C19.7426 12.75 20.75 13.7574 20.75 15V18.5C20.75 19.7426 19.7426 20.75 18.5 20.75H15C13.7574 20.75 12.75 19.7426 12.75 18.5V15C12.75 13.7574 13.7574 12.75 15 12.75H18.5ZM5.5 14.25C5.0858 14.25 4.75002 14.5858 4.75 15V18.5C4.75001 18.9142 5.08579 19.25 5.5 19.25H9C9.41421 19.25 9.74999 18.9142 9.75 18.5V15C9.74998 14.5858 9.4142 14.25 9 14.25H5.5ZM15 14.25C14.5858 14.25 14.25 14.5858 14.25 15V18.5C14.25 18.9142 14.5858 19.25 15 19.25H18.5C18.9142 19.25 19.25 18.9142 19.25 18.5V15C19.25 14.5858 18.9142 14.25 18.5 14.25H15ZM9 3.25C10.2426 3.25 11.25 4.25736 11.25 5.5V9C11.25 10.2426 10.2426 11.25 9 11.25H5.5C4.25737 11.25 3.25001 10.2426 3.25 9V5.5C3.25 4.25736 4.25736 3.25 5.5 3.25H9ZM18.5 3.25C19.7426 3.25 20.75 4.25736 20.75 5.5V9C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 9V5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5ZM5.5 4.75C5.08579 4.75 4.75 5.08579 4.75 5.5V9C4.75001 9.4142 5.08579 9.75 5.5 9.75H9C9.41421 9.75 9.74999 9.4142 9.75 9V5.5C9.75 5.08579 9.41421 4.75 9 4.75H5.5ZM15 4.75C14.5858 4.75 14.25 5.08579 14.25 5.5V9C14.25 9.4142 14.5858 9.75 15 9.75H18.5C18.9142 9.75 19.25 9.4142 19.25 9V5.5C19.25 5.08579 18.9142 4.75 18.5 4.75H15Z" fill="currentColor"/>
                                                </svg>
                                                <div class="flex-1 relative leading-5 font-medium">Dashboard</div>
                                            </div>
                                        </a>

                                        <!-- Kelola User -->
                                        <a href="{{ route('admin.users.index') }}"
                                        class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out
                                        {{ request()->routeIs('admin.users.*') ? 'bg-white !text-darkslategray' :  'bg-darkslategray text-white' }}">
                                            <div class="flex-1 flex flex-row items-center justify-start gap-3">
                                                <svg width="24" height="24" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M17.754 11C18.72 11 19.504 11.784 19.504 12.75V19.499C19.504 20.958 18.9244 22.3572 17.8928 23.3888C16.8612 24.4204 15.462 25 14.003 25C12.544 25 11.1448 24.4204 10.1132 23.3888C9.08157 22.3572 8.502 20.958 8.502 19.499V12.75C8.502 11.784 9.285 11 10.252 11H17.754ZM3.75 11L8.132 10.998C7.77165 11.4322 7.5547 11.9674 7.511 12.53L7.501 12.75V19.499C7.501 20.632 7.792 21.698 8.301 22.626C7.61593 22.9252 6.86712 23.049 6.12221 22.9862C5.3773 22.9235 4.65975 22.6761 4.0344 22.2665C3.40905 21.8569 2.89558 21.298 2.5404 20.6402C2.18521 19.9825 1.99948 19.2466 2 18.499V12.75C2 12.5201 2.0453 12.2925 2.13331 12.0801C2.22132 11.8677 2.35031 11.6747 2.51292 11.5122C2.67553 11.3497 2.86856 11.2208 3.081 11.1329C3.29343 11.045 3.5201 10.9999 3.75 11ZM19.875 10.998L24.25 11C25.216 11 26 11.784 26 12.75V18.5C26.0003 19.2472 25.8146 19.9826 25.4595 20.6401C25.1045 21.2975 24.5914 21.8562 23.9664 22.2657C23.3415 22.6752 22.6244 22.9227 21.8799 22.9857C21.1354 23.0488 20.3869 22.9255 19.702 22.627L19.758 22.525C20.187 21.712 20.448 20.796 20.496 19.825L20.504 19.499V12.75C20.504 12.084 20.268 11.474 19.875 10.998ZM14 3C14.4596 3 14.9148 3.09053 15.3394 3.26642C15.764 3.44231 16.1499 3.70012 16.4749 4.02513C16.7999 4.35013 17.0577 4.73597 17.2336 5.16061C17.4095 5.58525 17.5 6.04037 17.5 6.5C17.5 6.95963 17.4095 7.41475 17.2336 7.83939C17.0577 8.26403 16.7999 8.64987 16.4749 8.97487C16.1499 9.29988 15.764 9.55769 15.3394 9.73358C14.9148 9.90947 14.4596 10 14 10C13.0717 10 12.1815 9.63125 11.5251 8.97487C10.8688 8.3185 10.5 7.42826 10.5 6.5C10.5 5.57174 10.8688 4.6815 11.5251 4.02513C12.1815 3.36875 13.0717 3 14 3ZM22.003 4C22.397 4 22.7871 4.0776 23.1511 4.22836C23.515 4.37913 23.8457 4.6001 24.1243 4.87868C24.4029 5.15726 24.6239 5.48797 24.7746 5.85195C24.9254 6.21593 25.003 6.60603 25.003 7C25.003 7.39397 24.9254 7.78407 24.7746 8.14805C24.6239 8.51203 24.4029 8.84274 24.1243 9.12132C23.8457 9.3999 23.515 9.62087 23.1511 9.77164C22.7871 9.9224 22.397 10 22.003 10C21.2074 10 20.4443 9.68393 19.8817 9.12132C19.3191 8.55871 19.003 7.79565 19.003 7C19.003 6.20435 19.3191 5.44129 19.8817 4.87868C20.4443 4.31607 21.2074 4 22.003 4ZM5.997 4C6.39097 4 6.78107 4.0776 7.14505 4.22836C7.50903 4.37913 7.83975 4.6001 8.11832 4.87868C8.3969 5.15726 8.61788 5.48797 8.76864 5.85195C8.9194 6.21593 8.997 6.60603 8.997 7C8.997 7.39397 8.9194 7.78407 8.76864 8.14805C8.61788 8.51203 8.3969 8.84274 8.11832 9.12132C7.83975 9.3999 7.50903 9.62087 7.14505 9.77164C6.78107 9.9224 6.39097 10 5.997 10C5.20135 10 4.43829 9.68393 3.87568 9.12132C3.31307 8.55871 2.997 7.79565 2.997 7C2.997 6.20435 3.31307 5.44129 3.87568 4.87868C4.43829 4.31607 5.20135 4 5.997 4Z" fill="currentColor"/>
                                                </svg>  
                                                <div class="flex-1 relative leading-5 font-medium">Kelola User</div>
                                            </div>
                                        </a>

                                        <!-- Jenis Pohon -->
                                        <a href="{{ route('admin.jenis-pohon.index') }}"
                                        class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out
                                        {{ request()->routeIs('admin.jenis-pohon.*') ? 'bg-white !text-darkslategray' :  'bg-darkslategray text-white' }}">
                                            <div class="flex-1 flex flex-row items-center justify-start gap-3">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M14.2499 19V17.75H10.7499V21C10.7499 21.1989 10.6709 21.3897 10.5302 21.5303C10.3896 21.671 10.1988 21.75 9.99991 21.75C9.801 21.75 9.61023 21.671 9.46958 21.5303C9.32893 21.3897 9.24991 21.1989 9.24991 21V17.75H2.99991C2.86308 17.7499 2.72889 17.7124 2.61187 17.6415C2.49484 17.5706 2.39944 17.469 2.33599 17.3478C2.27255 17.2265 2.24347 17.0903 2.25192 16.9537C2.26036 16.8171 2.30601 16.6855 2.38391 16.573L6.06891 11.25H4.49991C4.36147 11.2498 4.22578 11.2112 4.10789 11.1387C3.99 11.0661 3.8945 10.9623 3.83197 10.8388C3.76943 10.7153 3.7423 10.5768 3.75359 10.4389C3.76488 10.3009 3.81413 10.1687 3.89591 10.057L9.39591 2.557L9.45191 2.488C9.52723 2.40741 9.6194 2.34444 9.72186 2.30355C9.82431 2.26267 9.93452 2.24489 10.0446 2.25148C10.1547 2.25807 10.262 2.28887 10.3589 2.34168C10.4557 2.39448 10.5397 2.46801 10.6049 2.557L13.0489 5.89L14.4689 4.47L14.5299 4.415C14.6089 4.35154 14.7 4.30475 14.7976 4.27746C14.8952 4.25017 14.9973 4.24295 15.0978 4.25622C15.1982 4.26949 15.295 4.30298 15.3821 4.35468C15.4693 4.40638 15.5451 4.4752 15.6049 4.557L21.1049 12.057L21.1609 12.145C21.2223 12.2592 21.253 12.3874 21.2501 12.517C21.2471 12.6466 21.2106 12.7733 21.1441 12.8846C21.0777 12.9959 20.9834 13.0881 20.8707 13.1521C20.758 13.2162 20.6306 13.2499 20.5009 13.25H19.0329L21.5919 16.54C21.678 16.6508 21.7312 16.7836 21.7456 16.9232C21.76 17.0627 21.7349 17.2036 21.6732 17.3296C21.6116 17.4557 21.5158 17.5619 21.3967 17.6362C21.2777 17.7105 21.1402 17.7499 20.9999 17.75H15.7499V19C15.7499 19.1989 15.6709 19.3897 15.5302 19.5303C15.3896 19.671 15.1988 19.75 14.9999 19.75C14.801 19.75 14.6102 19.671 14.4696 19.5303C14.3289 19.3897 14.2499 19.1989 14.2499 19Z" fill="currentColor"/>
                                                </svg>
                                                <div class="flex-1 relative leading-5 font-medium">Jenis Pohon</div>
                                            </div>
                                        </a>

                                        <!-- Kategori Anggaran -->
                                        <a href="{{ route('admin.kategori-anggaran.index') }}"
                                        class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out
                                        {{ request()->routeIs('admin.kategori-anggaran.*') ? 'bg-white !text-darkslategray' :  'bg-darkslategray text-white' }}">
                                            <div class="flex-1 flex flex-row items-center justify-start gap-3">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M3 10V8C3 7.46957 3.21071 6.96086 3.58579 6.58579C3.96086 6.21071 4.46957 6 5 6H7M3 10C4.333 10 7 9.2 7 6M3 10V14M7 6H17M3 14V16C3 16.5304 3.21071 17.0391 3.58579 17.4142C3.96086 17.7893 4.46957 18 5 18H7M3 14C4.333 14 7 14.8 7 18M21 10V8C21 7.46957 20.7893 6.96086 20.4142 6.58579C20.0391 6.21071 19.5304 6 19 6H17M21 10C19.667 10 17 9.2 17 6M21 10V12M7 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M12 14C13.1046 14 14 13.1046 14 12C14 10.8954 13.1046 10 12 10C10.8954 10 10 10.8954 10 12C10 13.1046 10.8954 14 12 14Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M18 15V18M18 18V21M18 18H15M18 18H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                <div class="flex-1 relative leading-5 font-medium">Kategori Anggaran</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                <!-- Setting Section -->
                                <div class="self-stretch flex flex-col items-start justify-start gap-4 mt-4">
                                    <div class="self-stretch relative leading-5 uppercase text-xs text-darkslategray">SETTING</div>

                                    <div class="self-stretch flex flex-col items-start justify-start gap-1 text-sm text-white">
                                        <!-- Profile Link -->
                                        <a href="{{ route('profile.edit') }}" class="self-stretch rounded-lg bg-darkslategray flex flex-row items-center justify-start py-2 px-3 no-underline text-white hover:bg-white hover:text-darkslategray ease-in-out">
                                            <div class="flex-1 flex flex-row items-center justify-start gap-3">
                                                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="12" cy="7" r="4"></circle>
                                                </svg>
                                                <div class="flex-1 relative leading-5 font-medium">Profile</div>
                                            </div>
                                        </a>
                                        
                                        <!-- Logout Button -->
                                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                                            @csrf
                                            <button type="submit" class="w-full border-none rounded-lg bg-darkslategray flex flex-row items-center justify-start py-2 px-3 no-underline text-white font-outfit font-medium hover:bg-white hover:text-darkslategray ease-in-out">
                                                <div class="flex-1 flex flex-row items-center justify-start gap-3">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M14 8V6C14 5.46957 13.7893 4.96086 13.4142 4.58579C13.0391 4.21071 12.5304 4 12 4H5C4.46957 4 3.96086 4.21071 3.58579 4.58579C3.21071 4.96086 3 5.46957 3 6V18C3 18.5304 3.21071 19.0391 3.58579 19.4142C3.96086 19.7893 4.46957 20 5 20H12C12.5304 20 13.0391 19.7893 13.4142 19.4142C13.7893 19.0391 14 18.5304 14 18V16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M9 12H21M21 12L18 9M21 12L18 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                    <span>Logout</span>
                                                </div>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Sidebar Overlay -->
                    <div
                        x-show="mobileMenuOpen"
                        @click="mobileMenuOpen = false"
                        x-transition:enter="transition-opacity ease-linear duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition-opacity ease-linear duration-300"
                        x-transition: leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 z-40 bg-black/50 lg:hidden"
                        style="display: none;">
                    </div>

                    <!-- Mobile Sidebar -->
                    <div
                        x-show="mobileMenuOpen"
                        @click.outside="mobileMenuOpen = false"
                        x-transition:enter="transition ease-in-out duration-300 transform"
                        x-transition:enter-start="-translate-x-full"
                        x-transition:enter-end="translate-x-0"
                        x-transition:leave="transition ease-in-out duration-300 transform"
                        x-transition:leave-start="translate-x-0"
                        x-transition:leave-end="-translate-x-full"
                        class="fixed inset-y-0 left-0 z-50 w-64 lg:hidden bg-gainsboro shadow-lg overflow-y-auto"
                        style="display: none;">
                        
                        <!-- Mobile Sidebar Content -->
                        <div class="flex flex-col h-full pt-8 px-5 pb-5 gap-7 text-left text-sm text-white font-outfit">
                            <!-- Close Button -->
                            <div class="flex justify-end">
                                <button @click="mobileMenuOpen = false" class="text-darkslategray hover:text-white hover:bg-darkslategray rounded-lg p-2 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Home Button -->
                            <div class="self-stretch flex flex-col items-start justify-start">
                                <a href="{{ route('lahan.index') }}" class="self-stretch shadow-[0px_1px_2px_rgba(16,_24,_40,_0.05)] rounded-lg bg-darkslategray h-9 overflow-hidden flex flex-row items-center justify-start py-3.5 px-4 box-border gap-3 no-underline text-white hover:bg-white hover:text-darkslategray ease-in-out">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 19V10C4 9.68333 4.071 9.38333 4.213 9.1C4.355 8.81667 4.55067 8.58333 4.8 8.4L10.8 3.9C11.15 3.63333 11.55 3.5 12 3.5C12.45 3.5 12.85 3.63333 13.2 3.9L19.2 8.4C19.45 8.58333 19.646 8.81667 19.788 9.1C19.93 9.38333 20.0007 9.68333 20 10V19C20 19.55 19.804 20.021 19.412 20.413C19.02 20.805 18.5493 21.0007 18 21H15C14.7167 21 14.4793 20.904 14.288 20.712C14.0967 20.52 14.0007 20.2827 14 20V15C14 14.7167 13.904 14.4793 13.712 14.288C13.52 14.0967 13.2827 14.0007 13 14H11C10.7167 14 10.4793 14.096 10.288 14.288C10.0967 14.48 10.0007 14.7173 10 15V20C10 20.2833 9.904 20.521 9.712 20.713C9.52 20.905 9.28267 21.0007 9 21H6C5.45 21 4.97933 20.8043 4.588 20.413C4.19667 20.0217 4.00067 19.5507 4 19Z" fill="currentColor"/>
                                    </svg>
                                    <div class="relative leading-5 font-medium">Home</div>
                                </a>
                            </div>

                            <!-- Menu Section -->
                            <div class="self-stretch flex flex-col items-start justify-start gap-2.5">
                                <div class="self-stretch flex flex-col items-start justify-start gap-4">
                                    <div class="relative leading-5 uppercase text-xs text-darkslategray">MENU</div>
                                    
                                    <div class="self-stretch flex flex-col items-start justify-start gap-1 text-sm">
                                        <!-- Dashboard -->
                                        <a href="{{ route('admin.dashboard') }}"
                                        class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out
                                        {{ request()->routeIs('admin.dashboard') ? 'bg-white ! text-darkslategray' :  'bg-darkslategray text-white' }}">
                                            <div class="flex-1 flex flex-row items-center justify-start gap-3">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9 12.75C10.2426 12.75 11.25 13.7574 11.25 15V18.5C11.25 19.7426 10.2426 20.75 9 20.75H5.5C4.25737 20.75 3.25001 19.7426 3.25 18.5V15C3.25002 13.7574 4.25737 12.75 5.5 12.75H9ZM18.5 12.75C19.7426 12.75 20.75 13.7574 20.75 15V18.5C20.75 19.7426 19.7426 20.75 18.5 20.75H15C13.7574 20.75 12.75 19.7426 12.75 18.5V15C12.75 13.7574 13.7574 12.75 15 12.75H18.5ZM5.5 14.25C5.0858 14.25 4.75002 14.5858 4.75 15V18.5C4.75001 18.9142 5.08579 19.25 5.5 19.25H9C9.41421 19.25 9.74999 18.9142 9.75 18.5V15C9.74998 14.5858 9.4142 14.25 9 14.25H5.5ZM15 14.25C14.5858 14.25 14.25 14.5858 14.25 15V18.5C14.25 18.9142 14.5858 19.25 15 19.25H18.5C18.9142 19.25 19.25 18.9142 19.25 18.5V15C19.25 14.5858 18.9142 14.25 18.5 14.25H15ZM9 3.25C10.2426 3.25 11.25 4.25736 11.25 5.5V9C11.25 10.2426 10.2426 11.25 9 11.25H5.5C4.25737 11.25 3.25001 10.2426 3.25 9V5.5C3.25 4.25736 4.25736 3.25 5.5 3.25H9ZM18.5 3.25C19.7426 3.25 20.75 4.25736 20.75 5.5V9C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 9V5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5ZM5.5 4.75C5.08579 4.75 4.75 5.08579 4.75 5.5V9C4.75001 9.4142 5.08579 9.75 5.5 9.75H9C9.41421 9.75 9.74999 9.4142 9.75 9V5.5C9.75 5.08579 9.41421 4.75 9 4.75H5.5ZM15 4.75C14.5858 4.75 14.25 5.08579 14.25 5.5V9C14.25 9.4142 14.5858 9.75 15 9.75H18.5C18.9142 9.75 19.25 9.4142 19.25 9V5.5C19.25 5.08579 18.9142 4.75 18.5 4.75H15Z" fill="currentColor"/>
                                                </svg>
                                                <div class="flex-1 relative leading-5 font-medium">Dashboard</div>
                                            </div>
                                        </a>

                                        <!-- Kelola User -->
                                        <a href="{{ route('admin.users.index') }}"
                                        class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out
                                        {{ request()->routeIs('admin.users.*') ? 'bg-white !text-darkslategray' :  'bg-darkslategray text-white' }}">
                                            <div class="flex-1 flex flex-row items-center justify-start gap-3">
                                                <svg width="24" height="24" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M17.754 11C18.72 11 19.504 11.784 19.504 12.75V19.499C19.504 20.958 18.9244 22.3572 17.8928 23.3888C16.8612 24.4204 15.462 25 14.003 25C12.544 25 11.1448 24.4204 10.1132 23.3888C9.08157 22.3572 8.502 20.958 8.502 19.499V12.75C8.502 11.784 9.285 11 10.252 11H17.754ZM3.75 11L8.132 10.998C7.77165 11.4322 7.5547 11.9674 7.511 12.53L7.501 12.75V19.499C7.501 20.632 7.792 21.698 8.301 22.626C7.61593 22.9252 6.86712 23.049 6.12221 22.9862C5.3773 22.9235 4.65975 22.6761 4.0344 22.2665C3.40905 21.8569 2.89558 21.298 2.5404 20.6402C2.18521 19.9825 1.99948 19.2466 2 18.499V12.75C2 12.5201 2.0453 12.2925 2.13331 12.0801C2.22132 11.8677 2.35031 11.6747 2.51292 11.5122C2.67553 11.3497 2.86856 11.2208 3.081 11.1329C3.29343 11.045 3.5201 10.9999 3.75 11ZM19.875 10.998L24.25 11C25.216 11 26 11.784 26 12.75V18.5C26.0003 19.2472 25.8146 19.9826 25.4595 20.6401C25.1045 21.2975 24.5914 21.8562 23.9664 22.2657C23.3415 22.6752 22.6244 22.9227 21.8799 22.9857C21.1354 23.0488 20.3869 22.9255 19.702 22.627L19.758 22.525C20.187 21.712 20.448 20.796 20.496 19.825L20.504 19.499V12.75C20.504 12.084 20.268 11.474 19.875 10.998ZM14 3C14.4596 3 14.9148 3.09053 15.3394 3.26642C15.764 3.44231 16.1499 3.70012 16.4749 4.02513C16.7999 4.35013 17.0577 4.73597 17.2336 5.16061C17.4095 5.58525 17.5 6.04037 17.5 6.5C17.5 6.95963 17.4095 7.41475 17.2336 7.83939C17.0577 8.26403 16.7999 8.64987 16.4749 8.97487C16.1499 9.29988 15.764 9.55769 15.3394 9.73358C14.9148 9.90947 14.4596 10 14 10C13.0717 10 12.1815 9.63125 11.5251 8.97487C10.8688 8.3185 10.5 7.42826 10.5 6.5C10.5 5.57174 10.8688 4.6815 11.5251 4.02513C12.1815 3.36875 13.0717 3 14 3ZM22.003 4C22.397 4 22.7871 4.0776 23.1511 4.22836C23.515 4.37913 23.8457 4.6001 24.1243 4.87868C24.4029 5.15726 24.6239 5.48797 24.7746 5.85195C24.9254 6.21593 25.003 6.60603 25.003 7C25.003 7.39397 24.9254 7.78407 24.7746 8.14805C24.6239 8.51203 24.4029 8.84274 24.1243 9.12132C23.8457 9.3999 23.515 9.62087 23.1511 9.77164C22.7871 9.9224 22.397 10 22.003 10C21.2074 10 20.4443 9.68393 19.8817 9.12132C19.3191 8.55871 19.003 7.79565 19.003 7C19.003 6.20435 19.3191 5.44129 19.8817 4.87868C20.4443 4.31607 21.2074 4 22.003 4ZM5.997 4C6.39097 4 6.78107 4.0776 7.14505 4.22836C7.50903 4.37913 7.83975 4.6001 8.11832 4.87868C8.3969 5.15726 8.61788 5.48797 8.76864 5.85195C8.9194 6.21593 8.997 6.60603 8.997 7C8.997 7.39397 8.9194 7.78407 8.76864 8.14805C8.61788 8.51203 8.3969 8.84274 8.11832 9.12132C7.83975 9.3999 7.50903 9.62087 7.14505 9.77164C6.78107 9.9224 6.39097 10 5.997 10C5.20135 10 4.43829 9.68393 3.87568 9.12132C3.31307 8.55871 2.997 7.79565 2.997 7C2.997 6.20435 3.31307 5.44129 3.87568 4.87868C4.43829 4.31607 5.20135 4 5.997 4Z" fill="currentColor"/>
                                                </svg>
                                                <div class="flex-1 relative leading-5 font-medium">Kelola User</div>
                                            </div>
                                        </a>

                                        <!-- Jenis Pohon -->
                                        <a href="{{ route('admin.jenis-pohon.index') }}"
                                        class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out
                                        {{ request()->routeIs('admin.jenis-pohon.*') ? 'bg-white !text-darkslategray' :  'bg-darkslategray text-white' }}">
                                            <div class="flex-1 flex flex-row items-center justify-start gap-3">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M14.2499 19V17.75H10.7499V21C10.7499 21.1989 10.6709 21.3897 10.5302 21.5303C10.3896 21.671 10.1988 21.75 9.99991 21.75C9.801 21.75 9.61023 21.671 9.46958 21.5303C9.32893 21.3897 9.24991 21.1989 9.24991 21V17.75H2.99991C2.86308 17.7499 2.72889 17.7124 2.61187 17.6415C2.49484 17.5706 2.39944 17.469 2.33599 17.3478C2.27255 17.2265 2.24347 17.0903 2.25192 16.9537C2.26036 16.8171 2.30601 16.6855 2.38391 16.573L6.06891 11.25H4.49991C4.36147 11.2498 4.22578 11.2112 4.10789 11.1387C3.99 11.0661 3.8945 10.9623 3.83197 10.8388C3.76943 10.7153 3.7423 10.5768 3.75359 10.4389C3.76488 10.3009 3.81413 10.1687 3.89591 10.057L9.39591 2.557L9.45191 2.488C9.52723 2.40741 9.6194 2.34444 9.72186 2.30355C9.82431 2.26267 9.93452 2.24489 10.0446 2.25148C10.1547 2.25807 10.262 2.28887 10.3589 2.34168C10.4557 2.39448 10.5397 2.46801 10.6049 2.557L13.0489 5.89L14.4689 4.47L14.5299 4.415C14.6089 4.35154 14.7 4.30475 14.7976 4.27746C14.8952 4.25017 14.9973 4.24295 15.0978 4.25622C15.1982 4.26949 15.295 4.30298 15.3821 4.35468C15.4693 4.40638 15.5451 4.4752 15.6049 4.557L21.1049 12.057L21.1609 12.145C21.2223 12.2592 21.253 12.3874 21.2501 12.517C21.2471 12.6466 21.2106 12.7733 21.1441 12.8846C21.0777 12.9959 20.9834 13.0881 20.8707 13.1521C20.758 13.2162 20.6306 13.2499 20.5009 13.25H19.0329L21.5919 16.54C21.678 16.6508 21.7312 16.7836 21.7456 16.9232C21.76 17.0627 21.7349 17.2036 21.6732 17.3296C21.6116 17.4557 21.5158 17.5619 21.3967 17.6362C21.2777 17.7105 21.1402 17.7499 20.9999 17.75H15.7499V19C15.7499 19.1989 15.6709 19.3897 15.5302 19.5303C15.3896 19.671 15.1988 19.75 14.9999 19.75C14.801 19.75 14.6102 19.671 14.4696 19.5303C14.3289 19.3897 14.2499 19.1989 14.2499 19Z" fill="currentColor"/>
                                                </svg>
                                                <div class="flex-1 relative leading-5 font-medium">Jenis Pohon</div>
                                            </div>
                                        </a>

                                        <!-- Kategori Anggaran -->
                                        <a href="{{ route('admin.kategori-anggaran.index') }}"
                                        class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out
                                        {{ request()->routeIs('admin.kategori-anggaran.*') ? 'bg-white !text-darkslategray' :  'bg-darkslategray text-white' }}">
                                            <div class="flex-1 flex flex-row items-center justify-start gap-3">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M3 10V8C3 7.46957 3.21071 6.96086 3.58579 6.58579C3.96086 6.21071 4.46957 6 5 6H7M3 10C4.333 10 7 9.2 7 6M3 10V14M7 6H17M3 14V16C3 16.5304 3.21071 17.0391 3.58579 17.4142C3.96086 17.7893 4.46957 18 5 18H7M3 14C4.333 14 7 14.8 7 18M21 10V8C21 7.46957 20.7893 6.96086 20.4142 6.58579C20.0391 6.21071 19.5304 6 19 6H17M21 10C19.667 10 17 9.2 17 6M21 10V12M7 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M12 14C13.1046 14 14 13.1046 14 12C14 10.8954 13.1046 10 12 10C10.8954 10 10 10.8954 10 12C10 13.1046 10.8954 14 12 14Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M18 15V18M18 18V21M18 18H15M18 18H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                <div class="flex-1 relative leading-5 font-medium">Kategori Anggaran</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                <!-- Setting Section -->
                                <div class="self-stretch flex flex-col items-start justify-start gap-4 mt-4">
                                    <div class="self-stretch relative leading-5 uppercase text-xs text-darkslategray">SETTING</div>

                                    <div class="self-stretch flex flex-col items-start justify-start gap-1 text-sm text-white">
                                        <!-- Profile Link -->
                                        <a href="{{ route('profile.edit') }}" class="self-stretch rounded-lg bg-darkslategray flex flex-row items-center justify-start py-2 px-3 no-underline text-white hover:bg-white hover:text-darkslategray ease-in-out">
                                            <div class="flex-1 flex flex-row items-center justify-start gap-3">
                                                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="12" cy="7" r="4"></circle>
                                                </svg>
                                                <div class="flex-1 relative leading-5 font-medium">Profile</div>
                                            </div>
                                        </a>

                                        <!-- Admin Dashboard Link -->
                                        <a type="button" href="{{ route('admin.dashboard') }}" class="self-stretch rounded-lg bg-darkslategray flex flex-row items-center justify-start py-2 px-3 no-underline text-white hover:bg-white hover:text-darkslategray ease-in-out">
                                            <div class="flex-1 flex flex-row items-center justify-start gap-3">
                                                <svg width="24" height="24" viewBox="0 0 18 18" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M9.00883 6.86602C8.48324 6.86602 7.99105 7.06992 7.6184 7.44258C7.2475 7.81523 7.04184 8.30742 7.04184 8.83301C7.04184 9.35859 7.2475 9.85078 7.6184 10.2234C7.99105 10.5943 8.48324 10.8 9.00883 10.8C9.53441 10.8 10.0266 10.5943 10.3993 10.2234C10.7702 9.85078 10.9758 9.35859 10.9758 8.83301C10.9758 8.30742 10.7702 7.81523 10.3993 7.44258C10.2172 7.25916 10.0006 7.11374 9.7619 7.01477C9.52321 6.91579 9.26723 6.86523 9.00883 6.86602ZM16.2563 11.0057L15.1067 10.023C15.1612 9.68906 15.1893 9.34805 15.1893 9.00879C15.1893 8.66953 15.1612 8.32676 15.1067 7.99453L16.2563 7.01191C16.3431 6.93757 16.4053 6.83855 16.4345 6.72803C16.4637 6.6175 16.4586 6.5007 16.4198 6.39316L16.4039 6.34746C16.0876 5.46274 15.6135 4.64266 15.0047 3.92695L14.9731 3.89004C14.8992 3.80312 14.8006 3.74064 14.6905 3.71083C14.5804 3.68102 14.4638 3.68528 14.3561 3.72305L12.9288 4.23105C12.4014 3.79863 11.8143 3.45762 11.178 3.22031L10.902 1.72793C10.8812 1.6155 10.8266 1.51207 10.7456 1.43138C10.6646 1.35069 10.561 1.29656 10.4485 1.27617L10.401 1.26738C9.48695 1.10215 8.52367 1.10215 7.60961 1.26738L7.56215 1.27617C7.44964 1.29656 7.346 1.35069 7.265 1.43138C7.18399 1.51207 7.12945 1.6155 7.10863 1.72793L6.8309 3.22734C6.20059 3.46653 5.61342 3.80672 5.09242 4.23457L3.65453 3.72305C3.54689 3.68498 3.43022 3.68056 3.32001 3.71039C3.2098 3.74022 3.11128 3.80288 3.03754 3.89004L3.0059 3.92695C2.3982 4.64341 1.92426 5.46329 1.60668 6.34746L1.59086 6.39316C1.51176 6.61289 1.5768 6.85898 1.75434 7.01191L2.91801 8.00508C2.86352 8.33555 2.83715 8.67305 2.83715 9.00703C2.83715 9.34453 2.86352 9.68203 2.91801 10.009L1.75785 11.0021C1.67101 11.0765 1.60885 11.1755 1.57965 11.286C1.55045 11.3966 1.55559 11.5134 1.59437 11.6209L1.6102 11.6666C1.92836 12.5508 2.3977 13.3682 3.00941 14.0871L3.04105 14.124C3.11498 14.2109 3.2135 14.2734 3.32364 14.3032C3.43379 14.333 3.55038 14.3288 3.65805 14.291L5.09594 13.7795C5.61977 14.2102 6.20336 14.5512 6.83441 14.7867L7.11215 16.2861C7.13297 16.3986 7.18751 16.502 7.26851 16.5827C7.34952 16.6634 7.45316 16.7175 7.56566 16.7379L7.61313 16.7467C8.53618 16.9128 9.48148 16.9128 10.4045 16.7467L10.452 16.7379C10.5645 16.7175 10.6681 16.6634 10.7491 16.5827C10.8301 16.502 10.8847 16.3986 10.9055 16.2861L11.1815 14.7938C11.8178 14.5547 12.4049 14.2154 12.9323 13.783L14.3596 14.291C14.4672 14.3291 14.5839 14.3335 14.6941 14.3037C14.8043 14.2738 14.9029 14.2112 14.9766 14.124L15.0082 14.0871C15.62 13.3646 16.0893 12.5508 16.4075 11.6666L16.4233 11.6209C16.4989 11.4029 16.4338 11.1586 16.2563 11.0057ZM9.00883 11.9232C7.30199 11.9232 5.91859 10.5398 5.91859 8.83301C5.91859 7.12617 7.30199 5.74277 9.00883 5.74277C10.7157 5.74277 12.0991 7.12617 12.0991 8.83301C12.0991 10.5398 10.7157 11.9232 9.00883 11.9232Z" fill="currentColor"/>
                                                </svg>
                                                <div class="flex-1 relative leading-5 font-medium">Admin Dashboard</div>
                                            </div>
                                        </a>
                                        
                                        <!-- Logout Button -->
                                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                                            @csrf
                                            <button type="submit" class="w-full border-none rounded-lg bg-darkslategray flex flex-row items-center justify-start py-2 px-3 no-underline text-white font-outfit font-medium hover:bg-white hover:text-darkslategray ease-in-out">
                                                <div class="flex-1 flex flex-row items-center justify-start gap-3">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M14 8V6C14 5.46957 13.7893 4.96086 13.4142 4.58579C13.0391 4.21071 12.5304 4 12 4H5C4.46957 4 3.96086 4.21071 3.58579 4.58579C3.21071 4.96086 3 5.46957 3 6V18C3 18.5304 3.21071 19.0391 3.58579 19.4142C3.96086 19.7893 4.46957 20 5 20H12C12.5304 20 13.0391 19.7893 13.4142 19.4142C13.7893 19.0391 14 18.5304 14 18V16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M9 12H21M21 12L18 9M21 12L18 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                    <span>Logout</span>
                                                </div>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Main Content Area -->
                    <div class="flex-1 flex flex-col main-content">
                        <!-- Top Navigation -->
                        <div class="w-full sticky top-0 z-50">
                            @include('layouts.admin-header')
                        </div>

                        @isset($header)
                            <header class="bg-transparent">
                                <div class="self-stretch mx-auto py-4 px-4 sm:px-6 lg:px-8">
                                    {{ $header }}
                                </div>
                            </header>
                        @endisset

                        <!-- Main Content -->
                        <main class="py-6 px-4 sm:px-6 lg:px-8">
                            <div class="mx-auto w-full">
                                {{ $slot }}
                            </div>
                        </main>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>