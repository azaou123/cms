@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>{{ __('Meetings') }}</h2>
        <a href="{{ route('meetings.create') }}" class="btn btn-dark">Create Meeting</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
    @endif

    @if($meetings->count() > 0)
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Cell</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($meetings as $meeting)
                <tr>
                    <td>{{ $meeting->title }}</td>
                    <td>{{ $meeting->date->format('M d, Y') }}</td>
                    <td>{{ date('h:i A', strtotime($meeting->start_time)) }} - {{ date('h:i A', strtotime($meeting->end_time)) }}</td>
                    <td>{{ $meeting->cell->name }}</td>
                    <td>
                        <span class="badge {{ $meeting->type === 'in-person' ? 'badge-success text-dark' : ($meeting->type === 'virtual' ? 'badge-primary' : 'badge-info') }}">
                            {{ ucfirst($meeting->type) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('meetings.show', $meeting) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('meetings.edit', $meeting) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('meetings.destroy', $meeting) }}" class="d-inline-block"
                            onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>

                    @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $meetings->links() }}
    </div>
    @else
    <div class="text-center py-4">
        <p class="text-muted">No meetings found.</p>
        <a href="{{ route('meetings.create') }}" class="btn btn-dark">Schedule Your First Meeting</a>
    </div>
    @endif
</div>
@endsection