@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Projects</h5>
                    <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm">New Project</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Cell</th>
                                    <th>Manager</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Team Size</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($projects as $project)
                                    <tr>
                                        <td>{{ $project->name }}</td>
                                        <td>{{ $project->cell->name }}</td>
                                        <td>
                                            @php
                                                $manager = $project->users()->wherePivot('is_manager', true)->first();
                                            @endphp
                                            {{ $manager ? $manager->name : 'No manager assigned' }}
                                        </td>
                                        <td>{{ $project->start_date->format('M d, Y') }}</td>
                                        <td>{{ $project->end_date ? $project->end_date->format('M d, Y') : 'Not set' }}</td>
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
                                        <td>{{ $project->users->count() }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('projects.show', $project) }}" class="btn btn-sm btn-info">View</a>
                                                <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-primary">Edit</a>
                                                <form action="{{ route('projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this project?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No projects found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection