@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Meeting Details') }}</span>
                    <a href="{{ route('meetings.generate-report', $meeting) }}" class="btn btn-success btn-sm">
                        {{ __('Generate Report') }}
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <label for="title" class="col-md-4 col-form-label text-md-end">{{ __('Title') }}</label>
                        <div class="col-md-6">
                            <input id="title" type="text" class="form-control" value="{{ $meeting->title }}" disabled>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="date_time" class="col-md-4 col-form-label text-md-end">{{ __('Date & Time') }}</label>
                        <div class="col-md-6">
                            <input id="date_time" type="text" class="form-control" value="{{ $meeting->date->format('F d, Y') }}, {{ date('h:i A', strtotime($meeting->start_time)) }} - {{ date('h:i A', strtotime($meeting->end_time)) }}" disabled>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="type" class="col-md-4 col-form-label text-md-end">{{ __('Type') }}</label>
                        <div class="col-md-6">
                            <input id="type" type="text" class="form-control" value="{{ ucfirst($meeting->type) }}" disabled>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="location" class="col-md-4 col-form-label text-md-end">{{ __('Location') }}</label>
                        <div class="col-md-6">
                            <input id="location" type="text" class="form-control" value="{{ $meeting->location ?: 'Not specified' }}" disabled>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="cell" class="col-md-4 col-form-label text-md-end">{{ __('Cell') }}</label>
                        <div class="col-md-6">
                            <input id="cell" type="text" class="form-control" value="{{ $meeting->cell->name }}" disabled>
                        </div>
                    </div>

                    @if($meeting->project)
                    <div class="row mb-3">
                        <label for="project" class="col-md-4 col-form-label text-md-end">{{ __('Project') }}</label>
                        <div class="col-md-6">
                            <input id="project" type="text" class="form-control" value="{{ $meeting->project->name }}" disabled>
                        </div>
                    </div>
                    @endif

                    @if($meeting->board)
                    <div class="row mb-3">
                        <label for="board" class="col-md-4 col-form-label text-md-end">{{ __('Board') }}</label>
                        <div class="col-md-6">
                            <input id="board" type="text" class="form-control" value="{{ $meeting->board->name }}" disabled>
                        </div>
                    </div>
                    @endif

                    <div class="row mb-3">
                        <label for="description" class="col-md-4 col-form-label text-md-end">{{ __('Description') }}</label>
                        <div class="col-md-6">
                            <textarea id="description" name="description" rows="4" class="form-control" disabled>{{ $meeting->description ?: 'No description provided.' }}</textarea>
                        </div>
                    </div>

                    <h5 class="mb-3">{{ __('Attendees') }}</h5>

                    <div class="row mb-3">
                        <label for="status" class="col-md-4 col-form-label text-md-end">{{ __('Your Response') }}</label>
                        <div class="col-md-6">
                            <form method="POST" action="{{ route('meetings.update-attendance', $meeting) }}">
                                @csrf
                                <div class="d-flex gap-2">
                                    <select name="status" class="form-select">
                                        <option value="attending" {{ $meeting->status === 'attending' ? 'selected' : '' }}>Attending</option>
                                        <option value="not_attending" {{ $meeting->status === 'not_attending' ? 'selected' : '' }}>Not Attending</option>
                                        <option value="maybe" {{ $meeting->status === 'maybe' ? 'selected' : '' }}>Maybe</option>
                                    </select>

                                    <input type="text" name="notes" value="{{ $meeting->notes }}" placeholder="Optional notes" class="form-control">
                                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="overflow-auto">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('Attendee') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Notes') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($meeting->attendances as $attendance)
                                <tr>
                                    <td>{{ $attendance->user->name }}</td>
                                    <td>
                                        <span class="badge
                                            {{ $attendance->status === 'attending' ? 'bg-success' :
                                               ($attendance->status === 'not_attending' ? 'bg-danger' :
                                               ($attendance->status === 'maybe' ? 'bg-warning' : 'bg-secondary')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                                        </span>
                                    </td>
                                    <td>{{ $attendance->notes ?: '—' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">{{ __('No attendees have been invited yet.') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('meetings.index') }}" class="btn btn-secondary">{{ __('Back to Meetings') }}</a>
                        <a href="{{ route('meetings.edit', $meeting) }}" class="btn btn-warning">{{ __('Edit Meeting') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
