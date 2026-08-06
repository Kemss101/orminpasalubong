<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
</head>
<body>
    <h1>Products</h1>

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>ID</th>
                <th>City</th>
                <th>Country</th>
                <th>Year Listed</th>
                <th>Rooms</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->city }}</td>
                    <td>{{ $product->country }}</td>
                    <td>{{ $product->year_listed }}</td>
                    <td>{{ $product->number_of_rooms ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>

