<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CustomerDashboardController extends Controller
{
    public function account(): View
    {
        return view('dashboards.customer-account');
    }

    public function index(): View
    {
        $ordersQuery = Order::query()
            ->where('user_id', auth()->id())
            ->latest();

        $cart = session('cart', []);
        $cartItemCount = collect($cart)->sum('quantity');
        $cartSubtotal = collect($cart)->sum(function (array $item): float {
            return ((float) $item['price']) * ((int) $item['quantity']);
        });

        $totalOrders = (clone $ordersQuery)->count();
        $pendingOrders = (clone $ordersQuery)->where('status', 'Pending')->count();
        $acceptedOrders = (clone $ordersQuery)->where('status', 'Accepted')->count();

        $recentOrders = (clone $ordersQuery)
            ->withCount('items')
            ->limit(10)
            ->get();

        return view('dashboards.customer', compact(
            'totalOrders',
            'pendingOrders',
            'acceptedOrders',
            'recentOrders',
            'cart',
            'cartItemCount',
            'cartSubtotal'
        ));
    }

    public function orderStatus(): JsonResponse
    {
        $orders = Order::query()
            ->where('user_id', auth()->id())
            ->latest()
            ->limit(10)
            ->get(['id', 'status', 'delivery_status']);

        return response()->json([
            'orders' => $orders->map(function (Order $order) {
                return [
                    'id' => $order->id,
                    'status' => $order->status ?: 'Pending',
                    'delivery_status' => $order->delivery_status ?: 'Pending',
                ];
            }),
        ]);
    }

    public function updateAccount(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'address' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->address = trim((string) $validated['address']);

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('customer.account')
            ->with('success', 'Account details updated successfully.');
    }
}
