@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Schedule New Meeting') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('meetings.store') }}">
                        @csrf

                        <!-- Title Field -->
                        <div class="row mb-3">
                            <label for="title" class="col-md-4 col-form-label text-md-end">{{ __('Title') }}</label>
                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required autofocus>
                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Cell Field -->
                        <div class="row mb-3">
                            <label for="cell_id" class="col-md-4 col-form-label text-md-end">{{ __('Cell') }}</label>
                            <div class="col-md-6">
                                <select id="cell_id" name="cell_id" class="form-select">
                                    <option value="">Select Cell</option>
                                    @foreach($cells as $cell)
                                        <option value="{{ $cell->id }}" {{ old('cell_id') == $cell->id ? 'selected' : '' }}>{{ $cell->name }}</option>
                                    @endforeach
                                </select>
                                @error('cell_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Project Field -->
                        <div class="row mb-3">
                            <label for="project_id" class="col-md-4 col-form-label text-md-end">{{ __('Project') }}</label>
                            <div class="col-md-6">
                                <select id="project_id" name="project_id" class="form-select">
                                    <option value="">Select Project (Optional)</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Description Field -->
                        <div class="row mb-3">
                            <label for="description" class="col-md-4 col-form-label text-md-end">{{ __('Description') }}</label>
                            <div class="col-md-6">
                                <textarea id="description" name="description" rows="4" class="form-control">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Date Field -->
                        <div class="row mb-3">
                            <label for="date" class="col-md-4 col-form-label text-md-end">{{ __('Date') }}</label>
                            <div class="col-md-6">
                                <input id="date" type="date" class="form-control @error('date') is-invalid @enderror" name="date" value="{{ old('date') }}" required>
                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Start Time Field -->
                        <div class="row mb-3">
                            <label for="start_time" class="col-md-4 col-form-label text-md-end">{{ __('Start Time') }}</label>
                            <div class="col-md-6">
                                <input id="start_time" type="time" class="form-control @error('start_time') is-invalid @enderror" name="start_time" value="{{ old('start_time') }}" required>
                                @error('start_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- End Time Field -->
                        <div class="row mb-3">
                            <label for="end_time" class="col-md-4 col-form-label text-md-end">{{ __('End Time') }}</label>
                            <div class="col-md-6">
                                <input id="end_time" type="time" class="form-control @error('end_time') is-invalid @enderror" name="end_time" value="{{ old('end_time') }}" required>
                                @error('end_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Location Field -->
                        <div class="row mb-3">
                            <label for="location" class="col-md-4 col-form-label text-md-end">{{ __('Location') }}</label>
                            <div class="col-md-6">
                                <input id="location" type="text" class="form-control @error('location') is-invalid @enderror" name="location" value="{{ old('location') }}">
                                @error('location')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Type Field -->
                        <div class="row mb-3">
                            <label for="type" class="col-md-4 col-form-label text-md-end">{{ __('Type') }}</label>
                            <div class="col-md-6">
                                <select id="type" name="type" class="form-select" required>
                                    <option value="in-person" {{ old('type') == 'in-person' ? 'selected' : '' }}>{{ __('In-person') }}</option>
                                    <option value="virtual" {{ old('type') == 'virtual' ? 'selected' : '' }}>{{ __('Virtual') }}</option>
                                    <option value="hybrid" {{ old('type') == 'hybrid' ? 'selected' : '' }}>{{ __('Hybrid') }}</option>
                                </select>
                                @error('type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Attendees Field -->
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">{{ __('Invite Attendees') }}</label>
                            <div class="col-md-6">
                                @foreach($users as $user)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="attendees[]" value="{{ $user->id }}" id="user{{ $user->id }}" {{ in_array($user->id, old('attendees', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="user{{ $user->id }}">{{ $user->name }}</label>
                                    </div>
                                @endforeach
                                @error('attendees')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <a href="{{ route('meetings.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">{{ __('Schedule Meeting') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
