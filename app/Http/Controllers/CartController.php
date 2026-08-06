<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = session('cart', []);

        $subtotal = collect($cart)->sum(function (array $item): float {
            return $item['price'] * $item['quantity'];
        });

        return view('cart.index', [
            'cart' => $cart,
            'subtotal' => $subtotal,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'category' => ['nullable', 'string', 'max:100'],
        ]);

        $product = Product::query()->findOrFail($validated['product_id']);

        if ($product->stock_quantity <= 0) {
            return redirect()->route('home')->with('error', $product->name.' is currently out of stock.');
        }

        $cart = session('cart', []);
        $productKey = (string) $product->id;

        if (isset($cart[$productKey])) {
            $cart[$productKey]['quantity']++;
        } else {
            $cart[$productKey] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'category' => $validated['category'] ?? 'General',
                'price' => (float) $product->price,
                'image' => $product->image_path ?: 'images/products/default-product.jpg',
                'quantity' => 1,
            ];
        }

        session(['cart' => $cart]);

        return redirect()->back()->with('success', $product->name.' added to your cart.');
    }

    public function update(Request $request, string $productId): RedirectResponse
    {
        $validated = $request->validate([
            'action' => ['nullable', 'in:increase,decrease'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $cart = session('cart', []);

        if (!isset($cart[$productId])) {
            return redirect()->back()->with('error', 'Item not found in cart.');
        }

        $currentQuantity = (int) $cart[$productId]['quantity'];
        $newQuantity = $currentQuantity;

        if (isset($validated['quantity'])) {
            $newQuantity = (int) $validated['quantity'];
        } elseif (($validated['action'] ?? '') === 'increase') {
            $newQuantity = $currentQuantity + 1;
        } elseif (($validated['action'] ?? '') === 'decrease') {
            $newQuantity = max(1, $currentQuantity - 1);
        }

        $cart[$productId]['quantity'] = $newQuantity;
        session(['cart' => $cart]);

        return redirect()->back()->with('success', 'Cart quantity updated.');
    }

    public function remove(string $productId): RedirectResponse
    {
        $cart = session('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session(['cart' => $cart]);
        }

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }
}
