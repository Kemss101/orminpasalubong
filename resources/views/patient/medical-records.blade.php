@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Medical Records</h1>

    @if($medicalRecords->isEmpty())
        <div class="alert alert-warning">
            No medical records found.
        </div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Record ID</th>
                    <th>Details</th>
                    <th>Date Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($medicalRecords as $record)
                    <tr>
                        <td>{{ $record->record_id }}</td>
                        <td>{{ $record->details }}</td>
                        <td>{{ $record->created_at->format('d-m-Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection