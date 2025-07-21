<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="turbo-visit-control" content="reload">
        <meta name="turbo-cache-control" content="no-cache">
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

        <title>{{ config('app.name', 'Management Reclamation') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <style>
        body {
            margin: 0;
            padding: 0;
        }
    </style>
    <body class="font-sans antialiased h-full">
        <div class="min-h-full bg-whitesmoke">
            <div class="w-full">
                @include('layouts.navigation')
            </div>

            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="py-6 px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-7xl">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </body>
</html>