@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('My Profile') }}</span>
                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-primary">{{ __('Edit Profile') }}</a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            @if ($user->profile_picture)
                                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}" class="img-fluid rounded-circle" style="max-width: 150px; max-height: 150px;">
                            @else
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 150px; height: 150px; font-size: 3rem;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h3>{{ $user->name }}</h3>
                            <p class="text-muted">{{ $user->email }}</p>
                            
                            @if ($user->phone)
                                <p><strong>{{ __('Phone:') }}</strong> {{ $user->phone }}</p>
                            @endif
                            
                            <p><strong>{{ __('Joined:') }}</strong> {{ $user->join_date->format('F d, Y') }}</p>
                            <p><strong>{{ __('Status:') }}</strong> 
                                <span class="badge {{ $user->status === 'active' ? 'bg-success' : ($user->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </p>
                            
                            @if ($user->bio)
                                <hr>
                                <h5>{{ __('Bio') }}</h5>
                                <p>{{ $user->bio }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection