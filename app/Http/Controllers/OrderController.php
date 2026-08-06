<?php

namespace App\Http\Controllers;

use App\Models\GcashTransaction;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function showCheckout(Request $request): View|RedirectResponse
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        return view('checkout.index', $this->buildCheckoutSummary($request, $cart));
    }

    public function checkout(Request $request): RedirectResponse
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $validated = $request->validate([
            'delivery_address' => ['required', 'string', 'max:500'],
            'delivery_method' => ['required', 'in:standard,express'],
            'payment_method' => ['required', 'in:gcash,cod'],
            'contact_number' => ['required', 'string', 'max:20'],
            'gcash_number' => ['nullable', 'string', 'regex:/^[0-9]{11}$/'],
            'order_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $productIds = collect($cart)
            ->pluck('product_id')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $products = Product::query()
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        if ($products->count() !== $productIds->count()) {
            return redirect()->route('cart.index')->with('error', 'Some products are unavailable. Please refresh your cart.');
        }

        foreach ($cart as $item) {
            $product = $products->get((int) $item['product_id']);
            $quantity = max(1, (int) $item['quantity']);

            if ($product->stock_quantity < $quantity) {
                return redirect()->route('cart.index')->with('error', 'Insufficient stock for '.$product->name.'.');
            }
        }

        $shippingFee = $validated['delivery_method'] === 'express' ? 75.00 : 35.00;
        $paymentMethod = $validated['payment_method'];

        $order = DB::transaction(function () use ($cart, $products, $request, $validated, $shippingFee, $paymentMethod) {
            $subtotal = 0;

            foreach ($cart as $item) {
                $product = $products->get((int) $item['product_id']);
                $quantity = max(1, (int) $item['quantity']);
                $subtotal += ((float) $product->price * $quantity);
            }

            $order = Order::query()->create([
                'user_id' => $request->user()->id,
                'total_amount' => $subtotal,
                'status' => 'Pending',
                'delivery_address' => $validated['delivery_address'],
                'delivery_method' => $validated['delivery_method'],
                'shipping_fee' => $shippingFee,
                'contact_number' => $validated['contact_number'],
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentMethod === 'gcash' ? 'pending' : 'unpaid',
                'delivery_status' => 'Pending',
            ]);

            foreach ($cart as $item) {
                $product = $products->get((int) $item['product_id']);
                $quantity = max(1, (int) $item['quantity']);

                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => (float) $product->price,
                ]);

                $product->decrement('stock_quantity', $quantity);
            }

            $order->deliveryTracking()->create([
                'status' => 'Pending',
                'notes' => $validated['order_notes'] ?? null,
            ]);

            if ($paymentMethod === 'gcash') {
                $referenceNumber = 'GCH-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6));

                $transaction = GcashTransaction::create([
                    'user_id' => $request->user()->id,
                    'order_id' => $order->id,
                    'reference_number' => $referenceNumber,
                    'amount' => $subtotal + $shippingFee,
                    'status' => 'pending',
                    'type' => 'payment',
                    'notes' => 'Created from checkout',
                ]);

                $order->update(['gcash_transaction_id' => $transaction->id]);
            }

            return $order;
        });

        session()->forget('cart');

        if ($paymentMethod === 'gcash') {
            return redirect()->route('payment.show', $order)->with('success', 'Order placed. Please complete your GCash payment.');
        }

        return redirect()->route('customer.dashboard')->with('success', 'Order placed successfully. Your delivery is now being prepared.');
    }

    private function buildCheckoutSummary(Request $request, array $cart): array
    {
        $subtotal = collect($cart)->sum(function (array $item): float {
            return ((float) $item['price']) * ((int) $item['quantity']);
        });

        $shippingMethod = $request->old('delivery_method', 'standard');
        $shippingFee = $shippingMethod === 'express' ? 75.00 : 35.00;

        return [
            'cart' => $cart,
            'subtotal' => $subtotal,
            'shippingFee' => $shippingFee,
            'grandTotal' => $subtotal + $shippingFee,
            'defaultAddress' => old('delivery_address', optional($request->user())->address),
            'defaultName' => optional($request->user())->name,
            'defaultEmail' => optional($request->user())->email,
            'defaultPaymentMethod' => $request->old('payment_method', 'gcash'),
            'defaultDeliveryMethod' => $shippingMethod,
            'cartItemCount' => collect($cart)->sum('quantity'),
        ];
    }
}
