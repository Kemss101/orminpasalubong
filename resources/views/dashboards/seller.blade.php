<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard | Ormin's Pasalubong Center</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-primary/30 text-gray-800">
    @php
        $sellerActions = [
            ['label' => 'Open POS', 'url' => route('seller.pos'), 'featured' => false],
            ['label' => 'View Product Stock', 'url' => route('seller.stock'), 'featured' => true],
            ['label' => 'Print Receipt', 'url' => route('seller.receipt.latest'), 'featured' => false],
        ];
    @endphp

    <header class="border-b bg-primary">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900">Seller Dashboard</h1>
                <p class="text-sm text-gray-700">Process sales and serve customers quickly.</p>
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

        @if(session('error'))
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6">
            <div class="rounded-xl border border-gray-100 bg-white p-5 shadow">
                <p class="text-sm text-gray-500">Transactions Today</p>
                <h2 id="transactionsTodayValue" class="mt-2 text-3xl font-bold text-gray-900">
                    <span data-metric-value="transactions">{{ $transactionsToday }}</span>
                    <span class="ml-1 text-sm font-semibold text-gray-400">orders</span>
                </h2>
                <p class="mt-1 text-xs text-gray-500">Total orders created today</p>
            </div>
            <div class="rounded-xl border border-gray-100 bg-white p-5 shadow">
                <p class="text-sm text-gray-500">Revenue Today</p>
                <h2 id="revenueTodayValue" class="mt-2 text-3xl font-bold text-primary-strong">
                    <span data-metric-value="revenue">{{ $revenueTodayFormatted }}</span>
                    <span class="ml-1 text-sm font-semibold text-gray-400">PHP</span>
                </h2>
                <p class="mt-1 text-xs text-gray-500">Gross sales for today</p>
            </div>
            <div class="rounded-xl border border-gray-100 bg-white p-5 shadow">
                <p class="text-sm text-gray-500">Pending Checkout</p>
                <h2 id="pendingPaymentsValue" class="mt-2 text-3xl font-bold text-red-600">
                    <span data-metric-value="pending">{{ $pendingPayments }}</span>
                    <span class="ml-1 text-sm font-semibold text-gray-400">orders</span>
                </h2>
                <p class="mt-1 text-xs text-gray-500">Orders waiting for payment</p>
            </div>
            <div class="rounded-xl border border-gray-100 bg-white p-5 shadow">
                <p class="text-sm text-gray-500">COD Orders</p>
                <h2 class="mt-2 text-3xl font-bold text-gray-900">{{ $codOrders }} <span class="ml-1 text-sm font-semibold text-gray-400">orders</span></h2>
                <p class="mt-1 text-xs text-gray-500">Cash on delivery orders</p>
            </div>
            <div class="rounded-xl border border-gray-100 bg-white p-5 shadow">
                <p class="text-sm text-gray-500">GCash Orders</p>
                <h2 class="mt-2 text-3xl font-bold text-gray-900">{{ $gcashOrders }} <span class="ml-1 text-sm font-semibold text-gray-400">orders</span></h2>
                <p class="mt-1 text-xs text-gray-500">Online payment orders</p>
            </div>
            <div class="rounded-xl border border-gray-100 bg-white p-5 shadow">
                <p class="text-sm text-gray-500">Ready to Fulfill</p>
                <h2 class="mt-2 text-3xl font-bold text-emerald-700">{{ $readyForFulfillment }} <span class="ml-1 text-sm font-semibold text-gray-400">orders</span></h2>
                <p class="mt-1 text-xs text-gray-500">Accepted, packed, shipped, or out for delivery</p>
            </div>
            <div class="rounded-xl border border-gray-100 bg-white p-5 shadow">
                <p class="text-sm text-gray-500">Latest Receipt</p>
                <h2 id="latestReceiptValue" class="mt-2 text-xl font-bold text-gray-900">
                    <span data-metric-value="receipt">{{ $latestReceipt }}</span>
                    <span class="ml-1 text-sm font-semibold text-gray-400">ref</span>
                </h2>
                <p class="mt-1 text-xs text-gray-500">Most recent receipt reference</p>
            </div>
        </div>
        <p class="mt-2 text-xs text-gray-500">Synced from customer checkout queue. Live refresh every 10 seconds. Last update: <span id="sellerDashboardLastSync">just now</span>.</p>

        <section class="mt-6 rounded-xl border border-gray-200 bg-white p-6 shadow">
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($sellerActions as $action)
                    <a
                        href="{{ $action['url'] }}"
                        class="rounded-lg px-4 py-3 text-center font-semibold transition {{ $action['featured'] ? 'bg-primary text-gray-900 ring-1 ring-primary-strong/60 shadow-sm hover:bg-primary-strong hover:text-white' : 'bg-secondary text-gray-900 hover:bg-secondary-strong' }}"
                    >
                        {{ $action['label'] }}
                    </a>
                @endforeach
            </div>
        </section>

        <section class="mt-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow">
            <div class="bg-gradient-to-r from-secondary/40 via-white to-primary/50 px-6 py-5">
                <p class="inline-flex items-center rounded-full bg-white/90 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.12em] text-gray-600">Seller Account Sync</p>
                <h3 class="mt-2 text-2xl font-black tracking-tight text-gray-900">Pasalubong Order Queue</h3>
                <p class="mt-1 text-sm text-gray-600">I-process mo rito ang COD at GCash orders ng Ormin's Pasalubong Center: accept, pack, ship, out for delivery, delivered.</p>
            </div>

            <div class="p-5">
                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-primary/55 text-gray-900">
                            <tr>
                                <th class="px-4 py-3">Order #</th>
                                <th class="px-4 py-3">Customer</th>
                                <th class="px-4 py-3">Payment</th>
                                <th class="px-4 py-3">Delivery</th>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Total</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Process Order</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($recentCustomerOrders as $order)
                                @php
                                    $statusLabel = $order->status ?: 'Pending';
                                    $paymentMethodLabel = $order->payment_method === 'gcash' ? 'GCash Online' : 'Cash on Delivery';
                                    $paymentStatusLabel = ucfirst((string) ($order->payment_status ?? 'unpaid'));
                                    $deliveryStatusLabel = $order->getDeliveryStatus();
                                    $statusBadgeClass = match ($statusLabel) {
                                        'Accepted' => 'rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700',
                                        'Packed' => 'rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-700',
                                        'Shipped' => 'rounded-full bg-cyan-100 px-2 py-1 text-xs font-semibold text-cyan-700',
                                        'Out for Delivery' => 'rounded-full bg-indigo-100 px-2 py-1 text-xs font-semibold text-indigo-700',
                                        'Delivered' => 'rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700',
                                        'Cancelled' => 'rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-700',
                                        default => 'rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-700',
                                    };
                                    $paymentBadgeClass = $order->payment_method === 'gcash'
                                        ? ($order->payment_status === 'completed'
                                            ? 'rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700'
                                            : 'rounded-full bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-700')
                                        : 'rounded-full bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-700';
                                    $deliveryBadgeClass = match ($deliveryStatusLabel) {
                                        'Packed' => 'rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-700',
                                        'Shipped' => 'rounded-full bg-cyan-100 px-2 py-1 text-xs font-semibold text-cyan-700',
                                        'Out for Delivery' => 'rounded-full bg-indigo-100 px-2 py-1 text-xs font-semibold text-indigo-700',
                                        'Delivered' => 'rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700',
                                        'Cancelled' => 'rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-700',
                                        default => 'rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-700',
                                    };
                                    $actionOptions = ['Pending', 'Accepted', 'Packed', 'Shipped', 'Out for Delivery', 'Delivered', 'Cancelled'];
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">ORD-{{ str_pad((string) $order->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-4 py-3">{{ optional($order->user)->name ?? 'Unknown' }}</td>
                                    <td class="px-4 py-3 space-y-1">
                                        <div><span class="rounded-full {{ $paymentBadgeClass }}">{{ $paymentMethodLabel }}</span></div>
                                        <div class="text-xs text-gray-500">Payment: {{ $paymentStatusLabel }}</div>
                                    </td>
                                    <td class="px-4 py-3 space-y-1">
                                        <div><span class="rounded-full {{ $deliveryBadgeClass }}">{{ $deliveryStatusLabel }}</span></div>
                                        <div class="text-xs text-gray-500">{{ ucfirst($order->delivery_method ?? 'standard') }} delivery</div>
                                    </td>
                                    <td class="px-4 py-3">{{ $order->created_at?->format('M d, Y h:i A') ?? '-' }}</td>
                                    <td class="px-4 py-3">P {{ number_format($order->getGrandTotal(), 2) }}</td>
                                    <td class="px-4 py-3">
                                        <span data-status-badge class="{{ $statusBadgeClass }}">{{ $statusLabel }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <form method="POST" action="{{ route('seller.orders.update-status', $order) }}" class="status-sync-form flex flex-wrap items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="rounded-md border border-gray-300 px-2.5 py-1.5 text-xs font-semibold text-gray-700 focus:border-primary-strong focus:outline-none focus:ring-1 focus:ring-primary-strong">
                                                @foreach($actionOptions as $actionStatus)
                                                    <option
                                                        value="{{ $actionStatus }}"
                                                        @selected($statusLabel === $actionStatus)
                                                    >
                                                        {{ $actionStatus }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="rounded-md bg-gray-900 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-gray-700">Update</button>
                                        </form>
                                        <p class="mt-2 text-[11px] text-emerald-700">Ready for fulfillment flow: accept, pack, ship, delivered.</p>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">No customer checkout orders yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const endpoint = @json(route('seller.dashboard.stats'));
            const transactionsTodayValue = document.getElementById('transactionsTodayValue');
            const revenueTodayValue = document.getElementById('revenueTodayValue');
            const pendingPaymentsValue = document.getElementById('pendingPaymentsValue');
            const latestReceiptValue = document.getElementById('latestReceiptValue');
            const sellerDashboardLastSync = document.getElementById('sellerDashboardLastSync');
            const statusForms = Array.from(document.querySelectorAll('.status-sync-form'));

            const statusBadgeClasses = {
                Accepted: 'rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700',
                Packed: 'rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-700',
                Shipped: 'rounded-full bg-cyan-100 px-2 py-1 text-xs font-semibold text-cyan-700',
                'Out for Delivery': 'rounded-full bg-indigo-100 px-2 py-1 text-xs font-semibold text-indigo-700',
                Delivered: 'rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700',
                Cancelled: 'rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-700',
                Pending: 'rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-700',
                Declined: 'rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-700',
            };

            const applyStatusBadge = (form, nextStatus) => {
                const row = form.closest('tr');
                if (!row) {
                    return;
                }

                const badge = row.querySelector('[data-status-badge]');
                if (!badge) {
                    return;
                }

                const status = statusBadgeClasses[nextStatus] ? nextStatus : 'Declined';
                badge.className = statusBadgeClasses[status];
                badge.textContent = status;
            };

            if (!transactionsTodayValue || !revenueTodayValue || !pendingPaymentsValue || !latestReceiptValue) {
                return;
            }

            const getMetricTarget = (container, key) => {
                if (!container) {
                    return null;
                }

                return container.querySelector(`[data-metric-value="${key}"]`) || container;
            };

            const transactionsTodayTarget = getMetricTarget(transactionsTodayValue, 'transactions');
            const revenueTodayTarget = getMetricTarget(revenueTodayValue, 'revenue');
            const pendingPaymentsTarget = getMetricTarget(pendingPaymentsValue, 'pending');
            const latestReceiptTarget = getMetricTarget(latestReceiptValue, 'receipt');

            let isRefreshing = false;

            const refreshStats = async () => {
                if (isRefreshing) {
                    return;
                }

                isRefreshing = true;

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

                    if (transactionsTodayTarget) {
                        transactionsTodayTarget.textContent = payload.transactionsToday ?? '0';
                    }
                    if (revenueTodayTarget) {
                        revenueTodayTarget.textContent = payload.revenueTodayFormatted ?? 'P 0.00';
                    }
                    if (pendingPaymentsTarget) {
                        pendingPaymentsTarget.textContent = payload.pendingPayments ?? '0';
                    }
                    if (latestReceiptTarget) {
                        latestReceiptTarget.textContent = payload.latestReceipt ?? 'None yet';
                    }

                    if (sellerDashboardLastSync) {
                        const now = new Date();
                        sellerDashboardLastSync.textContent = now.toLocaleTimeString('en-PH', {
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit',
                        });
                    }
                } catch (error) {
                    // Silent fail so dashboard remains usable even if refresh temporarily fails.
                } finally {
                    isRefreshing = false;
                }
            };

            const syncStatusAsync = async (form) => {
                if (!form || form.dataset.submitting === '1') {
                    return;
                }

                const select = form.querySelector('select[name="status"]');
                const submitButton = form.querySelector('button[type="submit"]');

                if (!select || !select.value) {
                    return;
                }

                const previousStatus = form.dataset.lastStatus || select.value;
                const nextStatus = select.value;

                form.dataset.submitting = '1';
                if (submitButton) {
                    submitButton.disabled = true;
                }

                applyStatusBadge(form, nextStatus);

                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')
                            ? document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            : document.querySelector('input[name="_token"]')?.value;

                        const sendUpdate = async (method) => fetch(form.action, {
                            method,
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': csrfToken || '',
                            },
                            body: JSON.stringify({ status: nextStatus }),
                        });

                        let response = await sendUpdate('PATCH');
                        if (!response.ok) {
                            response = await sendUpdate('POST');
                        }

                        const contentType = response.headers.get('content-type') || '';
                        const payload = contentType.includes('application/json')
                            ? await response.json()
                            : {};

                        if (!response.ok) {
                            throw new Error(payload.message || 'Status sync failed.');
                        }

                        const confirmedStatus = payload.status || nextStatus;

                        applyStatusBadge(form, confirmedStatus);
                        form.dataset.lastStatus = confirmedStatus;

                        await refreshStats();
                    } catch (error) {
                        select.value = previousStatus;
                        applyStatusBadge(form, previousStatus);
                        // fallback to regular submit for full page flow
                        form.submit();
                    } finally {
                        form.dataset.submitting = '0';
                        if (submitButton) {
                            submitButton.disabled = false;
                        }
                    }
            };

            statusForms.forEach((form) => {
                const select = form.querySelector('select[name="status"]');
                if (!select) {
                    return;
                }

                form.dataset.lastStatus = select.value;

                select.addEventListener('change', () => {
                    syncStatusAsync(form);
                });

                form.addEventListener('submit', (event) => {
                    event.preventDefault();
                    syncStatusAsync(form);
                });
            });

            refreshStats();

            setInterval(() => {
                if (document.visibilityState === 'visible') {
                    refreshStats();
                }
            }, 10000);

            document.addEventListener('visibilitychange', () => {
                if (document.visibilityState === 'visible') {
                    refreshStats();
                }
            });
        });
    </script>
</body>
</html>
