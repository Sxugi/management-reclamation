<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
        <title>{{ $title ?? config('app.name') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-outfit antialiased bg-gray-50 text-darkslategray">
        <div class="min-h-screen flex flex-col">
            <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                    <div class="w-[191px] h-[37px] flex flex-row items-center justify-center">
                        <a href="#" class="w-[191px] h-[37px] flex">
                            <x-main.application-logo class="w-[41px] h-[37px] object-cover" />
                        </a>
                    </div>
                    
                    <div class="hidden sm:block text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full font-bold uppercase tracking-wider">
                        Public Transparency Access
                    </div>
                </div>
            </nav>

            <main class="flex-1 w-full max-w-3xl mx-auto p-4 sm:p-6 lg:p-8 pb-24">
                {{ $slot }}
            </main>

            <footer class="fixed bottom-0 left-0 right-0 bg-white/90 backdrop-blur-md border-t p-4 z-40 sm:hidden">
                <a href="{{ route('login') }}" class="block w-full text-center py-3 bg-darkslategray text-white rounded-xl font-medium shadow-lg">
                    Login Portal Staff
                </a>
            </footer>
        </div>
    </body>
</html>