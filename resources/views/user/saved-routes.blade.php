@extends('layouts.user')

@section('title', 'Saved Items')

@section('content')
<div class="saved-container">
    <div class="page-header">
        <h2 class="page-title">
            <i class="fas fa-bookmark" style="color: var(--teal);"></i> Saved Items
        </h2>
        <p class="page-subtitle">Your bookmarked routes and favorite locations</p>
    </div>

    {{-- Tabs --}}
    <div class="tabs">
        <button class="tab-btn active" data-tab="routes">
            <i class="fas fa-route"></i> Routes
        </button>
        <button class="tab-btn" data-tab="locations">
            <i class="fas fa-map-pin"></i> Locations
        </button>
    </div>

    {{-- Routes Tab --}}
    <div id="routes-tab" class="tab-content active">
        <div class="saved-grid">
            @php $hasRoutes = false; @endphp
            @foreach($savedRoutes as $route)
                @if($route->type !== 'recommended_place')
                    @php $hasRoutes = true; @endphp
                    <div class="route-card">
                        <div class="route-card-header">
                            <h3 class="route-name">{{ $route->name }}</h3>
                            <span class="route-date">
                                <i class="far fa-calendar-alt"></i> {{ $route->created_at->format('M d, Y') }}
                            </span>
                        </div>
                        <div class="route-card-body">
                            <div class="route-location">
                                <div class="location-point">
                                    <div class="location-dot green"></div>
                                    <div class="location-text">
                                        <span class="location-label">Origin</span>
                                        <span class="location-address">{{ Str::limit($route->origin_address ?? $route->origin_lat . ', ' . $route->origin_lng, 80) }}</span>
                                    </div>
                                </div>
                                <div class="location-arrow">
                                    <i class="fas fa-arrow-down"></i>
                                </div>
                                <div class="location-point">
                                    <div class="location-dot red"></div>
                                    <div class="location-text">
                                        <span class="location-label">Destination</span>
                                        <span class="location-address">{{ Str::limit($route->dest_address ?? $route->dest_lat . ', ' . $route->dest_lng, 80) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="route-card-footer">
                            <button class="btn-view-route view-route-btn" 
                                    data-origin-lat="{{ $route->origin_lat }}"
                                    data-origin-lng="{{ $route->origin_lng }}"
                                    data-origin-address="{{ addslashes($route->origin_address) }}"
                                    data-dest-lat="{{ $route->dest_lat }}"
                                    data-dest-lng="{{ $route->dest_lng }}"
                                    data-dest-address="{{ addslashes($route->dest_address) }}"
                                    data-route-name="{{ addslashes($route->name) }}">
                                <i class="fas fa-route"></i> View Route
                            </button>
                            <button class="btn-delete delete-route" data-id="{{ $route->id }}">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                @endif
            @endforeach
            @if(!$hasRoutes)
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-route"></i>
                    </div>
                    <h3 class="empty-state-title">No saved routes yet</h3>
                    <p class="empty-state-text">Find a route and click save to bookmark it here.</p>
                    <a href="{{ route('find-route') }}" class="empty-state-btn">
                        <i class="fas fa-search"></i> Find a Route
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Locations Tab --}}
    <div id="locations-tab" class="tab-content">
        <div class="saved-grid">
            @php $hasLocations = false; @endphp
            @foreach($savedRoutes as $route)
                @if($route->type === 'recommended_place')
                    @php $hasLocations = true; @endphp
                    <div class="location-card">
                        <div class="location-card-header">
                            <div class="location-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="location-info">
                                <h3 class="location-name">{{ $route->name }}</h3>
                                <span class="location-date">
                                    <i class="far fa-calendar-alt"></i> Saved on {{ $route->created_at->format('M d, Y') }}
                                </span>
                            </div>
                        </div>
                        <div class="location-card-body">
                            <p class="location-address-preview">{{ Str::limit($route->dest_address ?? $route->dest_lat . ', ' . $route->dest_lng, 100) }}</p>
                        </div>
                        <div class="location-card-footer">
                            <button class="btn-view-location view-location-btn"
                                    data-id="{{ $route->recommended_place_id }}"
                                    data-name="{{ addslashes($route->name) }}"
                                    data-lat="{{ $route->dest_lat }}"
                                    data-lng="{{ $route->dest_lng }}">
                                <i class="fas fa-info-circle"></i> View Details
                            </button>
                            <button class="btn-delete delete-route" data-id="{{ $route->id }}">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                @endif
            @endforeach
            @if(!$hasLocations)
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-map-pin"></i>
                    </div>
                    <h3 class="empty-state-title">No saved locations yet</h3>
                    <p class="empty-state-text">Browse tourist spots and save your favorites here.</p>
                    <a href="{{ route('home') }}" class="empty-state-btn">
                        <i class="fas fa-map-marked-alt"></i> Browse Places
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Route Modal --}}
<div id="route-modal" class="modal">
    <div class="modal-overlay"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-route" style="color: var(--teal);"></i> 
                <span id="modal-route-name">Route Preview</span>
            </h3>
            <button class="modal-close" onclick="closeRouteModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div id="modal-loading" class="modal-loading">
                <i class="fas fa-spinner fa-spin"></i> Loading route...
            </div>
            <div id="modal-content" style="display: none;">
                <div id="modal-map" style="height: 300px; width: 100%; border-radius: 12px; margin-bottom: 20px;"></div>
                <div class="modal-summary">
                    <div class="summary-card">
                        <div class="summary-icon"><i class="fas fa-road"></i></div>
                        <div>
                            <div class="summary-label">Distance</div>
                            <div class="summary-value" id="modal-distance">—</div>
                            <div class="summary-unit">kilometres</div>
                        </div>
                    </div>
                    <div class="summary-card">
                        <div class="summary-icon"><i class="fas fa-clock"></i></div>
                        <div>
                            <div class="summary-label">Est. Time</div>
                            <div class="summary-value" id="modal-time">—</div>
                            <div class="summary-unit">minutes</div>
                        </div>
                    </div>
                </div>
                <div class="modal-section">
                    <h4 class="modal-section-title">
                        <i class="fas fa-money-bill-wave"></i> Fare Estimates
                    </h4>
                    <div id="modal-fares" class="modal-fares"></div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="modal-btn-secondary" onclick="closeRouteModal()">
                <i class="fas fa-arrow-left"></i> Back
            </button>
        </div>
    </div>
</div>

{{-- Location Details Modal --}}
<div id="location-modal" class="modal">
    <div class="modal-overlay"></div>
    <div class="modal-container location-modal-container">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-map-marker-alt" style="color: var(--teal);"></i> 
                <span id="location-modal-name">Location Details</span>
            </h3>
            <button class="modal-close" onclick="closeLocationModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div id="location-modal-loading" class="modal-loading">
                <i class="fas fa-spinner fa-spin"></i> Loading details...
            </div>
            <div id="location-modal-content" style="display: none;">
                <div id="location-modal-map" style="height: 250px; width: 100%; border-radius: 12px; margin-bottom: 20px;"></div>
                <div class="location-details">
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-tag"></i> Name:</span>
                        <span class="detail-value" id="location-detail-name">—</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-map-pin"></i> Coordinates:</span>
                        <span class="detail-value" id="location-detail-coords">—</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-align-left"></i> Description:</span>
                        <p class="detail-description" id="location-detail-desc">—</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="modal-btn-primary" id="route-to-location-btn">
                <i class="fas fa-route"></i> Get Route to Here
            </button>
            <button class="modal-btn-secondary" onclick="closeLocationModal()">
                <i class="fas fa-arrow-left"></i> Back
            </button>
        </div>
    </div>
</div>

<style>
    .saved-container {
        width: 100%;
        padding: 0;
    }

    .page-header {
        margin-bottom: 32px;
    }

    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .page-subtitle {
        font-size: 14px;
        color: var(--text-muted);
    }

    /* Tabs */
    .tabs {
        display: flex;
        gap: 12px;
        margin-bottom: 28px;
        border-bottom: 1px solid var(--border);
        padding-bottom: 12px;
    }

    .tab-btn {
        padding: 8px 20px;
        background: none;
        border: none;
        font-size: 14px;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
        color: var(--text-muted);
        cursor: pointer;
        border-radius: 10px;
        transition: all 0.2s;
    }

    .tab-btn:hover {
        background: var(--sand);
        color: var(--text-mid);
    }

    .tab-btn.active {
        background: var(--teal);
        color: #fff;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .saved-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 20px;
    }

    /* Route Card Styles */
    .route-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .route-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    }

    .route-card-header {
        padding: 16px 20px;
        background: var(--sand);
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .route-name {
        font-size: 15px;
        font-weight: 600;
        color: var(--text-dark);
        margin: 0;
    }

    .route-date {
        font-size: 11px;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .route-card-body {
        padding: 20px;
    }

    .route-location {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .location-point {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .location-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
        margin-top: 4px;
    }

    .location-dot.green {
        background: #22c55e;
        box-shadow: 0 0 0 2px rgba(34,197,94,0.2);
    }

    .location-dot.red {
        background: #ef4444;
        box-shadow: 0 0 0 2px rgba(239,68,68,0.2);
    }

    .location-text {
        flex: 1;
    }

    .location-label {
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        display: block;
        margin-bottom: 4px;
    }

    .location-address {
        font-size: 13px;
        color: var(--text-mid);
        line-height: 1.4;
    }

    .location-arrow {
        text-align: center;
        color: var(--text-muted);
        font-size: 12px;
        margin-left: 4px;
    }

    .route-card-footer {
        padding: 16px 20px;
        border-top: 1px solid var(--border);
        display: flex;
        gap: 12px;
        background: var(--white);
    }

    /* Location Card Styles */
    .location-card {
        background: linear-gradient(135deg, var(--white) 0%, var(--sand) 100%);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .location-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    }

    .location-card-header {
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    }

    .location-icon {
        width: 48px;
        height: 48px;
        background: var(--white);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: var(--teal);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .location-info {
        flex: 1;
    }

    .location-name {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0 0 4px 0;
    }

    .location-date {
        font-size: 11px;
        color: #92400e;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .location-card-body {
        padding: 16px 20px;
    }

    .location-address-preview {
        font-size: 13px;
        color: var(--text-mid);
        margin: 0;
        line-height: 1.5;
    }

    .location-card-footer {
        padding: 16px 20px;
        border-top: 1px solid var(--border);
        display: flex;
        gap: 12px;
        background: var(--white);
    }

    /* Button Styles */
    .btn-view-route, .btn-view-location {
        flex: 1;
        padding: 10px;
        background: var(--navy);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: background 0.2s;
    }

    .btn-view-route:hover, .btn-view-location:hover {
        background: var(--navy-mid);
    }

    .btn-delete {
        flex: 1;
        padding: 10px;
        background: #fee2e2;
        color: #dc2626;
        border: none;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-delete:hover {
        background: #fecaca;
    }

    /* Empty State */
    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 60px 20px;
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 20px;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        background: var(--sand);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }

    .empty-state-icon i {
        font-size: 36px;
        color: var(--teal);
    }

    .empty-state-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 8px;
    }

    .empty-state-text {
        font-size: 14px;
        color: var(--text-muted);
        margin-bottom: 24px;
    }

    .empty-state-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: var(--teal);
        color: #fff;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        transition: background 0.2s;
    }

    .empty-state-btn:hover {
        background: #0c7a60;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1000;
    }

    .modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(4px);
    }

    .modal-container {
        position: relative;
        background: var(--white);
        border-radius: 20px;
        width: 90%;
        max-width: 700px;
        max-height: 90vh;
        overflow-y: auto;
        z-index: 1001;
        animation: modalSlideIn 0.3s ease;
    }

    .location-modal-container {
        max-width: 500px;
    }

    @keyframes modalSlideIn {
        from {
            transform: translateY(-30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
        background: var(--sand);
        border-radius: 20px 20px 0 0;
    }

    .modal-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: var(--text-muted);
        padding: 8px;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .modal-close:hover {
        background: rgba(0,0,0,0.05);
        color: var(--text-dark);
    }

    .modal-body {
        padding: 24px;
    }

    .modal-loading {
        text-align: center;
        padding: 40px;
        color: var(--text-muted);
    }

    .modal-loading i {
        font-size: 32px;
        margin-bottom: 12px;
        display: block;
    }

    .modal-summary {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 24px;
    }

    .summary-card {
        background: var(--sand);
        border-radius: 12px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .summary-icon {
        width: 44px;
        height: 44px;
        background: var(--white);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: var(--teal);
    }

    .summary-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
    }

    .summary-value {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-dark);
        line-height: 1.2;
    }

    .summary-unit {
        font-size: 11px;
        color: var(--text-muted);
    }

    .modal-section {
        margin-top: 24px;
    }

    .modal-section-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .modal-fares {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .modal-fare-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        background: var(--sand);
        border-radius: 12px;
        transition: background 0.2s;
    }

    .modal-fare-item:hover {
        background: #eef2f5;
    }

    .modal-fare-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .modal-fare-icon {
        width: 36px;
        height: 36px;
        background: var(--white);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: var(--teal);
    }

    .modal-fare-name {
        font-weight: 600;
        font-size: 14px;
        color: var(--text-dark);
    }

    .modal-fare-detail {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .modal-fare-amount {
        font-size: 20px;
        font-weight: 700;
        color: var(--teal);
    }

    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        background: var(--white);
        border-radius: 0 0 20px 20px;
    }

    .modal-btn-secondary {
        padding: 10px 20px;
        background: var(--sand);
        color: var(--text-mid);
        border: none;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.2s;
    }

    .modal-btn-secondary:hover {
        background: #e5e8eb;
    }

    .modal-btn-primary {
        padding: 10px 20px;
        background: var(--teal);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: background 0.2s;
    }

    .modal-btn-primary:hover {
        background: #0c7a60;
    }

    /* Location Details */
    .location-details {
        margin-top: 16px;
    }

    .detail-row {
        margin-bottom: 16px;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .detail-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-muted);
        min-width: 100px;
    }

    .detail-value {
        font-size: 13px;
        color: var(--text-dark);
        flex: 1;
    }

    .detail-description {
        font-size: 13px;
        color: var(--text-mid);
        line-height: 1.6;
        margin: 0;
        flex: 1;
    }

    @media (max-width: 768px) {
        .saved-grid {
            grid-template-columns: 1fr;
        }
        
        .route-card-header, .location-card-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .route-card-footer, .location-card-footer {
            flex-direction: column;
        }
        
        .modal-container {
            width: 95%;
        }
        
        .modal-summary {
            grid-template-columns: 1fr;
        }
        
        .tabs {
            justify-content: center;
        }
    }
</style>

@push('scripts')
<script>
let routeModalMap = null;
let locationModalMap = null;

// Tab switching
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const tab = this.dataset.tab;
        
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        
        this.classList.add('active');
        document.getElementById(`${tab}-tab`).classList.add('active');
    });
});

// Delete route/location
document.querySelectorAll('.delete-route').forEach(btn => {
    btn.addEventListener('click', function() {
        if(confirm('Delete this saved item?')) {
            const id = this.dataset.id;
            fetch(`/user/saved-routes/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).then(() => location.reload());
        }
    });
});

// View Route Modal
document.querySelectorAll('.view-route-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        openRouteModal(
            parseFloat(this.dataset.originLat),
            parseFloat(this.dataset.originLng),
            parseFloat(this.dataset.destLat),
            parseFloat(this.dataset.destLng),
            this.dataset.originAddress,
            this.dataset.destAddress,
            this.dataset.routeName
        );
    });
});

function openRouteModal(originLat, originLng, destLat, destLng, originAddress, destAddress, routeName) {
    const modal = document.getElementById('route-modal');
    const loading = document.getElementById('modal-loading');
    const content = document.getElementById('modal-content');
    
    document.getElementById('modal-route-name').textContent = routeName;
    modal.classList.add('active');
    loading.style.display = 'block';
    content.style.display = 'none';
    
    const url = `https://router.project-osrm.org/route/v1/driving/${originLng},${originLat};${destLng},${destLat}?overview=full&geometries=geojson`;
    
    fetch(url)
        .then(res => res.json())
        .then(data => {
            if (data.code !== 'Ok') throw new Error('Could not calculate route');
            
            const route = data.routes[0];
            const distanceKm = (route.distance / 1000).toFixed(2);
            const durationMin = Math.round(route.duration / 60);
            
            document.getElementById('modal-distance').textContent = distanceKm;
            document.getElementById('modal-time').textContent = durationMin;
            
            if (routeModalMap) routeModalMap.remove();
            
            routeModalMap = L.map('modal-map').setView([(originLat + destLat) / 2, (originLng + destLng) / 2], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(routeModalMap);
            
            L.geoJSON(route.geometry, { style: { color: '#0c2340', weight: 5 } }).addTo(routeModalMap);
            
            const greenIcon = L.divIcon({ html: '<div style="width:12px;height:12px;background:#22c55e;border-radius:50%;border:2px solid #fff;box-shadow:0 0 0 2px #22c55e"></div>', iconSize: [12, 12] });
            const redIcon = L.divIcon({ html: '<div style="width:12px;height:12px;background:#ef4444;border-radius:50%;border:2px solid #fff;box-shadow:0 0 0 2px #ef4444"></div>', iconSize: [12, 12] });
            
            L.marker([originLat, originLng], { icon: greenIcon }).addTo(routeModalMap).bindPopup(`<strong>Origin</strong><br>${originAddress}`);
            L.marker([destLat, destLng], { icon: redIcon }).addTo(routeModalMap).bindPopup(`<strong>Destination</strong><br>${destAddress}`);
            
            routeModalMap.fitBounds(L.latLngBounds([[originLat, originLng], [destLat, destLng]]), { padding: [50, 50] });
            
            fetchFares(distanceKm);
            
            loading.style.display = 'none';
            content.style.display = 'block';
        })
        .catch(error => {
            loading.innerHTML = '<i class="fas fa-exclamation-circle"></i> Could not load route.';
        });
}

// View Location Modal
document.querySelectorAll('.view-location-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const placeId = this.dataset.id;
        const placeName = this.dataset.name;
        const lat = parseFloat(this.dataset.lat);
        const lng = parseFloat(this.dataset.lng);
        
        openLocationModal(placeId, placeName, lat, lng);
    });
});

function openLocationModal(placeId, placeName, lat, lng) {
    const modal = document.getElementById('location-modal');
    const loading = document.getElementById('location-modal-loading');
    const content = document.getElementById('location-modal-content');
    
    document.getElementById('location-modal-name').textContent = placeName;
    modal.classList.add('active');
    loading.style.display = 'block';
    content.style.display = 'none';
    
    // Get place details from API or database
    fetch(`/api/place/${placeId}`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('location-detail-name').textContent = data.name;
            document.getElementById('location-detail-coords').textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            document.getElementById('location-detail-desc').textContent = data.description || 'No description available.';
            
            if (locationModalMap) locationModalMap.remove();
            
            locationModalMap = L.map('location-modal-map').setView([lat, lng], 14);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(locationModalMap);
            
            const tealIcon = L.divIcon({ html: '<div style="width:14px;height:14px;background:#0e8a6e;border-radius:50%;border:2px solid #fff;box-shadow:0 0 0 2px #0e8a6e"></div>', iconSize: [14, 14] });
            L.marker([lat, lng], { icon: tealIcon }).addTo(locationModalMap).bindPopup(`<strong>${data.name}</strong>`).openPopup();
            
            loading.style.display = 'none';
            content.style.display = 'block';
            
            document.getElementById('route-to-location-btn').onclick = function() {
                window.location.href = `/?dest_lat=${lat}&dest_lng=${lng}&dest_name=${encodeURIComponent(placeName)}`;
                closeLocationModal();
            };
        })
        .catch(() => {
            loading.innerHTML = '<i class="fas fa-exclamation-circle"></i> Could not load location details.';
        });
}

async function fetchFares(distanceKm) {
    try {
        const response = await fetch('{{ route("calculate-fares") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ distance_km: distanceKm, is_night: false })
        });
        const fares = await response.json();
        
        document.getElementById('modal-fares').innerHTML = fares.map(v => `
            <div class="modal-fare-item">
                <div class="modal-fare-info">
                    <div class="modal-fare-icon"><i class="${v.icon}"></i></div>
                    <div>
                        <div class="modal-fare-name">${v.name}</div>
                        <div class="modal-fare-detail">₱${v.base_fare} first ${v.base_km}km + ₱${v.per_km_rate}/km</div>
                    </div>
                </div>
                <div class="modal-fare-amount">₱${v.fare}</div>
            </div>
        `).join('');
    } catch (error) {
        document.getElementById('modal-fares').innerHTML = '<div class="error">Could not load fare estimates</div>';
    }
}

function closeRouteModal() {
    document.getElementById('route-modal').classList.remove('active');
    if (routeModalMap) { routeModalMap.remove(); routeModalMap = null; }
}

function closeLocationModal() {
    document.getElementById('location-modal').classList.remove('active');
    if (locationModalMap) { locationModalMap.remove(); locationModalMap = null; }
}

document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function() {
        closeRouteModal();
        closeLocationModal();
    });
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeRouteModal();
        closeLocationModal();
    }
});
</script>
@endpush
@endsection