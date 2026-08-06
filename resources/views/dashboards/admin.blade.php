<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Ormin's Pasalubong Center</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-primary/30 text-gray-800">
    <header class="border-b bg-primary">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900">Admin Dashboard</h1>
                <p class="text-sm text-gray-700">Manage inventory, orders, sales, and staff.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">Home</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-lg bg-red-500 px-4 py-2 text-sm font-semibold text-white hover:bg-red-600">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8">
        @if(session('success'))
            <div class="mb-4 rounded-lg border border-primary-strong bg-primary px-4 py-3 text-sm font-medium text-gray-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm text-gray-500">Total Products</p>
                <h2 class="mt-2 text-3xl font-bold text-primary-strong">{{ $totalProducts }}</h2>
            </div>
            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm text-gray-500">Pending Orders</p>
                <h2 class="mt-2 text-3xl font-bold text-secondary-strong">{{ $pendingOrders }}</h2>
            </div>
            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm text-gray-500">Sales Today</p>
                <h2 class="mt-2 text-3xl font-bold text-primary-strong">P {{ number_format($salesToday, 2) }}</h2>
            </div>
            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm text-gray-500">Low Stock Alerts</p>
                <h2 class="mt-2 text-3xl font-bold text-red-600">{{ $lowStockAlerts }}</h2>
            </div>
        </div>

        @php
            $dashboardActions = [
                ['label' => 'Approve Orders', 'route' => 'admin.orders.index', 'featured' => false],
                ['label' => 'Sales Reports', 'route' => 'admin.reports.sales', 'featured' => true],
                ['label' => 'Manage Users', 'route' => 'admin.users.index', 'featured' => false],
            ];
        @endphp

        <section class="mt-6 rounded-xl border border-gray-200 bg-white p-6 shadow">
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($dashboardActions as $action)
                    <a
                        href="{{ route($action['route']) }}"
                        class="rounded-lg px-4 py-3 text-center font-semibold transition {{ $action['featured'] ? 'bg-primary text-gray-900 ring-1 ring-primary-strong/60 shadow-sm hover:bg-primary-strong hover:text-white' : 'bg-secondary text-gray-900 hover:bg-secondary-strong' }}"
                    >
                        {{ $action['label'] }}
                    </a>
                @endforeach
            </div>
        </section>

        @php
            $addedCount = $managementHistory->filter(fn ($item) => $item->created_at && $item->updated_at && $item->created_at->equalTo($item->updated_at))->count();
            $updatedCount = $managementHistory->count() - $addedCount;
            $lowStockCount = $managementHistory->filter(fn ($item) => $item->stock_quantity <= $item->low_stock_threshold)->count();
        @endphp

        <section id="management-history" class="mt-8 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow">
            <div class="bg-gradient-to-r from-secondary/40 via-white to-primary/50 px-6 py-5">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="inline-flex items-center rounded-full bg-white/90 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.12em] text-gray-600">Marketplace Activity Center</p>
                        <h3 class="mt-2 text-2xl font-black tracking-tight text-gray-900">Management History</h3>
                        <p class="mt-1 text-sm text-gray-600">Marketplace-style activity feed for latest inventory updates.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button id="historyFilterButton" data-dropdown-toggle="historyFilterMenu" class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50" type="button">
                            Filter
                            <svg class="ms-2 h-2.5 w-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                        </button>

                        <div id="historyFilterMenu" class="z-10 hidden w-44 divide-y divide-gray-100 rounded-lg bg-white shadow">
                            <ul class="py-2 text-sm text-gray-700" aria-labelledby="historyFilterButton">
                                <li><span class="block px-4 py-2">All: {{ $managementHistory->count() }}</span></li>
                                <li><span class="block px-4 py-2">Added: {{ $addedCount }}</span></li>
                                <li><span class="block px-4 py-2">Updated: {{ $updatedCount }}</span></li>
                                <li><span class="block px-4 py-2">Low Stock: {{ $lowStockCount }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($managementHistory as $history)
                    @php
                        $isAdded = $history->created_at && $history->updated_at && $history->created_at->equalTo($history->updated_at);
                        $isLowStock = $history->stock_quantity <= $history->low_stock_threshold;
                        $denominator = max($history->low_stock_threshold * 2, 1);
                        $stockProgress = min(100, (int) round(($history->stock_quantity / $denominator) * 100));
                    @endphp

                    <article class="group flex flex-col gap-4 p-5 transition hover:bg-gray-50 sm:flex-row sm:items-center">
                        <div class="relative h-20 w-20 shrink-0 overflow-hidden rounded-xl border border-gray-200 bg-white">
                            <img
                                src="{{ asset($history->image_path ?? 'images/products/default-product.jpg') }}"
                                alt="{{ $history->name }}"
                                class="h-full w-full object-cover"
                            >
                            <span class="absolute left-1 top-1 rounded bg-black/70 px-1.5 py-0.5 text-[10px] font-semibold text-white">SKU</span>
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h4 class="truncate text-base font-extrabold tracking-tight text-gray-900">{{ $history->name }}</h4>
                                @if($isAdded)
                                    <span class="rounded-full bg-emerald-100 px-2 py-1 text-[11px] font-bold text-emerald-700">NEW</span>
                                @else
                                    <span class="rounded-full bg-amber-100 px-2 py-1 text-[11px] font-bold text-amber-700">UPDATED</span>
                                @endif

                                @if($isLowStock)
                                    <span class="rounded-full bg-red-100 px-2 py-1 text-[11px] font-bold text-red-700">LOW STOCK</span>
                                @endif
                            </div>

                            <p class="mt-1 text-xs text-gray-500">SKU {{ $history->sku_code }} • {{ $history->updated_at?->format('M d, Y h:i A') ?? '-' }}</p>

                            <div class="mt-3 flex flex-wrap items-center gap-x-5 gap-y-1 text-sm">
                                <p class="font-semibold text-gray-900">Price: P {{ number_format($history->price ?? 0, 2) }}</p>
                                <p class="text-gray-700">Stock: {{ $history->stock_quantity }} pcs</p>
                                <p class="text-gray-700">Threshold: {{ $history->low_stock_threshold }}</p>
                            </div>

                            <div class="mt-3 h-2 w-full overflow-hidden rounded-full bg-gray-200">
                                <div
                                    class="h-full rounded-full {{ $isLowStock ? 'bg-red-500' : 'bg-emerald-500' }}"
                                    style="width: {{ $stockProgress }}%"
                                ></div>
                            </div>
                        </div>

                        <div class="shrink-0 text-left sm:text-right">
                            <p class="text-xs uppercase tracking-wide text-gray-400">Activity</p>
                            <p class="text-sm font-bold text-gray-900">{{ $isAdded ? 'Product Added' : 'Product Updated' }}</p>
                            <p class="text-xs text-gray-500">{{ $history->updated_at?->diffForHumans() ?? '-' }}</p>
                        </div>
                    </article>
                @empty
                    <div class="p-8 text-center">
                        <p class="text-sm text-gray-500">No management history yet.</p>
                    </div>
                @endforelse
            </div>
        </section>

    </main>
</body>
</html>
