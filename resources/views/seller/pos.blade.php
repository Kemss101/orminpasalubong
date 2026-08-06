<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller POS | Ormin's Pasalubong Center</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-primary/30 text-gray-800">
    @php
        $viewErrors = $errors ?? new \Illuminate\Support\ViewErrorBag();
        $sellerActions = [
            ['label' => 'Open POS', 'url' => route('seller.pos'), 'active' => request()->routeIs('seller.pos')],
            ['label' => 'View Product Stock', 'url' => route('seller.stock'), 'active' => request()->routeIs('seller.stock')],
            ['label' => 'Print Receipt', 'url' => route('seller.receipt.latest'), 'active' => request()->routeIs('seller.receipt*')],
        ];
    @endphp

    <header class="border-b bg-primary">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900">Seller POS</h1>
                <p class="text-sm text-gray-700">Create transactions and deduct stock automatically.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('seller.dashboard') }}" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">Dashboard</a>
                <a href="{{ route('home') }}" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">Home</a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8">
        <section class="rounded-xl border border-gray-200 bg-white p-6 shadow">
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($sellerActions as $action)
                    <a
                        href="{{ $action['url'] }}"
                        class="rounded-lg px-4 py-3 text-center font-semibold transition {{ $action['active'] ? 'bg-primary text-gray-900 ring-1 ring-primary-strong/60 shadow-sm' : 'bg-secondary text-gray-900 hover:bg-secondary-strong' }}"
                    >
                        {{ $action['label'] }}
                    </a>
                @endforeach
            </div>
        </section>

        @if(session('success'))
            <div class="mb-4 mt-6 rounded-lg border border-primary-strong bg-primary px-4 py-3 text-sm font-medium text-gray-800">
                {{ session('success') }}
            </div>
        @endif

        @if($viewErrors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc pl-5">
                    @foreach($viewErrors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($products->isEmpty())
            <div class="rounded-xl border border-gray-100 bg-white p-8 text-center shadow">
                <h2 class="text-xl font-bold">No products available</h2>
                <p class="mt-2 text-sm text-gray-600">Add products first to start using POS.</p>
            </div>
        @else
            <section class="mt-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow">
                <div class="bg-gradient-to-r from-secondary/40 via-white to-primary/50 px-6 py-5">
                    <p class="inline-flex items-center rounded-full bg-white/90 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.12em] text-gray-600">Point of Sale</p>
                    <h2 class="mt-2 text-2xl font-black tracking-tight text-gray-900">Product Checkout</h2>
                    <p class="mt-1 text-sm text-gray-600">Select quantity, set payment method, and complete transaction.</p>
                </div>

                <div class="p-5">
                    <div class="overflow-x-auto rounded-xl border border-gray-100">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-primary/55 text-gray-900">
                                <tr>
                                    <th class="px-4 py-3">Product</th>
                                    <th class="px-4 py-3">Category</th>
                                    <th class="px-4 py-3">Price</th>
                                    <th class="px-4 py-3">Stock</th>
                                    <th class="px-4 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach($products as $product)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-4">
                                            <p class="font-semibold text-gray-900">{{ $product->name }}</p>
                                            <p class="text-xs text-gray-500">SKU: {{ $product->sku_code }}</p>
                                        </td>
                                        <td class="px-4 py-4">
                                            <span class="rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-700">
                                                {{ $product->category?->name ?? 'Uncategorized' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 font-semibold text-primary-strong">P {{ number_format($product->price, 2) }}</td>
                                        <td class="px-4 py-4">
                                            <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $product->stock_quantity <= $product->low_stock_threshold ? 'bg-red-100 text-red-700' : 'bg-primary text-primary-strong' }}">
                                                {{ $product->stock_quantity }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <form method="POST" action="{{ route('seller.pos.checkout') }}" class="flex flex-wrap items-center gap-2">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input
                                                    type="number"
                                                    name="quantity"
                                                    min="1"
                                                    max="{{ $product->stock_quantity }}"
                                                    value="1"
                                                    class="w-20 rounded-lg border border-gray-300 px-2 py-2 focus:border-primary-strong focus:outline-none"
                                                    {{ $product->stock_quantity < 1 ? 'disabled' : '' }}
                                                >
                                                <select
                                                    name="payment_method"
                                                    class="rounded-lg border border-gray-300 px-2 py-2 focus:border-primary-strong focus:outline-none"
                                                    {{ $product->stock_quantity < 1 ? 'disabled' : '' }}
                                                >
                                                    <option value="Cash">Cash</option>
                                                    <option value="Pending">Pending</option>
                                                </select>
                                                <button
                                                    type="submit"
                                                    class="rounded-lg bg-secondary px-3 py-2 text-xs font-bold text-gray-900 hover:bg-secondary-strong disabled:cursor-not-allowed disabled:bg-gray-200"
                                                    {{ $product->stock_quantity < 1 ? 'disabled' : '' }}
                                                >
                                                    Sell
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        @endif
    </main>
</body>
</html>
