@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Your Appointments</h1>
    
    @if($appointments->isEmpty())
        <p>You have no appointments scheduled.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Doctor</th>
                    <th>Date & Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appointment)
                    <tr>
                        <td>{{ $appointment->doctor->name }}</td>
                        <td>{{ $appointment->date_time->format('d-m-Y H:i') }}</td>
                        <td>{{ $appointment->status }}</td>
                        <td>
                            <form action="{{ route('appointments.cancel', $appointment->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Cancel</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('appointments.schedule') }}" class="btn btn-primary">Schedule New Appointment</a>
</div>
@endsection