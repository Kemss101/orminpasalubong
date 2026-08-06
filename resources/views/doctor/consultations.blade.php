<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - Ormin's Pasalubong Center</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    @include('layouts.navbar')

    <div class="container">
        <h1>Consultations</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Date</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($consultations as $consultation)
                    <tr>
                        <td>{{ $consultation->patient->name }}</td>
                        <td>{{ $consultation->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $consultation->notes }}</td>
                        <td>
                            <a href="{{ route('consultations.show', $consultation->id) }}" class="btn btn-info">View</a>
                            <form action="{{ route('consultations.destroy', $consultation->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination">
            {{ $consultations->links() }}
        </div>
    </div>

    @include('layouts.footer')
</body>
</html>