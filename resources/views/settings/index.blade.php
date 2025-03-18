@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">{{ __('Settings') }}</div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('settings.general') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-cog fa-fw mr-2"></i> {{ __('General') }}
                        </a>
                        <a href="{{ route('settings.social-media') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-share-alt fa-fw mr-2"></i> {{ __('Social Media') }}
                        </a>
                        <a href="{{ route('settings.system') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-server fa-fw mr-2"></i> {{ __('System') }}
                        </a>
                        <a href="{{ route('settings.appearance') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-paint-brush fa-fw mr-2"></i> {{ __('Appearance') }}
                        </a>
                        <a href="{{ route('settings.notifications') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-bell fa-fw mr-2"></i> {{ __('Notifications') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">{{ __('Club Settings') }}</div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if($settings->logo_path)
                            <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="Club Logo" class="img-fluid mb-3" style="max-height: 100px;">
                        @else
                            <div class="text-muted">No logo uploaded</div>
                        @endif
                        <h2>{{ $settings->club_name ?: 'Your Club Name' }}</h2>
                        @if($settings->slogan)
                            <p class="text-muted">{{ $settings->slogan }}</p>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-light">Contact Information</div>
                                <div class="card-body">
                                    <p><strong>Email:</strong> {{ $settings->email ?: 'Not set' }}</p>
                                    <p><strong>Phone:</strong> {{ $settings->phone ?: 'Not set' }}</p>
                                    <p><strong>Address:</strong> {{ $settings->address ?: 'Not set' }}</p>
                                    @if($settings->city || $settings->state || $settings->postal_code)
                                        <p>{{ $settings->city }} {{ $settings->state }} {{ $settings->postal_code }}</p>
                                    @endif
                                    <p><strong>Country:</strong> {{ $settings->country ?: 'Not set' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-light">System Configuration</div>
                                <div class="card-body">
                                    <p><strong>Registration:</strong> 
                                        @if($settings->enable_member_registration)
                                            <span class="badge badge-success">Enabled</span>
                                        @else
                                            <span class="badge badge-secondary">Disabled</span>
                                        @endif
                                    </p>
                                    <p><strong>Events Module:</strong> 
                                        @if($settings->enable_events_module)
                                            <span class="badge badge-success">Enabled</span>
                                        @else
                                            <span class="badge badge-secondary">Disabled</span>
                                        @endif
                                    </p>
                                    <p><strong>Payments Module:</strong> 
                                        @if($settings->enable_payments_module)
                                            <span class="badge badge-success">Enabled</span>
                                        @else
                                            <span class="badge badge-secondary">Disabled</span>
                                        @endif
                                    </p>
                                    <p><strong>Newsletter:</strong> 
                                        @if($settings->enable_newsletter)
                                            <span class="badge badge-success">Enabled</span>
                                        @else
                                            <span class="badge badge-secondary">Disabled</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i> Use the navigation on the left to configure different aspects of your club settings.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection