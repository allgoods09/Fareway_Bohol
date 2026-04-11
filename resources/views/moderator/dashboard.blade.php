{{-- resources/views/moderator/dashboard.blade.php --}}
@extends('layouts.moderator')

@section('title', 'Dashboard')

@section('content')
<div>
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Moderator Dashboard</h1>
        <p class="text-gray-500 text-sm mt-1">Welcome back, {{ auth()->user()->name }}!</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Reports -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Total Reports</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalReports }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-flag text-red-500 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Pending Reports -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Pending Reports</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $pendingReports }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-amber-500 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Tourist Spots -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Tourist Spots</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalPlaces }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-map-marker-alt text-emerald-500 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Vehicles -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Active Vehicles</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $activeVehicles }}</p>
                </div>
                <div class="w-12 h-12 bg-sky-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-bus text-sky-500 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Reports Table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-flag text-red-500"></i>
                Recent Reports
            </h2>
            <p class="text-gray-500 text-sm mt-0.5">Latest user reports awaiting review</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Reported</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($recentReports as $report)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-3 text-sm font-medium text-gray-900">#{{ $report->id }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ $report->user->name }}</td>
                        <td class="px-6 py-3 text-sm">
                            @php
                                $typeColors = [
                                    'wrong_fare' => 'bg-red-100 text-red-700',
                                    'road_closure' => 'bg-amber-100 text-amber-700',
                                    'vehicle_unavailable' => 'bg-orange-100 text-orange-700',
                                    'technical_issue' => 'bg-purple-100 text-purple-700',
                                    'other' => 'bg-gray-100 text-gray-700'
                                ];
                                $typeColor = $typeColors[$report->type] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="px-2 py-1 rounded-lg text-xs font-medium {{ $typeColor }}">
                                {{ str_replace('_', ' ', ucfirst($report->type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-sm">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'in_progress' => 'bg-blue-100 text-blue-700',
                                    'resolved' => 'bg-emerald-100 text-emerald-700',
                                    'rejected' => 'bg-red-100 text-red-700'
                                ];
                                $statusColor = $statusColors[$report->status] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="px-2 py-1 rounded-lg text-xs font-medium {{ $statusColor }}">
                                {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-500">
                            {{ $report->created_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-3 text-sm">
                            <a href="{{ route('moderator.reports.show', $report) }}" 
                               class="inline-flex items-center gap-1 text-emerald-600 hover:text-emerald-800 font-medium transition">
                                <i class="fas fa-eye text-xs"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl text-gray-300 mb-2 block"></i>
                            No reports found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
        <div class="bg-gradient-to-r from-emerald-50 to-emerald-100 rounded-xl border border-emerald-200 p-5">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-tag text-white text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-800">Manage Fare Rates</h3>
                    <p class="text-sm text-gray-600">Review and update transport fare regulations</p>
                </div>
                <a href="{{ route('moderator.fare-rates.index') }}" 
                   class="px-4 py-2 bg-white text-emerald-700 rounded-lg text-sm font-medium hover:bg-emerald-50 transition shadow-sm">
                    Go <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-amber-50 to-amber-100 rounded-xl border border-amber-200 p-5">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-map-marker-alt text-white text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-800">Manage Tourist Spots</h3>
                    <p class="text-sm text-gray-600">Add or update popular destinations</p>
                </div>
                <a href="{{ route('moderator.recommended-places.index') }}" 
                   class="px-4 py-2 bg-white text-amber-700 rounded-lg text-sm font-medium hover:bg-amber-50 transition shadow-sm">
                    Go <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection