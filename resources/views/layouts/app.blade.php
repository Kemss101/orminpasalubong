<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ormin's Pasalubong Center - Online Shopping System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-primary/25 via-white to-secondary/20 text-gray-800">
    <div id="app">
        @include('layouts.navbar')

        <main class="py-4">
            @yield('content')
        </main>

        <footer class="border-t border-gray-200 bg-white/80 py-6 backdrop-blur">
            <div class="container mx-auto px-4 text-center text-sm text-gray-600">
                <span class="text-muted">© {{ date('Y') }} Ormin's Pasalubong Center. All rights reserved.</span>
            </div>
        </footer>
    </div>
</body>
</html>