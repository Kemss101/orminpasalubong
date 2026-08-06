<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-primary/20 text-gray-800">
    <main class="mx-auto max-w-3xl px-4 py-8">
        <div class="rounded-xl bg-white p-6 shadow">
            <h1 class="text-2xl font-extrabold">Edit Product</h1>
            <p class="text-sm text-gray-600">Update inventory item details.</p>

            @if(isset($errors) && $errors->any())
                <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.inventory.update', $product) }}" enctype="multipart/form-data" class="mt-6 grid gap-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="sku_code" class="mb-1 block text-sm font-semibold text-gray-700">SKU Code</label>
                    <input id="sku_code" type="text" name="sku_code" value="{{ old('sku_code', $product->sku_code) }}" placeholder="SKU Code" class="w-full rounded-lg border border-gray-300 px-4 py-2" required>
                </div>

                <div>
                    <label for="name" class="mb-1 block text-sm font-semibold text-gray-700">Product Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $product->name) }}" placeholder="Product Name" class="w-full rounded-lg border border-gray-300 px-4 py-2" required>
                </div>

                <div>
                    <label for="description" class="mb-1 block text-sm font-semibold text-gray-700">Description</label>
                    <textarea id="description" name="description" placeholder="Product description" class="w-full rounded-lg border border-gray-300 px-4 py-2">{{ old('description', $product->description) }}</textarea>
                </div>

                <div>
                    <label for="image" class="mb-1 block text-sm font-semibold text-gray-700">Product Image</label>
                    <input id="image" type="file" name="image" accept="image/*" class="w-full rounded-lg border border-gray-300 px-4 py-2">
                    @if($product->image_path)
                        <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" class="mt-3 h-24 w-24 rounded-lg border border-gray-200 object-cover">
                    @endif
                </div>

                <div>
                    <label for="price" class="mb-1 block text-sm font-semibold text-gray-700">Price</label>
                    <input id="price" type="number" step="0.01" min="0" name="price" value="{{ old('price', $product->price) }}" placeholder="Price" class="w-full rounded-lg border border-gray-300 px-4 py-2" required>
                </div>

                <div>
                    <label for="stock_quantity" class="mb-1 block text-sm font-semibold text-gray-700">Stock Quantity</label>
                    <input id="stock_quantity" type="number" min="0" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" placeholder="Stock Quantity" class="w-full rounded-lg border border-gray-300 px-4 py-2" required>
                </div>

                <div>
                    <label for="low_stock_threshold" class="mb-1 block text-sm font-semibold text-gray-700">Low Stock Threshold</label>
                    <input id="low_stock_threshold" type="number" min="0" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}" placeholder="Low Stock Threshold" class="w-full rounded-lg border border-gray-300 px-4 py-2" required>
                </div>

                <div class="mt-2 flex gap-2">
                    <button type="submit" class="rounded-lg bg-yellow-300 px-4 py-2 font-semibold text-gray-900 hover:bg-yellow-400">Update Product</button>
                    <a href="{{ route('admin.inventory.index') }}" class="rounded-lg bg-gray-200 px-4 py-2 font-semibold text-gray-800 hover:bg-gray-300">Cancel</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
