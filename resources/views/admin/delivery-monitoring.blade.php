@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Delivery Monitoring</h1>

        <!-- Filter & Search -->
        <form method="GET" class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text" name="search" placeholder="Search by customer name, email, or order ID..." 
                    value="{{ request('search') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="">All Status</option>
                    <option value="Pending" @if(request('status') === 'Pending') selected @endif>Pending</option>
                    <option value="Shipped" @if(request('status') === 'Shipped') selected @endif>Shipped</option>
                    <option value="Out for Delivery" @if(request('status') === 'Out for Delivery') selected @endif>Out for Delivery</option>
                    <option value="Delivered" @if(request('status') === 'Delivered') selected @endif>Delivered</option>
                    <option value="Cancelled" @if(request('status') === 'Cancelled') selected @endif>Cancelled</option>
                </select>

                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition">
                    Filter
                </button>
            </div>
        </form>

        <!-- Deliveries Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if($deliveries->count() > 0)
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Order ID</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Customer</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tracking #</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Created</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Delivered</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deliveries as $delivery)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-semibold text-gray-800">#{{ $delivery->order->id }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <div>{{ $delivery->order->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $delivery->order->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm font-mono text-gray-600">{{ $delivery->tracking_number ?? 'Not assigned' }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if($delivery->status === 'Delivered')
                                        <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">✓ Delivered</span>
                                    @elseif($delivery->status === 'Out for Delivery')
                                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">🚚 Out for Delivery</span>
                                    @elseif($delivery->status === 'Shipped')
                                        <span class="inline-block px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">📦 Shipped</span>
                                    @elseif($delivery->status === 'Pending')
                                        <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">⏳ Pending</span>
                                    @else
                                        <span class="inline-block px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">✗ Cancelled</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $delivery->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    @if($delivery->delivered_at)
                                        {{ $delivery->delivered_at->format('M d, Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <button onclick="editDelivery({{ $delivery->id }})" class="text-blue-600 hover:underline">Edit</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="p-6 border-t border-gray-200">
                    {{ $deliveries->links() }}
                </div>
            @else
                <div class="p-6 text-center text-gray-600">
                    <p>No deliveries found.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Edit Delivery Modal -->
<div id="deliveryModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4" onclick="event.stopPropagation()">
        <h2 class="text-xl font-bold mb-4">Update Delivery</h2>
        <input type="hidden" id="orderId">
        <div class="space-y-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="statusSelect" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="Pending">Pending</option>
                    <option value="Shipped">Shipped</option>
                    <option value="Out for Delivery">Out for Delivery</option>
                    <option value="Delivered">Delivered</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tracking Number</label>
                <input type="text" id="trackingInput" placeholder="Enter tracking number" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea id="notesInput" placeholder="Add notes..." class="w-full px-4 py-2 border border-gray-300 rounded-lg h-20 resize-none"></textarea>
            </div>

            <div class="flex gap-2">
                <button onclick="updateDelivery()" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg">
                    Update
                </button>
                <button onclick="closeDeliveryModal()" class="flex-1 px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white font-bold rounded-lg">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentDeliveryId = null;

function editDelivery(id) {
    currentDeliveryId = id;
    // In a real application, you would fetch the delivery details
    document.getElementById('deliveryModal').classList.remove('hidden');
}

function closeDeliveryModal() {
    document.getElementById('deliveryModal').classList.add('hidden');
    currentDeliveryId = null;
}

async function updateDelivery() {
    const status = document.getElementById('statusSelect').value;
    const tracking = document.getElementById('trackingInput').value;
    const notes = document.getElementById('notesInput').value;
    
    try {
        const response = await fetch(`/admin/delivery/${document.getElementById('orderId').value}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
            },
            body: JSON.stringify({ 
                status, 
                tracking_number: tracking,
                notes 
            })
        });
        
        const data = await response.json();
        alert(data.message);
        location.reload();
    } catch (error) {
        alert('Error updating delivery: ' + error.message);
    }
}

document.getElementById('deliveryModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeliveryModal();
    }
});
</script>
@endsection
