<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-primary/20 text-gray-800">
    <main class="mx-auto max-w-3xl px-4 py-8">
        <div class="rounded-xl bg-white p-6 shadow">
            <h1 class="text-2xl font-extrabold">Add Product</h1>
            <p class="text-sm text-gray-600">Create a new inventory item.</p>

            @if(isset($errors) && $errors->any())
                <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.inventory.store') }}" enctype="multipart/form-data" class="mt-6 grid gap-4">
                @csrf
                <input type="text" name="sku_code" value="{{ old('sku_code') }}" placeholder="SKU Code" class="rounded-lg border border-gray-300 px-4 py-2" required>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Product Name" class="rounded-lg border border-gray-300 px-4 py-2" required>
                <textarea name="description" placeholder="Description" class="rounded-lg border border-gray-300 px-4 py-2">{{ old('description') }}</textarea>
                <div>
                    <label for="image" class="mb-1 block text-sm font-semibold text-gray-700">Product Image</label>
                    <input id="image" type="file" name="image" accept="image/*" class="w-full rounded-lg border border-gray-300 px-4 py-2">
                </div>
                <input type="number" step="0.01" min="0" name="price" value="{{ old('price') }}" placeholder="Price" class="rounded-lg border border-gray-300 px-4 py-2" required>
                <input type="number" min="0" name="stock_quantity" value="{{ old('stock_quantity') }}" placeholder="Stock Quantity" class="rounded-lg border border-gray-300 px-4 py-2" required>
                <input type="number" min="0" name="low_stock_threshold" value="{{ old('low_stock_threshold', 10) }}" placeholder="Low Stock Threshold" class="rounded-lg border border-gray-300 px-4 py-2" required>

                <div class="mt-2 flex gap-2">
                    <button type="submit" class="rounded-lg bg-yellow-300 px-4 py-2 font-semibold text-gray-900 hover:bg-yellow-400">Save Product</button>
                    <a href="{{ route('admin.inventory.index') }}" class="rounded-lg bg-gray-200 px-4 py-2 font-semibold text-gray-800 hover:bg-gray-300">Cancel</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
