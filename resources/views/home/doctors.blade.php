<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Ormin's Pasalubong Center</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    @include('layouts.navbar')

    <div class="container">
        <h1>Featured Products</h1>
        <div class="row">
            @foreach($doctors as $doctor)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $doctor->name }}</h5>
                            <p class="card-text">Category: {{ $doctor->specialization }}</p>
                            <a href="{{ route('home') }}" class="btn btn-primary">Shop Now</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @include('layouts.footer')
</body>
</html>