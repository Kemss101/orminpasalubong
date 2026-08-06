<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $products = Product::query()
            ->when($search, function ($query, $searchTerm) {
                $query->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('sku_code', 'like', "%{$searchTerm}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.inventory.index', compact('products', 'search'));
    }

    public function create(): View
    {
        return view('admin.inventory.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sku_code' => ['required', 'string', 'max:100', 'unique:products,sku_code'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'low_stock_threshold' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $imageFile = $request->file('image');
        unset($validated['image']);

        if ($imageFile) {
            $validated['image_path'] = $this->storeProductImage($imageFile);
        }

        Product::query()->create($validated);

        return redirect()->route('admin.inventory.index')->with('success', 'Product added successfully.');
    }

    public function edit(Product $product): View
    {
        return view('admin.inventory.edit', compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'sku_code' => ['required', 'string', 'max:100', 'unique:products,sku_code,'.$product->id],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'low_stock_threshold' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $imageFile = $request->file('image');
        unset($validated['image']);

        if ($imageFile) {
            $newImagePath = $this->storeProductImage($imageFile);

            if ($product->image_path) {
                $this->deleteProductImage($product->image_path);
            }

            $validated['image_path'] = $newImagePath;
        }

        $product->update($validated);

        return redirect()->route('admin.inventory.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image_path) {
            $this->deleteProductImage($product->image_path);
        }

        $product->delete();

        return redirect()->route('admin.inventory.index')->with('success', 'Product deleted successfully.');
    }

    private function storeProductImage(UploadedFile $file): string
    {
        $directory = public_path('images/products');

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $fileName = now()->format('YmdHis').'-'.Str::uuid().'.'.$file->getClientOriginalExtension();
        $file->move($directory, $fileName);

        return 'images/products/'.$fileName;
    }

    private function deleteProductImage(string $imagePath): void
    {
        $absolutePath = public_path($imagePath);

        if (is_file($absolutePath)) {
            unlink($absolutePath);
        }
    }
}
