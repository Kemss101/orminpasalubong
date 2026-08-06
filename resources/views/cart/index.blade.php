<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart | Ormin's Pasalubong Center</title>
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
                    },
                },
            },
        };
    </script>
</head>
<body class="min-h-screen bg-primary/20 text-gray-800">
    @php
        $itemCount = collect($cart)->sum('quantity');
    @endphp

    <header class="border-b bg-primary shadow-sm">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900">My Cart</h1>
                <p class="text-sm text-gray-700">Review your selected pasalubong items.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">Continue Shopping</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-lg bg-red-500 px-4 py-2 text-sm font-semibold text-white hover:bg-red-600">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8">
        @if(session('success'))
            <div class="mb-6 rounded-lg border border-primaryStrong bg-primary px-4 py-3 text-sm font-medium text-gray-800">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                {{ session('error') }}
            </div>
        @endif

        @if(empty($cart))
            <div class="rounded-2xl border border-dashed border-primaryStrong bg-white p-10 text-center shadow">
                <h2 class="text-2xl font-bold text-gray-900">Your cart is empty</h2>
                <p class="mt-2 text-sm text-gray-600">Start adding products from the marketplace.</p>
                <a href="{{ route('home') }}" class="mt-5 inline-block rounded-xl bg-secondary px-6 py-3 font-semibold text-gray-900 hover:bg-secondaryStrong">Browse Products</a>
            </div>
        @else
            <div class="grid gap-6 lg:grid-cols-3">
                <section class="space-y-4 lg:col-span-2">
                    @foreach($cart as $item)
                        <article class="flex flex-col gap-4 rounded-xl bg-white p-4 shadow sm:flex-row sm:items-center">
                            <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" class="h-24 w-full rounded-lg object-cover sm:w-36">

                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-900">{{ $item['name'] }}</h3>
                                <p class="text-sm text-gray-500">Category: {{ $item['category'] }}</p>
                                <div class="mt-2 flex items-center gap-3">
                                    <span class="text-sm text-gray-600">Quantity</span>
                                    <form method="POST" action="{{ route('cart.update', $item['product_id']) }}" class="flex items-center gap-2">
                                        @csrf
                                        <button type="submit" name="action" value="decrease" class="inline-flex h-7 w-7 items-center justify-center rounded-md border border-gray-200 text-sm font-semibold text-gray-700 hover:bg-gray-100">-</button>
                                        <span class="min-w-[2rem] text-center text-sm font-semibold text-gray-800">{{ $item['quantity'] }}</span>
                                        <button type="submit" name="action" value="increase" class="inline-flex h-7 w-7 items-center justify-center rounded-md border border-gray-200 text-sm font-semibold text-gray-700 hover:bg-gray-100">+</button>
                                    </form>
                                </div>
                            </div>

                            <div class="text-right">
                                <p class="text-sm text-gray-500">Unit Price</p>
                                <p class="text-lg font-semibold text-primaryStrong">P {{ number_format($item['price'], 2) }}</p>
                                <p class="text-sm text-gray-500">Subtotal</p>
                                <p class="text-lg font-bold text-secondaryStrong">P {{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                                <form method="POST" action="{{ route('cart.remove', $item['product_id']) }}" class="mt-2">
                                    @csrf
                                    <button type="submit" class="rounded-md bg-red-500 px-3 py-1 text-xs font-semibold text-white hover:bg-red-600">Remove</button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </section>

                <aside class="h-fit rounded-xl bg-white p-5 shadow">
                    <h2 class="text-xl font-bold text-gray-900">Order Summary</h2>
                    <div class="mt-4 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Items</span>
                            <span class="font-semibold">{{ $itemCount }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-semibold">P {{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-semibold">P 0.00</span>
                        </div>
                        <hr class="my-2">
                        <div class="flex justify-between text-base">
                            <span class="font-bold text-gray-900">Total</span>
                            <span class="font-bold text-primaryStrong">P {{ number_format($subtotal, 2) }}</span>
                        </div>
                    </div>

                    <a href="{{ route('checkout.show') }}" class="mt-5 block w-full rounded-xl bg-secondary px-4 py-3 text-center font-bold text-gray-900 hover:bg-secondaryStrong">
                        Proceed to Checkout
                    </a>
                </aside>
            </div>
        @endif
    </main>
</body>
</html>
