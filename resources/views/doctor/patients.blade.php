@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Patients</h1>

    @if($patients->isEmpty())
        <div class="alert alert-warning">No patients assigned to you.</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Patient ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Contact</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patients as $patient)
                    <tr>
                        <td>{{ $patient->patient_id }}</td>
                        <td>{{ $patient->name }}</td>
                        <td>{{ $patient->age }}</td>
                        <td>{{ $patient->gender }}</td>
                        <td>{{ $patient->contact }}</td>
                        <td>{{ $patient->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection