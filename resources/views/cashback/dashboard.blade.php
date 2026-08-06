@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Cashback Dashboard</h1>

        <!-- Main Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg shadow p-6 border-l-4 border-blue-600">
                <p class="text-gray-600 text-sm font-semibold">Current Balance</p>
                <p class="text-3xl font-bold text-blue-600 mt-2">₱{{ number_format($stats['current_balance'], 2) }}</p>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg shadow p-6 border-l-4 border-green-600">
                <p class="text-gray-600 text-sm font-semibold">Total Earned</p>
                <p class="text-3xl font-bold text-green-600 mt-2">₱{{ number_format($stats['total_earned'], 2) }}</p>
            </div>

            <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg shadow p-6 border-l-4 border-orange-600">
                <p class="text-gray-600 text-sm font-semibold">Total Spent</p>
                <p class="text-3xl font-bold text-orange-600 mt-2">₱{{ number_format($stats['total_spent'], 2) }}</p>
            </div>
        </div>

        <!-- Information Card -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
            <p class="text-sm text-blue-800"><strong>💡 Cashback Rate:</strong> 0.05% on all purchases</p>
            <p class="text-xs text-blue-700 mt-1">Example: ₱1000 purchase = ₱0.50 cashback</p>
        </div>

        <!-- Redeem Section -->
        @if($stats['current_balance'] > 0)
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h2 class="text-xl font-bold mb-4">Redeem Cashback</h2>
                <div class="flex gap-4">
                    <input type="number" id="redeemAmount" placeholder="Amount (₱)" 
                        min="1" 
                        max="{{ $stats['current_balance'] }}"
                        step="0.01"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                    <button onclick="redeemCashback()" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition">
                        Redeem
                    </button>
                </div>
            </div>
        @endif

        <!-- Transaction History -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold">Transaction History</h2>
            </div>

            @if($transactions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b">
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Type</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Amount</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Balance</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Reason</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $txn)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm">
                                        @if($txn->type === 'earned')
                                            <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">+ Earned</span>
                                        @elseif($txn->type === 'spent')
                                            <span class="inline-block px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">- Spent</span>
                                        @else
                                            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">↻ Refunded</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold">
                                        <span class="@if($txn->type === 'earned') text-green-600 @elseif($txn->type === 'spent') text-red-600 @endif">
                                            ₱{{ number_format($txn->amount, 2) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-mono">₱{{ number_format($txn->new_balance, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $txn->reason }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $txn->created_at->format('M d, Y h:i A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-6 border-t border-gray-200">
                    {{ $transactions->links() }}
                </div>
            @else
                <div class="p-6 text-center text-gray-600">
                    <p>No cashback transactions yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function redeemCashback() {
    const amount = parseFloat(document.getElementById('redeemAmount').value);
    
    if (!amount || amount <= 0) {
        alert('Please enter a valid amount');
        return;
    }
    
    fetch('{{ route("cashback.redeem") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
        },
        body: JSON.stringify({ amount: amount })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Cashback redeemed successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => alert('Error: ' + error.message));
}
</script>
@endsection
