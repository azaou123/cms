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
                        <a href="{{ route('settings.appearance') }}" class="list-group-item list-group-item-action active">
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
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ __('Appearance Settings') }}</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('settings.appearance.update') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Club Logo -->
                        <div class="mb-3">
                            <label for="logo" class="form-label">{{ __('Club Logo') }}</label>
                            <div class="d-flex align-items-center">
                                @if($settings->logo_path)
                                <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="Club Logo" class="img-thumbnail me-3" style="max-height: 80px;">
                                @endif
                                <div class="w-100">
                                    <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo">
                                    <small class="text-muted">Recommended size: 200x200px. Max file size: 2MB.</small>
                                    @error('logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Cover Image -->
                        <div class="mb-3">
                            <label for="cover_image" class="form-label">{{ __('Cover Image') }}</label>
                            <div class="d-flex align-items-center">
                                @if($settings->cover_image_path)
                                <img src="{{ asset('storage/' . $settings->cover_image_path) }}" alt="Cover Image" class="img-thumbnail me-3" style="max-width: 120px;">
                                @endif
                                <div class="w-100">
                                    <input type="file" class="form-control @error('cover_image') is-invalid @enderror" id="cover_image" name="cover_image">
                                    <small class="text-muted">Recommended size: 1200x400px. Max file size: 2MB.</small>
                                    @error('cover_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Primary Color -->
                        <div class="mb-3">
                            <label for="primary_color" class="form-label">{{ __('Primary Color') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-palette"></i></span>
                                <input type="color" class="form-control form-control-color @error('primary_color') is-invalid @enderror" id="primary_color" name="primary_color" value="{{ old('primary_color', $settings->primary_color ?? '#3490dc') }}">
                                <input type="text" class="form-control" id="primary_color_hex" value="{{ old('primary_color', $settings->primary_color ?? '#3490dc') }}" readonly>
                            </div>
                            @error('primary_color')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Secondary Color -->
                        <div class="mb-3">
                            <label for="secondary_color" class="form-label">{{ __('Secondary Color') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-palette"></i></span>
                                <input type="color" class="form-control form-control-color @error('secondary_color') is-invalid @enderror" id="secondary_color" name="secondary_color" value="{{ old('secondary_color', $settings->secondary_color ?? '#6c757d') }}">
                                <input type="text" class="form-control" id="secondary_color_hex" value="{{ old('secondary_color', $settings->secondary_color ?? '#6c757d') }}" readonly>
                            </div>
                            @error('secondary_color')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> {{ __('Save Changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    // Update color hex value display when color is changed
    document.getElementById('primary_color').addEventListener('input', function() {
        document.getElementById('primary_color_hex').value = this.value;
    });

    document.getElementById('secondary_color').addEventListener('input', function() {
        document.getElementById('secondary_color_hex').value = this.value;
    });

    // Update the custom file input label with the selected filename
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = e.target.files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });
</script>
@endpush
@endsection