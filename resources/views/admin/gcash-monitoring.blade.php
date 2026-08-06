@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">GCash Transaction Monitoring</h1>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
            <div class="bg-blue-50 rounded-lg shadow p-4 border-l-4 border-blue-600">
                <p class="text-gray-600 text-xs font-semibold">Total Transactions</p>
                <p class="text-2xl font-bold text-blue-600">{{ $stats['total_transactions'] }}</p>
            </div>

            <div class="bg-green-50 rounded-lg shadow p-4 border-l-4 border-green-600">
                <p class="text-gray-600 text-xs font-semibold">Completed</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['completed_count'] }}</p>
                <p class="text-xs text-green-600">₱{{ number_format($stats['total_amount_completed'], 2) }}</p>
            </div>

            <div class="bg-yellow-50 rounded-lg shadow p-4 border-l-4 border-yellow-600">
                <p class="text-gray-600 text-xs font-semibold">Pending</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending_count'] }}</p>
                <p class="text-xs text-yellow-600">₱{{ number_format($stats['pending_amount'], 2) }}</p>
            </div>

            <div class="bg-red-50 rounded-lg shadow p-4 border-l-4 border-red-600">
                <p class="text-gray-600 text-xs font-semibold">Failed</p>
                <p class="text-2xl font-bold text-red-600">{{ $stats['failed_count'] }}</p>
            </div>

            <div class="bg-purple-50 rounded-lg shadow p-4 border-l-4 border-purple-600">
                <p class="text-gray-600 text-xs font-semibold">Success Rate</p>
                <p class="text-2xl font-bold text-purple-600">
                    @if($stats['total_transactions'] > 0)
                        {{ round(($stats['completed_count'] / $stats['total_transactions']) * 100, 1) }}%
                    @else
                        0%
                    @endif
                </p>
            </div>
        </div>

        <!-- Filter & Search -->
        <form method="GET" class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" name="search" placeholder="Search by ref, user, email..." 
                    value="{{ request('search') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="">All Status</option>
                    <option value="pending" @if(request('status') === 'pending') selected @endif>Pending</option>
                    <option value="completed" @if(request('status') === 'completed') selected @endif>Completed</option>
                    <option value="failed" @if(request('status') === 'failed') selected @endif>Failed</option>
                    <option value="refunded" @if(request('status') === 'refunded') selected @endif>Refunded</option>
                </select>

                <input type="date" name="from_date" placeholder="From Date"
                    value="{{ request('from_date') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">

                <input type="date" name="to_date" placeholder="To Date"
                    value="{{ request('to_date') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>

            <div class="mt-4 flex gap-2">
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition">
                    Filter
                </button>
                <a href="{{ route('admin.gcash.transactions') }}" class="px-6 py-2 bg-gray-400 hover:bg-gray-500 text-white font-bold rounded-lg transition">
                    Clear
                </a>
                <a href="{{ route('admin.gcash.export') }}" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition">
                    Export CSV
                </a>
            </div>
        </form>

        <!-- Transactions Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if($transactions->count() > 0)
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Reference</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">User</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Order ID</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Amount</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Created</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                            <tr class="border-b hover:bg-gray-50 cursor-pointer" onclick="viewTransaction({{ $transaction->id }})">
                                <td class="px-6 py-4 text-sm font-mono text-gray-800">{{ $transaction->reference_number }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <div>{{ $transaction->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $transaction->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    @if($transaction->order)
                                        #{{ $transaction->order->id }}
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
                                    @elseif($transaction->status === 'failed')
                                        <span class="inline-block px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">✗ Failed</span>
                                    @else
                                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">↻ Refunded</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $transaction->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <button onclick="event.stopPropagation(); editTransaction({{ $transaction->id }})" 
                                        class="text-blue-600 hover:underline">Edit</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="p-6 border-t border-gray-200">
                    {{ $transactions->links() }}
                </div>
            @else
                <div class="p-6 text-center text-gray-600">
                    <p>No transactions found.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Transaction Detail Modal -->
<div id="transactionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4" onclick="event.stopPropagation()">
        <h2 class="text-xl font-bold mb-4">Transaction Details</h2>
        <div id="transactionDetails" class="space-y-3 mb-6"></div>
        <div class="space-y-3">
            <select id="statusSelect" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
                <option value="failed">Failed</option>
                <option value="refunded">Refunded</option>
            </select>
            <textarea id="notesInput" placeholder="Add notes..." class="w-full px-4 py-2 border border-gray-300 rounded-lg h-20 resize-none"></textarea>
            <button onclick="updateTransaction()" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg">
                Update
            </button>
            <button onclick="closeModal()" class="w-full px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white font-bold rounded-lg">
                Close
            </button>
        </div>
    </div>
</div>

<script>
let currentTransactionId = null;

async function viewTransaction(id) {
    currentTransactionId = id;
    try {
        const response = await fetch(`/admin/gcash/transactions/${id}`);
        const data = await response.json();
        
        const details = `
            <p><strong>Reference:</strong> ${data.reference_number}</p>
            <p><strong>User:</strong> ${data.user.name} (${data.user.email})</p>
            <p><strong>Amount:</strong> ₱${data.amount.toFixed(2)}</p>
            <p><strong>Status:</strong> ${data.status}</p>
            <p><strong>Type:</strong> ${data.type}</p>
            <p><strong>Receipt:</strong> ${data.gcash_receipt_number || 'N/A'}</p>
            <p><strong>Created:</strong> ${data.created_at}</p>
        `;
        
        document.getElementById('transactionDetails').innerHTML = details;
        document.getElementById('statusSelect').value = data.status;
        document.getElementById('notesInput').value = data.notes || '';
        document.getElementById('transactionModal').classList.remove('hidden');
    } catch (error) {
        alert('Error loading transaction: ' + error.message);
    }
}

function closeModal() {
    document.getElementById('transactionModal').classList.add('hidden');
    currentTransactionId = null;
}

async function updateTransaction() {
    const status = document.getElementById('statusSelect').value;
    const notes = document.getElementById('notesInput').value;
    
    try {
        const response = await fetch(`/admin/gcash/transactions/${currentTransactionId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
            },
            body: JSON.stringify({ status, notes })
        });
        
        const data = await response.json();
        alert(data.message);
        location.reload();
    } catch (error) {
        alert('Error updating transaction: ' + error.message);
    }
}

function editTransaction(id) {
    viewTransaction(id);
}

document.getElementById('transactionModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection
