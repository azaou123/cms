@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">{{ __('Settings') }}</div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('settings.general') }}" class="list-group-item list-group-item-action active">
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
                <div class="card-header">{{ __('General Settings') }}</div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('settings.general.update') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="club_name" class="col-md-4 col-form-label text-md-right">{{ __('Club Name') }} <span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <input id="club_name" type="text" class="form-control my-1 @error('club_name') is-invalid @enderror" name="club_name" value="{{ old('club_name', $settings->club_name) }}" required>
                                @error('club_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="slogan" class="col-md-4 col-form-label text-md-right">{{ __('Slogan') }}</label>
                            <div class="col-md-6">
                                <input id="slogan" type="text" class="form-control my-1 @error('slogan') is-invalid @enderror" name="slogan" value="{{ old('slogan', $settings->slogan) }}">
                                @error('slogan')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>
                            <div class="col-md-6">
                                <textarea id="description" class="form-control my-1 @error('description') is-invalid @enderror" name="description" rows="4">{{ old('description', $settings->description) }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3">Contact Information</h5>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Email') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control my-1 @error('email') is-invalid @enderror" name="email" value="{{ old('email', $settings->email) }}">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone') }}</label>
                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control my-1 @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $settings->phone) }}">
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>
                            <div class="col-md-6">
                                <input id="address" type="text" class="form-control my-1 @error('address') is-invalid @enderror" name="address" value="{{ old('address', $settings->address) }}">
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="city" class="col-md-4 col-form-label text-md-right">{{ __('City') }}</label>
                            <div class="col-md-6">
                                <input id="city" type="text" class="form-control my-1 @error('city') is-invalid @enderror" name="city" value="{{ old('city', $settings->city) }}">
                                @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="state" class="col-md-4 col-form-label text-md-right">{{ __('State/Province') }}</label>
                            <div class="col-md-6">
                                <input id="state" type="text" class="form-control my-1 @error('state') is-invalid @enderror" name="state" value="{{ old('state', $settings->state) }}">
                                @error('state')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="postal_code" class="col-md-4 col-form-label text-md-right">{{ __('Postal Code') }}</label>
                            <div class="col-md-6">
                                <input id="postal_code" type="text" class="form-control my-1 @error('postal_code') is-invalid @enderror" name="postal_code" value="{{ old('postal_code', $settings->postal_code) }}">
                                @error('postal_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="country" class="col-md-4 col-form-label text-md-right">{{ __('Country') }}</label>
                            <div class="col-md-6">
                                <input id="country" type="text" class="form-control my-1 @error('country') is-invalid @enderror" name="country" value="{{ old('country', $settings->country) }}">
                                @error('country')
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