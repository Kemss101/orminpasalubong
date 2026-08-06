<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    @include('layouts.navbar')

    <div class="container">
        <h1>Welcome, {{ auth()->user()->name }}</h1>

        <div class="dashboard">
            <h2>Your Dashboard</h2>
            <div class="dashboard-links">
                <a href="{{ route('patient.appointments') }}">View Appointments</a>
                <a href="{{ route('patient.medicalRecords') }}">View Medical Records</a>
                <a href="{{ route('patient.scheduleConsultation') }}">Schedule a Consultation</a>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; {{ date('Y') }} Grace Mission Hospital. All rights reserved.</p>
    </footer>
</body>
</html>