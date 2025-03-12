@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $project->name }}</h5>
                    <div>
                        <a href="{{ route('projects.edit', $project) }}" class="btn btn-primary btn-sm me-2">Edit</a>
                        <a href="{{ route('projects.index') }}" class="btn btn-secondary btn-sm">Back to Projects</a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Project Details</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="150">Cell</th>
                                        <td>{{ $project->cell->name }}</td>
                                        <th width="150">Status</th>
                                        <td>
                                            <span class="badge bg-{{ 
                                                $project->status == 'planning' ? 'secondary' : 
                                                ($project->status == 'in_progress' ? 'primary' : 
                                                ($project->status == 'on_hold' ? 'warning' : 
                                                ($project->status == 'completed' ? 'success' : 'dark'))) 
                                            }}">
                                                {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Start Date</th>
                                        <td>{{ $project->start_date->format('M d, Y') }}</td>
                                        <th>End Date</th>
                                        <td>{{ $project->end_date ? $project->end_date->format('M d, Y') : 'Not set' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Budget</th>
                                        <td>{{ $project->budget ? '$' . number_format($project->budget, 2) : 'Not set' }}</td>
                                        <th>Created</th>
                                        <td>{{ $project->created_at->format('M d, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Description</th>
                                        <td colspan="3">{{ $project->description ?: 'No description provided' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Project Manager</h6>
                                </div>
                                <div class="card-body">
                                    @php
                                    $manager = $project->users()->wherePivot('is_manager', true)->first();
                                    @endphp
                                    @if($manager)
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            @if($manager->profile_picture)
                                            <img src="{{ asset('storage/' . $manager->profile_picture) }}" alt="Profile" class="rounded-circle" width="60">
                                            @else
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                <span style="font-size: 24px;">{{ substr($manager->name, 0, 1) }}</span>
                                            </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h5 class="mb-1">{{ $manager->name }}</h5>
                                            <p class="mb-0 small text-muted">{{ $manager->email }}</p>
                                            <p class="mb-0 small text-muted">
                                                Manager since: {{ \Carbon\Carbon::parse($manager->pivot->assigned_at)->format('M d, Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    @else
                                    <p class="text-center text-muted">No manager assigned</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Team Members</h6>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addTeamMemberModal">
                                        Add Member
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Role</th>
                                                    <th>Since</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                $members = $project->users()->wherePivot('is_manager', false)->get();
                                                @endphp
                                                @forelse($members as $member)
                                                <tr>
                                                    <td>{{ $member->name }}</td>
                                                    <td>{{ ucfirst($member->pivot->role) }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($member->pivot->assigned_at)->format('M d, Y') }}</td>
                                                    <td>
                                                        <form action="{{ route('projects.edit', $project) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="user_id" value="{{ $member->id }}">
                                                            <input type="hidden" name="action" value="remove">
                                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to remove this member?')">
                                                                Remove
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">No team members assigned</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add more sections for tasks, meetings, documents, etc. -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Team Member Modal -->
<div class="modal fade" id="addTeamMemberModal" tabindex="-1" aria-labelledby="addTeamMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('projects.updateTeam', $project) }}" method="POST">
                @csrf
                @method('PUT') <!-- Simulate PUT method -->
                <input type="hidden" name="action" value="add">

                <div class="modal-header">
                    <h5 class="modal-title" id="addTeamMemberModalLabel">Add Team Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Select User</label>
                        <select class="form-select" id="user_id" name="user_id" required>
                            <option value="">Select User</option>
                            @foreach($usersNotInProject as $user)
                            @php
                            $isAlreadyMember = $project->users->contains($user->id);
                            @endphp
                            <option value="{{ $user->id }}" {{ $isAlreadyMember ? 'disabled' : '' }}>
                                {{ $user->name }} {{ $isAlreadyMember ? '(Already a member)' : '' }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="member">Member</option>
                            <option value="manager">Manager</option>
                            <option value="viewer">Viewer</option>
                        </select>
                        <div class="form-text">
                            Note: Assigning a new manager will replace the current manager.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Member</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection