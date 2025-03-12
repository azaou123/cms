@extends('layouts.app') <!-- Assuming you have a layout file called app.blade.php -->

@section('content')
    <div class="container">
        <h1>My Registered Events</h1>

        @if($registrations->isEmpty())
            <p>You have not registered for any events yet.</p>
        @else
            <div class="list-group">
                @foreach ($registrations as $registration)
                    <div class="list-group-item">
                        <h3>{{ $registration->event->title }}</h3>
                        <p>{{ $registration->event->description }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($registration->status) }}</p>
                        <p><strong>Location:</strong> {{ $registration->event->location }}</p>
                        <p><strong>Start Date:</strong> {{ $registration->event->start_date }}</p>
                        <p><strong>End Date:</strong> {{ $registration->event->end_date }}</p>
                    </div>
                @endforeach
            </div>

            <!-- Pagination Links -->
            <div class="pagination">
                {{ $registrations->links() }}
            </div>
        @endif
    </div>
@endsection
