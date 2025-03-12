@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('All Cells') }}</span>
                    <a href="{{ route('cells.create') }}" class="btn btn-primary btn-sm">{{ __('Create New Cell') }}</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (count($cells) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Description') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Members') }}</th>
                                        <th>{{ __('Created By') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cells as $cell)
                                        <tr>
                                            <td>{{ $cell->name }}</td>
                                            <td>{{ Str::limit($cell->description, 50) }}</td>
                                            <td>
                                                <span class="badge {{ $cell->status === 'active' ? 'bg-success' : ($cell->status === 'inactive' ? 'bg-warning' : 'bg-secondary') }}">
                                                    {{ ucfirst($cell->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $cell->members->count() }}</td>
                                            <td>{{ $cell->creator->name }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('cells.show', $cell) }}" class="btn btn-info btn-sm">{{ __('View') }}</a>
                                                    <a href="{{ route('cells.edit', $cell) }}" class="btn btn-warning btn-sm">{{ __('Edit') }}</a>
                                                    <a href="{{ route('cells.members', $cell) }}" class="btn btn-secondary btn-sm">{{ __('Members') }}</a>
                                                    <form action="{{ route('cells.destroy', $cell) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this cell?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">{{ __('Delete') }}</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center">{{ __('No cells found. Create your first cell!') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection