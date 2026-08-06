<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ormin's Pasalubong Center - Home</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    @include('layouts.navbar')

    <div class="container">
        <header class="text-center my-5">
            <h1>Welcome to Ormin's Pasalubong Center</h1>
            <p>Your online pasalubong shopping system.</p>
        </header>

        <section id="services" class="my-5">
            <h2>Our Services</h2>
            <p>We offer pasalubong items, shopping checkout, delivery tracking, and cashback rewards.</p>
            <ul>
                <li>Online Consultations</li>
                <li>Medical Record Management</li>
                <li>Appointment Scheduling</li>
                <li>24/7 Support</li>
            </ul>
        </section>

        <section id="doctors" class="my-5">
            <h2>Meet Our Doctors</h2>
            <p>Browse featured products, checkout options, and delivery updates.</p>
            <a href="{{ route('cart.index') }}" class="btn btn-primary">View Cart</a>
        </section>

        <section id="about" class="my-5">
            <h2>About Us</h2>
            <p>Ormin's Pasalubong Center is dedicated to providing quality products and a smooth shopping experience.</p>
            <a href="{{ route('home') }}#about" class="btn btn-primary">Learn More</a>
        </section>

        <section id="login" class="my-5">
            <h2>Client Login</h2>
            <p>If you are a client, please log in to access your dashboard.</p>
            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
        </section>

        <section id="register" class="my-5">
            <h2>New Client Registration</h2>
            <p>New to our services? Register now to get started.</p>
            <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
        </section>
    </div>

    <footer class="text-center my-5">
        <p>&copy; {{ date('Y') }} Grace Mission Hospital. All rights reserved.</p>
    </footer>
</body>
</html>