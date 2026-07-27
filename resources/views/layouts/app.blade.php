<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Raangalay') }} - @yield('title', 'Modern Online Shopping in Bangladesh')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo/ico.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo/ico.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo/ico.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (CDN for development) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#771A09',
                        secondary: '#202020',
                        danger: '#F44336',
                        'dark-color': '#242424',
                        'light-color': '#fcfcfc',
                        'sky-color': '#647589',
                        'bg-primary': '#F9F9F9',
                    },
                    fontFamily: {
                        sans: ['Jost', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @livewireStyles
    @yield('styles')
</head>
<body class="font-sans antialiased bg-bg-primary">
    <!-- Header with Integrated Category Dropdown -->
    @include('layouts.header')

    <!-- Main Content -->
    <main class="min-h-screen">
        @if(session('success'))
            <div class="container mx-auto px-4 mt-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <button class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.parentElement.style.display='none'">
                        <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                    </button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container mx-auto px-4 mt-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                    <button class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.parentElement.style.display='none'">
                        <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                    </button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    @include('layouts.footer')

    @livewireScripts
    @yield('scripts')
</body>
</html>
