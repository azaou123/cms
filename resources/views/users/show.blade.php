@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5>User Details</h5>
            <a href="{{ route('users') }}" class="btn btn-sm btn-secondary">Back</a>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Name:</strong> {{ $user->name }}</li>
                <li class="list-group-item"><strong>Email:</strong> {{ $user->email }}</li>
                <li class="list-group-item"><strong>Cell:</strong> {{ optional($user->cell)->name ?? '—' }}</li>
                <li class="list-group-item">
                    <strong>Projects:</strong>
                    @forelse ($user->projects as $proj)
                        <span class="badge bg-info text-dark">{{ $proj->name }}</span>
                    @empty
                        <span class="text-muted">No projects</span>
                    @endforelse
                </li>
                <li class="list-group-item"><strong>Created At:</strong> {{ $user->created_at->format('F d, Y H:i') }}</li>
            </ul>
        </div>
    </div>
</div>
@endsection