<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'ALDI') }}</title>
        <link rel="icon" type="image/svg+xml"
            href="{{ asset('favicon-light.svg') }}"
            media="(prefers-color-scheme: light)">

        <link rel="icon" type="image/svg+xml"
            href="{{ asset('favicon-dark.svg') }}"
            media="(prefers-color-scheme: dark)">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                {{-- LOGIKA BREADCRUMBS GLOBAL --}}
                @if (isset($breadcrumbs))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-2">
                        <nav class="flex" aria-label="Breadcrumb">
                            <ol class="inline-flex items-center">
                                {{-- Slot akan otomatis mengisi li di sini --}}
                                {{ $breadcrumbs }}
                            </ol>
                        </nav>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
        @stack('scripts')
    </body>
</html>