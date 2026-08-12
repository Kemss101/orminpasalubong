<nav class="sticky top-0 z-40 border-b border-white/60 bg-white/85 shadow-sm backdrop-blur">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <div class="grid h-10 w-10 place-items-center rounded-2xl bg-primary text-primary-strong shadow-sm">OC</div>
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-secondary-strong">Ormin's</p>
                <p class="text-sm font-extrabold text-gray-900">Pasalubong Center</p>
            </div>
        </a>

        <div class="flex flex-wrap items-center gap-2 text-sm font-semibold text-gray-700">
            <a href="{{ route('home') }}" class="rounded-full px-4 py-2 hover:bg-primary/40">Home</a>
            <a href="{{ route('home') }}#services" class="rounded-full px-4 py-2 hover:bg-primary/40">Services</a>
            <a href="{{ route('home') }}#about" class="rounded-full px-4 py-2 hover:bg-primary/40">About</a>

            <!-- GUEST VIEW: Shown when logged out (Controlled by Firebase class .auth-guest) -->
            <div class="auth-guest flex items-center gap-2">
                <button type="button" onclick="openLoginModal(false)" class="rounded-full px-4 py-2 hover:bg-primary/40">
                    Login
                </button>
                <button type="button" onclick="openLoginModal(true)" class="rounded-full bg-secondary px-4 py-2 text-gray-900 hover:bg-secondaryStrong">
                    Register
                </button>
            </div>

            <!-- USER VIEW: Shown when logged in (Controlled by Firebase class .auth-user) -->
            <div class="auth-user flex items-center gap-2" style="display: none;">
                <a href="#" class="rounded-full px-4 py-2 hover:bg-primary/40">Dashboard</a>
                <button type="button" onclick="import('https://www.gstatic.com/firebasejs/10.7.1/firebase-auth.js').then(({getAuth, signOut}) => signOut(getAuth()))" class="rounded-full bg-red-500 px-4 py-2 text-white hover:bg-red-600">
                    Logout
                </button>
            </div>
        </div>
    </div>
</nav>