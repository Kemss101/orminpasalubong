<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Stock | Seller</title>
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
                <h1 class="text-2xl font-extrabold text-gray-900">Product Stock</h1>
                <p class="text-sm text-gray-700">Monitor available inventory and low stock alerts.</p>
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

        <section class="mt-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow">
            <div class="bg-gradient-to-r from-secondary/40 via-white to-primary/50 px-6 py-5">
                <p class="inline-flex items-center rounded-full bg-white/90 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.12em] text-gray-600">Stock Monitoring</p>
                <h2 class="mt-2 text-2xl font-black tracking-tight text-gray-900">Product Stock Control</h2>
                <p class="mt-1 text-sm text-gray-600">Pwede mo nang i-edit kung ilang stock ang ilalagay per product.</p>
            </div>

            <div class="p-5">
                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-primary/55 text-gray-900">
                            <tr>
                                <th class="px-4 py-3">SKU</th>
                                <th class="px-4 py-3">Product Name</th>
                                <th class="px-4 py-3">Category</th>
                                <th class="px-4 py-3">Price</th>
                                <th class="px-4 py-3">Current Stock</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Edit Stock</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($products as $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">{{ $product->sku_code }}</td>
                                    <td class="px-4 py-3 font-semibold text-gray-900">{{ $product->name }}</td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-700">
                                            {{ $product->category?->name ?? 'Uncategorized' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">P {{ number_format($product->price, 2) }}</td>
                                    <td class="px-4 py-3 font-semibold text-gray-900">{{ $product->stock_quantity }}</td>
                                    <td class="px-4 py-3">
                                        @if($product->stock_quantity <= 0)
                                            <span class="rounded-full bg-red-100 px-2 py-1 text-xs font-bold text-red-700">Out of Stock</span>
                                        @elseif($product->stock_quantity <= $product->low_stock_threshold)
                                            <span class="rounded-full bg-yellow-100 px-2 py-1 text-xs font-bold text-yellow-700">Low Stock</span>
                                        @else
                                            <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-bold text-emerald-700">Available</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <form method="POST" action="{{ route('seller.stock.update', $product) }}" class="flex flex-wrap items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <input
                                                type="number"
                                                name="stock_quantity"
                                                min="0"
                                                value="{{ $product->stock_quantity }}"
                                                class="w-24 rounded-lg border border-gray-300 px-2 py-1.5 text-sm focus:border-primary-strong focus:outline-none"
                                            >
                                            <button type="submit" class="rounded-lg bg-secondary px-3 py-1.5 text-xs font-semibold text-gray-900 hover:bg-secondary-strong">
                                                Save
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">No products found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
