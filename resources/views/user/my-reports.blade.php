@extends('layouts.user')

@section('title', 'My Reports')

@section('hero-content')
<div class="hero-content">
    <div class="hero-tag">
        <div class="hero-tag-dot"></div>
        Support Center
    </div>
    <h1>My <span>Reports</span></h1>
    <p>Track and manage your submitted reports</p>
</div>
@endsection

@section('content')
<div class="reports-container">
    <div class="reports-header">
        <div class="reports-stats">
            <div class="stat-card">
                <div class="stat-number">{{ $totalReports }}</div>
                <div class="stat-label">Total Reports</div>
            </div>
            <div class="stat-card">
                <div class="stat-number text-amber-500">{{ $pendingReports }}</div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card">
                <div class="stat-number text-emerald-500">{{ $resolvedReports }}</div>
                <div class="stat-label">Resolved</div>
            </div>
        </div>
    </div>

    <div class="reports-list">
        @forelse($reports as $report)
        <div class="report-item" data-id="{{ $report->id }}">
            <div class="report-header">
                <div class="report-info">
                    <span class="report-id">#{{ $report->id }}</span>
                    <span class="report-type">
                        @php
                            $typeLabels = [
                                'wrong_fare' => 'Wrong Fare',
                                'road_closure' => 'Road Closure',
                                'vehicle_unavailable' => 'Vehicle Unavailable',
                                'technical_issue' => 'Technical Issue',
                                'other' => 'Other'
                            ];
                        @endphp
                        {{ $typeLabels[$report->type] ?? $report->type }}
                    </span>
                    <span class="report-status status-{{ $report->status }}">
                        {{ ucfirst($report->status) }}
                    </span>
                </div>
                <div class="report-date">
                    {{ $report->created_at->format('M d, Y h:i A') }}
                </div>
            </div>
            <div class="report-body">
                <p class="report-description">{{ Str::limit($report->description, 100) }}</p>
                <button class="btn-view-details" data-id="{{ $report->id }}">
                    <i class="fas fa-eye"></i> View Details
                </button>
            </div>
            <div class="report-footer">
                @if($report->status !== 'resolved' && $report->status !== 'rejected' && $report->status !== 'closed')
                <button class="btn-resolve-report" data-id="{{ $report->id }}">
                    <i class="fas fa-check-circle"></i> Mark as Resolved
                </button>
                @endif
                <span class="report-updated">Last updated: {{ $report->updated_at->diffForHumans() }}</span>
            </div>
        </div>
        @empty
        <div class="empty-reports">
            <i class="fas fa-inbox"></i>
            <h3>No reports yet</h3>
            <p>You haven't submitted any reports. Need help? <a href="{{ route('user.report.create') }}">Report an issue</a></p>
        </div>
        @endforelse
    </div>

    <div class="pagination-wrapper">
        {{ $reports->links() }}
    </div>
</div>

{{-- Report Details Modal --}}
<div id="report-modal" class="report-modal">
    <div class="report-modal-overlay"></div>
    <div class="report-modal-content">
        <div class="report-modal-header">
            <div class="report-header-left">
                <div class="report-type-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div>
                    <h3 id="modal-report-title">Report #<span id="modal-report-id"></span></h3>
                    <p class="report-header-meta">Submitted on <span id="modal-report-date"></span></p>
                </div>
            </div>
            <button class="report-modal-close" onclick="closeReportModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="report-modal-body" id="report-modal-body">
            <div class="modal-loading">
                <i class="fas fa-spinner fa-spin"></i>
                <span>Loading report...</span>
            </div>
        </div>
        
        <div class="report-modal-footer">
            <button class="modal-btn-secondary" onclick="closeReportModal()">
                <i class="fas fa-chevron-left"></i> Close
            </button>
        </div>
    </div>
</div>

<style>
    .reports-container {
        max-width: 900px;
        margin: 0 auto;
    }

    .reports-header {
        margin-bottom: 32px;
    }

    .reports-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .stat-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 20px;
        text-align: center;
    }

    .stat-number {
        font-size: 32px;
        font-weight: 700;
        color: var(--text-dark);
    }

    .stat-label {
        font-size: 13px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    .reports-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .report-item {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        transition: box-shadow 0.2s;
    }

    .report-item:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .report-header {
        padding: 16px 20px;
        background: var(--sand);
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .report-info {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .report-id {
        font-weight: 700;
        color: var(--teal);
        font-size: 13px;
    }

    .report-type {
        font-size: 12px;
        padding: 4px 10px;
        background: var(--teal-light);
        color: var(--teal);
        border-radius: 20px;
    }

    .report-status {
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 500;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-in_progress {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-resolved {
        background: #d1fae5;
        color: #065f46;
    }

    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-closed {
        background: #f3f4f6;
        color: #374151;
    }

    .report-date {
        font-size: 12px;
        color: var(--text-muted);
    }

    .report-body {
        padding: 20px;
    }

    .report-description {
        font-size: 14px;
        color: var(--text-mid);
        line-height: 1.6;
        margin-bottom: 12px;
    }

    .btn-view-details {
        padding: 6px 12px;
        background: var(--sand);
        color: var(--teal);
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-view-details:hover {
        background: var(--teal-light);
        border-color: var(--teal);
    }

    .report-footer {
        padding: 16px 20px;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .btn-resolve-report {
        padding: 8px 16px;
        background: var(--teal);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-resolve-report:hover {
        background: #0c7a60;
        transform: translateY(-1px);
    }

    .report-updated {
        font-size: 11px;
        color: var(--text-muted);
    }

    .empty-reports {
        text-align: center;
        padding: 60px 20px;
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 20px;
    }

    .empty-reports i {
        font-size: 48px;
        color: var(--text-muted);
        margin-bottom: 16px;
    }

    .empty-reports h3 {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 8px;
    }

    .empty-reports p {
        font-size: 14px;
        color: var(--text-muted);
    }

    .empty-reports a {
        color: var(--teal);
        text-decoration: none;
    }

    .pagination-wrapper {
        margin-top: 32px;
    }

    /* Report Modal Styles */
    .report-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10000;
        align-items: center;
        justify-content: center;
    }

    .report-modal.active {
        display: flex;
    }

    .report-modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(3px);
    }

    .report-modal-content {
        position: relative;
        background: var(--white);
        border-radius: 20px;
        width: 90%;
        max-width: 580px;
        max-height: 85vh;
        overflow: hidden;
        animation: modalSlideIn 0.25s ease-out;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .report-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 24px 28px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 1px solid var(--border);
    }

    .report-header-left {
        display: flex;
        gap: 16px;
    }

    .report-type-icon {
        width: 48px;
        height: 48px;
        background: var(--teal-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .report-type-icon i {
        font-size: 22px;
        color: var(--teal);
    }

    .report-modal-header h3 {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0 0 4px 0;
    }

    .report-header-meta {
        font-size: 12px;
        color: var(--text-muted);
        margin: 0;
    }

    .report-modal-close {
        width: 32px;
        height: 32px;
        background: rgba(0, 0, 0, 0.05);
        border: none;
        border-radius: 50%;
        font-size: 14px;
        cursor: pointer;
        color: var(--text-muted);
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .report-modal-close:hover {
        background: rgba(0, 0, 0, 0.1);
        color: var(--text-dark);
    }

    .report-modal-body {
        padding: 0;
        overflow-y: auto;
        max-height: calc(85vh - 160px);
    }

    .modal-loading {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
        padding: 60px;
        color: var(--text-muted);
    }

    .modal-loading i {
        font-size: 36px;
    }

    .report-ticket {
        padding: 28px;
    }

    .status-bar {
        background: #f8fafc;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .status-label {
        font-size: 12px;
        font-weight: 500;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-value {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .info-row {
        display: flex;
        padding: 14px 0;
        border-bottom: 1px solid var(--border);
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        width: 100px;
        flex-shrink: 0;
        font-size: 12px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .info-value {
        flex: 1;
        font-size: 14px;
        color: var(--text-dark);
        font-weight: 500;
    }

    .info-value p {
        margin: 0;
        font-weight: 400;
        line-height: 1.5;
    }

    .description-box {
        background: #f8fafc;
        border-radius: 12px;
        padding: 16px 20px;
        margin: 20px 0;
    }

    .description-box p {
        margin: 0;
        font-size: 14px;
        line-height: 1.6;
        color: var(--text-mid);
    }

    .response-box {
        background: #f0fdf4;
        border-radius: 12px;
        padding: 16px 20px;
        margin-top: 20px;
    }

    .response-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(14, 138, 110, 0.2);
    }

    .response-header i {
        color: var(--teal);
        font-size: 14px;
    }

    .response-header span {
        font-size: 12px;
        font-weight: 600;
        color: var(--teal);
        text-transform: uppercase;
    }

    .response-message {
        font-size: 14px;
        line-height: 1.6;
        color: var(--text-dark);
        margin-bottom: 12px;
    }

    .response-meta {
        font-size: 11px;
        color: var(--text-muted);
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }

    .report-modal-footer {
        padding: 16px 28px;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: flex-end;
        background: var(--white);
    }

    .modal-btn-secondary {
        padding: 8px 20px;
        background: var(--sand);
        color: var(--text-mid);
        border: none;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .modal-btn-secondary:hover {
        background: #e5e8eb;
    }

    @media (max-width: 768px) {
        .report-modal-content {
            width: 95%;
            max-width: none;
        }
        
        .report-modal-header {
            padding: 18px 20px;
        }
        
        .report-ticket {
            padding: 20px;
        }
        
        .info-row {
            flex-direction: column;
            gap: 6px;
        }
        
        .info-label {
            width: 100%;
        }
        
        .status-bar {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

@push('scripts')
<script>
    // Cache for location names
    let locationCache = {};

    // Reverse geocode function
    async function getLocationName(lat, lng) {
        const cacheKey = `${lat},${lng}`;
        if (locationCache[cacheKey]) {
            return locationCache[cacheKey];
        }
        
        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18`);
            const data = await response.json();
            let name = data.display_name?.split(',')[0] || `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
            locationCache[cacheKey] = name;
            return name;
        } catch {
            return `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
        }
    }

    // Store current report ID to prevent duplicate loads
    let currentReportId = null;

    // View report details
    document.querySelectorAll('.btn-view-details').forEach(btn => {
        btn.addEventListener('click', function() {
            const reportId = this.closest('.report-item').dataset.id;
            if (currentReportId === reportId) {
                document.getElementById('report-modal').classList.add('active');
                return;
            }
            openReportModal(reportId);
        });
    });

    async function openReportModal(reportId) {
        const modal = document.getElementById('report-modal');
        const modalBody = document.getElementById('report-modal-body');
        const modalReportId = document.getElementById('modal-report-id');
        const modalReportDate = document.getElementById('modal-report-date');
        
        currentReportId = reportId;
        modal.classList.add('active');
        
        modalBody.innerHTML = '<div class="modal-loading"><i class="fas fa-spinner fa-spin"></i><span>Loading report...</span></div>';
        modalReportId.textContent = '';
        modalReportDate.textContent = '';
        
        try {
            const response = await fetch(`/api/user/report/${reportId}`);
            const data = await response.json();
            
            const typeLabels = {
                'wrong_fare': 'Wrong Fare Calculation',
                'road_closure': 'Road Closure / Detour',
                'vehicle_unavailable': 'Vehicle Not Available',
                'technical_issue': 'Technical Issue',
                'other': 'Other'
            };
            
            const statusConfig = {
                'pending': { class: 'bg-amber-100 text-amber-700', icon: 'fa-clock', label: 'Pending Review' },
                'in_progress': { class: 'bg-blue-100 text-blue-700', icon: 'fa-spinner', label: 'In Progress' },
                'resolved': { class: 'bg-emerald-100 text-emerald-700', icon: 'fa-check-circle', label: 'Resolved' },
                'rejected': { class: 'bg-red-100 text-red-700', icon: 'fa-times-circle', label: 'Rejected' },
                'closed': { class: 'bg-gray-100 text-gray-700', icon: 'fa-check', label: 'Closed' }
            };
            
            const status = statusConfig[data.status] || statusConfig.pending;
            
            modalReportId.textContent = data.id;
            modalReportDate.textContent = new Date(data.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            
            let originName = '';
            let destName = '';
            
            if (data.origin_lat && data.origin_lng) {
                originName = await getLocationName(data.origin_lat, data.origin_lng);
                if (data.dest_lat && data.dest_lng) {
                    destName = await getLocationName(data.dest_lat, data.dest_lng);
                }
            }
            
            modalBody.innerHTML = `
                <div class="report-ticket">
                    <div class="status-bar">
                        <span class="status-label">Current Status</span>
                        <span class="status-value ${status.class}">
                            <i class="fas ${status.icon}"></i> ${status.label}
                        </span>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">Issue Type</div>
                        <div class="info-value">${typeLabels[data.type] || data.type}</div>
                    </div>
                    
                    ${data.origin_lat ? `
                    <div class="info-row">
                        <div class="info-label">Location</div>
                        <div class="info-value">
                            <div><i class="fas fa-map-marker-alt" style="color: #22c55e; width: 14px; margin-right: 6px;"></i> <strong>Origin:</strong> ${originName}</div>
                            ${data.dest_lat ? `<div style="margin-top: 4px;"><i class="fas fa-flag-checkered" style="color: #ef4444; width: 14px; margin-right: 6px;"></i> <strong>Destination:</strong> ${destName}</div>` : ''}
                        </div>
                    </div>
                    ` : ''}
                    
                    <div class="description-box">
                        <p>${data.description.replace(/\n/g, '<br>')}</p>
                    </div>
                    
                    ${data.admin_notes ? `
                    <div class="response-box">
                        <div class="response-header">
                            <i class="fas fa-reply-all"></i>
                            <span>Staff Response</span>
                        </div>
                        <div class="response-message">${data.admin_notes}</div>
                        <div class="response-meta">
                            ${data.resolver_name ? `<span><i class="fas fa-user"></i> ${data.resolver_name}</span>` : ''}
                            ${data.resolved_at ? `<span><i class="fas fa-calendar"></i> ${new Date(data.resolved_at).toLocaleString()}</span>` : ''}
                        </div>
                    </div>
                    ` : ''}
                </div>
            `;
        } catch (error) {
            console.error('Error loading report:', error);
            modalBody.innerHTML = '<div class="modal-loading"><i class="fas fa-exclamation-circle"></i><span>Error loading report. Please try again.</span></div>';
            currentReportId = null;
        }
    }

    function closeReportModal() {
        const modal = document.getElementById('report-modal');
        modal.classList.remove('active');
    }

    document.querySelector('.report-modal-overlay')?.addEventListener('click', closeReportModal);

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeReportModal();
        }
    });

    // Mark as Resolved
    document.querySelectorAll('.btn-resolve-report').forEach(btn => {
        btn.addEventListener('click', function() {
            const reportId = this.closest('.report-item').dataset.id;
            const originalText = this.innerHTML;
            
            // Show loading state
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            this.disabled = true;
            
            fetch(`/user/reports/${reportId}/resolve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Report marked as resolved! Thank you for your feedback.', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast('Error updating report status', 'error');
                    this.innerHTML = originalText;
                    this.disabled = false;
                }
            })
            .catch(() => {
                showToast('Error updating report status', 'error');
                this.innerHTML = originalText;
                this.disabled = false;
            });
        });
    });
</script>
@endpush
@endsection