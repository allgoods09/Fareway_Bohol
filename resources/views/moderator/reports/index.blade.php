{{-- resources/views/moderator/reports/index.blade.php --}}
@extends('layouts.moderator')

@section('title', 'User Reports')

@section('content')
<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">User Reports</h1>
        <p class="text-gray-500 text-sm mt-1">Review and manage user feedback and issue reports</p>
    </div>

    <!-- Status Filter Tabs -->
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('moderator.reports.index', ['status' => 'all']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition-all
                  {{ $status == 'all' ? 'bg-gray-800 text-white shadow' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            All
        </a>
        <a href="{{ route('moderator.reports.index', ['status' => 'pending']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition-all
                  {{ $status == 'pending' ? 'bg-amber-500 text-white shadow' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
            Pending
        </a>
        <a href="{{ route('moderator.reports.index', ['status' => 'in_progress']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition-all
                  {{ $status == 'in_progress' ? 'bg-blue-500 text-white shadow' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
            In Progress
        </a>
        <a href="{{ route('moderator.reports.index', ['status' => 'resolved']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition-all
                  {{ $status == 'resolved' ? 'bg-emerald-500 text-white shadow' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
            Resolved
        </a>
        <a href="{{ route('moderator.reports.index', ['status' => 'rejected']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition-all
                  {{ $status == 'rejected' ? 'bg-red-500 text-white shadow' : 'bg-red-50 text-red-700 hover:bg-red-100' }}">
            Rejected
        </a>
    </div>

    <!-- Reports Table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Reported</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($reports as $report)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-3 text-sm font-medium text-gray-900">#{{ $report->id }}</td>
                        <td class="px-6 py-3">
                            <div class="text-sm font-medium text-gray-900">{{ $report->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $report->user->email }}</div>
                        </td>
                        <td class="px-6 py-3 text-sm">
                            @php
                                $typeStyles = [
                                    'wrong_fare' => 'bg-red-100 text-red-700',
                                    'road_closure' => 'bg-amber-100 text-amber-700',
                                    'vehicle_unavailable' => 'bg-orange-100 text-orange-700',
                                    'technical_issue' => 'bg-purple-100 text-purple-700',
                                    'other' => 'bg-gray-100 text-gray-700'
                                ];
                                $typeStyle = $typeStyles[$report->type] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="inline-flex px-2 py-1 rounded-lg text-xs font-medium {{ $typeStyle }}">
                                {{ str_replace('_', ' ', ucfirst($report->type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-600 max-w-xs">
                            {{ Str::limit($report->description, 60) }}
                        </td>
                        <td class="px-6 py-3 text-sm">
                            @php
                                $statusStyles = [
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'in_progress' => 'bg-blue-100 text-blue-700',
                                    'resolved' => 'bg-emerald-100 text-emerald-700',
                                    'rejected' => 'bg-red-100 text-red-700'
                                ];
                                $statusStyle = $statusStyles[$report->status] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="inline-flex px-2 py-1 rounded-lg text-xs font-medium {{ $statusStyle }}">
                                {{ ucfirst($report->status) }}
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
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl text-gray-300 mb-2 block"></i>
                            No reports found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $reports->links() }}
        </div>
    </div>
</div>
@endsection