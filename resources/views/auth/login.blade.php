<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Ormin's Pasalubong Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#CDEFD3',
                        secondary: '#FFE169',
                        primaryStrong: '#85C88A',
                        secondaryStrong: '#F5C542',
                        deep: '#1F2937',
                    },
                },
            },
        };
    </script>
</head>
<body class="min-h-screen bg-gradient-to-b from-primary via-yellow-50 to-white text-gray-800">
    <div class="mx-auto flex min-h-screen w-full max-w-2xl items-center justify-center px-4 py-10">
        <div class="grid w-full overflow-hidden rounded-3xl border border-primary bg-white shadow-2xl lg:grid-cols-1">

            <main class="p-6 sm:p-10">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-primaryStrong hover:text-secondaryStrong">&larr; Back to Home</a>
                <h2 class="mt-6 text-3xl font-extrabold text-deep">Login</h2>
                <p class="mt-1 text-sm text-gray-500">Access your dashboard based on your role.</p>

                @if(request('message'))
                    <div class="mt-4 rounded-lg border border-secondaryStrong bg-secondary/40 px-4 py-3 text-sm text-gray-800">{{ request('message') }}</div>
                @endif

                @if(session('error'))
                    <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
                @endif

                @if (isset($errors) && $errors->any())
                    <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ url('/login') }}" method="POST" class="mt-8 space-y-5">
                    @csrf
                    <div>
                        <label for="email" class="mb-1 block text-sm font-semibold text-gray-700">Email Address</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            required
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 outline-none ring-primary/40 transition focus:border-primaryStrong focus:ring"
                            placeholder="you@example.com"
                        >
                    </div>

                    <div>
                        <label for="password" class="mb-1 block text-sm font-semibold text-gray-700">Password</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 outline-none ring-primary/40 transition focus:border-primaryStrong focus:ring"
                            placeholder="Enter your password"
                        >
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-secondary px-4 py-3 font-bold text-deep transition hover:bg-secondaryStrong"
                    >
                        Sign In
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-gray-600">
                    No account yet?
                    <a href="{{ route('register') }}" class="font-bold text-primaryStrong hover:text-secondaryStrong">Register here</a>
                </p>
            </main>
        </div>
    </div>
</body>
</html>
