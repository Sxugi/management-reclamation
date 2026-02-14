<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="turbo-cache-control" content="no-cache">
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

        <title>@yield('title', 'Buku Panduan') - {{ config('app.name', 'Management Reclamation') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <style>
        body {
            margin:0;
            padding:0;
        }
        @media (min-width:1024px) {
            .main-content {
                margin-left:16rem;
            }
            .sidebar-spacer {
                min-width:16rem;
                width:16rem;
            }
        }
    </style>
    <body class="font-outfit antialiased bg-whitesmoke">
        <div class="min-h-full" x-data="{ activeTab:'intro', mobileMenuOpen:false }">
            <div class="w-full min-h-screen">
                <div class="min-h-full bg-whitesmoke flex flex-row">
                    
                    <!-- Desktop Sidebar -->
                    <div class="hidden lg:block h-screen">
                        <div class="h-full w-64 fixed overflow-y-auto overflow-x-hidden bg-gainsboro border-darkslategray border-solid border-b-[0px] border-r-[1px] border-l-[0px] border-t-[0px] box-border flex flex-col items-start justify-start pt-8 px-5 pb-5 gap-7 text-left text-sm text-white font-outfit">
                                
                            <!-- Logo/Brand -->
                            <div class="self-stretch flex flex-row items-center justify-center gap-3">
                                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white shadow-lg shadow-blue-100">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                                <div class="font-bold text-darkslategray text-lg leading-none">
                                    Pusat<br><span class="text-blue-600">Bantuan</span>
                                </div>
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
                                        <!-- Pendahuluan -->
                                        <button @click="activeTab = 'intro'"
                                                :class="activeTab === 'intro' ? 'bg-white ! text-darkslategray' :'bg-darkslategray text-white hover:bg-white hover:text-darkslategray'"
                                                class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out cursor-pointer">    
                                            <div class="flex-1 flex flex-row items-center justify-start gap-3">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                <div class="flex-1 relative leading-5 font-medium text-left">Pendahuluan</div>
                                            </div>
                                        </button>

                                        <!-- Manajemen Lahan -->
                                        <button @click="activeTab = 'lahan'"
                                                :class="activeTab === 'lahan' ? 'bg-white !text-darkslategray' :'bg-darkslategray text-white hover:bg-white hover:text-darkslategray'"
                                                class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out cursor-pointer">    
                                            <div class="flex-1 flex flex-row items-center justify-start gap-3">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12 8L18 5L12 2V12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M8.00011 11.99L2.50011 15.13C2.34621 15.2172 2.21821 15.3437 2.12915 15.4965C2.04009 15.6494 1.99316 15.8231 1.99316 16C1.99316 16.1769 2.04009 16.3506 2.12915 16.5035C2.21821 16.6563 2.34621 16.7828 2.50011 16.87L11.0001 21.73C11.3042 21.9055 11.649 21.9979 12.0001 21.9979C12.3512 21.9979 12.6961 21.9055 13.0001 21.73L21.5001 16.87C21.654 16.7828 21.782 16.6563 21.8711 16.5035C21.9601 16.3506 22.0071 16.1769 22.0071 16C22.0071 15.8231 21.9601 15.6494 21.8711 15.4965C21.782 15.3437 21.654 15.2172 21.5001 15.13L16.0001 12M6.49011 12.85L17.5101 19.15M17.5101 12.85L6.50011 19.15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                <div class="flex-1 relative leading-5 font-medium text-left">Manajemen Lahan</div>
                                            </div>
                                        </button>

                                        <!-- Tim & Hak Akses -->
                                        <button @click="activeTab = 'team'" 
                                                :class="activeTab === 'team' ? 'bg-white !text-darkslategray' :'bg-darkslategray text-white hover:bg-white hover:text-darkslategray'"
                                                class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out cursor-pointer">    
                                            <div class="flex-1 flex flex-row items-center justify-start gap-3">
                                                <svg width="24" height="24" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M17.754 11C18.72 11 19.504 11.784 19.504 12.75V19.499C19.504 20.958 18.9244 22.3572 17.8928 23.3888C16.8612 24.4204 15.462 25 14.003 25C12.544 25 11.1448 24.4204 10.1132 23.3888C9.08157 22.3572 8.502 20.958 8.502 19.499V12.75C8.502 11.784 9.285 11 10.252 11H17.754ZM3.75 11L8.132 10.998C7.77165 11.4322 7.5547 11.9674 7.511 12.53L7.501 12.75V19.499C7.501 20.632 7.792 21.698 8.301 22.626C7.61593 22.9252 6.86712 23.049 6.12221 22.9862C5.3773 22.9235 4.65975 22.6761 4.0344 22.2665C3.40905 21.8569 2.89558 21.298 2.5404 20.6402C2.18521 19.9825 1.99948 19.2466 2 18.499V12.75C2 12.5201 2.0453 12.2925 2.13331 12.0801C2.22132 11.8677 2.35031 11.6747 2.51292 11.5122C2.67553 11.3497 2.86856 11.2208 3.081 11.1329C3.29343 11.045 3.5201 10.9999 3.75 11ZM19.875 10.998L24.25 11C25.216 11 26 11.784 26 12.75V18.5C26.0003 19.2472 25.8146 19.9826 25.4595 20.6401C25.1045 21.2975 24.5914 21.8562 23.9664 22.2657C23.3415 22.6752 22.6244 22.9227 21.8799 22.9857C21.1354 23.0488 20.3869 22.9255 19.702 22.627L19.758 22.525C20.187 21.712 20.448 20.796 20.496 19.825L20.504 19.499V12.75C20.504 12.084 20.268 11.474 19.875 10.998ZM14 3C14.4596 3 14.9148 3.09053 15.3394 3.26642C15.764 3.44231 16.1499 3.70012 16.4749 4.02513C16.7999 4.35013 17.0577 4.73597 17.2336 5.16061C17.4095 5.58525 17.5 6.04037 17.5 6.5C17.5 6.95963 17.4095 7.41475 17.2336 7.83939C17.0577 8.26403 16.7999 8.64987 16.4749 8.97487C16.1499 9.29988 15.764 9.55769 15.3394 9.73358C14.9148 9.90947 14.4596 10 14 10C13.0717 10 12.1815 9.63125 11.5251 8.97487C10.8688 8.3185 10.5 7.42826 10.5 6.5C10.5 5.57174 10.8688 4.6815 11.5251 4.02513C12.1815 3.36875 13.0717 3 14 3ZM22.003 4C22.397 4 22.7871 4.0776 23.1511 4.22836C23.515 4.37913 23.8457 4.6001 24.1243 4.87868C24.4029 5.15726 24.6239 5.48797 24.7746 5.85195C24.9254 6.21593 25.003 6.60603 25.003 7C25.003 7.39397 24.9254 7.78407 24.7746 8.14805C24.6239 8.51203 24.4029 8.84274 24.1243 9.12132C23.8457 9.3999 23.515 9.62087 23.1511 9.77164C22.7871 9.9224 22.397 10 22.003 10C21.2074 10 20.4443 9.68393 19.8817 9.12132C19.3191 8.55871 19.003 7.79565 19.003 7C19.003 6.20435 19.3191 5.44129 19.8817 4.87868C20.4443 4.31607 21.2074 4 22.003 4ZM5.997 4C6.39097 4 6.78107 4.0776 7.14505 4.22836C7.50903 4.37913 7.83975 4.6001 8.11832 4.87868C8.3969 5.15726 8.61788 5.48797 8.76864 5.85195C8.9194 6.21593 8.997 6.60603 8.997 7C8.997 7.39397 8.9194 7.78407 8.76864 8.14805C8.61788 8.51203 8.3969 8.84274 8.11832 9.12132C7.83975 9.3999 7.50903 9.62087 7.14505 9.77164C6.78107 9.9224 6.39097 10 5.997 10C5.20135 10 4.43829 9.68393 3.87568 9.12132C3.31307 8.55871 2.997 7.79565 2.997 7C2.997 6.20435 3.31307 5.44129 3.87568 4.87868C4.43829 4.31607 5.20135 4 5.997 4Z" fill="currentColor"/>
                                                </svg>    
                                                <div class="flex-1 relative leading-5 font-medium text-left">Tim Pengelola</div>
                                            </div>
                                        </button>

                                        <!-- Plot Lahan (Peta) -->
                                        <button @click="activeTab = 'plot'" 
                                                :class="activeTab === 'plot' ? 'bg-white !text-darkslategray' :'bg-darkslategray text-white hover:bg-white hover:text-darkslategray'"
                                                class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out cursor-pointer">    
                                            <div class="flex-1 flex flex-row items-center justify-start gap-3">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M22.5001 4.5C22.4977 3.72206 22.1927 2.97557 21.6495 2.41862C21.1064 1.86168 20.3678 1.53802 19.5901 1.51618C18.8125 1.49434 18.0569 1.77605 17.4833 2.30163C16.9098 2.82722 16.5633 3.55541 16.5173 4.332L7.18956 6.19725C6.97147 5.74397 6.64263 5.35297 6.23345 5.0604C5.82426 4.76784 5.34793 4.58313 4.84847 4.52337C4.34901 4.46361 3.84255 4.53072 3.37589 4.71848C2.90922 4.90625 2.49743 5.20863 2.17855 5.59766C1.85967 5.9867 1.64401 6.44984 1.55149 6.94428C1.45897 7.43872 1.49258 7.9485 1.64922 8.42651C1.80585 8.90452 2.08046 9.33534 2.44765 9.67914C2.81485 10.0229 3.26278 10.2686 3.75006 10.3935V16.6065C3.23826 16.7377 2.77028 17.0019 2.39364 17.3724C2.01699 17.7429 1.7451 18.2065 1.60557 18.7161C1.46604 19.2257 1.46383 19.7631 1.59918 20.2738C1.73452 20.7845 2.0026 21.2503 2.37619 21.6239C2.74978 21.9975 3.21557 22.2655 3.72628 22.4009C4.23698 22.5362 4.77441 22.534 5.28398 22.3945C5.79356 22.255 6.25714 21.9831 6.62765 21.6064C6.99816 21.2298 7.26241 20.7618 7.39356 20.25H13.6066C13.7312 20.7376 13.9768 21.1859 14.3207 21.5534C14.6645 21.9209 15.0955 22.1958 15.5737 22.3527C16.0519 22.5095 16.5619 22.5433 17.0566 22.4508C17.5514 22.3583 18.0148 22.1425 18.404 21.8235C18.7932 21.5044 19.0957 21.0924 19.2835 20.6255C19.4713 20.1585 19.5384 19.6518 19.4785 19.1521C19.4185 18.6524 19.2336 18.1758 18.9407 17.7666C18.6478 17.3573 18.2564 17.0285 17.8028 16.8105L19.6681 7.48275C20.4316 7.44084 21.1502 7.10859 21.6767 6.55405C22.2032 5.99952 22.4978 5.26467 22.5001 4.5ZM19.5001 3C19.7967 3 20.0867 3.08798 20.3334 3.2528C20.5801 3.41762 20.7723 3.65189 20.8859 3.92598C20.9994 4.20007 21.0291 4.50167 20.9712 4.79264C20.9134 5.08361 20.7705 5.35088 20.5607 5.56066C20.3509 5.77044 20.0837 5.9133 19.7927 5.97118C19.5017 6.02906 19.2001 5.99935 18.926 5.88582C18.6519 5.77229 18.4177 5.58003 18.2529 5.33336C18.088 5.08668 18.0001 4.79667 18.0001 4.5C18.0001 4.10218 18.1581 3.72065 18.4394 3.43934C18.7207 3.15804 19.1022 3 19.5001 3ZM3.00006 7.5C3.00006 7.20333 3.08803 6.91332 3.25285 6.66665C3.41767 6.41997 3.65194 6.22771 3.92603 6.11418C4.20012 6.00065 4.50172 5.97095 4.79269 6.02882C5.08366 6.0867 5.35094 6.22956 5.56072 6.43934C5.77049 6.64912 5.91336 6.91639 5.97123 7.20737C6.02911 7.49834 5.99941 7.79994 5.88588 8.07403C5.77234 8.34812 5.58008 8.58238 5.33341 8.74721C5.08674 8.91203 4.79673 9 4.50006 9C4.10223 9 3.7207 8.84197 3.4394 8.56066C3.15809 8.27936 3.00006 7.89783 3.00006 7.5ZM4.50006 21C4.20338 21 3.91337 20.912 3.6667 20.7472C3.42003 20.5824 3.22777 20.3481 3.11424 20.074C3.0007 19.7999 2.971 19.4983 3.02888 19.2074C3.08676 18.9164 3.22962 18.6491 3.4394 18.4393C3.64917 18.2296 3.91645 18.0867 4.20742 18.0288C4.49839 17.9709 4.79999 18.0007 5.07408 18.1142C5.34817 18.2277 5.58244 18.42 5.74726 18.6666C5.91208 18.9133 6.00006 19.2033 6.00006 19.5C6.00006 19.8978 5.84202 20.2794 5.56072 20.5607C5.27941 20.842 4.89788 21 4.50006 21ZM13.6066 18.75H7.39356C7.25878 18.2346 6.98918 17.7643 6.61246 17.3876C6.23574 17.0109 5.76549 16.7413 5.25006 16.6065V10.3935C5.86267 10.2338 6.40885 9.88391 6.81005 9.39417C7.21125 8.90443 7.44679 8.30007 7.48281 7.668L16.8106 5.80275C17.1043 6.40759 17.5926 6.89622 18.1973 7.19025L16.3321 16.5173C15.7 16.5533 15.0956 16.7888 14.6059 17.19C14.1161 17.5912 13.7663 18.1374 13.6066 18.75ZM16.5001 21C16.2034 21 15.9134 20.912 15.6667 20.7472C15.42 20.5824 15.2278 20.3481 15.1142 20.074C15.0007 19.7999 14.971 19.4983 15.0289 19.2074C15.0868 18.9164 15.2296 18.6491 15.4394 18.4393C15.6492 18.2296 15.9164 18.0867 16.2074 18.0288C16.4984 17.9709 16.8 18.0007 17.0741 18.1142C17.3482 18.2277 17.5824 18.42 17.7473 18.6666C17.9121 18.9133 18.0001 19.2033 18.0001 19.5C18.0001 19.8978 17.842 20.2794 17.5607 20.5607C17.2794 20.842 16.8979 21 16.5001 21Z" fill="currentColor"/>
                                                </svg>
                                                <div class="flex-1 relative leading-5 font-medium text-left">Plot Lahan</div>
                                            </div>
                                        </button>

                                        <!-- Input Anggaran -->
                                        <button @click="activeTab = 'anggaran'"
                                                :class="activeTab === 'anggaran' ? 'bg-white !text-darkslategray' :'bg-darkslategray text-white hover:bg-white hover:text-darkslategray'"
                                                class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out cursor-pointer">    
                                            <div class="flex-1 flex flex-row items-center justify-start gap-3">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12 16C11.45 16 10.9793 15.8043 10.588 15.413C10.1967 15.0217 10.0007 14.5507 10 14C9.99933 13.4493 10.1953 12.9787 10.588 12.588C10.9807 12.1973 11.4513 12.0013 12 12C12.5487 11.9987 13.0197 12.1947 13.413 12.588C13.8063 12.9813 14.002 13.452 14 14C13.998 14.548 13.8023 15.019 13.413 15.413C13.0237 15.807 12.5527 16.0027 12 16ZM7.375 7H16.625L17.9 4.45C18.0667 4.11667 18.054 3.79167 17.862 3.475C17.67 3.15833 17.3827 3 17 3H7C6.61667 3 6.32933 3.15833 6.138 3.475C5.94667 3.79167 5.934 4.11667 6.1 4.45L7.375 7ZM8.4 21H15.6C17.1 21 18.375 20.4793 19.425 19.438C20.475 18.3967 21 17.1173 21 15.6C21 14.9667 20.8917 14.35 20.675 13.75C20.4583 13.15 20.15 12.6083 19.75 12.125L17.15 9H6.85L4.25 12.125C3.85 12.6083 3.54167 13.15 3.325 13.75C3.10833 14.35 3 14.9667 3 15.6C3 17.1167 3.521 18.396 4.563 19.438C5.605 20.48 6.884 21.0007 8.4 21Z" fill="currentColor"/>
                                                </svg>
                                                <div class="flex-1 relative leading-5 font-medium text-left">Input Anggaran</div>
                                            </div>
                                        </button>

                                        <!-- Laporan & Export -->
                                        <button @click="activeTab = 'laporan'"
                                                :class="activeTab === 'laporan' ? 'bg-white !text-darkslategray' :'bg-darkslategray text-white hover:bg-white hover:text-darkslategray'"
                                                class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out cursor-pointer">    
                                            <div class="flex-1 flex flex-row items-center justify-start gap-3">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                <div class="flex-1 relative leading-5 font-medium text-left">Laporan & Export</div>
                                            </div>
                                        </button>
                                    </div>
                                </div>

                                <!-- Bantuan Section -->
                                <div class="self-stretch flex flex-col items-start justify-start gap-4 mt-4">
                                    <div class="self-stretch relative leading-5 uppercase text-xs text-darkslategray">BANTUAN</div>

                                    <div class="self-stretch flex flex-col items-start justify-start gap-1 text-sm text-white">
                                        <!-- FAQ -->
                                        <button @click="activeTab = 'faq'"
                                                :class="activeTab === 'faq' ? 'bg-white !text-darkslategray' :'bg-darkslategray text-white hover:bg-white hover:text-darkslategray'"
                                                class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 border-none cursor-pointer transition-colors ease-in-out">
                                            <div class="flex-1 flex flex-row items-center justify-start gap-3">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12 0C10.4308 0 9.23077 1.2 9.23077 2.76923V8.30769C9.23077 9.87692 10.4308 11.0769 12 11.0769H17.5385L21.2308 14.7692V11.0769C22.8 11.0769 24 9.87692 24 8.30769V2.76923C24 1.2 22.8 0 21.2308 0H12ZM15.8658 2.76923H17.4517L19.0098 8.30769H17.6252L17.2495 6.92308H15.8649L15.5197 8.30769H14.3077L15.8658 2.76923ZM16.6154 3.69231C16.5231 4.06154 16.4197 4.512 16.3265 4.78892L16.068 6H17.1637L16.9034 4.788C16.7197 4.512 16.6154 4.06154 16.6154 3.69231ZM2.76923 9.23077C1.2 9.23077 0 10.4308 0 12V17.5385C0 19.1077 1.2 20.3077 2.76923 20.3077V24L6.46154 20.3077H12C13.5692 20.3077 14.7692 19.1077 14.7692 17.5385V12H12C10.2462 12 8.856 10.8 8.39446 9.23077H2.76923ZM7.00985 11.9132C8.57908 11.9132 9.31754 13.2055 9.31754 14.6825C9.31754 15.9748 8.87354 16.7945 8.13508 17.1637C8.50431 17.3483 8.94185 17.4462 9.40338 17.5385L9.05815 18.4615C8.412 18.2769 7.74185 17.9889 7.09569 17.7111C7.00338 17.6188 6.84185 17.6252 6.74954 17.6252C5.64185 17.5329 4.61538 16.6154 4.61538 14.7692C4.61538 13.2 5.53292 11.9132 7.00985 11.9132ZM7.00985 12.9231C6.27138 12.9231 5.91323 13.7538 5.91323 14.7692C5.91323 15.8769 6.27138 16.6154 7.00985 16.6154C7.74831 16.6154 8.13415 15.7846 8.13415 14.7692C8.13415 13.7538 7.74831 12.9231 7.00985 12.9231Z" fill="currentColor"/>
                                                </svg>
                                                <div class="flex-1 relative leading-5 font-medium text-left">FAQ & Masalah Umum</div>
                                            </div>
                                        </button>
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
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 z-40 bg-black/50 lg:hidden"
                        style="display:none;">
                    </div>

                    <!-- Mobile Sidebar -->
                    <div
                        x-show="mobileMenuOpen"
                        @click. outside="mobileMenuOpen = false"
                        x-transition:enter="transition ease-in-out duration-300 transform"
                        x-transition:enter-start="-translate-x-full"
                        x-transition:enter-end="translate-x-0"
                        x-transition:leave="transition ease-in-out duration-300 transform"
                        x-transition:leave-start="translate-x-0"
                        x-transition:leave-end="-translate-x-full"
                        class="fixed inset-y-0 left-0 z-50 w-64 lg:hidden bg-gainsboro shadow-lg overflow-y-auto"
                        style="display:none;">
                        
                        <!-- Mobile Sidebar Content -->
                        <div class="flex flex-col h-full pt-8 px-5 pb-5 gap-7 text-left text-sm text-white font-outfit">

                            <!-- Home Button -->
                            <div class="self-stretch flex flex-col items-start justify-start">
                                <a href="{{ route('lahan.index') }}" class="self-stretch shadow-[0px_1px_2px_rgba(16,_24,_40,_0.05)] rounded-lg bg-darkslategray h-9 overflow-hidden flex flex-row items-center justify-start py-3.5 px-4 box-border gap-3 no-underline text-white hover:bg-white hover:text-darkslategray ease-in-out">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 19V10C4 9.68333 4.071 9.38333 4.213 9.1C4.355 8.81667 4.55067 8.58333 4.8 8.4L10.8 3.9C11.15 3.63333 11.55 3.5 12 3.5C12.45 3.5 12.85 3.63333 13.2 3.9L19.2 8.4C19.45 8.58333 19.646 8.81667 19.788 9.1C19.93 9.38333 20.0007 9.68333 20 10V19C20 19.55 19.804 20.021 19.412 20.413C19.02 20.805 18.5493 21.0007 18 21H15C14.7167 21 14.4793 20.904 14.288 20.712C14.0967 20.52 14.0007 20.2827 14 20V15C14 14.7167 13.904 14.4793 13.712 14.288C13.52 14.0967 13.2827 14.0007 13 14H11C10.7167 14 10.4793 14.096 10.288 14.288C10.0967 14.48 10.0007 14.7173 10 15V20C10 20.2833 9.904 20.521 9.712 20.713C9.52 20.905 9.28267 21.0007 9 21H6C5.45 21 4.97933 20.8043 4.588 20.413C4.19667 20.0217 4.00067 19.5507 4 19Z" fill="currentColor"/>
                                    </svg>
                                    <div class="relative leading-5 font-medium">Home</div>
                                </a>
                            </div>

                            <!-- Menu Items (same as desktop) -->
                            <div class="self-stretch flex flex-col items-start justify-start gap-2.5">
                                <div class="self-stretch flex flex-col items-start justify-start gap-4">
                                    <div class="relative leading-5 uppercase text-xs text-darkslategray">MENU</div>
                                    
                                    <div class="self-stretch flex flex-col items-start justify-start gap-1 text-sm">
                                        <!-- Pendahuluan -->
                                        <button @click="activeTab = 'intro'"
                                                :class="activeTab === 'intro' ? 'bg-white ! text-darkslategray' :'bg-darkslategray text-white hover:bg-white hover:text-darkslategray'"
                                                class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out cursor-pointer">    
                                            <div class="flex-1 flex flex-row items-center justify-start gap-3">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                <div class="flex-1 relative leading-5 font-medium text-left">Pendahuluan</div>
                                            </div>
                                        </button>

                                        <!-- Manajemen Lahan -->
                                        <button @click="activeTab = 'lahan'"
                                                :class="activeTab === 'lahan' ? 'bg-white !text-darkslategray' :'bg-darkslategray text-white hover:bg-white hover:text-darkslategray'"
                                                class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out cursor-pointer">    
                                            <div class="flex-1 flex flex-row items-center justify-start gap-3">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12 8L18 5L12 2V12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M8.00011 11.99L2.50011 15.13C2.34621 15.2172 2.21821 15.3437 2.12915 15.4965C2.04009 15.6494 1.99316 15.8231 1.99316 16C1.99316 16.1769 2.04009 16.3506 2.12915 16.5035C2.21821 16.6563 2.34621 16.7828 2.50011 16.87L11.0001 21.73C11.3042 21.9055 11.649 21.9979 12.0001 21.9979C12.3512 21.9979 12.6961 21.9055 13.0001 21.73L21.5001 16.87C21.654 16.7828 21.782 16.6563 21.8711 16.5035C21.9601 16.3506 22.0071 16.1769 22.0071 16C22.0071 15.8231 21.9601 15.6494 21.8711 15.4965C21.782 15.3437 21.654 15.2172 21.5001 15.13L16.0001 12M6.49011 12.85L17.5101 19.15M17.5101 12.85L6.50011 19.15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                <div class="flex-1 relative leading-5 font-medium text-left">Manajemen Lahan</div>
                                            </div>
                                        </button>

                                        <!-- Input Anggaran -->
                                        <button @click="activeTab = 'anggaran'"
                                                :class="activeTab === 'anggaran' ? 'bg-white !text-darkslategray' :'bg-darkslategray text-white hover:bg-white hover:text-darkslategray'"
                                                class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out cursor-pointer">    
                                            <div class="flex-1 flex flex-row items-center justify-start gap-3">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12 16C11.45 16 10.9793 15.8043 10.588 15.413C10.1967 15.0217 10.0007 14.5507 10 14C9.99933 13.4493 10.1953 12.9787 10.588 12.588C10.9807 12.1973 11.4513 12.0013 12 12C12.5487 11.9987 13.0197 12.1947 13.413 12.588C13.8063 12.9813 14.002 13.452 14 14C13.998 14.548 13.8023 15.019 13.413 15.413C13.0237 15.807 12.5527 16.0027 12 16ZM7.375 7H16.625L17.9 4.45C18.0667 4.11667 18.054 3.79167 17.862 3.475C17.67 3.15833 17.3827 3 17 3H7C6.61667 3 6.32933 3.15833 6.138 3.475C5.94667 3.79167 5.934 4.11667 6.1 4.45L7.375 7ZM8.4 21H15.6C17.1 21 18.375 20.4793 19.425 19.438C20.475 18.3967 21 17.1173 21 15.6C21 14.9667 20.8917 14.35 20.675 13.75C20.4583 13.15 20.15 12.6083 19.75 12.125L17.15 9H6.85L4.25 12.125C3.85 12.6083 3.54167 13.15 3.325 13.75C3.10833 14.35 3 14.9667 3 15.6C3 17.1167 3.521 18.396 4.563 19.438C5.605 20.48 6.884 21.0007 8.4 21Z" fill="currentColor"/>
                                                </svg>
                                                <div class="flex-1 relative leading-5 font-medium text-left">Input Anggaran</div>
                                            </div>
                                        </button>

                                        <!-- Laporan & Export -->
                                        <button @click="activeTab = 'laporan'"
                                                :class="activeTab === 'laporan' ? 'bg-white !text-darkslategray' :'bg-darkslategray text-white hover:bg-white hover:text-darkslategray'"
                                                class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out cursor-pointer">    
                                            <div class="flex-1 flex flex-row items-center justify-start gap-3">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                <div class="flex-1 relative leading-5 font-medium text-left">Laporan & Export</div>
                                            </div>
                                        </button>
                                    </div>

                                    <div class="self-stretch flex flex-col items-start justify-start gap-4 mt-4">
                                        <div class="self-stretch relative leading-5 uppercase text-xs text-darkslategray">Bantuan</div>
                                        <!-- FAQ -->
                                        <button @click="activeTab = 'faq'"
                                                :class="activeTab === 'faq' ? 'bg-white !text-darkslategray' :'bg-darkslategray text-white hover:bg-white hover:text-darkslategray'"
                                                class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 border-none cursor-pointer transition-colors ease-in-out">
                                            <div class="flex-1 flex flex-row items-center justify-start gap-3">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12 0C10.4308 0 9.23077 1.2 9.23077 2.76923V8.30769C9.23077 9.87692 10.4308 11.0769 12 11.0769H17.5385L21.2308 14.7692V11.0769C22.8 11.0769 24 9.87692 24 8.30769V2.76923C24 1.2 22.8 0 21.2308 0H12ZM15.8658 2.76923H17.4517L19.0098 8.30769H17.6252L17.2495 6.92308H15.8649L15.5197 8.30769H14.3077L15.8658 2.76923ZM16.6154 3.69231C16.5231 4.06154 16.4197 4.512 16.3265 4.78892L16.068 6H17.1637L16.9034 4.788C16.7197 4.512 16.6154 4.06154 16.6154 3.69231ZM2.76923 9.23077C1.2 9.23077 0 10.4308 0 12V17.5385C0 19.1077 1.2 20.3077 2.76923 20.3077V24L6.46154 20.3077H12C13.5692 20.3077 14.7692 19.1077 14.7692 17.5385V12H12C10.2462 12 8.856 10.8 8.39446 9.23077H2.76923ZM7.00985 11.9132C8.57908 11.9132 9.31754 13.2055 9.31754 14.6825C9.31754 15.9748 8.87354 16.7945 8.13508 17.1637C8.50431 17.3483 8.94185 17.4462 9.40338 17.5385L9.05815 18.4615C8.412 18.2769 7.74185 17.9889 7.09569 17.7111C7.00338 17.6188 6.84185 17.6252 6.74954 17.6252C5.64185 17.5329 4.61538 16.6154 4.61538 14.7692C4.61538 13.2 5.53292 11.9132 7.00985 11.9132ZM7.00985 12.9231C6.27138 12.9231 5.91323 13.7538 5.91323 14.7692C5.91323 15.8769 6.27138 16.6154 7.00985 16.6154C7.74831 16.6154 8.13415 15.7846 8.13415 14.7692C8.13415 13.7538 7.74831 12.9231 7.00985 12.9231Z" fill="currentColor"/>
                                                </svg>
                                                <div class="flex-1 relative leading-5 font-medium text-left">FAQ & Masalah Umum</div>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Main Content Area -->
                    <div class="flex-1 flex flex-col main-content">
                        <!-- Top Navigation -->
                        <div class="w-full sticky top-0 z-50">
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
                                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="inline-flex items-center justify-center p-2 rounded-lg border-solid border-[1px] text-white hover:text-darkslategray bg-darkslategray hover:bg-white focus:outline-none transition duration-150 ease-in-out">
                                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                            <path :class="{'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                            <path :class="{'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </nav>
                        </div>

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