{{-- resources/views/admin/reports/index.blade.php --}}
@extends('layouts.admin')

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
        <a href="{{ route('admin.reports.index', ['status' => 'all']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition-all
                  {{ $status == 'all' ? 'bg-gray-800 text-white shadow' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            All
        </a>
        <a href="{{ route('admin.reports.index', ['status' => 'pending']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition-all
                  {{ $status == 'pending' ? 'bg-amber-500 text-white shadow' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
            Pending
        </a>
        <a href="{{ route('admin.reports.index', ['status' => 'in_progress']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition-all
                  {{ $status == 'in_progress' ? 'bg-blue-500 text-white shadow' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
            In Progress
        </a>
        <a href="{{ route('admin.reports.index', ['status' => 'resolved']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition-all
                  {{ $status == 'resolved' ? 'bg-emerald-500 text-white shadow' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
            Resolved
        </a>
        <a href="{{ route('admin.reports.index', ['status' => 'rejected']) }}" 
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
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-8">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        </th>
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
                        <td class="px-4 py-3">
                            <input type="checkbox" class="report-checkbox rounded border-gray-300 text-emerald-600 focus:ring-emerald-500" 
                                   value="{{ $report->id }}">
                        </td>
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
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.reports.show', $report) }}" 
                                   class="text-emerald-600 hover:text-emerald-800 transition" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" 
                                        class="text-red-600 hover:text-red-800 transition delete-btn" 
                                        data-id="{{ $report->id }}" 
                                        title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl text-gray-300 mb-2 block"></i>
                            No reports found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Bulk Actions & Pagination -->
        @if($reports->count() > 0)
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <select id="bulk-action" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Bulk Actions</option>
                    <option value="in_progress">Mark In Progress</option>
                    <option value="resolved">Mark Resolved</option>
                    <option value="rejected">Mark Rejected</option>
                </select>
                <button id="apply-bulk" 
                        class="px-4 py-2 bg-emerald-500 text-white rounded-lg text-sm font-medium hover:bg-emerald-600 transition">
                    Apply
                </button>
            </div>
            {{ $reports->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Delete Form -->
<form id="delete-form" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
    // Delete single report
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            if(confirm('Are you sure you want to delete this report?')) {
                const form = document.getElementById('delete-form');
                form.action = `/admin/reports/${id}`;
                form.submit();
            }
        });
    });

    // Select all functionality
    const selectAllCheckbox = document.getElementById('select-all');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            document.querySelectorAll('.report-checkbox').forEach(cb => cb.checked = this.checked);
        });
    }

    // Bulk action
    const applyBulkBtn = document.getElementById('apply-bulk');
    if (applyBulkBtn) {
        applyBulkBtn.addEventListener('click', function() {
            const selected = Array.from(document.querySelectorAll('.report-checkbox:checked')).map(cb => cb.value);
            const action = document.getElementById('bulk-action').value;
            
            if(selected.length === 0) {
                alert('Please select at least one report.');
                return;
            }
            
            if(!action) {
                alert('Please select an action.');
                return;
            }
            
            if(confirm(`Apply "${action}" to ${selected.length} report(s)?`)) {
                fetch('{{ route("admin.reports.bulk-update") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        reports: selected,
                        status: action
                    })
                }).then(response => response.json()).then(data => {
                    if(data.success) {
                        location.reload();
                    }
                });
            }
        });
    }
</script>
@endpush
@endsection