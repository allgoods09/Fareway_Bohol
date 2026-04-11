{{-- resources/views/admin/analytics/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Analytics')

@section('content')
<div>
    <!-- Header -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Analytics Report</h1>
            <p class="text-gray-500 text-sm mt-1">Monitor system usage and trends</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <form method="GET" action="{{ route('admin.analytics.index') }}" class="flex gap-2">
                <select name="month" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 w-48">
                    @foreach($availableMonths as $month)
                        <option value="{{ $month }}" {{ $selectedMonth == $month ? 'selected' : '' }}>
                            {{ Carbon\Carbon::parse($month . '-01')->format('F Y') }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-emerald-500 text-white rounded-lg text-sm font-medium hover:bg-emerald-600 transition">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </form>
            <a href="{{ route('admin.analytics.export-pdf', ['month' => $selectedMonth]) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 text-white rounded-lg text-sm font-medium hover:bg-red-600 transition">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Route Searches -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Total Route Searches</p>
                    <p class="text-3xl font-bold text-gray-800">{{ number_format($totalSearches) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-route text-blue-500 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Unique Users -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Unique Users</p>
                    <p class="text-3xl font-bold text-gray-800">{{ number_format($uniqueUsers) }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-emerald-500 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Reports -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Total Reports</p>
                    <p class="text-3xl font-bold text-gray-800">{{ number_format($totalReports) }}</p>
                    <p class="text-xs text-emerald-600 mt-1">{{ number_format($resolvedReports) }} resolved</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-flag text-red-500 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Average Distance -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Avg. Distance</p>
                    <p class="text-3xl font-bold text-gray-800">{{ number_format($avgDistance, 2) }} km</p>
                    <p class="text-xs text-gray-500 mt-1">{{ number_format($avgDuration) }} mins avg</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-purple-500 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Daily Trend Chart -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden lg:col-span-2">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-chart-line text-emerald-500"></i>
                    Daily Search Trend
                </h2>
                <p class="text-gray-500 text-sm mt-0.5">Route searches per day for {{ Carbon\Carbon::parse($selectedMonth . '-01')->format('F Y') }}</p>
            </div>
            <div class="p-6">
                <canvas id="dailyTrendChart" height="100"></canvas>
            </div>
        </div>

        <!-- Most Searched Routes -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-route text-emerald-500"></i>
                    Most Searched Routes
                </h2>
                <p class="text-gray-500 text-sm mt-0.5">Top 10 most frequent route searches</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Route</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Searches</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($mostSearchedRoutes as $index => $route)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-3 text-sm text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-6 py-3 text-sm text-gray-600">
                                <code class="text-xs">
                                    {{ number_format($route->origin_lat, 4) }}, {{ number_format($route->origin_lng, 4) }}
                                    <i class="fas fa-arrow-right mx-1 text-gray-400"></i>
                                    {{ number_format($route->dest_lat, 4) }}, {{ number_format($route->dest_lng, 4) }}
                                </code>
                            </td>
                            <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ $route->search_count }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-chart-simple text-2xl text-gray-300 mb-2 block"></i>
                                No route data available
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Popular Destinations -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-emerald-500"></i>
                    Popular Saved Destinations
                </h2>
                <p class="text-gray-500 text-sm mt-0.5">Most bookmarked tourist spots</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Place Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Saves</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($popularDestinations as $index => $place)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-3 text-sm text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ $place->name }}</td>
                            <td class="px-6 py-3 text-sm text-gray-600">{{ $place->saved_routes_count ?? 0 }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-map-pin text-2xl text-gray-300 mb-2 block"></i>
                                No destination data available
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Vehicle Type Usage Chart -->
    @if(count($vehicleUsage) > 0)
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-chart-bar text-emerald-500"></i>
                Vehicle Type Usage
            </h2>
            <p class="text-gray-500 text-sm mt-0.5">Number of searches per vehicle type</p>
        </div>
        <div class="p-6">
            <canvas id="vehicleUsageChart" height="80"></canvas>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Daily Trend Chart
const ctx1 = document.getElementById('dailyTrendChart').getContext('2d');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: @json($dailyTrend->pluck('date')->map(function($date) {
            return \Carbon\Carbon::parse($date)->format('M d');
        })),
        datasets: [{
            label: 'Searches',
            data: @json($dailyTrend->pluck('count')),
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#10b981',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                mode: 'index',
                intersect: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: '#e5e7eb'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

// Vehicle Usage Chart
@if(count($vehicleUsage) > 0)
const ctx2 = document.getElementById('vehicleUsageChart').getContext('2d');
new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: @json(array_keys($vehicleUsage)),
        datasets: [{
            label: 'Number of Searches',
            data: @json(array_values($vehicleUsage)),
            backgroundColor: 'rgba(16, 185, 129, 0.7)',
            borderColor: '#10b981',
            borderWidth: 1,
            borderRadius: 8,
            barPercentage: 0.6,
            categoryPercentage: 0.8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'top',
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                    precision: 0
                },
                grid: {
                    color: '#e5e7eb'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});
@endif
</script>
@endpush
@endsection