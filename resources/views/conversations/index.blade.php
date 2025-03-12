@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>{{ __('Conversations') }}</h2>
        <a href="{{ route('conversations.create') }}" class="btn btn-dark">New Conversation</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if($conversations->isEmpty())
        <div class="text-center py-4">
            <p class="text-muted">You have no conversations yet.</p>
            <a href="{{ route('conversations.create') }}" class="btn btn-dark">Start Your First Conversation</a>
        </div>
    @else
        <div class="list-group">
            @foreach($conversations as $conversation)
                <a href="{{ route('conversations.show', $conversation) }}" class="list-group-item list-group-item-action">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">
                                @if($conversation->is_group)
                                    {{ $conversation->name }}
                                @else
                                    {{ $conversation->users->where('id', '!=', auth()->id())->first()->name }}
                                @endif
                            </h5>
                            <p class="mb-1 text-muted">{{ $conversation->lastMessage?->body ?? 'No messages yet' }}</p>
                        </div>
                        <div>
                            @if($conversation->lastMessage)
                                <small class="text-muted">{{ $conversation->lastMessage->created_at->diffForHumans() }}</small>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection