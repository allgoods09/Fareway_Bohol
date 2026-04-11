{{-- resources/views/moderator/reports/show.blade.php --}}
@extends('layouts.moderator')

@section('title', 'Report Details')

@section('content')
<div>
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Report #{{ $report->id }}</h1>
            <p class="text-gray-500 text-sm mt-1">Review and update report status</p>
        </div>
        <a href="{{ route('moderator.reports.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
            <i class="fas fa-arrow-left"></i> Back to Reports
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Report Details Card -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-file-alt text-emerald-500"></i>
                        Report Details
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Report Type</label>
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
                            <div class="mt-1">
                                <span class="inline-flex px-3 py-1 rounded-lg text-sm font-medium {{ $typeStyle }}">
                                    {{ str_replace('_', ' ', ucfirst($report->type)) }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</label>
                            @php
                                $statusStyles = [
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'in_progress' => 'bg-blue-100 text-blue-700',
                                    'resolved' => 'bg-emerald-100 text-emerald-700',
                                    'rejected' => 'bg-red-100 text-red-700'
                                ];
                                $statusStyle = $statusStyles[$report->status] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <div class="mt-1">
                                <span class="inline-flex px-3 py-1 rounded-lg text-sm font-medium {{ $statusStyle }}">
                                    {{ ucfirst($report->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Description</label>
                        <div class="mt-2 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $report->description }}</p>
                        </div>
                    </div>

                    @if($report->origin_lat && $report->origin_lng)
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Route Location</label>
                        <div class="mt-2 grid grid-cols-2 gap-4">
                            <div class="p-3 bg-green-50 rounded-lg border border-green-200">
                                <div class="text-xs text-green-600 font-medium">Origin</div>
                                <div class="text-sm text-gray-700">{{ $report->origin_lat }}, {{ $report->origin_lng }}</div>
                            </div>
                            <div class="p-3 bg-red-50 rounded-lg border border-red-200">
                                <div class="text-xs text-red-600 font-medium">Destination</div>
                                <div class="text-sm text-gray-700">{{ $report->dest_lat }}, {{ $report->dest_lng }}</div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4 pt-2">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Reported By</label>
                            <div class="mt-1 text-sm font-medium text-gray-900">{{ $report->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $report->user->email }}</div>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Reported On</label>
                            <div class="mt-1 text-sm text-gray-700">{{ $report->created_at->format('F d, Y h:i A') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admin Notes Card -->
            @if($report->admin_notes)
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-sticky-note text-emerald-500"></i>
                        Moderator Notes
                    </h2>
                </div>
                <div class="p-6">
                    <p class="text-gray-700">{{ $report->admin_notes }}</p>
                    @if($report->resolved_by)
                        <div class="mt-3 text-xs text-gray-500">
                            Updated by {{ $report->resolver->name }} on {{ $report->resolved_at->format('F d, Y h:i A') }}
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Update Status Card -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden sticky top-6">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-tasks text-emerald-500"></i>
                        Update Status
                    </h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('moderator.reports.update-status', $report) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" id="status" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ $report->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ $report->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="rejected" {{ $report->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">Moderator Notes</label>
                            <textarea name="admin_notes" id="admin_notes" rows="5" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                      placeholder="Add notes about this report...">{{ $report->admin_notes }}</textarea>
                        </div>

                        <button type="submit" 
                                class="w-full px-4 py-2 bg-emerald-500 text-white rounded-lg font-medium hover:bg-emerald-600 transition">
                            <i class="fas fa-save mr-2"></i> Update Report
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection