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

            @if(Auth::check())
                @if(Auth::user()->user_type === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="rounded-full px-4 py-2 hover:bg-primary/40">Dashboard</a>
                @elseif(Auth::user()->user_type === 'seller')
                    <a href="{{ route('seller.dashboard') }}" class="rounded-full px-4 py-2 hover:bg-primary/40">Dashboard</a>
                @else
                    <a href="{{ route('customer.dashboard') }}" class="rounded-full px-4 py-2 hover:bg-primary/40">Dashboard</a>
                @endif

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="rounded-full bg-red-500 px-4 py-2 text-white hover:bg-red-600">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="rounded-full px-4 py-2 hover:bg-primary/40">Login</a>
                <a href="{{ route('register') }}" class="rounded-full bg-secondary px-4 py-2 text-gray-900 hover:bg-secondaryStrong">Register</a>
            @endif
        </div>
    </div>
</nav>