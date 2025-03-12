@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Create New Project</h5>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('projects.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Project Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date') }}">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="planning" {{ old('status') == 'planning' ? 'selected' : '' }}>Planning</option>
                                    <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="on_hold" {{ old('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="budget" class="form-label">Budget</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control @error('budget') is-invalid @enderror" id="budget" name="budget" value="{{ old('budget') }}">
                                </div>
                                @error('budget')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="cell_id" class="form-label">Cell <span class="text-danger">*</span></label>
                            <select class="form-select @error('cell_id') is-invalid @enderror" id="cell_id" name="cell_id" required>
                                <option value="">Select Cell</option>
                                @foreach($cells as $cell)
                                    <option value="{{ $cell->id }}" {{ old('cell_id') == $cell->id ? 'selected' : '' }}>
                                        {{ $cell->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cell_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="manager_id" class="form-label">Project Manager <span class="text-danger">*</span></label>
                            <select class="form-select @error('manager_id') is-invalid @enderror" id="manager_id" name="manager_id" required>
                                <option value="">Select Manager</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('manager_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('manager_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="team_members" class="form-label">Team Members</label>
                            <select class="form-select @error('team_members') is-invalid @enderror" id="team_members" name="team_members[]" multiple>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ (is_array(old('team_members')) && in_array($user->id, old('team_members'))) ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Hold Ctrl (or Cmd on Mac) to select multiple members</div>
                            @error('team_members')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('projects.index') }}" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Project</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Optional: Add script to enhance multi-select experience
    document.addEventListener('DOMContentLoaded', function() {
        // You can use a library like Choices.js or Select2 here to enhance the multi-select experience
    });
</script>
@endsection