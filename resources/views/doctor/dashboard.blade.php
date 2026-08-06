<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    @include('layouts.navbar')

    <div class="container">
        <h1>Welcome, Dr. {{ Auth::user()->name }}</h1>

        <div class="dashboard">
            <h2>Your Appointments</h2>
            <table>
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->patient->name }}</td>
                            <td>{{ $appointment->date_time }}</td>
                            <td>{{ $appointment->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <h2>Your Consultations</h2>
            <table>
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Notes</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($consultations as $consultation)
                        <tr>
                            <td>{{ $consultation->patient->name }}</td>
                            <td>{{ $consultation->notes }}</td>
                            <td>{{ $consultation->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <h2>Your Patients</h2>
            <table>
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Contact</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patients as $patient)
                        <tr>
                            <td>{{ $patient->name }}</td>
                            <td>{{ $patient->contact }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>