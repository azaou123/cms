@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ $cell->name }}</span>
                    <div>
                        <a href="{{ route('cells.edit', $cell) }}" class="btn btn-warning btn-sm">{{ __('Edit') }}</a>
                        <a href="{{ route('cells.members', $cell) }}" class="btn btn-info btn-sm">{{ __('Manage Members') }}</a>
                        <a href="{{ route('cells.index') }}" class="btn btn-secondary btn-sm">{{ __('Back to Cells') }}</a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-8">
                            <h5>{{ __('Description') }}</h5>
                            <p>{{ $cell->description ?? 'No description provided.' }}</p>
                            
                            <h5 class="mt-4">{{ __('Details') }}</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">{{ __('Status') }}</th>
                                    <td>
                                        <span class="badge {{ $cell->status === 'active' ? 'bg-success' : ($cell->status === 'inactive' ? 'bg-warning' : 'bg-secondary') }}">
                                            {{ ucfirst($cell->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('Created By') }}</th>
                                    <td>{{ $cell->creator->name }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Created At') }}</th>
                                    <td>{{ $cell->created_at->format('F d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Last Updated') }}</th>
                                    <td>{{ $cell->updated_at->format('F d, Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">{{ __('Cell Members') }} ({{ $members->count() }})</div>
                                <div class="card-body p-0">
                                    <ul class="list-group list-group-flush">
                                        @forelse ($members as $member)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $member->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ ucfirst($member->pivot->role) }}</small>
                                                </div>
                                            </li>
                                        @empty
                                            <li class="list-group-item">{{ __('No members yet') }}</li>
                                        @endforelse
                                    </ul>
                                </div>
                                <div class="card-footer text-center">
                                    <a href="{{ route('cells.members', $cell) }}" class="btn btn-sm btn-primary">{{ __('Manage Members') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection