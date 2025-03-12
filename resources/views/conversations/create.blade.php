@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>{{ __('New Conversation') }}</h2>
        <a href="{{ route('conversations.index') }}" class="btn btn-secondary">Back to Conversations</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('conversations.store') }}">
                @csrf
                <div class="form-group mb-3">
                    <label for="user_id">Select User</label>
                    <select id="user_id" name="user_id" class="form-control @error('user_id') is-invalid @enderror">
                        <option value="">-- Select User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="text-end">
                    <button type="submit" class="btn btn-dark">Start Conversation</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection