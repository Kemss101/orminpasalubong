<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve Orders | Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-primary/30 text-gray-800">
    @php
        $actionLinks = [
            ['label' => 'Approve Orders', 'route' => 'admin.orders.index'],
            ['label' => 'Sales Reports', 'route' => 'admin.reports.sales'],
            ['label' => 'Manage Users', 'route' => 'admin.users.index'],
            ['label' => 'Management History', 'url' => route('admin.dashboard').'#management-history'],
        ];
    @endphp

    <header class="border-b bg-primary">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900">Approve Orders</h1>
                <p class="text-sm text-gray-700">Accept or decline customer orders.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">Dashboard</a>
                <a href="{{ route('home') }}" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">Home</a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8">
        @if(session('success'))
            <div class="mb-4 rounded-lg border border-primary-strong bg-primary px-4 py-3 text-sm font-medium text-gray-800">
                {{ session('success') }}
            </div>
        @endif

        <section class="rounded-xl border border-gray-200 bg-white p-6 shadow">
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($actionLinks as $action)
                    @php
                        $isActive = isset($action['route']) && request()->routeIs($action['route']);
                    @endphp
                    <a
                        href="{{ $action['url'] ?? route($action['route']) }}"
                        class="rounded-lg px-4 py-3 text-center font-semibold transition {{ $isActive ? 'bg-primary text-gray-900 ring-1 ring-primary-strong/60 shadow-sm' : 'bg-secondary text-gray-900 hover:bg-secondary-strong' }}"
                    >
                        {{ $action['label'] }}
                    </a>
                @endforeach
            </div>
        </section>

        <section class="mt-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow">
            <div class="bg-gradient-to-r from-secondary/40 via-white to-primary/50 px-6 py-5">
                <p class="inline-flex items-center rounded-full bg-white/90 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.12em] text-gray-600">Order Operations</p>
                <h2 class="mt-2 text-2xl font-black tracking-tight text-gray-900">Order Approval Queue</h2>
                <p class="mt-1 text-sm text-gray-600">Review pending orders and update customer request status.</p>
            </div>

            <div class="p-5">
                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-primary/55 text-gray-900">
                            <tr>
                                <th class="px-4 py-3">Order #</th>
                                <th class="px-4 py-3">Customer</th>
                                <th class="px-4 py-3">Items</th>
                                <th class="px-4 py-3">Total</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($orders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-semibold text-gray-900">#{{ $order->id }}</td>
                                    <td class="px-4 py-3">{{ optional($order->user)->name ?? 'Unknown' }}</td>
                                    <td class="px-4 py-3">{{ $order->items->sum('quantity') }}</td>
                                    <td class="px-4 py-3">P {{ number_format($order->total_amount, 2) }}</td>
                                    <td class="px-4 py-3">
                                        @if($order->status === 'Pending')
                                            <span class="rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-700">Pending</span>
                                        @elseif($order->status === 'Accepted')
                                            <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700">Accepted</span>
                                        @else
                                            <span class="rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-700">Declined</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($order->status === 'Pending')
                                            <div class="flex flex-wrap gap-2">
                                                <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="Accepted">
                                                    <button type="submit" class="rounded-lg bg-emerald-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-600">Accept</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="Declined">
                                                    <button type="submit" class="rounded-lg bg-red-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-red-600">Decline</button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-500">No actions</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">No orders found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            </div>
        </section>
    </main>
</body>
</html>
