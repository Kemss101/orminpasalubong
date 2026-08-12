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
                <span class="text-muted">&copy; {{ date('Y') }} Ormin's Pasalubong Center. All rights reserved.</span>
            </div>
        </footer>
    </div>

   <!-- Login / Create Account Modal Structure -->
    <div id="loginModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;">
        <div class="w-full max-w-md rounded-2xl bg-white p-8 shadow-2xl relative max-h-[90vh] overflow-y-auto">
            <button onclick="document.getElementById('loginModal').style.display='none'" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 font-bold text-lg">
                &times;
            </button>
            
            <h2 id="modalTitle" class="text-2xl font-bold mb-6 text-gray-800">Sign In</h2>

            <!-- Sign In Form -->
            <form id="loginForm" method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" name="email" required class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary" placeholder="you@example.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" required class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary" placeholder="Enter password">
                </div>
                <button type="submit" class="w-full rounded-lg bg-yellow-400 py-2.5 font-semibold text-gray-900 shadow-md hover:bg-yellow-500 transition">
                    Sign In
                </button>
                <p class="text-center text-sm text-gray-600 mt-4">
                    No account yet? <button type="button" onclick="toggleAuthMode()" class="text-primary font-medium hover:underline">Create one</button>
                </p>
            </form>

            <!-- Create Account Form (Updated with Name, Address, and Password Confirmation) -->
            <form id="registerForm" method="POST" action="{{ route('register') }}" class="space-y-4 hidden">
                @csrf
                <input type="hidden" name="user_type" value="customer">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" name="name" required class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary" placeholder="Juan Dela Cruz">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" name="email" required class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary" placeholder="you@example.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Address</label>
                    <input type="text" name="address" required class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary" placeholder="Complete Address">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" required class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary" placeholder="At least 8 characters">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" name="password_confirmation" required class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary" placeholder="Re-enter password">
                </div>
                <button type="submit" class="w-full rounded-lg bg-yellow-400 py-2.5 font-semibold text-gray-900 shadow-md hover:bg-yellow-500 transition">
                    Create Account
                </button>
                <p class="text-center text-sm text-gray-600 mt-4">
                    Already have an account? <button type="button" onclick="toggleAuthMode()" class="text-primary font-medium hover:underline">Sign In</button>
                </p>
            </form>
        </div>
    </div>

    <!-- Script para sa Modal Toggle -->
    <script>
        window.toggleAuthMode = function() {
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            const modalTitle = document.getElementById('modalTitle');
            
            loginForm.classList.toggle('hidden');
            registerForm.classList.toggle('hidden');
            modalTitle.textContent = loginForm.classList.contains('hidden') ? 'Create Account' : 'Sign In';
        };

        window.openLoginModal = function(isRegister = false) {
            const modal = document.getElementById('loginModal');
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            const modalTitle = document.getElementById('modalTitle');

            modal.style.display = 'flex';

            if (isRegister) {
                loginForm.classList.add('hidden');
                registerForm.classList.remove('hidden');
                modalTitle.textContent = 'Create Account';
            } else {
                registerForm.classList.add('hidden');
                loginForm.classList.remove('hidden');
                modalTitle.textContent = 'Sign In';
            }
        };
    </script>