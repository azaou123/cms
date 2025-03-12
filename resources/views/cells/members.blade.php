@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Manage Members') }} - {{ $cell->name }}</span>
                    <a href="{{ route('cells.show', $cell) }}" class="btn btn-secondary btn-sm">{{ __('Back to Cell') }}</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                    @endif

                    <div class="row">
                        <!-- Current Members -->
                        <div class="col-md-7">
                            <h5>{{ __('Current Members') }}</h5>
                            @if (count($members) > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Email') }}</th>
                                            <th>{{ __('Role') }}</th>
                                            <th>{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($members as $member)
                                        <tr>
                                            <td>{{ $member->name }}</td>
                                            <td>{{ $member->email }}</td>
                                            <td>
                                                <form action="{{ route('cells.members.update', [$cell, $member->id]) }}" method="POST" class="d-flex">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="role" class="form-select form-select-sm me-2" style="width: 120px;">
                                                        <option value="leader" {{ $member->pivot->role === 'leader' ? 'selected' : '' }}>Leader</option>
                                                        <option value="secretary" {{ $member->pivot->role === 'secretary' ? 'selected' : '' }}>Secretary</option>
                                                        <option value="member" {{ $member->pivot->role === 'member' ? 'selected' : '' }}>Member</option>
                                                    </select>
                                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-save"></i> <!-- Save/Update Icon -->
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <form action="{{ route('cells.members.remove', [$cell, $member->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to remove this member?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-trash"></i> <!-- Trash/Remove Icon -->
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <form action="{{ route('conversations.start-with-user') }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="{{ $member->id }}">
                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-chat-dots"></i> <!-- Chat Icon -->
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $member->isOnline() ? 'success' : 'secondary' }}">
                                                    {{ $member->isOnline() ? 'Online' : 'Offline' }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                            @else
                            <p>{{ __('No members found.') }}</p>
                            @endif
                        </div>

                        <!-- Add New Member -->
                        <div class="col-md-5">
                            <div class="card">
                                <div class="card-header">{{ __('Add New Member') }}</div>
                                <div class="card-body">
                                    @if (count($users) > 0)
                                    <form action="{{ route('cells.members.add', $cell) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="user_id" class="form-label">{{ __('Select User') }}</label>
                                            <select id="user_id" name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                                                <option value="">{{ __('-- Select User --') }}</option>
                                                @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                                @endforeach
                                            </select>
                                            @error('user_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="role" class="form-label">{{ __('Assign Role') }}</label>
                                            <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                                                <option value="leader">Leader</option>
                                                <option value="secretary">Secretary</option>
                                                <option value="member" selected>Member</option>
                                            </select>
                                            @error('role')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn btn-primary">{{ __('Add Member') }}</button>
                                    </form>
                                    @else
                                    <p>{{ __('All users are already members of this cell.') }}</p>
                                    @endif
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