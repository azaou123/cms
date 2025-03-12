@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Upcoming Events') }}</span>
                    <div>
                        <a href="{{ route('events.create') }}" class="btn btn-primary btn-sm">{{ __('Create New Event') }}</a>
                        <a href="{{ route('events.my') }}" class="btn btn-success btn-sm">{{ __('My Registered Events') }}</a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (count($events) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Location') }}</th>
                                        <th>{{ __('Cell') }}</th>
                                        <th>{{ __('Type') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($events as $event)
                                        <tr>
                                            <td>{{ $event->title }}</td>
                                            <td>
                                                {{ $event->start_date->format('M d, Y') }}
                                                @if($event->start_date->format('Y-m-d') != $event->end_date->format('Y-m-d'))
                                                    - {{ $event->end_date->format('M d, Y') }}
                                                @endif
                                            </td>
                                            <td>{{ $event->location ?? 'N/A' }}</td>
                                            <td>{{ $event->cell->name ?? 'N/A' }}</td>
                                            <td>{{ ucfirst($event->type) }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('events.show', $event) }}" class="btn btn-info btn-sm">{{ __('View') }}</a>
                                                    <a href="{{ route('events.edit', $event) }}" class="btn btn-warning btn-sm">{{ __('Edit') }}</a>
                                                    <form action="{{ route('events.destroy', $event) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this event?');">
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
                        <div class="mt-3">
                            {{ $events->links() }}
                        </div>
                    @else
                        <p class="text-center">{{ __('No events found. Create your first event!') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
