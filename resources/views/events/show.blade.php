@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>{{ $event->title }}</h2>
                    <a href="{{ route('events.index') }}" class="btn btn-secondary btn-sm">Back to Events</a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('info'))
                        <div class="alert alert-info">{{ session('info') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="mb-3 d-flex justify-content-end">
                        @if(auth()->check() && !$userRegistered)
                            <form action="{{ route('events.register', $event) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">Register for Event</button>
                            </form>
                        @elseif($userRegistered && $registration->status === 'registered')
                            <form action="{{ route('registrations.cancel', $registration) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Cancel Registration</button>
                            </form>
                        @endif
                        
                        @if(auth()->check() && auth()->user()->isAdmin)
                            <a href="{{ route('events.edit', $event) }}" class="btn btn-warning">Edit Event</a>
                            <form action="{{ route('events.destroy', $event) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete Event</button>
                            </form>
                        @endif
                    </div>

                    <div class="card mb-4">
                        <div class="card-body">
                            <h4>Event Details</h4>
                            <p><strong>Cell:</strong> {{ $event->cell->name }}</p>
                            <p><strong>Type:</strong> {{ ucfirst($event->type) }}</p>
                            <p><strong>Dates:</strong> 
                                {{ $event->start_date->format('M d, Y') }}
                                @if($event->start_date->format('Y-m-d') != $event->end_date->format('Y-m-d'))
                                    - {{ $event->end_date->format('M d, Y') }}
                                @endif
                            </p>
                            <p><strong>Location:</strong> {{ $event->location ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-body">
                            <h4>Description</h4>
                            <p>{{ $event->description ?? 'No description provided.' }}</p>
                        </div>
                    </div>

                    @if(auth()->check() && auth()->user()->isAdmin)
                        <div class="card">
                            <div class="card-body">
                                <h4>Registered Attendees</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Registration Date</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($event->registrations as $registration)
                                                <tr>
                                                    <td>{{ $registration->user->name }}</td>
                                                    <td>{{ $registration->user->email }}</td>
                                                    <td>{{ $registration->registration_date->format('M d, Y') }}</td>
                                                    <td>{{ ucfirst($registration->status) }}</td>
                                                    <td>
                                                        <form action="{{ route('registrations.update-status', $registration) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <select name="status" class="form-select d-inline w-auto">
                                                                <option value="registered" {{ $registration->status === 'registered' ? 'selected' : '' }}>Registered</option>
                                                                <option value="attended" {{ $registration->status === 'attended' ? 'selected' : '' }}>Attended</option>
                                                                <option value="cancelled" {{ $registration->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                                <option value="no_show" {{ $registration->status === 'no_show' ? 'selected' : '' }}>No Show</option>
                                                            </select>
                                                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">No registrations yet.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
