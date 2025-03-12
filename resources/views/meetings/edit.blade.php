@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Meeting') }}: {{ $meeting->title }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('meetings.update', $meeting) }}">
                        @csrf
                        @method('PUT')

                        <!-- Title Field -->
                        <div class="mb-3">
                            <label for="title" class="form-label">{{ __('Title') }}</label>
                            <input id="title" type="text" class="form-control" name="title" value="{{ old('title', $meeting->title) }}" required autofocus>
                            @error('title')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <!-- Cell Field -->
                        <div class="mb-3">
                            <label for="cell_id" class="form-label">{{ __('Cell') }}</label>
                            <select id="cell_id" name="cell_id" class="form-select">
                                <option value="">Select Cell</option>
                                @foreach($cells as $cell)
                                    <option value="{{ $cell->id }}" {{ old('cell_id', $meeting->cell_id) == $cell->id ? 'selected' : '' }}>{{ $cell->name }}</option>
                                @endforeach
                            </select>
                            @error('cell_id')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <!-- Project Field (Optional) -->
                        <div class="mb-3">
                            <label for="project_id" class="form-label">{{ __('Project (Optional)') }}</label>
                            <select id="project_id" name="project_id" class="form-select">
                                <option value="">Select Project</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id', $meeting->project_id) == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                @endforeach
                            </select>
                            @error('project_id')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <!-- Date Field -->
                        <div class="mb-3">
                            <label for="date" class="form-label">{{ __('Date') }}</label>
                            <input id="date" type="date" class="form-control" name="date" value="{{ old('date', $meeting->date->format('Y-m-d')) }}" required>
                            @error('date')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <!-- Start Time Field -->
                        <div class="mb-3">
                            <label for="start_time" class="form-label">{{ __('Start Time') }}</label>
                            <input id="start_time" type="time" class="form-control" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($meeting->start_time)->format('H:i')) }}" required>
                            @error('start_time')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <!-- End Time Field -->
                        <div class="mb-3">
                            <label for="end_time" class="form-label">{{ __('End Time') }}</label>
                            <input id="end_time" type="time" class="form-control" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($meeting->end_time)->format('H:i')) }}" required>
                            @error('end_time')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <!-- Location Field -->
                        <div class="mb-3">
                            <label for="location" class="form-label">{{ __('Location') }}</label>
                            <input id="location" type="text" class="form-control" name="location" value="{{ old('location', $meeting->location) }}">
                            @error('location')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <!-- Description Field -->
                        <div class="mb-3">
                            <label for="description" class="form-label">{{ __('Description') }}</label>
                            <textarea id="description" class="form-control" name="description" rows="4">{{ old('description', $meeting->description) }}</textarea>
                            @error('description')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('meetings.show', $meeting) }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                            <button type="submit" class="btn btn-primary">{{ __('Update Meeting') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
