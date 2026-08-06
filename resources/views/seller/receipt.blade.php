<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $sale->id }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-primary/30 text-gray-800">
    @php
        $sellerActions = [
            ['label' => 'Open POS', 'url' => route('seller.pos'), 'active' => request()->routeIs('seller.pos')],
            ['label' => 'View Product Stock', 'url' => route('seller.stock'), 'active' => request()->routeIs('seller.stock')],
            ['label' => 'Print Receipt', 'url' => route('seller.receipt.latest'), 'active' => request()->routeIs('seller.receipt*')],
        ];
    @endphp

    <header class="border-b bg-primary">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900">Sales Receipt</h1>
                <p class="text-sm text-gray-700">Review details and print a clean customer copy.</p>
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

        <section class="mt-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow">
            <div class="bg-gradient-to-r from-secondary/40 via-white to-primary/50 px-6 py-5">
                <p class="inline-flex items-center rounded-full bg-white/90 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.12em] text-gray-600">Receipt Detail</p>
                <h2 class="mt-2 text-2xl font-black tracking-tight text-gray-900">Receipt #{{ $sale->id }}</h2>
                <p class="mt-1 text-sm text-gray-600">Date: {{ $sale->created_at->format('M d, Y h:i A') }} | Seller: {{ optional($sale->user)->name ?? 'N/A' }}</p>
            </div>

            <div class="p-6">
                <div class="rounded-xl border border-gray-100 p-4">
                    <p class="text-sm text-gray-600">Payment Method: <span class="font-semibold text-gray-900">{{ $sale->payment_method }}</span></p>
                </div>

                <div class="mt-4 overflow-x-auto rounded-xl border border-gray-100">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-primary/55 text-gray-900">
                            <tr>
                                <th class="px-4 py-3">Item</th>
                                <th class="px-4 py-3">Qty</th>
                                <th class="px-4 py-3">Unit Price</th>
                                <th class="px-4 py-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach($sale->items as $item)
                                <tr>
                                    <td class="px-4 py-3">{{ optional($item->product)->name ?? 'Unknown Product' }}</td>
                                    <td class="px-4 py-3">{{ $item->quantity }}</td>
                                    <td class="px-4 py-3">P {{ number_format($item->unit_price, 2) }}</td>
                                    <td class="px-4 py-3 text-right font-semibold text-gray-900">P {{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 text-right">
                    <p class="text-sm text-gray-600">Discount: P {{ number_format($sale->discount_applied, 2) }}</p>
                    <p class="text-2xl font-bold text-gray-900">Total: P {{ number_format($sale->total_amount, 2) }}</p>
                </div>

                <div class="mt-6 flex flex-wrap gap-2">
                    <button onclick="window.print()" class="rounded-lg bg-secondary px-4 py-2 text-sm font-semibold text-gray-900 hover:bg-secondary-strong">Print Receipt</button>
                    <a href="{{ route('seller.pos') }}" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-gray-900 hover:bg-primary-strong hover:text-white">Back to POS</a>
                    <a href="{{ route('seller.dashboard') }}" class="rounded-lg bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-900">Dashboard</a>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
