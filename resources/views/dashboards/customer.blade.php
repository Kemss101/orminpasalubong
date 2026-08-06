<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard | Ormin's Pasalubong Center</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-primary/30 text-gray-800">
    <header class="border-b bg-primary">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900">Customer Dashboard</h1>
                <p class="text-sm text-gray-700">Track orders, view purchases, and continue shopping.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">Back to Marketplace</a>
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

        @if(session('error'))
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                {{ session('error') }}
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

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm text-gray-500">Total Orders</p>
                <h2 class="mt-2 text-3xl font-bold text-gray-900">{{ $totalOrders }}</h2>
            </div>
            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm text-gray-500">Pending Orders</p>
                <h2 class="mt-2 text-3xl font-bold text-secondary-strong">{{ $pendingOrders }}</h2>
            </div>
            <div class="rounded-xl bg-white p-5 shadow">
                <p class="text-sm text-gray-500">Accepted Orders</p>
                <h2 class="mt-2 text-3xl font-bold text-primary-strong">{{ $acceptedOrders }}</h2>
            </div>
        </div>

        <section class="mt-8 rounded-xl bg-white p-6 shadow">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">My Cart</h3>
                    <p class="mt-1 text-sm text-gray-600">Review cart items and checkout directly here.</p>
                </div>
                <a href="{{ route('home') }}" class="rounded-lg bg-secondary px-4 py-2 text-sm font-semibold text-gray-900 hover:bg-secondary-strong">Add More Items</a>
            </div>

            @if(empty($cart))
                <div class="mt-5 rounded-xl border border-dashed border-primary-strong bg-primary/20 p-6 text-center">
                    <p class="text-base font-semibold text-gray-800">Your cart is empty.</p>
                    <p class="mt-1 text-sm text-gray-600">Add products from the marketplace to checkout.</p>
                </div>
            @else
                <div class="mt-5 grid gap-6 lg:grid-cols-3">
                    <div class="space-y-3 lg:col-span-2">
                        @foreach($cart as $item)
                            <article class="flex flex-col gap-4 rounded-xl border border-gray-100 p-4 sm:flex-row sm:items-center">
                                <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" class="h-24 w-full rounded-lg object-cover sm:w-32">

                                <div class="flex-1">
                                    <h4 class="text-base font-bold text-gray-900">{{ $item['name'] }}</h4>
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

                                <div class="text-left sm:text-right">
                                    <p class="text-sm text-gray-500">Subtotal</p>
                                    <p class="text-base font-bold text-primary-strong">P {{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                                    <form method="POST" action="{{ route('cart.remove', $item['product_id']) }}" class="mt-2">
                                        @csrf
                                        <button type="submit" class="rounded-md bg-red-500 px-3 py-1 text-xs font-semibold text-white hover:bg-red-600">Remove</button>
                                    </form>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <aside class="h-fit rounded-xl border border-gray-100 bg-gray-50 p-4">
                        <h4 class="text-lg font-bold text-gray-900">Checkout Summary</h4>
                        <div class="mt-3 space-y-2 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Items</span>
                                <span class="font-semibold">{{ $cartItemCount }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-semibold">P {{ number_format($cartSubtotal, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Shipping</span>
                                <span class="font-semibold">P 0.00</span>
                            </div>
                            <hr class="my-2">
                            <div class="flex items-center justify-between text-base">
                                <span class="font-bold text-gray-900">Total</span>
                                <span class="font-bold text-primary-strong">P {{ number_format($cartSubtotal, 2) }}</span>
                            </div>
                        </div>

                        <a href="{{ route('checkout.show') }}" class="mt-4 block w-full rounded-xl bg-secondary px-4 py-3 text-center font-bold text-gray-900 hover:bg-secondary-strong">
                            Checkout Now
                        </a>
                    </aside>
                </div>
            @endif
        </section>

        <section class="mt-8 rounded-xl bg-white p-6 shadow">
            <h3 class="text-xl font-bold text-gray-900">Recent Order History</h3>
            <p class="mt-1 text-sm text-gray-600">Orders checked out from your cart are synced to the seller account queue.</p>
            <div class="mt-4 overflow-hidden rounded-lg border border-gray-200">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-3">Order #</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Items</th>
                            <th class="px-4 py-3">Total</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Seller Queue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            @php
                                $statusLabel = $order->status ?: 'Pending';
                                $deliveryLabel = $order->getDeliveryStatus();
                                $statusBadgeClass = match ($statusLabel) {
                                    'Accepted' => 'rounded-full bg-primary px-2 py-1 text-xs font-semibold text-primary-strong',
                                    'Packed' => 'rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-700',
                                    'Shipped' => 'rounded-full bg-cyan-100 px-2 py-1 text-xs font-semibold text-cyan-700',
                                    'Out for Delivery' => 'rounded-full bg-indigo-100 px-2 py-1 text-xs font-semibold text-indigo-700',
                                    'Delivered' => 'rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700',
                                    'Cancelled' => 'rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-700',
                                    default => 'rounded-full bg-secondary px-2 py-1 text-xs font-semibold text-secondary-strong',
                                };
                            @endphp
                            <tr class="border-t" data-order-row data-order-id="{{ $order->id }}">
                                <td class="px-4 py-3">ORD-{{ str_pad((string) $order->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-4 py-3">{{ $order->created_at?->format('M d, Y') ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $order->items_count }}</td>
                                <td class="px-4 py-3">P {{ number_format($order->total_amount, 2) }}</td>
                                <td class="px-4 py-3">
                                    <span data-status-badge class="{{ $statusBadgeClass }}">{{ $statusLabel }}</span>
                                    <p class="mt-1 text-xs text-gray-500">Delivery: <span data-delivery-label>{{ $deliveryLabel }}</span></p>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700">Synced</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">No recent orders yet. Add products to cart then checkout.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const endpoint = @json(route('customer.orders.status'));
            const rows = Array.from(document.querySelectorAll('[data-order-row]'));
            const rowMap = new Map(rows.map(row => [row.dataset.orderId, row]));

            const statusBadgeClasses = {
                Accepted: 'rounded-full bg-primary px-2 py-1 text-xs font-semibold text-primary-strong',
                Packed: 'rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-700',
                Shipped: 'rounded-full bg-cyan-100 px-2 py-1 text-xs font-semibold text-cyan-700',
                'Out for Delivery': 'rounded-full bg-indigo-100 px-2 py-1 text-xs font-semibold text-indigo-700',
                Delivered: 'rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700',
                Cancelled: 'rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-700',
                Pending: 'rounded-full bg-secondary px-2 py-1 text-xs font-semibold text-secondary-strong',
                Declined: 'rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-700',
            };

            const applyStatus = (row, status, deliveryStatus) => {
                const badge = row.querySelector('[data-status-badge]');
                if (badge) {
                    const cls = statusBadgeClasses[status] || statusBadgeClasses.Pending;
                    badge.className = cls;
                    badge.textContent = status;
                }

                const deliveryLabel = row.querySelector('[data-delivery-label]');
                if (deliveryLabel) {
                    deliveryLabel.textContent = deliveryStatus || 'Pending';
                }
            };

            const refreshOrders = async () => {
                try {
                    const response = await fetch(endpoint, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        cache: 'no-store',
                    });

                    if (!response.ok) {
                        return;
                    }

                    const payload = await response.json();
                    (payload.orders || []).forEach((order) => {
                        const row = rowMap.get(String(order.id));
                        if (!row) {
                            return;
                        }

                        applyStatus(row, order.status || 'Pending', order.delivery_status || 'Pending');
                    });
                } catch (error) {
                    // Silent fail to keep dashboard usable on temporary errors.
                }
            };

            refreshOrders();
            setInterval(refreshOrders, 10000);
        });
    </script>
</body>
</html>
