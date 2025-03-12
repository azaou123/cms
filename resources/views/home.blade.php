@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <h4>{{ __('General Club Information') }}</h4>
                        <ul>
                            <li><strong>{{ __('Club Name') }}:</strong> My Club</li>
                            <li><strong>{{ __('Total Members') }}:</strong> 150</li>
                            <li><strong>{{ __('Upcoming Events') }}:</strong> 3 events</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h4>{{ __('Club Statistics') }}</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <canvas id="membersChart"></canvas>
                            </div>
                            <div class="col-md-6">
                                <canvas id="eventsChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <p>{{ __('You are logged in!') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Example chart for members
    var ctx1 = document.getElementById('membersChart').getContext('2d');
    var membersChart = new Chart(ctx1, {
        type: 'pie',
        data: {
            labels: ['Active', 'Inactive'],
            datasets: [{
                label: 'Members',
                data: [120, 30], // Example data, adjust with actual numbers
                backgroundColor: ['#28a745', '#dc3545'],
                borderColor: ['#ffffff', '#ffffff'],
                borderWidth: 1
            }]
        }
    });

    // Example chart for events
    var ctx2 = document.getElementById('eventsChart').getContext('2d');
    var eventsChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ['Upcoming', 'Completed'],
            datasets: [{
                label: 'Events',
                data: [3, 10], // Example data, adjust with actual numbers
                backgroundColor: '#007bff',
                borderColor: '#ffffff',
                borderWidth: 1
            }]
        }
    });
</script>
@endsection
@endsection
