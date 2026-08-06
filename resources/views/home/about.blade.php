<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Ormin's Pasalubong Center</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    @include('layouts.navbar')

    <div class="container">
        <h1>About Ormin's Pasalubong Center</h1>
        <p>Ormin's Pasalubong Center is dedicated to providing quality pasalubong products, secure payments, and dependable delivery for our customers.</p>
        
        <h2>Our Mission</h2>
        <p>To deliver convenient, secure, and reliable shopping experiences to the communities we serve.</p>

        <h2>Our Vision</h2>
        <p>To be a trusted pasalubong marketplace with seamless checkout, payment, and delivery support.</p>

        <h2>Our Values</h2>
        <ul>
            <li>Compassion</li>
            <li>Integrity</li>
            <li>Excellence</li>
            <li>Collaboration</li>
            <li>Respect</li>
        </ul>

        <h2>Contact Us</h2>
        <p>If you have any questions or need further information, please feel free to reach out to us.</p>
    </div>

    @include('layouts.footer')
</body>
</html>