@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">{{ __('Dashboard') }}</div>
                <div class="card-body">
                    <h4>Club Management Overview</h4>
                    <p>Welcome to your club management dashboard. Here you can view key metrics and statistics about your club.</p>
                </div>
            </div>
            
            <!-- Membership Stats -->
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">Membership Growth</div>
                        <div class="card-body">
                            <canvas id="membershipChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">Member Demographics</div>
                        <div class="card-body">
                            <canvas id="demographicsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Financial Stats -->
            <div class="row">
                <div class="col-md-8 mb-4">
                    <div class="card">
                        <div class="card-header">Revenue & Expenses</div>
                        <div class="card-body">
                            <canvas id="financialChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">Revenue Breakdown</div>
                        <div class="card-body">
                            <canvas id="revenueBreakdownChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Event Stats -->
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">Event Attendance</div>
                        <div class="card-body">
                            <canvas id="eventAttendanceChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">Facility Usage</div>
                        <div class="card-body">
                            <canvas id="facilityUsageChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Fake data for all charts
    document.addEventListener("DOMContentLoaded", function() {
        // ----------------
        // Membership Growth Chart - Line Chart
        // ----------------
        const membershipCtx = document.getElementById('membershipChart').getContext('2d');
        const membershipData = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'New Members',
                data: [15, 12, 20, 18, 24, 30, 22, 25, 18, 16, 21, 27],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                tension: 0.3
            }, {
                label: 'Cancelled Memberships',
                data: [5, 7, 4, 6, 8, 5, 7, 6, 9, 4, 5, 8],
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
                tension: 0.3
            }]
        };
        
        new Chart(membershipCtx, {
            type: 'line',
            data: membershipData,
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Monthly Membership Changes'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Members'
                        }
                    }
                }
            }
        });
        
        // ----------------
        // Demographics - Pie Chart
        // ----------------
        const demographicsCtx = document.getElementById('demographicsChart').getContext('2d');
        const demographicsData = {
            labels: ['18-24', '25-34', '35-44', '45-54', '55-64', '65+'],
            datasets: [{
                label: 'Age Groups',
                data: [15, 30, 25, 20, 7, 3],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)'
                ],
                borderWidth: 1
            }]
        };
        
        new Chart(demographicsCtx, {
            type: 'pie',
            data: demographicsData,
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Member Age Distribution (%)'
                    },
                    legend: {
                        position: 'right'
                    }
                }
            }
        });
        
        // ----------------
        // Financial Data - Bar Chart
        // ----------------
        const financialCtx = document.getElementById('financialChart').getContext('2d');
        const financialData = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Revenue',
                data: [12500, 13200, 14800, 15500, 16200, 18000, 19500, 19000, 17800, 16500, 18200, 20000],
                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }, {
                label: 'Expenses',
                data: [10200, 10500, 11200, 12000, 13500, 14200, 15000, 14800, 14500, 13800, 14600, 15800],
                backgroundColor: 'rgba(255, 99, 132, 0.7)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        };
        
        new Chart(financialCtx, {
            type: 'bar',
            data: financialData,
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Monthly Financial Performance'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Amount ($)'
                        }
                    }
                }
            }
        });
        
        // ----------------
        // Revenue Breakdown - Doughnut Chart
        // ----------------
        const revenueBreakdownCtx = document.getElementById('revenueBreakdownChart').getContext('2d');
        const revenueBreakdownData = {
            labels: ['Membership Fees', 'Events', 'Facility Rentals', 'Merchandise', 'Donations'],
            datasets: [{
                label: 'Revenue Sources',
                data: [65, 15, 10, 5, 5],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)'
                ],
                borderWidth: 1
            }]
        };
        
        new Chart(revenueBreakdownCtx, {
            type: 'doughnut',
            data: revenueBreakdownData,
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Revenue Sources (%)'
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 15
                        }
                    }
                }
            }
        });
        
        // ----------------
        // Event Attendance - Bar Chart
        // ----------------
        const eventAttendanceCtx = document.getElementById('eventAttendanceChart').getContext('2d');
        const eventAttendanceData = {
            labels: ['Annual Gala', 'Summer BBQ', 'Workshop Series', 'Networking Event', 'Holiday Party', 'Sports Tournament'],
            datasets: [{
                label: 'Attendance',
                data: [120, 95, 75, 60, 105, 85],
                backgroundColor: 'rgba(153, 102, 255, 0.7)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }, {
                label: 'Capacity',
                data: [150, 100, 80, 75, 120, 100],
                backgroundColor: 'rgba(201, 203, 207, 0.5)',
                borderColor: 'rgba(201, 203, 207, 1)',
                borderDash: [5, 5],
                type: 'line',
                fill: false
            }]
        };
        
        new Chart(eventAttendanceCtx, {
            type: 'bar',
            data: eventAttendanceData,
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Event Attendance vs. Capacity'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Attendees'
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });
        
        // ----------------
        // Facility Usage - Radar Chart
        // ----------------
        const facilityUsageCtx = document.getElementById('facilityUsageChart').getContext('2d');
        const facilityUsageData = {
            labels: ['Main Hall', 'Meeting Room A', 'Meeting Room B', 'Outdoor Space', 'Fitness Center', 'Lounge'],
            datasets: [{
                label: 'Weekday Usage (%)',
                data: [60, 75, 70, 45, 80, 55],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(54, 162, 235, 1)'
            }, {
                label: 'Weekend Usage (%)',
                data: [85, 55, 50, 90, 65, 75],
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(255, 99, 132, 1)'
            }]
        };
        
        new Chart(facilityUsageCtx, {
            type: 'radar',
            data: facilityUsageData,
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Facility Utilization Rates'
                    }
                },
                scales: {
                    r: {
                        angleLines: {
                            display: true
                        },
                        suggestedMin: 0,
                        suggestedMax: 100
                    }
                }
            }
        });
    });
</script>
@endsection