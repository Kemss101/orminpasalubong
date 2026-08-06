<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SellerController extends Controller
{
    public function dashboard(): View
    {
        $recentCustomerOrders = Order::query()
            ->with(['user', 'items.product', 'gcashTransaction', 'deliveryTracking'])
            ->latest()
            ->limit(15)
            ->get();

        $dashboardMetrics = $this->getDashboardMetrics($recentCustomerOrders);

        return view('dashboards.seller', array_merge(
            $dashboardMetrics,
            compact(
            'recentCustomerOrders'
            )
        ));
    }

    public function dashboardStats(): JsonResponse
    {
        $recentCustomerOrders = Order::query()
            ->latest()
            ->limit(15)
            ->get();

        return response()->json($this->getDashboardMetrics($recentCustomerOrders));
    }

    public function pos(): View
    {
        $products = Product::query()
            ->with('category')
            ->orderBy('name')
            ->get();

        return view('seller.pos', compact('products'));
    }

    public function updateOrderStatus(Request $request, Order $order): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:Pending,Accepted,Packed,Shipped,Out for Delivery,Delivered,Cancelled'],
        ]);

        $deliveryStatus = match ($validated['status']) {
            'Pending' => 'Pending',
            'Accepted' => 'Pending',
            'Packed' => 'Pending',
            'Shipped' => 'Shipped',
            'Out for Delivery' => 'Out for Delivery',
            'Delivered' => 'Delivered',
            'Cancelled' => 'Cancelled',
            default => $order->delivery_status ?? 'Pending',
        };

        $order->update([
            'status' => $validated['status'],
            'delivery_status' => $deliveryStatus,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Order status updated.',
                'order_id' => $order->id,
                'status' => $order->status,
            ]);
        }

        return redirect()
            ->route('seller.dashboard')
            ->with('success', 'Order status updated to '.$validated['status'].'.');
    }

    public function checkout(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'in:Cash,Pending'],
        ]);

        $product = Product::query()->findOrFail($validated['product_id']);

        if ($product->stock_quantity < $validated['quantity']) {
            return back()->withErrors([
                'quantity' => 'Insufficient stock for '.$product->name.'. Available: '.$product->stock_quantity,
            ]);
        }

        $sale = DB::transaction(function () use ($product, $validated) {
            $quantity = (int) $validated['quantity'];
            $lineTotal = (float) $product->price * $quantity;

            $sale = Sale::query()->create([
                'user_id' => auth()->id(),
                'customer_id' => null,
                'total_amount' => $lineTotal,
                'discount_applied' => 0,
                'payment_method' => $validated['payment_method'],
            ]);

            SaleItem::query()->create([
                'sale_id' => $sale->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => (float) $product->price,
                'subtotal' => $lineTotal,
            ]);

            $product->decrement('stock_quantity', $quantity);

            return $sale;
        });

        return redirect()
            ->route('seller.receipt', $sale)
            ->with('success', 'Sale recorded successfully.');
    }

    public function stock(): View
    {
        $products = Product::query()
            ->with('category')
            ->orderBy('stock_quantity')
            ->orderBy('name')
            ->get();

        return view('seller.stock', compact('products'));
    }

    public function updateStock(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'stock_quantity' => ['required', 'integer', 'min:0'],
        ]);

        $product->update([
            'stock_quantity' => (int) $validated['stock_quantity'],
        ]);

        return redirect()
            ->route('seller.stock')
            ->with('success', 'Stock updated for '.$product->name.'.');
    }

    public function latestReceipt(): RedirectResponse
    {
        $latestSale = Sale::query()
            ->where('user_id', auth()->id())
            ->latest()
            ->first();

        if (! $latestSale) {
            return redirect()
                ->route('seller.dashboard')
                ->with('error', 'No recent receipt available yet. Create a sale first.');
        }

        return redirect()->route('seller.receipt', $latestSale);
    }

    public function receipt(Sale $sale): View
    {
        if ((int) $sale->user_id !== (int) auth()->id()) {
            abort(403, 'Unauthorized receipt access.');
        }

        $sale->load('items.product', 'user');

        return view('seller.receipt', compact('sale'));
    }

    private function getDashboardMetrics(?Collection $orders = null): array
    {
        $orders = $orders ?? Order::query()
            ->latest()
            ->limit(15)
            ->get();

        $transactionsToday = $orders->count();
        $revenueToday = (float) $orders->sum(fn (Order $order) => $order->getGrandTotal());
        $pendingPayments = $orders
            ->filter(fn (Order $order) => $order->payment_status === 'pending' || $order->status === 'Pending')
            ->count();
        $codOrders = $orders->where('payment_method', 'cash')->count();
        $gcashOrders = $orders->where('payment_method', 'gcash')->count();
        $readyForFulfillment = $orders
            ->filter(function (Order $order) {
                $paymentReady = $order->payment_method === 'cash' || $order->payment_status === 'completed';
                $statusReady = in_array($order->status, ['Accepted', 'Packed', 'Shipped', 'Out for Delivery'], true);

                return $paymentReady && $statusReady;
            })
            ->count();

        $latestOrder = $orders->first();

        return [
            'transactionsToday' => $transactionsToday,
            'revenueToday' => $revenueToday,
            'revenueTodayFormatted' => 'P '.number_format($revenueToday, 2),
            'pendingPayments' => $pendingPayments,
            'codOrders' => $codOrders,
            'gcashOrders' => $gcashOrders,
            'readyForFulfillment' => $readyForFulfillment,
            'latestReceipt' => $latestOrder ? '#'.$latestOrder->id : 'None yet',
        ];
    }
}
