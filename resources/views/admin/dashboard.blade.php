{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div>
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-gray-500 text-sm mt-1">Welcome back, {{ auth()->user()->name }}!</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Total Users</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalUsers }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $totalModerators }} moderators</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-blue-500 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Reports -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Total Reports</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalReports }}</p>
                    <p class="text-xs text-amber-600 mt-1">{{ $pendingReports }} pending</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-flag text-red-500 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Route Searches -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Route Searches</p>
                    <p class="text-3xl font-bold text-gray-800">{{ number_format($totalSearches) }}</p>
                    <p class="text-xs text-emerald-600 mt-1">{{ number_format($todaySearches) }} today</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-route text-emerald-500 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Vehicles -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Active Vehicles</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $activeVehicles ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-bus text-purple-500 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-bolt text-amber-500"></i>
                Quick Actions
            </h2>
            <p class="text-gray-500 text-sm mt-0.5">Common administrative tasks</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.fare-rates.create') }}" 
                   class="flex items-center justify-center gap-2 px-4 py-3 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg transition group">
                    <i class="fas fa-plus text-emerald-500 group-hover:scale-110 transition"></i>
                    <span class="text-sm font-medium text-gray-700">Add Vehicle Type</span>
                </a>
                <a href="{{ route('admin.recommended-places.create') }}" 
                   class="flex items-center justify-center gap-2 px-4 py-3 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg transition group">
                    <i class="fas fa-map-marker-alt text-emerald-500 group-hover:scale-110 transition"></i>
                    <span class="text-sm font-medium text-gray-700">Add Tourist Spot</span>
                </a>
                <a href="{{ route('admin.users.create') }}" 
                   class="flex items-center justify-center gap-2 px-4 py-3 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg transition group">
                    <i class="fas fa-user-plus text-emerald-500 group-hover:scale-110 transition"></i>
                    <span class="text-sm font-medium text-gray-700">Add User</span>
                </a>
                <a href="{{ route('admin.reports.index') }}?status=pending" 
                   class="flex items-center justify-center gap-2 px-4 py-3 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg transition group">
                    <i class="fas fa-clock text-amber-500 group-hover:scale-110 transition"></i>
                    <span class="text-sm font-medium text-gray-700">Review Reports</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-user-plus text-blue-500"></i>
                    Recent Users
                </h2>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentUsers ?? [] as $user)
                <div class="px-6 py-3 flex items-center justify-between hover:bg-gray-50 transition">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                    </div>
                    <span class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</span>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="fas fa-user-slash text-2xl text-gray-300 mb-2 block"></i>
                    No recent users
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Reports -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-flag text-red-500"></i>
                    Recent Reports
                </h2>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentReports ?? [] as $report)
                <div class="px-6 py-3 flex items-center justify-between hover:bg-gray-50 transition">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $report->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ Str::limit($report->description, 50) }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex px-2 py-1 rounded-lg text-xs font-medium
                            {{ $report->status == 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                            {{ $report->status == 'in_progress' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $report->status == 'resolved' ? 'bg-emerald-100 text-emerald-700' : '' }}">
                            {{ ucfirst($report->status) }}
                        </span>
                        <a href="{{ route('admin.reports.show', $report) }}" 
                           class="text-emerald-600 hover:text-emerald-800 transition">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="fas fa-inbox text-2xl text-gray-300 mb-2 block"></i>
                    No recent reports
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection