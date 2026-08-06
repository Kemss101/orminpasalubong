<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing System</title>
    <link rel="stylesheet" href="">
</head>
<body>
    <div>
        <h1>Electricity Bill</h1>
        <p>Customer Name: {{ $bill['customer_name'] }}</p>
        <p>Customer Type: {{ $bill['customer_type'] }}</p>
        <p>Consumption: {{ $bill['consumption_kwh'] }} kWh</p>
        <p>Base Bill: ₱{{ $bill['base_bill'] }}</p>
        <p>Total Bill: ₱{{ $bill['total_bill'] }}</p>
    </div>
</body>
</html>