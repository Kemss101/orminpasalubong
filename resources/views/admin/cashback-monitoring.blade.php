@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Cashback Management</h1>

        <!-- Search -->
        <form method="GET" class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="flex gap-4">
                <input type="text" name="search" placeholder="Search by user name or email..." 
                    value="{{ request('search') }}"
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition">
                    Search
                </button>
            </div>
        </form>

        <!-- Cashback Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if($cashbacks->count() > 0)
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">User</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Current Balance</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cashbacks as $cashback)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ $cashback->user->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $cashback->user->email }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-blue-600">₱{{ number_format($cashback->balance, 2) }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <button onclick="openAdjustModal({{ $cashback->id }})" class="text-blue-600 hover:underline">Adjust</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="p-6 border-t border-gray-200">
                    {{ $cashbacks->links() }}
                </div>
            @else
                <div class="p-6 text-center text-gray-600">
                    <p>No users found.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Adjust Cashback Modal -->
<div id="adjustModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4" onclick="event.stopPropagation()">
        <h2 class="text-xl font-bold mb-4">Adjust Cashback Balance</h2>
        <input type="hidden" id="cashbackId">
        <div class="space-y-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Action</label>
                <select id="actionSelect" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="add">Add Cashback</option>
                    <option value="deduct">Deduct Cashback</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Amount (₱)</label>
                <input type="number" id="amountInput" placeholder="Enter amount" 
                    min="0.01" 
                    step="0.01"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                <input type="text" id="reasonInput" placeholder="Enter reason for adjustment" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>

            <div class="flex gap-2">
                <button onclick="adjustCashback()" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg">
                    Apply
                </button>
                <button onclick="closeAdjustModal()" class="flex-1 px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white font-bold rounded-lg">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openAdjustModal(cashbackId) {
    document.getElementById('cashbackId').value = cashbackId;
    document.getElementById('adjustModal').classList.remove('hidden');
}

function closeAdjustModal() {
    document.getElementById('adjustModal').classList.add('hidden');
    document.getElementById('amountInput').value = '';
    document.getElementById('reasonInput').value = '';
}

async function adjustCashback() {
    const cashbackId = document.getElementById('cashbackId').value;
    const action = document.getElementById('actionSelect').value;
    const amount = parseFloat(document.getElementById('amountInput').value);
    const reason = document.getElementById('reasonInput').value;

    if (!amount || amount <= 0) {
        alert('Please enter a valid amount');
        return;
    }

    if (!reason) {
        alert('Please enter a reason');
        return;
    }

    try {
        const response = await fetch(`/admin/cashback/${cashbackId}/adjust`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
            },
            body: JSON.stringify({ 
                action, 
                amount,
                reason
            })
        });
        
        const data = await response.json();
        if (response.ok) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.error);
        }
    } catch (error) {
        alert('Error adjusting cashback: ' + error.message);
    }
}

document.getElementById('adjustModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAdjustModal();
    }
});
</script>
@endsection
