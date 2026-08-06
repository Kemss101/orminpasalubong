<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ormin's Pasalubong Center</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Manrope', sans-serif;
            background: radial-gradient(circle at 10% 10%, #7c8853 0%, #47572c 40%, #eecb2e 100%);
        }

        .font-display {
            font-family: 'Sora', sans-serif;
        }

        .grain-bg {
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.55), rgba(255, 255, 255, 0.55)),
                linear-gradient(90deg, rgba(0, 0, 0, 0.025) 1px, transparent 1px),
                linear-gradient(rgba(0, 0, 0, 0.025) 1px, transparent 1px);
            background-size: auto, 24px 24px, 24px 24px;
            background-position: 0 0, -1px -1px, -1px -1px;
        }

        .product-float {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-float:hover {
            transform: translateY(-7px) rotate(-0.4deg);
            box-shadow: 0 18px 40px rgba(17, 24, 39, 0.12);
        }
    </style>
</head>
<body id="top" class="text-gray-800">
    @php
        $viewErrors = $errors ?? new \Illuminate\Support\ViewErrorBag();
        $cartCount = collect(session('cart', []))->sum('quantity');
        $loginMessage = 'Please log in first to access your cart.';
        $guestAddMessage = 'Please log in first to add items to your cart.';
        $openModal = request()->query('open');
        $hasRegisterErrors = $viewErrors->has('name') || $viewErrors->has('user_type') || $viewErrors->has('password_confirmation');
        if (!$openModal && $viewErrors->any()) {
            $openModal = $hasRegisterErrors ? 'register' : 'login';
        }
        $modalMessage = request()->query('message');
        $productsBySku = \App\Models\Product::query()
            ->whereIn('sku_code', ['PSLB-BAN-001', 'PSLB-COC-002', 'PSLB-UBE-003', 'PSLB-SUM-004'])
            ->get()
            ->keyBy('sku_code');
        $bananaProduct = $productsBySku->get('PSLB-BAN-001');
        $cocoProduct = $productsBySku->get('PSLB-COC-002');
        $ubeProduct = $productsBySku->get('PSLB-UBE-003');
        $sumanProduct = $productsBySku->get('PSLB-SUM-004');

        $categoryCatalog = [
            'sweets' => [
                [
                    'name' => 'Yema Candy',
                    'price' => 65,
                    'image' => 'images/products/yema-candy.jpg',
                    'description' => 'Creamy milk candy that melts in your mouth.',
                ],
                [
                    'name' => 'Polvoron',
                    'price' => 95,
                    'image' => 'images/products/polvoron.jpg',
                    'description' => 'Classic crumbly milk shortbread snack.',
                ],
                [
                    'name' => 'Pastillas de Leche',
                    'price' => 75,
                    'image' => 'images/products/pastillas.jpg',
                    'description' => 'Soft and milky candy wrapped in colorful paper.',
                ],
            ],
            'delicacies' => [
                [
                    'name' => 'Cassava Cake',
                    'price' => 140,
                    'image' => 'images/products/cassava-cake.jpg',
                    'description' => 'Rich cassava dessert with creamy topping.',
                ],
                [
                    'name' => 'Bibingka',
                    'price' => 120,
                    'image' => 'images/products/bibingka.jpg',
                    'description' => 'Traditional rice cake served warm and fragrant.',
                ],
                [
                    'name' => 'Buko Pie',
                    'price' => 230,
                    'image' => 'images/products/buko-pie.jpg',
                    'description' => 'Buttery crust pie with sweet young coconut filling.',
                ],
            ],
            'souvenirs' => [
                [
                    'name' => 'Souvenir Keychain',
                    'price' => 60,
                    'image' => 'images/products/souvenir-keychain.jpg',
                    'description' => 'Pocket souvenir keepsake for family and friends.',
                ],
                [
                    'name' => 'Ref Magnet',
                    'price' => 55,
                    'image' => 'images/products/ref-magnet.jpg',
                    'description' => 'Collectible fridge magnet with local travel vibe.',
                ],
                [
                    'name' => 'Handwoven Bag',
                    'price' => 320,
                    'image' => 'images/products/woven-bag.jpg',
                    'description' => 'Lightweight woven bag perfect for pasalubong gifts.',
                ],
            ],
            'wines-beverages' => [
                [
                    'name' => 'Calamansi Juice',
                    'price' => 45,
                    'image' => 'images/products/calamansi-juice.jpg',
                    'description' => 'Refreshing citrus drink, sweet and tangy.',
                ],
                [
                    'name' => 'Coconut Wine',
                    'price' => 180,
                    'image' => 'images/products/coconut-wine.jpg',
                    'description' => 'Traditional coconut-based local wine beverage.',
                ],
                [
                    'name' => 'Kapeng Barako',
                    'price' => 160,
                    'image' => 'images/products/kapeng-barako.jpg',
                    'description' => 'Strong local coffee with bold Batangas aroma.',
                ],
            ],
        ];

        $categoryProductNames = collect($categoryCatalog)
            ->flatten(1)
            ->pluck('name')
            ->unique()
            ->values();

        $categoryProductsByName = \App\Models\Product::query()
            ->whereIn('name', $categoryProductNames)
            ->get()
            ->keyBy('name');
    @endphp

    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute -left-20 -top-24 h-80 w-80 rounded-full bg-primary/60 blur-3xl"></div>
        <div class="absolute -bottom-20 right-10 h-72 w-72 rounded-full bg-secondary/45 blur-3xl"></div>
    </div>

    <nav class="sticky top-0 z-50 border-b border-white/60 bg-white/80 shadow-sm backdrop-blur-md">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="inline-flex items-center" aria-label="Ormin's Pasalubong Center">
                    <img src="{{ asset('images/ormins-logo.png') }}" alt="Ormin's Pasalubong Center logo" class="h-9 w-auto max-w-[140px] rounded-full border border-primary-strong/35 bg-white object-contain p-0.5 shadow-sm sm:h-10 sm:max-w-[170px]">
                </a>
            </div>

            <div class="hidden items-center gap-3 md:flex">
                <button id="categoryDropdownButton" data-dropdown-toggle="categoryDropdownMenu" class="inline-flex items-center rounded-full border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50" type="button">
                    Categories
                    <svg class="ms-2 h-2.5 w-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                </button>

                <div id="categoryDropdownMenu" class="z-10 hidden w-48 divide-y divide-gray-100 rounded-xl bg-white shadow">
                    <ul class="py-2 text-sm text-gray-700" aria-labelledby="categoryDropdownButton">
                        <li><a href="#category-sweets" class="block px-4 py-2 hover:bg-primary/35">Sweets</a></li>
                        <li><a href="#category-delicacies" class="block px-4 py-2 hover:bg-primary/35">Delicacies</a></li>
                        <li><a href="#category-souvenirs" class="block px-4 py-2 hover:bg-primary/35">Souvenirs</a></li>
                        <li><a href="#category-wines-beverages" class="block px-4 py-2 hover:bg-primary/35">Wines & Beverages</a></li>
                    </ul>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @auth
                    <span class="hidden text-sm font-medium text-gray-600 sm:inline">Hi, {{ auth()->user()->name }}</span>
                    @if(auth()->user()->user_type === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-gray-700 hover:text-secondary-strong">Dashboard</a>
                    @elseif(auth()->user()->user_type === 'seller')
                        <a href="{{ route('seller.dashboard') }}" class="text-sm font-semibold text-gray-700 hover:text-secondary-strong">POS Dashboard</a>
                    @else
                        <a href="{{ route('customer.account') }}" class="text-sm font-semibold text-gray-700 hover:text-secondary-strong">My Account</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="rounded-full bg-red-500 px-3 py-1.5 text-sm font-semibold text-white transition hover:bg-red-600">Logout</button>
                    </form>
                @else
                    <button id="openLoginModalButton" type="button" data-auth-open="loginModal" class="text-sm font-semibold text-gray-700 hover:text-secondary-strong">Login</button>
                    <button id="openRegisterModalButton" type="button" data-auth-open="registerModal" class="rounded-full bg-secondary px-4 py-2 text-sm font-bold text-gray-900 shadow hover:bg-secondary-strong">Register</button>
                @endauth

                <a href="{{ auth()->check() ? route('cart.index') : route('home', ['open' => 'login', 'message' => $loginMessage]) }}" title="Cart" class="relative">
                    <div class="grid h-10 w-10 place-items-center rounded-full border border-gray-200 bg-white text-gray-800">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <span class="absolute -right-1 -top-1 grid h-5 w-5 place-items-center rounded-full bg-red-500 text-xs font-bold text-white">{{ $cartCount }}</span>
                </a>
            </div>
        </div>
    </nav>

    <main class="mx-auto max-w-7xl px-4 pb-14 pt-8 sm:px-6 lg:px-8">
        @if(session('success'))
            <div id="successAlert" class="mb-4 flex items-center rounded-xl border border-primary-strong bg-primary/70 p-4 text-sm text-gray-800" role="alert">
                <svg class="me-3 h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.7-9.7-4 4a1 1 0 0 1-1.4 0l-2-2"/>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
                <button type="button" class="ms-auto inline-flex h-8 w-8 items-center justify-center rounded-lg text-gray-500 hover:bg-white/70" data-dismiss-target="#successAlert" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 12 12M13 1 1 13"/>
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div id="errorAlert" class="mb-4 flex items-center rounded-xl border border-red-300 bg-red-50 p-4 text-sm text-red-700" role="alert">
                <svg class="me-3 h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM10 6v4m0 4h.01"/>
                </svg>
                <span class="font-medium">{{ session('error') }}</span>
                <button type="button" class="ms-auto inline-flex h-8 w-8 items-center justify-center rounded-lg text-red-500 hover:bg-red-100" data-dismiss-target="#errorAlert" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 12 12M13 1 1 13"/>
                    </svg>
                </button>
            </div>
        @endif

        <section class="grain-bg overflow-hidden rounded-3xl border border-white/70 bg-white/75 p-5 shadow-xl sm:p-8">
            <div class="grid gap-8 lg:grid-cols-[1.05fr_0.95fr] lg:items-center">
                <div>
                    <p class="inline-flex items-center gap-2 rounded-full border border-primary-strong/40 bg-white px-3 py-1 text-xs font-bold uppercase tracking-[0.15em] text-gray-600">
                        <span class="inline-block h-2 w-2 rounded-full bg-secondary-strong"></span>
                        Fresh from Oriental Mindoro
                    </p>
                    <h1 class="font-display mt-4 text-3xl font-extrabold leading-tight text-gray-900 sm:text-5xl">
                        Curated Pasalubong,
                        <span class="text-primary-strong">brightly local</span>
                        and proudly handcrafted.
                    </h1>
                    <p class="mt-4 max-w-xl text-sm leading-relaxed text-gray-600 sm:text-base">
                        Discover local sweets and delicacies with a playful market vibe. From coco jams to suman classics, every product is selected for taste and authenticity.
                    </p>

                    <div class="mt-6 flex flex-wrap items-center gap-3">
                        <a href="#products" class="rounded-full bg-secondary px-6 py-3 text-sm font-extrabold text-gray-900 shadow-md transition hover:scale-[1.02] hover:bg-secondary-strong">Shop Featured</a>
                        <a href="{{ auth()->check() ? route('cart.index') : route('home', ['open' => 'login', 'message' => $loginMessage]) }}" class="rounded-full border border-gray-300 bg-white px-6 py-3 text-sm font-bold text-gray-700 hover:bg-gray-50">View Cart</a>
                    </div>
                </div>

                <div>
                    <div id="featured-carousel" class="relative w-full" data-carousel="slide">
                        <div class="relative h-60 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-lg sm:h-72">
                            <div class="hidden duration-700 ease-in-out" data-carousel-item="active">
                                <img src="{{ asset($bananaProduct?->image_path ?? 'images/products/banana-chips.jpg') }}" class="absolute inset-0 h-full w-full object-cover" alt="Banana chips">
                            </div>
                            <div class="hidden duration-700 ease-in-out" data-carousel-item>
                                <img src="{{ asset($cocoProduct?->image_path ?? 'images/products/coco-jam.jpg') }}" class="absolute inset-0 h-full w-full object-cover" alt="Coco jam">
                            </div>
                            <div class="hidden duration-700 ease-in-out" data-carousel-item>
                                <img src="{{ asset($ubeProduct?->image_path ?? 'images/products/ube-halaya.jpg') }}" class="absolute inset-0 h-full w-full object-cover" alt="Ube halaya">
                            </div>
                        </div>
                        <button type="button" class="group absolute start-0 top-0 flex h-full items-center justify-center px-3 focus:outline-none" data-carousel-prev>
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/70 text-gray-700 group-hover:bg-white">
                                <svg class="h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                                </svg>
                            </span>
                        </button>
                        <button type="button" class="group absolute end-0 top-0 flex h-full items-center justify-center px-3 focus:outline-none" data-carousel-next>
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/70 text-gray-700 group-hover:bg-white">
                                <svg class="h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                            </span>
                        </button>
                    </div>

                    <div class="mt-4 grid grid-cols-3 gap-3 text-center text-xs sm:text-sm">
                        <div class="rounded-xl border border-primary-strong/30 bg-primary/60 p-3"><p class="font-bold text-gray-900">50+</p><p class="text-gray-600">Daily Orders</p></div>
                        <div class="rounded-xl border border-secondary-strong/30 bg-secondary/55 p-3"><p class="font-bold text-gray-900">4.9</p><p class="text-gray-600">Customer Rating</p></div>
                        <div class="rounded-xl border border-gray-300 bg-white p-3"><p class="font-bold text-gray-900">100%</p><p class="text-gray-600">Local Goods</p></div>
                    </div>
                </div>
            </div>
        </section>

        <section id="products" class="mt-10">
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="font-display text-2xl font-extrabold text-gray-900 sm:text-3xl">Featured Products</h2>
                    <p class="mt-1 text-sm text-gray-600">Tap Add to send items directly to your cart.</p>
                </div>
                <div class="w-full sm:w-auto">
                    <div class="relative">
                        <input type="text" placeholder="Search delicacies..." class="w-full rounded-full border border-gray-200 bg-white px-4 py-2.5 pr-10 text-sm shadow-sm focus:border-primary-strong focus:outline-none sm:w-64">
                        <svg class="pointer-events-none absolute right-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <article class="product-float overflow-hidden rounded-2xl border border-gray-100 bg-white">
                    <div class="relative h-48 overflow-hidden bg-gray-200">
                        <span class="absolute right-2 top-2 rounded-full bg-secondary px-2 py-1 text-[11px] font-bold text-gray-900">Best Seller</span>
                    <img src="{{ asset($bananaProduct?->image_path ?? 'images/products/banana-chips.jpg') }}" alt="Sweetened Banana Chips" class="h-full w-full object-cover">
                    </div>
                    <div class="p-4">
                        <p class="text-xs uppercase tracking-wide text-gray-500">Delicacies</p>
                        <h3 class="mt-1 truncate text-lg font-extrabold text-gray-900">{{ $bananaProduct?->name ?? 'Sweetened Banana Chips' }}</h3>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-xl font-black text-primary-strong">P {{ number_format($bananaProduct?->price ?? 85, 2) }}</span>
                        @auth
                            @if($bananaProduct)
                                <form method="POST" action="{{ route('cart.add') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $bananaProduct->id }}">
                                    <input type="hidden" name="category" value="Delicacies">
                                    <button type="submit" class="rounded-full bg-primary px-4 py-2 text-sm font-bold text-gray-900 shadow-sm transition hover:bg-secondary">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        Add
                                    </button>
                                </form>
                            @else
                                <button type="button" disabled class="rounded bg-gray-200 px-4 py-2 text-sm font-medium text-gray-500">Unavailable</button>
                            @endif
                        @else
                            <a href="{{ route('home', ['open' => 'login', 'message' => $guestAddMessage]) }}" class="rounded-full bg-primary px-4 py-2 text-sm font-bold text-gray-900 shadow-sm transition hover:bg-secondary">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Add
                            </a>
                        @endauth
                        </div>
                    </div>
                </article>

                <article class="product-float overflow-hidden rounded-2xl border border-gray-100 bg-white">
                    <div class="h-48 overflow-hidden bg-gray-200">
                    <img src="{{ asset($cocoProduct?->image_path ?? 'images/products/coco-jam.jpg') }}" alt="Premium Coco Jam" class="h-full w-full object-cover">
                    </div>
                    <div class="p-4">
                        <p class="text-xs uppercase tracking-wide text-gray-500">Sweets</p>
                        <h3 class="mt-1 truncate text-lg font-extrabold text-gray-900">{{ $cocoProduct?->name ?? 'Premium Coco Jam' }}</h3>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-xl font-black text-primary-strong">P {{ number_format($cocoProduct?->price ?? 120, 2) }}</span>
                        @auth
                            @if($cocoProduct)
                                <form method="POST" action="{{ route('cart.add') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $cocoProduct->id }}">
                                    <input type="hidden" name="category" value="Sweets">
                                    <button type="submit" class="rounded-full bg-primary px-4 py-2 text-sm font-bold text-gray-900 shadow-sm transition hover:bg-secondary">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        Add
                                    </button>
                                </form>
                            @else
                                <button type="button" disabled class="rounded bg-gray-200 px-4 py-2 text-sm font-medium text-gray-500">Unavailable</button>
                            @endif
                        @else
                            <a href="{{ route('home', ['open' => 'login', 'message' => $guestAddMessage]) }}" class="rounded-full bg-primary px-4 py-2 text-sm font-bold text-gray-900 shadow-sm transition hover:bg-secondary">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Add
                            </a>
                        @endauth
                        </div>
                    </div>
                </article>

                <article class="product-float overflow-hidden rounded-2xl border border-gray-100 bg-white">
                    <div class="h-48 overflow-hidden bg-gray-200">
                    <img src="{{ asset($ubeProduct?->image_path ?? 'images/products/ube-halaya.jpg') }}" alt="Mindoro Ube Halaya" class="h-full w-full object-cover">
                    </div>
                    <div class="p-4">
                        <p class="text-xs uppercase tracking-wide text-gray-500">Sweets</p>
                        <h3 class="mt-1 truncate text-lg font-extrabold text-gray-900">{{ $ubeProduct?->name ?? "Mindoro's Ube Halaya" }}</h3>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-xl font-black text-primary-strong">P {{ number_format($ubeProduct?->price ?? 150, 2) }}</span>
                        @auth
                            @if($ubeProduct)
                                <form method="POST" action="{{ route('cart.add') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $ubeProduct->id }}">
                                    <input type="hidden" name="category" value="Sweets">
                                    <button type="submit" class="rounded-full bg-primary px-4 py-2 text-sm font-bold text-gray-900 shadow-sm transition hover:bg-secondary">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        Add
                                    </button>
                                </form>
                            @else
                                <button type="button" disabled class="rounded bg-gray-200 px-4 py-2 text-sm font-medium text-gray-500">Unavailable</button>
                            @endif
                        @else
                            <a href="{{ route('home', ['open' => 'login', 'message' => $guestAddMessage]) }}" class="rounded-full bg-primary px-4 py-2 text-sm font-bold text-gray-900 shadow-sm transition hover:bg-secondary">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Add
                            </a>
                        @endauth
                        </div>
                    </div>
                </article>

                <article class="product-float overflow-hidden rounded-2xl border border-gray-100 bg-white">
                    <div class="h-48 overflow-hidden bg-gray-200">
                    <img src="{{ asset($sumanProduct?->image_path ?? 'images/products/suman.jpg') }}" alt="Suman sa Lihiya" class="h-full w-full object-cover">
                    </div>
                    <div class="p-4">
                        <p class="text-xs uppercase tracking-wide text-gray-500">Delicacies</p>
                        <h3 class="mt-1 truncate text-lg font-extrabold text-gray-900">{{ $sumanProduct?->name ?? 'Suman sa Lihiya' }}</h3>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-xl font-black text-primary-strong">P {{ number_format($sumanProduct?->price ?? 50, 2) }}</span>
                        @auth
                            @if($sumanProduct)
                                <form method="POST" action="{{ route('cart.add') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $sumanProduct->id }}">
                                    <input type="hidden" name="category" value="Delicacies">
                                    <button type="submit" class="rounded-full bg-primary px-4 py-2 text-sm font-bold text-gray-900 shadow-sm transition hover:bg-secondary">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        Add
                                    </button>
                                </form>
                            @else
                                <button type="button" disabled class="rounded bg-gray-200 px-4 py-2 text-sm font-medium text-gray-500">Unavailable</button>
                            @endif
                        @else
                            <a href="{{ route('home', ['open' => 'login', 'message' => $guestAddMessage]) }}" class="rounded-full bg-primary px-4 py-2 text-sm font-bold text-gray-900 shadow-sm transition hover:bg-secondary">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Add
                            </a>
                        @endauth
                        </div>
                    </div>
                </article>
            </div>
        </section>

        <section class="mt-12 space-y-10">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h2 class="font-display text-2xl font-extrabold text-gray-900 sm:text-3xl">Shop by Category</h2>
                    <p class="mt-1 text-sm text-gray-600">Click each category section to discover more products with real photos.</p>
                </div>
                <a href="#top" class="text-sm font-semibold text-gray-600 hover:text-primary-strong">Back to top</a>
            </div>

            @php
                $categoryTitles = [
                    'sweets' => 'Sweets',
                    'delicacies' => 'Delicacies',
                    'souvenirs' => 'Souvenirs',
                    'wines-beverages' => 'Wines & Beverages',
                ];
            @endphp

            @foreach($categoryTitles as $slug => $title)
                <div id="category-{{ $slug }}" class="rounded-2xl border border-gray-200 bg-white/85 p-5 shadow-sm sm:p-6">
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
                        <h3 class="font-display text-xl font-extrabold text-gray-900">{{ $title }}</h3>
                        <a href="#products" class="rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-semibold text-gray-700 hover:bg-gray-50">Jump to Featured</a>
                    </div>

                    <div class="grid items-stretch gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($categoryCatalog[$slug] as $item)
                            @php
                                $catalogProduct = $categoryProductsByName->get($item['name']);
                            @endphp
                            <article class="group flex h-full flex-col overflow-hidden rounded-xl border border-gray-100 bg-white transition hover:-translate-y-1 hover:shadow-lg">
                                <div class="h-44 overflow-hidden bg-gray-200">
                                    @if($item['name'] === 'Buko Pie')
                                        <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" class="h-full w-full scale-75 object-cover transition group-hover:scale-80">
                                    @else
                                        <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" class="h-full w-full object-cover transition group-hover:scale-105">
                                    @endif
                                </div>
                                <div class="flex flex-1 flex-col p-4">
                                    <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">{{ $title }}</p>
                                    <h4 class="mt-1 line-clamp-2 text-base font-extrabold text-gray-900">{{ $item['name'] }}</h4>
                                    <p class="mt-1 line-clamp-2 min-h-10 text-sm text-gray-600">{{ $item['description'] }}</p>
                                    <div class="mt-auto flex items-center justify-between pt-3">
                                        <span class="text-base font-black text-primary-strong">P {{ number_format($item['price'], 2) }}</span>

                                        @auth
                                            @if(auth()->user()->user_type === 'customer')
                                                @if($catalogProduct)
                                                    <form method="POST" action="{{ route('cart.add') }}">
                                                        @csrf
                                                        <input type="hidden" name="product_id" value="{{ $catalogProduct->id }}">
                                                        <input type="hidden" name="category" value="{{ $title }}">
                                                        <button type="submit" class="rounded-full bg-secondary px-3 py-1 text-xs font-bold text-gray-900 transition hover:bg-secondary-strong">Add</button>
                                                    </form>
                                                @else
                                                    <button type="button" disabled class="rounded-full bg-gray-200 px-3 py-1 text-xs font-semibold text-gray-500">Unavailable</button>
                                                @endif
                                            @else
                                                <span class="rounded-full bg-gray-200 px-3 py-1 text-xs font-semibold text-gray-600">Customer only</span>
                                            @endif
                                        @else
                                            <a href="{{ route('home', ['open' => 'login', 'message' => 'Log in as customer to add items to cart.']) }}" class="rounded-full bg-secondary px-3 py-1 text-xs font-bold text-gray-900 transition hover:bg-secondary-strong">Add</a>
                                        @endauth
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </section>

        <section class="mt-10 grid gap-5 lg:grid-cols-[1.15fr_0.85fr]">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="font-display text-2xl font-extrabold text-gray-900">How To Order</h3>
                <p class="mt-1 text-sm text-gray-600">Simple checkout process for customers.</p>

                <div id="howToAccordion" data-accordion="collapse" class="mt-4">
                    <h2 id="howToHeadingOne">
                        <button type="button" class="flex w-full items-center justify-between gap-3 rounded-t-xl border border-gray-200 p-4 text-left font-semibold text-gray-700" data-accordion-target="#howToBodyOne" aria-expanded="true" aria-controls="howToBodyOne">
                            <span>1. Add your favorites to cart</span>
                            <svg data-accordion-icon class="h-3 w-3 shrink-0 rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                            </svg>
                        </button>
                    </h2>
                    <div id="howToBodyOne" class="hidden" aria-labelledby="howToHeadingOne">
                        <div class="border border-t-0 border-gray-200 p-4 text-sm text-gray-600">Browse featured products and tap Add to include them in your cart.</div>
                    </div>

                    <h2 id="howToHeadingTwo">
                        <button type="button" class="flex w-full items-center justify-between gap-3 border border-t-0 border-gray-200 p-4 text-left font-semibold text-gray-700" data-accordion-target="#howToBodyTwo" aria-expanded="false" aria-controls="howToBodyTwo">
                            <span>2. Review order summary</span>
                            <svg data-accordion-icon class="h-3 w-3 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                            </svg>
                        </button>
                    </h2>
                    <div id="howToBodyTwo" class="hidden" aria-labelledby="howToHeadingTwo">
                        <div class="border border-t-0 border-gray-200 p-4 text-sm text-gray-600">Check quantities, subtotal, and your selected items before checkout.</div>
                    </div>

                    <h2 id="howToHeadingThree">
                        <button type="button" class="flex w-full items-center justify-between gap-3 rounded-b-xl border border-t-0 border-gray-200 p-4 text-left font-semibold text-gray-700" data-accordion-target="#howToBodyThree" aria-expanded="false" aria-controls="howToBodyThree">
                            <span>3. Confirm and wait for updates</span>
                            <svg data-accordion-icon class="h-3 w-3 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                            </svg>
                        </button>
                    </h2>
                    <div id="howToBodyThree" class="hidden" aria-labelledby="howToHeadingThree">
                        <div class="border border-t-0 border-gray-200 p-4 text-sm text-gray-600">Your order moves to pending review and updates are managed by the admin side.</div>
                    </div>
                </div>
            </div>

            <aside class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="font-display text-xl font-extrabold text-gray-900">Why Locals Love It</h3>
                <ul class="mt-4 space-y-3 text-sm text-gray-700">
                    <li class="rounded-lg bg-primary/45 p-3"><span class="font-bold">Fresh batches daily:</span> always updated stock.</li>
                    <li class="rounded-lg bg-secondary/40 p-3"><span class="font-bold">Handpicked products:</span> curated local favorites.</li>
                    <li class="rounded-lg bg-primary/45 p-3"><span class="font-bold">Simple checkout:</span> quick and mobile-friendly flow.</li>
                </ul>
            </aside>
        </section>
    </main>

    @guest
        <div id="loginModal" tabindex="-1" aria-hidden="true" class="fixed inset-0 z-50 hidden items-center justify-center overflow-y-auto bg-black/50 p-4">
            <div class="relative w-full max-w-sm">
                <div class="relative max-h-[88vh] overflow-hidden rounded-2xl bg-white shadow-xl">
                    <div class="flex items-center justify-between border-b border-gray-200 p-4">
                        <h3 class="font-display text-xl font-extrabold text-gray-900">Login</h3>
                        <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-900" data-auth-close="loginModal" aria-label="Close login modal">
                            <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 12 12M13 1 1 13"/>
                            </svg>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="space-y-2.5 overflow-y-auto p-4">
                        @csrf

                        @if($openModal === 'login' && $modalMessage)
                            <div class="rounded-lg border border-primary-strong/40 bg-primary/40 px-3 py-2 text-sm text-gray-700">
                                {{ $modalMessage }}
                            </div>
                        @endif

                        @if($openModal === 'login' && $viewErrors->any())
                            <div class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
                                {{ $viewErrors->first() }}
                            </div>
                        @endif

                        <div>
                            <label for="modal_login_email" class="mb-1 block text-sm font-semibold text-gray-700">Email Address</label>
                            <input id="modal_login_email" name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm outline-none ring-primary/40 transition focus:border-primary-strong focus:ring" placeholder="you@example.com">
                        </div>

                        <div>
                            <label for="modal_login_password" class="mb-1 block text-sm font-semibold text-gray-700">Password</label>
                            <input id="modal_login_password" name="password" type="password" required class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm outline-none ring-primary/40 transition focus:border-primary-strong focus:ring" placeholder="Enter password">
                        </div>

                        <button type="submit" class="w-full rounded-xl bg-secondary px-4 py-3 text-sm font-extrabold text-gray-900 transition hover:bg-secondary-strong">
                            Sign In
                        </button>

                        <p class="text-center text-sm text-gray-600">
                            No account yet?
                            <button type="button" data-auth-switch="registerModal" class="font-bold text-primary-strong hover:text-secondary-strong">Create one</button>
                        </p>
                    </form>
                </div>
            </div>
        </div>

        <div id="registerModal" tabindex="-1" aria-hidden="true" class="fixed inset-0 z-50 hidden items-center justify-center overflow-y-auto bg-black/50 p-4">
            <div class="relative w-full max-w-md">
                <div class="relative max-h-[90vh] overflow-hidden rounded-2xl bg-white shadow-xl">
                    <div class="flex items-center justify-between border-b border-gray-200 p-4">
                        <h3 class="font-display text-xl font-extrabold text-gray-900">Create Account</h3>
                        <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-900" data-auth-close="registerModal" aria-label="Close register modal">
                            <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 12 12M13 1 1 13"/>
                            </svg>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-2.5 overflow-y-auto p-4">
                        @csrf

                        @if($openModal === 'register' && $viewErrors->any())
                            <div class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
                                <ul class="list-disc pl-5">
                                    @foreach($viewErrors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div>
                            <label for="modal_register_name" class="mb-1 block text-sm font-semibold text-gray-700">Full Name</label>
                            <input id="modal_register_name" name="name" type="text" value="{{ old('name') }}" required class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm outline-none ring-primary/40 transition focus:border-primary-strong focus:ring" placeholder="Your full name">
                        </div>

                        <div>
                            <label for="modal_register_email" class="mb-1 block text-sm font-semibold text-gray-700">Email Address</label>
                            <input id="modal_register_email" name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm outline-none ring-primary/40 transition focus:border-primary-strong focus:ring" placeholder="you@example.com">
                        </div>

                        <div>
                            <label for="modal_register_user_type" class="mb-1 block text-sm font-semibold text-gray-700">Account Role</label>
                            <select id="modal_register_user_type" name="user_type" required class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm outline-none ring-primary/40 transition focus:border-primary-strong focus:ring">
                                <option value="" disabled {{ old('user_type') ? '' : 'selected' }}>Select a role</option>
                                <option value="seller" {{ old('user_type') === 'seller' ? 'selected' : '' }}>Seller</option>
                                <option value="customer" {{ old('user_type') === 'customer' ? 'selected' : '' }}>Customer</option>
                            </select>
                        </div>

                        <div id="modalRegisterAddressGroup" class="{{ old('user_type') === 'customer' ? '' : 'hidden' }}">
                            <label for="modal_register_address" class="mb-1 block text-sm font-semibold text-gray-700">Address <span class="text-red-500">*</span></label>
                            <input id="modal_register_address" name="address" type="text" value="{{ old('address') }}" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm outline-none ring-primary/40 transition focus:border-primary-strong focus:ring" placeholder="Enter your full address">
                            <p class="mt-1 text-xs text-gray-500">Required for customer accounts.</p>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="modal_register_password" class="mb-1 block text-sm font-semibold text-gray-700">Password</label>
                                <div class="relative">
                                    <input id="modal_register_password" name="password" type="password" required class="w-full rounded-xl border border-gray-300 px-3 py-2.5 pr-11 text-sm outline-none ring-primary/40 transition focus:border-primary-strong focus:ring" placeholder="At least 8 characters">
                                    <button id="toggleModalRegisterPassword" type="button" class="absolute inset-y-0 right-0 inline-flex items-center px-3 text-gray-500 hover:text-gray-700" aria-label="Show password">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.27 2.943 9.542 7-1.272 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label for="modal_register_password_confirmation" class="mb-1 block text-sm font-semibold text-gray-700">Confirm Password</label>
                                <div class="relative">
                                    <input id="modal_register_password_confirmation" name="password_confirmation" type="password" required class="w-full rounded-xl border border-gray-300 px-3 py-2.5 pr-11 text-sm outline-none ring-primary/40 transition focus:border-primary-strong focus:ring" placeholder="Retype password">
                                    <button id="toggleModalRegisterPasswordConfirmation" type="button" class="absolute inset-y-0 right-0 inline-flex items-center px-3 text-gray-500 hover:text-gray-700" aria-label="Show confirm password">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.27 2.943 9.542 7-1.272 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <p id="modalRegisterPasswordMatch" class="hidden text-xs font-semibold"></p>

                        <button type="submit" class="w-full rounded-xl bg-primary px-4 py-3 text-sm font-extrabold text-gray-900 transition hover:bg-primary-strong">
                            Create Account
                        </button>

                        <p class="text-center text-sm text-gray-600">
                            Already registered?
                            <button type="button" data-auth-switch="loginModal" class="font-bold text-primary-strong hover:text-secondary-strong">Sign in</button>
                        </p>
                    </form>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                    function authOpenModal(modalId) {
                        const modal = document.getElementById(modalId);
                        if (!modal) {
                            return;
                        }

                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                        document.body.classList.add('overflow-hidden');
                    }

                    function authCloseModal(modalId) {
                        const modal = document.getElementById(modalId);
                        if (!modal) {
                            return;
                        }

                        modal.classList.add('hidden');
                        modal.classList.remove('flex');

                        const stillOpen = Array.from(document.querySelectorAll('#loginModal, #registerModal'))
                            .some(function (candidate) {
                                return !candidate.classList.contains('hidden');
                            });

                        if (!stillOpen) {
                            document.body.classList.remove('overflow-hidden');
                        }
                    }

                    document.querySelectorAll('[data-auth-open]').forEach(function (button) {
                        button.addEventListener('click', function () {
                            authOpenModal(button.getAttribute('data-auth-open'));
                        });
                    });

                    document.querySelectorAll('[data-auth-close]').forEach(function (button) {
                        button.addEventListener('click', function () {
                            authCloseModal(button.getAttribute('data-auth-close'));
                        });
                    });

                    document.querySelectorAll('[data-auth-switch]').forEach(function (button) {
                        button.addEventListener('click', function () {
                            const currentModal = button.closest('[id$="Modal"]');
                            if (currentModal && currentModal.id) {
                                authCloseModal(currentModal.id);
                            }
                            authOpenModal(button.getAttribute('data-auth-switch'));
                        });
                    });

                    document.querySelectorAll('#loginModal, #registerModal').forEach(function (modal) {
                        modal.addEventListener('mousedown', function (event) {
                            if (event.target === modal) {
                                authCloseModal(modal.id);
                            }
                        });
                    });

                    document.addEventListener('keydown', function (event) {
                        if (event.key !== 'Escape') {
                            return;
                        }

                        document.querySelectorAll('#loginModal, #registerModal').forEach(function (modal) {
                            if (!modal.classList.contains('hidden')) {
                                authCloseModal(modal.id);
                            }
                        });
                    });

                    function setupPasswordToggle(inputId, buttonId) {
                        const input = document.getElementById(inputId);
                        const button = document.getElementById(buttonId);

                        if (!input || !button) {
                            return;
                        }

                        button.addEventListener('click', function () {
                            const shouldShow = input.type === 'password';
                            input.type = shouldShow ? 'text' : 'password';
                            button.setAttribute('aria-label', shouldShow ? 'Hide password' : 'Show password');
                        });
                    }

                    function setupCustomerAddressField(selectId, groupId, inputId) {
                        const roleSelect = document.getElementById(selectId);
                        const addressGroup = document.getElementById(groupId);
                        const addressInput = document.getElementById(inputId);

                        if (!roleSelect || !addressGroup || !addressInput) {
                            return;
                        }

                        const syncAddressField = function () {
                            const isCustomer = roleSelect.value === 'customer';
                            addressGroup.classList.toggle('hidden', !isCustomer);
                            addressInput.required = isCustomer;

                            if (!isCustomer) {
                                addressInput.value = '';
                            }
                        };

                        roleSelect.addEventListener('change', syncAddressField);
                        syncAddressField();
                    }

                    function setupPasswordMatch(passwordId, confirmId, messageId) {
                        const passwordField = document.getElementById(passwordId);
                        const confirmField = document.getElementById(confirmId);
                        const message = document.getElementById(messageId);

                        if (!passwordField || !confirmField || !message) {
                            return;
                        }

                        const updateMessage = function () {
                            if (!confirmField.value) {
                                message.classList.add('hidden');
                                message.classList.remove('text-red-600', 'text-emerald-600');
                                confirmField.setCustomValidity('');
                                return;
                            }

                            message.classList.remove('hidden');

                            if (passwordField.value === confirmField.value) {
                                message.textContent = 'Passwords match.';
                                message.classList.remove('text-red-600');
                                message.classList.add('text-emerald-600');
                                confirmField.setCustomValidity('');
                                return;
                            }

                            message.textContent = 'Passwords do not match.';
                            message.classList.remove('text-emerald-600');
                            message.classList.add('text-red-600');
                            confirmField.setCustomValidity('Passwords do not match.');
                        };

                        passwordField.addEventListener('input', updateMessage);
                        confirmField.addEventListener('input', updateMessage);
                        updateMessage();
                    }

                    setupPasswordToggle('modal_register_password', 'toggleModalRegisterPassword');
                    setupPasswordToggle('modal_register_password_confirmation', 'toggleModalRegisterPasswordConfirmation');
                    setupPasswordMatch('modal_register_password', 'modal_register_password_confirmation', 'modalRegisterPasswordMatch');
                    setupCustomerAddressField('modal_register_user_type', 'modalRegisterAddressGroup', 'modal_register_address');

                    const initialModal = @json($openModal);
                    if (initialModal === 'login' || initialModal === 'register') {
                        const targetButtonId = initialModal === 'register' ? 'openRegisterModalButton' : 'openLoginModalButton';
                        const targetButton = document.getElementById(targetButtonId);
                        if (targetButton) {
                            targetButton.click();
                        }
                    }
            });
        </script>
    @endguest

    <footer class="mt-12 border-t border-white/80 bg-primary-strong py-8 text-gray-900">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2026 Ormin's Pasalubong Center. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
