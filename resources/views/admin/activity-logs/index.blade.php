{{-- resources/views/admin/activity-logs/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Activity Logs')

@section('content')
<div>
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Activity Logs</h1>
            <p class="text-gray-500 text-sm mt-1">Track all user and admin actions</p>
        </div>
        <div class="flex gap-3">
            <form method="GET" class="flex gap-2">
                <select name="action" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <option value="">All Actions</option>
                    <option value="login">Login</option>
                    <option value="logout">Logout</option>
                    <option value="route_search">Route Search</option>
                    <option value="save_route">Save Route</option>
                    <option value="create_vehicle">Create Vehicle</option>
                    <option value="update_vehicle">Update Vehicle</option>
                    <option value="delete_vehicle">Delete Vehicle</option>
                    <option value="update_report">Update Report</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-emerald-500 text-white rounded-lg text-sm font-medium hover:bg-emerald-600 transition">
                    Filter
                </button>
            </form>
        </div>
    </div>

    <!-- Activity Logs Table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Details</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3 text-sm text-gray-600">
                            {{ $log->created_at->format('M d, Y h:i A') }}
                        </td>
                        <td class="px-6 py-3 text-sm font-medium text-gray-900">
                            {{ $log->user_name ?? 'System' }}
                        </td>
                        <td class="px-6 py-3 text-sm">
                            @if($log->user_role)
                                <span class="inline-flex px-2 py-1 rounded-lg text-xs font-medium 
                                    {{ $log->user_role == 'admin' ? 'bg-red-100 text-red-700' : 
                                       ($log->user_role == 'moderator' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700') }}">
                                    {{ ucfirst($log->user_role) }}
                                </span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-sm">
                            <span class="inline-flex px-2 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-700">
                                {{ str_replace('_', ' ', ucfirst($log->action)) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-600 max-w-md">
                            {{ $log->details ?? '—' }}
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-500 font-mono text-xs">
                            {{ $log->ip_address ?? '—' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-history text-3xl text-gray-300 mb-2 block"></i>
                            No activity logs found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection