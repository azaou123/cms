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
                        <a href="{{ route('settings.social-media') }}" class="list-group-item list-group-item-action active">
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
                <div class="card-header">{{ __('Social Media Settings') }}</div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('settings.social-media.update') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="website_url" class="col-md-4 col-form-label text-md-right">
                                <i class="fas fa-globe fa-fw mr-1"></i> {{ __('Website') }}
                            </label>
                            <div class="col-md-6">
                                <input id="website_url" type="url" class="form-control my-1 @error('website_url') is-invalid @enderror" name="website_url" value="{{ old('website_url', $settings->website_url) }}" placeholder="https://your-website.com">
                                @error('website_url')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="facebook_url" class="col-md-4 col-form-label text-md-right">
                                <i class="fab fa-facebook fa-fw mr-1"></i> {{ __('Facebook') }}
                            </label>
                            <div class="col-md-6">
                                <input id="facebook_url" type="url" class="form-control my-1 @error('facebook_url') is-invalid @enderror" name="facebook_url" value="{{ old('facebook_url', $settings->facebook_url) }}" placeholder="https://facebook.com/your-page">
                                @error('facebook_url')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="twitter_url" class="col-md-4 col-form-label text-md-right">
                                <i class="fab fa-twitter fa-fw mr-1"></i> {{ __('Twitter') }}
                            </label>
                            <div class="col-md-6">
                                <input id="twitter_url" type="url" class="form-control my-1 @error('twitter_url') is-invalid @enderror" name="twitter_url" value="{{ old('twitter_url', $settings->twitter_url) }}" placeholder="https://twitter.com/your-handle">
                                @error('twitter_url')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="instagram_url" class="col-md-4 col-form-label text-md-right">
                                <i class="fab fa-instagram fa-fw mr-1"></i> {{ __('Instagram') }}
                            </label>
                            <div class="col-md-6">
                                <input id="instagram_url" type="url" class="form-control my-1 @error('instagram_url') is-invalid @enderror" name="instagram_url" value="{{ old('instagram_url', $settings->instagram_url) }}" placeholder="https://instagram.com/your-handle">
                                @error('instagram_url')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="linkedin_url" class="col-md-4 col-form-label text-md-right">
                                <i class="fab fa-linkedin fa-fw mr-1"></i> {{ __('LinkedIn') }}
                            </label>
                            <div class="col-md-6">
                                <input id="linkedin_url" type="url" class="form-control my-1 @error('linkedin_url') is-invalid @enderror" name="linkedin_url" value="{{ old('linkedin_url', $settings->linkedin_url) }}" placeholder="https://linkedin.com/company/your-page">
                                @error('linkedin_url')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="youtube_url" class="col-md-4 col-form-label text-md-right">
                                <i class="fab fa-youtube fa-fw mr-1"></i> {{ __('YouTube') }}
                            </label>
                            <div class="col-md-6">
                                <input id="youtube_url" type="url" class="form-control my-1 @error('youtube_url') is-invalid @enderror" name="youtube_url" value="{{ old('youtube_url', $settings->youtube_url) }}" placeholder="https://youtube.com/c/your-channel">
                                @error('youtube_url')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tiktok_url" class="col-md-4 col-form-label text-md-right">
                                <i class="fab fa-tiktok fa-fw mr-1"></i> {{ __('TikTok') }}
                            </label>
                            <div class="col-md-6">
                                <input id="tiktok_url" type="url" class="form-control my-1 @error('tiktok_url') is-invalid @enderror" name="tiktok_url" value="{{ old('tiktok_url', $settings->tiktok_url) }}" placeholder="https://tiktok.com/@your-handle">
                                @error('tiktok_url')
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