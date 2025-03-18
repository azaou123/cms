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
                        <a href="{{ route('settings.system') }}" class="list-group-item list-group-item-action active">
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
                <div class="card-header">{{ __('System Settings') }}</div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('settings.system.update') }}">
                        @csrf

                        <h5 class="mb-3">Modules</h5>
                        <div class="form-group row">
                            <div class="col-md-8 offset-md-4">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input " id="enable_member_registration" name="enable_member_registration" {{ $settings->enable_member_registration ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="enable_member_registration">{{ __('Enable Member Registration') }}</label>
                                </div>
                                <div class="custom-control custom-switch mt-2">
                                    <input type="checkbox" class="custom-control-input " id="require_approval_for_registration" name="require_approval_for_registration" {{ $settings->require_approval_for_registration ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="require_approval_for_registration">{{ __('Require Approval for Registration') }}</label>
                                </div>
                                <div class="custom-control custom-switch mt-2">
                                    <input type="checkbox" class="custom-control-input " id="enable_events_module" name="enable_events_module" {{ $settings->enable_events_module ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="enable_events_module">{{ __('Enable Events Module') }}</label>
                                </div>
                                <div class="custom-control custom-switch mt-2">
                                    <input type="checkbox" class="custom-control-input " id="enable_payments_module" name="enable_payments_module" {{ $settings->enable_payments_module ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="enable_payments_module">{{ __('Enable Payments Module') }}</label>
                                </div>
                                <div class="custom-control custom-switch mt-2">
                                    <input type="checkbox" class="custom-control-input " id="enable_newsletter" name="enable_newsletter" {{ $settings->enable_newsletter ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="enable_newsletter">{{ __('Enable Newsletter') }}</label>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3">Locale Settings</h5>

                        <div class="form-group row">
                            <label for="currency" class="col-md-4 col-form-label text-md-right">{{ __('Currency') }} <span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <select id="currency" class="form-control my-1 @error('currency') is-invalid @enderror" name="currency" required>
                                    <option value="USD" {{ old('currency', $settings->currency) == 'USD' ? 'selected' : '' }}>USD ($)</option>
                                    <option value="EUR" {{ old('currency', $settings->currency) == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                    <option value="GBP" {{ old('currency', $settings->currency) == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                                    <option value="CAD" {{ old('currency', $settings->currency) == 'CAD' ? 'selected' : '' }}>CAD (C$)</option>
                                    <option value="AUD" {{ old('currency', $settings->currency) == 'AUD' ? 'selected' : '' }}>AUD (A$)</option>
                                    <option value="JPY" {{ old('currency', $settings->currency) == 'JPY' ? 'selected' : '' }}>JPY (¥)</option>
                                </select>
                                @error('currency')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="timezone" class="col-md-4 col-form-label text-md-right">{{ __('Timezone') }} <span class="text-danger">*</span></label>
                            <div class="col-md-6">
                            <select id="timezone" class="form-control my-1 @error('timezone') is-invalid @enderror" name="timezone" required>
                                    <option value="UTC" {{ old('timezone', $settings->timezone) == 'UTC' ? 'selected' : '' }}>UTC</option>
                                    <option value="America/New_York" {{ old('timezone', $settings->timezone) == 'America/New_York' ? 'selected' : '' }}>Eastern Time (US & Canada)</option>
                                    <option value="America/Chicago" {{ old('timezone', $settings->timezone) == 'America/Chicago' ? 'selected' : '' }}>Central Time (US & Canada)</option>
                                    <option value="America/Denver" {{ old('timezone', $settings->timezone) == 'America/Denver' ? 'selected' : '' }}>Mountain Time (US & Canada)</option>
                                    <option value="America/Los_Angeles" {{ old('timezone', $settings->timezone) == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time (US & Canada)</option>
                                    <option value="Europe/London" {{ old('timezone', $settings->timezone) == 'Europe/London' ? 'selected' : '' }}>London</option>
                                    <option value="Europe/Paris" {{ old('timezone', $settings->timezone) == 'Europe/Paris' ? 'selected' : '' }}>Paris</option>
                                    <!-- More options can be added as needed -->
                                </select>
                                @error('timezone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="date_format" class="col-md-4 col-form-label text-md-right">{{ __('Date Format') }} <span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <select id="date_format" class="form-control my-1 @error('date_format') is-invalid @enderror" name="date_format" required>
                                    <option value="Y-m-d" {{ old('date_format', $settings->date_format) == 'Y-m-d' ? 'selected' : '' }}>2025-03-18 (YYYY-MM-DD)</option>
                                    <option value="m/d/Y" {{ old('date_format', $settings->date_format) == 'm/d/Y' ? 'selected' : '' }}>03/18/2025 (MM/DD/YYYY)</option>
                                    <option value="d/m/Y" {{ old('date_format', $settings->date_format) == 'd/m/Y' ? 'selected' : '' }}>18/03/2025 (DD/MM/YYYY)</option>
                                    <option value="d.m.Y" {{ old('date_format', $settings->date_format) == 'd.m.Y' ? 'selected' : '' }}>18.03.2025 (DD.MM.YYYY)</option>
                                    <option value="F j, Y" {{ old('date_format', $settings->date_format) == 'F j, Y' ? 'selected' : '' }}>March 18, 2025</option>
                                    <option value="j F Y" {{ old('date_format', $settings->date_format) == 'j F Y' ? 'selected' : '' }}>18 March 2025</option>
                                </select>
                                @error('date_format')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="time_format" class="col-md-4 col-form-label text-md-right">{{ __('Time Format') }} <span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <select id="time_format" class="form-control my-1 @error('time_format') is-invalid @enderror" name="time_format" required>
                                    <option value="H:i" {{ old('time_format', $settings->time_format) == 'H:i' ? 'selected' : '' }}>14:30 (24-hour)</option>
                                    <option value="h:i A" {{ old('time_format', $settings->time_format) == 'h:i A' ? 'selected' : '' }}>02:30 PM (12-hour)</option>
                                </select>
                                @error('time_format')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
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