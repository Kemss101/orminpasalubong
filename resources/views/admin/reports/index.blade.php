<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Reports | Admin</title>
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
                <h1 class="text-2xl font-extrabold text-gray-900">Sales Reports</h1>
                <p class="text-sm text-gray-700">Track daily and monthly revenue performance.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">Dashboard</a>
                <a href="{{ route('home') }}" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">Home</a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8">
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
                <div class="flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <p class="inline-flex items-center rounded-full bg-white/90 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.12em] text-gray-600">Revenue Insights</p>
                        <h2 class="mt-2 text-2xl font-black tracking-tight text-gray-900">Sales Performance</h2>
                        <p class="mt-1 text-sm text-gray-600">Filter monthly data and monitor transaction trends.</p>
                    </div>
                    <form method="GET" action="{{ route('admin.reports.sales') }}" class="flex items-end gap-2">
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-600">Month</label>
                            <input type="month" name="month" value="{{ $month }}" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-primary-strong focus:outline-none">
                        </div>
                        <button type="submit" class="rounded-lg bg-secondary px-4 py-2 text-sm font-semibold text-gray-900 hover:bg-secondary-strong">Apply</button>
                    </form>
                </div>
            </div>

            <div class="p-5">
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                    <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm sm:col-span-2">
                        <p class="text-sm text-gray-500">Revenue ({{ $month }})</p>
                        <h3 class="mt-1 text-3xl font-bold text-emerald-700">P {{ number_format($totalRevenue, 2) }}</h3>
                    </div>
                    <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
                        <p class="text-sm text-gray-500">Transactions</p>
                        <h3 class="mt-1 text-3xl font-bold text-gray-900">{{ $totalTransactions }}</h3>
                    </div>
                    <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
                        <p class="text-sm text-gray-500">Pending Orders</p>
                        <h3 class="mt-1 text-3xl font-bold text-yellow-600">{{ $pendingOrders }}</h3>
                    </div>
                    <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
                        <p class="text-sm text-gray-500">Accepted / Declined</p>
                        <h3 class="mt-1 text-2xl font-bold text-gray-900">{{ $acceptedOrders }} / {{ $declinedOrders }}</h3>
                    </div>
                </div>

                <div class="mt-5 overflow-x-auto rounded-xl border border-gray-100">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-primary/55 text-gray-900">
                            <tr>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Transactions</th>
                                <th class="px-4 py-3">Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($dailySales as $day)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">{{ \Carbon\Carbon::parse($day->sale_date)->format('M d, Y') }}</td>
                                    <td class="px-4 py-3">{{ $day->transactions }}</td>
                                    <td class="px-4 py-3 font-semibold text-gray-900">P {{ number_format($day->revenue, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-gray-500">No sales found for this month.</td>
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
