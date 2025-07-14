<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

        <title>{{ config('app.name', 'Management Reclamation') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <style>
        body {
            margin: 0;
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
    <body class="font-sans antialiased bg-whitesmoke">
        <div x-data="{ sidebarOpen: false }" class="w-full min-h-screen">
            <div class="min-h-full bg-whitesmoke flex flex-row">
                <!-- Sidebar -->
                <div class="hidden lg:block h-screen">
                    @include('layouts.sidebar')
                </div>
                <!-- Main Content Area -->
                <div class="flex-1 flex flex-col main-content">
                    <!-- Top Navigation -->
                    <div class="w-full">
                        @include('layouts.header')
                    </div>

                    @isset($header)
                        <header class="bg-transparent">
                            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <!-- Main Content -->
                    <main class="py-6 px-4 sm:px-6 lg:px-8">
                        <div class="mx-auto max-w-7xl">
                            {{ $slot }}
                        </div>
                    </main>
                </div>
                <template x-if="sidebarOpen" @close-sidebar.window="sidebarOpen = false">
                    <x-main.mobile-sidebar />
                </template>
            </div>
        </div>
    </body>
</html>