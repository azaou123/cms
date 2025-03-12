@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create New Event') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('events.store') }}">
                        @csrf

                        <div class="mb-3 row">
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

                        <div class="mb-3 row">
                            <label for="description" class="col-md-4 col-form-label text-md-end">{{ __('Description') }}</label>

                            <div class="col-md-6">
                                <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" rows="4">{{ old('description') }}</textarea>

                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="start_date" class="col-md-4 col-form-label text-md-end">{{ __('Start Date') }}</label>

                            <div class="col-md-6">
                                <input type="date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" name="start_date" value="{{ old('start_date') }}" required>

                                @error('start_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="end_date" class="col-md-4 col-form-label text-md-end">{{ __('End Date') }}</label>

                            <div class="col-md-6">
                                <input type="date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" name="end_date" value="{{ old('end_date') }}" required>

                                @error('end_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
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

                        <div class="mb-3 row">
                            <label for="type" class="col-md-4 col-form-label text-md-end">{{ __('Event Type') }}</label>

                            <div class="col-md-6">
                                <select id="type" class="form-select @error('type') is-invalid @enderror" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="workshop" {{ old('type') === 'workshop' ? 'selected' : '' }}>Workshop</option>
                                    <option value="seminar" {{ old('type') === 'seminar' ? 'selected' : '' }}>Seminar</option>
                                    <option value="conference" {{ old('type') === 'conference' ? 'selected' : '' }}>Conference</option>
                                    <option value="meeting" {{ old('type') === 'meeting' ? 'selected' : '' }}>Meeting</option>
                                    <option value="other" {{ old('type') === 'other' ? 'selected' : '' }}>Other</option>
                                </select>

                                @error('type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="cell_id" class="col-md-4 col-form-label text-md-end">{{ __('Cell') }}</label>

                            <div class="col-md-6">
                                <select id="cell_id" class="form-select @error('cell_id') is-invalid @enderror" name="cell_id" required>
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

                        <div class="mb-3 row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Create Event') }}
                                </button>
                                <a href="{{ route('events.index') }}" class="btn btn-secondary">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
