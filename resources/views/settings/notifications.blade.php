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
                        <a href="{{ route('settings.notifications') }}" class="list-group-item list-group-item-action active">
                            <i class="fas fa-bell fa-fw mr-2"></i> {{ __('Notifications') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">{{ __('Notification Settings') }}</div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('settings.notifications.update') }}">
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-8 offset-md-4">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="email_notifications" name="email_notifications" {{ $settings->email_notifications ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="email_notifications">
                                        <i class="fas fa-envelope fa-fw mr-1"></i> {{ __('Email Notifications') }}
                                    </label>
                                    <div class="small text-muted mt-1">Receive notifications via email</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-8 offset-md-4">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="sms_notifications" name="sms_notifications" {{ $settings->sms_notifications ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="sms_notifications">
                                        <i class="fas fa-sms fa-fw mr-1"></i> {{ __('SMS Notifications') }}
                                    </label>
                                    <div class="small text-muted mt-1">Receive notifications via SMS</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-8 offset-md-4">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="push_notifications" name="push_notifications" {{ $settings->push_notifications ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="push_notifications">
                                        <i class="fas fa-mobile-alt fa-fw mr-1"></i> {{ __('Push Notifications') }}
                                    </label>
                                    <div class="small text-muted mt-1">Receive push notifications on your devices</div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i> Additional notification preferences for specific events will be available in a future update.
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Save Changes') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection