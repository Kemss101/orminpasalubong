<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Consultation</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    @include('layouts.navbar')

    <div class="container">
        <h1>Schedule a Consultation</h1>

        <form action="{{ route('appointments.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="doctor_id">Select Doctor:</label>
                <select name="doctor_id" id="doctor_id" class="form-control" required>
                    <option value="">-- Select Doctor --</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->name }} - {{ $doctor->specialization }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="date_time">Select Date and Time:</label>
                <input type="datetime-local" name="date_time" id="date_time" class="form-control" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Schedule Consultation</button>
            </div>
        </form>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>