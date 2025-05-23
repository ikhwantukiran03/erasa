@extends('layouts.app')

@section('title', 'Reports - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="flex mb-5" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-primary inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                        Dashboard
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="ml-1 text-gray-500 md:ml-2">Reports</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Reports & Analytics</h1>
                <p class="text-gray-600 mt-2">View financial and analytics reports for your business</p>
            </div>
        </div>

        <!-- Financial Report Section -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-dark mb-4">Financial Report</h2>
                
                <!-- Monthly Revenue Chart -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Monthly Revenue</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <canvas id="revenueChart" height="100"></canvas>
                    </div>
                </div>

                <!-- Financial Summary Tables -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Monthly Summary -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-700 mb-4">Monthly Summary</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Month</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Invoices</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Average</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($monthlySummary as $summary)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $summary['month'] }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 text-right">{{ $summary['total_invoices'] }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 text-right">RM {{ number_format($summary['total_revenue'], 2) }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 text-right">RM {{ number_format($summary['average_amount'], 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Yearly Summary -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-700 mb-4">Yearly Summary</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Invoices</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Average</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($yearlySummary as $summary)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $summary->year }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 text-right">{{ $summary->total_invoices }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 text-right">RM {{ number_format($summary->total_revenue, 2) }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 text-right">RM {{ number_format($summary->average_amount, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Report Section -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-dark mb-4">Analytics Report</h2>
                
                <!-- Monthly Bookings Chart -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Monthly Bookings</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <canvas id="bookingsChart" height="100"></canvas>
                    </div>
                </div>

                <!-- Top Events Table -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Top 5 Most Ordered Events</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package Name</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Bookings</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($topEvents as $event)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $event->package_name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 text-right">{{ $event->booking_count }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlyRevenue->pluck('month')) !!},
            datasets: [{
                label: 'Monthly Revenue (RM)',
                data: {!! json_encode($monthlyRevenue->pluck('total')) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'RM ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Bookings Chart
    const bookingsCtx = document.getElementById('bookingsChart').getContext('2d');
    new Chart(bookingsCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlyBookings->pluck('month')) !!},
            datasets: [{
                label: 'Monthly Bookings',
                data: {!! json_encode($monthlyBookings->pluck('total')) !!},
                backgroundColor: 'rgba(16, 185, 129, 0.5)',
                borderColor: 'rgb(16, 185, 129)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection 