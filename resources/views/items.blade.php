<!DOCTYPE html>
<html>
<head>
    <title>Items</title>
</head>
<body>
    <h1>Items</h1>
    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item->id_item }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->description }}</td>
                    <td>${{ number_format($item->price, 2) }}</td>
                    <td>{{ $item->stock }}</td>
                    <td>{{ $item->category->category ?? 'hardware' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
