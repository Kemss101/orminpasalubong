<?php

namespace App\Http\Controllers;

use App\Models\DeliveryTracking;
use App\Models\Order;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    /**
     * Get delivery tracking for order
     */
    public function show(Order $order)
    {
        $authUser = auth()->user();

        if (!$authUser || (!$authUser->isAdmin() && (int) $order->user_id !== (int) $authUser->id)) {
            abort(403);
        }

        $delivery = $order->deliveryTracking;

        return response()->json($delivery ?? ['error' => 'No delivery tracking found'], $delivery ? 200 : 404);
    }

    /**
     * Update delivery status (admin only)
     */
    public function update(Request $request, Order $order)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:Pending,Shipped,Out for Delivery,Delivered,Cancelled',
            'tracking_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $delivery = $order->deliveryTracking ?? $order->deliveryTracking()->create([
            'status' => 'Pending',
        ]);

        $delivery->updateStatus($validated['status']);

        if ($validated['tracking_number']) {
            $delivery->update(['tracking_number' => $validated['tracking_number']]);
        }

        if ($validated['notes']) {
            $delivery->update(['notes' => $validated['notes']]);
        }

        // Update order delivery status
        $order->update(['delivery_status' => $validated['status']]);

        return response()->json([
            'status' => 'success',
            'message' => 'Delivery status updated successfully.',
            'delivery' => $delivery,
        ]);
    }

    /**
     * Get all deliveries (admin dashboard)
     */
    public function allDeliveries(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $query = DeliveryTracking::with('order.user')
            ->orderBy('created_at', 'desc');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->whereHas('order', function ($q) use ($request) {
                $q->whereHas('user', function ($q2) use ($request) {
                    $q2->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%');
                })->orWhere('id', $request->search);
            });
        }

        $deliveries = $query->paginate(20);

        return view('admin.delivery-monitoring', compact('deliveries'));
    }

    /**
     * Get delivery history for customer
     */
    public function customerHistory()
    {
        $deliveries = auth()->user()->deliveryTrackings()
            ->with('order')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('delivery.history', compact('deliveries'));
    }

    /**
     * Bulk update delivery statuses
     */
    public function bulkUpdate(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'delivery_ids' => 'required|array',
            'delivery_ids.*' => 'exists:delivery_tracking,id',
            'status' => 'required|in:Pending,Shipped,Out for Delivery,Delivered,Cancelled',
        ]);

        DeliveryTracking::whereIn('id', $validated['delivery_ids'])
            ->each(function ($delivery) use ($validated) {
                $delivery->updateStatus($validated['status']);
                $delivery->order->update(['delivery_status' => $validated['status']]);
            });

        return response()->json([
            'status' => 'success',
            'message' => count($validated['delivery_ids']) . ' deliveries updated successfully.',
        ]);
    }
}
