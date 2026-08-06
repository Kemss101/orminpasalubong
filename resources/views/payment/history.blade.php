@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Payment History</h1>

        @if($transactions->count() > 0)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Reference</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Order ID</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Amount</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-mono text-gray-800">{{ $transaction->reference_number }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    @if($transaction->order)
                                        <a href="{{ route('payment.show', $transaction->order) }}" class="text-blue-600 hover:underline">#{{ $transaction->order->id }}</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-800">₱{{ number_format($transaction->amount, 2) }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if($transaction->status === 'completed')
                                        <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">✓ Completed</span>
                                    @elseif($transaction->status === 'pending')
                                        <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">⏳ Pending</span>
                                    @else
                                        <span class="inline-block px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">✗ {{ ucfirst($transaction->status) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $transaction->created_at->format('M d, Y h:i A') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $transactions->links() }}
            </div>
        @else
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
                <p class="text-gray-600">No payment transactions found.</p>
                <a href="{{ route('customer.dashboard') }}" class="mt-4 inline-block text-blue-600 hover:underline">Back to Dashboard</a>
            </div>
        @endif
    </div>
</div>
@endsection
