<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Inventory | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-primary/20 text-gray-800">
    <header class="border-b bg-primary">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
            <div>
                <h1 class="text-2xl font-extrabold">Manage Inventory</h1>
                <p class="text-sm text-gray-700">Add, edit, and remove products.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.dashboard') }}" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold hover:bg-gray-50">Dashboard</a>
                <a href="{{ route('admin.inventory.create') }}" class="rounded-lg bg-yellow-300 px-4 py-2 text-sm font-semibold text-gray-900 hover:bg-yellow-400">Add Product</a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8">
        @if(session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
        @endif

        <form method="GET" action="{{ route('admin.inventory.index') }}" class="mb-4 flex gap-2">
            <input type="text" name="search" value="{{ $search }}" placeholder="Search by product or SKU" class="w-full rounded-lg border border-gray-300 px-4 py-2">
            <button type="submit" class="rounded-lg bg-primaryStrong px-4 py-2 font-semibold text-white hover:bg-green-600">Search</button>
        </form>

        <div class="overflow-hidden rounded-xl bg-white shadow">
            <table class="w-full text-left text-sm">
                <thead class="bg-yellow-100 text-gray-900">
                    <tr>
                        <th class="px-4 py-3">Image</th>
                        <th class="px-4 py-3">SKU</th>
                        <th class="px-4 py-3">Product</th>
                        <th class="px-4 py-3">Price</th>
                        <th class="px-4 py-3">Stock</th>
                        <th class="px-4 py-3">Low Stock Threshold</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr class="border-t">
                            <td class="px-4 py-3">
                                @if($product->image_path)
                                    <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" class="h-12 w-12 rounded-md border border-gray-200 object-cover">
                                @else
                                    <div class="flex h-12 w-12 items-center justify-center rounded-md border border-dashed border-gray-300 text-[10px] text-gray-400">No image</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $product->sku_code }}</td>
                            <td class="px-4 py-3 font-semibold">{{ $product->name }}</td>
                            <td class="px-4 py-3">P {{ number_format($product->price, 2) }}</td>
                            <td class="px-4 py-3">{{ $product->stock_quantity }}</td>
                            <td class="px-4 py-3">{{ $product->low_stock_threshold }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.inventory.edit', $product) }}" class="rounded bg-blue-500 px-3 py-1 text-xs font-semibold text-white hover:bg-blue-600">Edit</a>
                                    <form method="POST" action="{{ route('admin.inventory.destroy', $product) }}" onsubmit="return confirm('Delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded bg-red-500 px-3 py-1 text-xs font-semibold text-white hover:bg-red-600">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-gray-500">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </main>
</body>
</html>
