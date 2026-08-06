<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account | Ormin's Pasalubong Center</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-primary/30 text-gray-800">
    <header class="border-b bg-primary">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900">My Account</h1>
                <p class="text-sm text-gray-700">View and edit your customer account details.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('customer.dashboard') }}" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">Back to Dashboard</a>
                <a href="{{ route('home') }}" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">Marketplace</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-lg bg-red-500 px-4 py-2 text-sm font-semibold text-white hover:bg-red-600">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-4 py-8">
        @if(session('success'))
            <div class="mb-4 rounded-lg border border-primary-strong bg-primary px-4 py-3 text-sm font-medium text-gray-800">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <p class="font-semibold">Please fix the following:</p>
                <ul class="mt-2 list-disc space-y-1 pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="rounded-xl bg-white p-6 shadow">
            <h2 class="text-xl font-bold text-gray-900">Customer Account Detail</h2>
            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                <div class="rounded-lg border border-gray-100 bg-gray-50 p-3">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Current Name</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                </div>
                <div class="rounded-lg border border-gray-100 bg-gray-50 p-3">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Current Email</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ auth()->user()->email }}</p>
                </div>
                <div class="rounded-lg border border-gray-100 bg-gray-50 p-3 sm:col-span-2">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Current Address</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ auth()->user()->address ?: 'No address set yet' }}</p>
                </div>
                <div class="rounded-lg border border-gray-100 bg-gray-50 p-3">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Account Type</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ ucfirst((string) auth()->user()->user_type) }}</p>
                </div>
                <div class="rounded-lg border border-gray-100 bg-gray-50 p-3">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Member Since</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ auth()->user()->created_at?->format('M d, Y') ?? '-' }}</p>
                </div>
            </div>
        </section>

        <section class="mt-6 rounded-xl bg-white p-6 shadow">
            <h2 class="text-xl font-bold text-gray-900">My Account</h2>
            <p class="mt-1 text-sm text-gray-600">Update your basic account details, including your address.</p>

            <form method="POST" action="{{ route('customer.account.update') }}" class="mt-5 grid gap-4 md:grid-cols-2">
                @csrf
                @method('PATCH')

                <div>
                    <label for="name" class="mb-1 block text-sm font-semibold text-gray-700">Full Name</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', auth()->user()->name) }}"
                        required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-strong focus:outline-none focus:ring-1 focus:ring-primary-strong"
                    >
                </div>

                <div>
                    <label for="email" class="mb-1 block text-sm font-semibold text-gray-700">Email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email', auth()->user()->email) }}"
                        required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-strong focus:outline-none focus:ring-1 focus:ring-primary-strong"
                    >
                </div>

                <div class="md:col-span-2">
                    <label for="address" class="mb-1 block text-sm font-semibold text-gray-700">Address</label>
                    <textarea
                        id="address"
                        name="address"
                        rows="2"
                        required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-strong focus:outline-none focus:ring-1 focus:ring-primary-strong"
                        placeholder="Enter your complete address"
                    >{{ old('address', auth()->user()->address) }}</textarea>
                </div>

                <div>
                    <label for="password" class="mb-1 block text-sm font-semibold text-gray-700">New Password (optional)</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        placeholder="Leave blank to keep current password"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-strong focus:outline-none focus:ring-1 focus:ring-primary-strong"
                    >
                </div>

                <div>
                    <label for="password_confirmation" class="mb-1 block text-sm font-semibold text-gray-700">Confirm New Password</label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        placeholder="Repeat new password"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary-strong focus:outline-none focus:ring-1 focus:ring-primary-strong"
                    >
                </div>

                <div class="md:col-span-2">
                    <button type="submit" class="rounded-xl bg-primary-strong px-5 py-2.5 text-sm font-bold text-white hover:bg-green-700">
                        Save Account Changes
                    </button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
