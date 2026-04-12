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
            
            <button id="export-csv-btn" 
                    data-month="{{ $selectedMonth }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600 transition">
                <i class="fas fa-file-csv"></i> Export CSV
            </button>
            
            <button id="export-pdf-btn" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 text-white rounded-lg text-sm font-medium hover:bg-red-600 transition">
                <i class="fas fa-file-pdf"></i> Export PDF
            </button>
        </div>
    </div>

    <!-- Stats Grid - Expanded -->
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

    <!-- Additional Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- New Users This Month -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">New Users</p>
                    <p class="text-3xl font-bold text-gray-800">{{ number_format($newUsersThisMonth ?? 0) }}</p>
                    <p class="text-xs text-blue-600 mt-1">This month</p>
                </div>
                <div class="w-12 h-12 bg-sky-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-plus text-sky-500 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Users -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Active Users</p>
                    <p class="text-3xl font-bold text-gray-800">{{ number_format($activeUsers ?? 0) }}</p>
                    <p class="text-xs text-emerald-600 mt-1">Made at least 1 search</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-check text-green-500 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Engagement Rate -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Engagement Rate</p>
                    <p class="text-3xl font-bold text-gray-800">{{ number_format($engagementRate ?? 0, 1) }}%</p>
                    <p class="text-xs text-amber-600 mt-1">Users who saved routes</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-simple text-amber-500 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Most Active Hour -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Peak Hour</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $peakHour ?? 'N/A' }}</p>
                    <p class="text-xs text-purple-600 mt-1">Most searches</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-purple-500 text-xl"></i>
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

    <!-- Report Type Distribution -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-chart-pie text-emerald-500"></i>
                    Report Types Distribution
                </h2>
                <p class="text-gray-500 text-sm mt-0.5">Breakdown of user reports by type</p>
            </div>
            <div class="p-6">
                <canvas id="reportTypeChart" height="200"></canvas>
            </div>
        </div>

        <!-- Vehicle Type Usage Chart -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-chart-bar text-emerald-500"></i>
                    Vehicle Type Usage
                </h2>
                <p class="text-gray-500 text-sm mt-0.5">Number of searches per vehicle type</p>
            </div>
            <div class="p-6">
                <canvas id="vehicleUsageChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Hourly Activity Heatmap -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-chart-line text-emerald-500"></i>
                Hourly Activity
            </h2>
            <p class="text-gray-500 text-sm mt-0.5">Search volume by hour of day</p>
        </div>
        <div class="p-6">
            <canvas id="hourlyChart" height="100"></canvas>
        </div>
    </div>
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

// Report Type Distribution Chart
@if(isset($reportTypeDistribution))
const ctx3 = document.getElementById('reportTypeChart').getContext('2d');
new Chart(ctx3, {
    type: 'doughnut',
    data: {
        labels: @json(array_keys($reportTypeDistribution)),
        datasets: [{
            data: @json(array_values($reportTypeDistribution)),
            backgroundColor: ['#ef4444', '#f59e0b', '#10b981', '#8b5cf6', '#6b7280'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});
@endif

// Hourly Activity Chart
@if(isset($hourlyActivity))
const ctx4 = document.getElementById('hourlyChart').getContext('2d');
new Chart(ctx4, {
    type: 'line',
    data: {
        labels: @json(array_keys($hourlyActivity)),
        datasets: [{
            label: 'Searches',
            data: @json(array_values($hourlyActivity)),
            borderColor: '#8b5cf6',
            backgroundColor: 'rgba(139, 92, 246, 0.1)',
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#8b5cf6'
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
                grid: {
                    color: '#e5e7eb'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Hour of Day (24h format)'
                }
            }
        }
    }
});
@endif

// PDF Export with Toast
document.getElementById('export-pdf-btn').addEventListener('click', function() {
    const btn = this;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
    btn.disabled = true;
    
    const month = document.querySelector('select[name="month"]').value;
    
    fetch(`{{ route('admin.analytics.export-pdf') }}?month=${month}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.blob())
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `fareway-analytics-${month}.pdf`;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        a.remove();
        
        showToast('PDF report exported successfully!', 'success');
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error generating PDF report', 'error');
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
});

document.querySelector('a[href*="export-csv"]')?.addEventListener('click', function(e) {
    // For regular link, no toast needed - browser handles download
    // This is just a normal download link, so no extra code needed
});

document.getElementById('export-csv-btn')?.addEventListener('click', function() {
    const btn = this;
    const month = btn.dataset.month;
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
    btn.disabled = true;
    
    fetch(`{{ route('admin.analytics.export-csv') }}?month=${month}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Export failed');
        return response.blob();
    })
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `fareway-searches-${month}.csv`;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        a.remove();
        
        showToast('CSV report exported successfully!', 'success');
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error generating CSV report', 'error');
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
});
</script>
@endpush
@endsection