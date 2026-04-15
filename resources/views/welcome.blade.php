@extends('layouts.user')

@section('title', 'Find Your Route')

@section('hero-content')
<div class="hero-content">
    <div class="hero-tag">
        <div class="hero-tag-dot"></div>
        Public Transport Navigator
    </div>
    <h1>Find Your Way<br>Around <span>Bohol</span></h1>
    <p>Accurate fare estimates, real-time routes, and travel times for every jeepney, bus, and tricycle on the island.</p>
    <div class="hero-stats">
        <div class="hero-stat">
            <div class="hero-stat-num">12+</div>
            <div class="hero-stat-label">Municipalities</div>
        </div>
        <div class="hero-stat">
            <div class="hero-stat-num">{{$vehicleCount ?? 0}}</div>
            <div class="hero-stat-label">Vehicle Types</div>
        </div>
        <div class="hero-stat">
            <div class="hero-stat-num">Free</div>
            <div class="hero-stat-label">Always</div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="grid-layout">
    {{-- Control Panel --}}
    <div class="control-panel">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Plan Your Trip</div>
                <div class="card-sub">Search or pick locations on the map</div>
            </div>
            <div class="card-body">
                {{-- Origin Field --}}
                <div class="field">
                    <div class="field-label">
                        <div class="field-dot green"></div> Origin
                    </div>
                    <div class="search-input-wrapper">
                        <input type="text" id="origin-input" class="search-input" placeholder="Search for origin..." autocomplete="off">
                        <button type="button" class="map-picker-btn" data-type="origin" title="Pick location on map">
                            <i class="fas fa-map-marker-alt"></i>
                        </button>
                        <div id="origin-suggestions" class="suggestions-dropdown hidden"></div>
                    </div>
                    <input type="hidden" id="origin-lat">
                    <input type="hidden" id="origin-lng">
                    <input type="hidden" id="origin-address">
                </div>

                {{-- Destination Field --}}
                <div class="field">
                    <div class="field-label">
                        <div class="field-dot red"></div> Destination
                    </div>
                    <div class="search-input-wrapper">
                        <input type="text" id="dest-input" class="search-input" placeholder="Search for destination..." autocomplete="off">
                        <button type="button" class="map-picker-btn" data-type="destination" title="Pick location on map">
                            <i class="fas fa-map-marker-alt"></i>
                        </button>
                        <div id="dest-suggestions" class="suggestions-dropdown hidden"></div>
                    </div>
                    <input type="hidden" id="dest-lat">
                    <input type="hidden" id="dest-lng">
                    <input type="hidden" id="dest-address">
                </div>

                <div class="night-row">
                    <div>
                        <div class="night-row-label">🌙 Night Travel</div>
                        <div class="night-row-sub">Surcharge applies 8 PM – 5 AM</div>
                    </div>
                    <input type="checkbox" id="night-travel" style="width:17px;height:17px;accent-color:var(--navy);cursor:pointer">
                </div>

                <button id="find-route-btn" class="btn-find" disabled>
                    <i class="fas fa-search"></i> Find Route & Fares
                </button>
                <button id="reset-btn" class="btn-reset">↺ Reset map</button>
            </div>
        </div>
    </div>

    {{-- Map Section --}}
    <div class="map-section">
        <div class="map-panel">
            <div id="map"></div>
            <div class="map-footer">
                <div class="map-hint">
                    <i class="fas fa-search-location"></i> Search or use map picker
                </div>
                <div class="map-hint">
                    <i class="fas fa-map-marker-alt text-green-500"></i> Origin
                </div>
                <div class="map-hint">
                    <i class="fas fa-flag-checkered text-red-500"></i> Destination
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Map Selection Modal - Larger size with bigger mini-map and search bar --}}
<div id="map-picker-modal" class="map-overlay hidden">
    <div class="overlay-content overlay-content-large">
        <div class="overlay-header">
            <div class="overlay-header-left">
                <h3 id="modal-title">Select Location on Map</h3>
                <div class="modal-search-wrapper">
                    <i class="fas fa-search modal-search-icon"></i>
                    <input type="text" id="modal-search-input" class="modal-search-input" placeholder="Search for a location to pan to..." autocomplete="off">
                    <div id="modal-suggestions" class="modal-suggestions-dropdown hidden"></div>
                </div>
            </div>
            <button id="close-modal-btn" class="overlay-close">&times;</button>
        </div>
        <div class="overlay-body">
            <p id="modal-instruction">📍 Click on the map to place a marker or search above to pan</p>
            <div id="mini-map" class="mini-map-container"></div>
            <div id="temp-marker-status" class="marker-status">No location selected yet</div>
        </div>
        <div class="overlay-footer">
            <button id="cancel-selection" class="overlay-btn secondary">Cancel</button>
            <button id="apply-selection" class="overlay-btn primary" disabled>✓ Apply as <span id="apply-type">Origin</span></button>
        </div>
    </div>
</div>

{{-- Route Results Section --}}
<div id="results-section" style="display: none;">
    <div class="results-header">
        <h3 class="results-title">
            <i class="fas fa-route" style="color: var(--teal);"></i> Route Summary
        </h3>
        <div class="results-summary">
            <div class="summary-badge">
                <i class="fas fa-road"></i>
                <span id="stat-dist">—</span> km
            </div>
            <div class="summary-badge">
                <i class="fas fa-clock"></i>
                <span id="stat-time">—</span> mins
            </div>
        </div>
    </div>
    
    <div class="vehicles-grid" id="fare-list"></div>
</div>

{{-- Save button --}}
@auth
    <button id="save-route-btn" class="save-route-btn" style="display: none;">
        <i class="far fa-bookmark"></i> Save This Route
    </button>
@else
    <div class="login-note" id="login-note" style="display: none;">
        <i class="fas fa-lock"></i> Login to save routes
    </div>
@endauth

{{-- Popular Places Section - Professional Styling --}}
<div class="places-section">
    <div class="places-header">
        <h2 class="section-title">Top 5 <span>Tourist Spots</span> in Bohol</h2>
        <a href="{{ route('user.recommended-places') }}" class="view-all-link">
            View All <i class="fas fa-arrow-right"></i>
        </a>
    </div>
    <div class="places-grid places-grid-enhanced">
        @foreach($recommendedPlaces as $place)
        <div class="place-card-enhanced" 
             data-name="{{ $place->name }}" 
             data-lat="{{ $place->latitude }}" 
             data-lng="{{ $place->longitude }}"
             data-category="{{ $place->category ?? 'Tourist Spot' }}">
            @if($place->image_url)
                <img src="{{ $place->image_url }}" alt="{{ $place->name }}" class="place-img-enhanced">
            @else
                <div class="place-img-fallback-enhanced">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
            @endif
            <div class="place-body-enhanced">
                <div class="place-header-enhanced">
                    <h3 class="place-name-enhanced">{{ $place->name }}</h3>
                    @if($place->category)
                        <span class="place-category-enhanced">{{ $place->category }}</span>
                    @endif
                </div>
                <p class="place-desc-enhanced">{{ Str::limit($place->description, 100) }}</p>
                <div class="place-stats-enhanced">
                    <span class="place-stats-item-enhanced">
                        <i class="fas fa-map-pin"></i> 
                        {{ number_format($place->latitude, 4) }}, {{ number_format($place->longitude, 4) }}
                    </span>
                    <span class="place-stats-item-enhanced">
                        <i class="fas fa-bookmark"></i> 
                        {{ $place->saved_routes_count ?? 0 }} saves
                    </span>
                </div>
                <div class="place-buttons-enhanced">
                    <button class="btn-route-enhanced set-destination-btn"
                            data-lat="{{ $place->latitude }}"
                            data-lng="{{ $place->longitude }}"
                            data-name="{{ addslashes($place->name) }}">
                        <i class="fas fa-route"></i> Get Route
                    </button>
                    @auth
                        @php
                            $isSaved = auth()->check() && in_array($place->id, $savedPlaceIds ?? []);
                        @endphp
                        <button class="btn-save-place-enhanced save-place-btn {{ $isSaved ? 'saved' : '' }}" 
                                data-id="{{ $place->id }}"
                                data-name="{{ addslashes($place->name) }}"
                                data-lat="{{ $place->latitude }}"
                                data-lng="{{ $place->longitude }}">
                            <i class="fas fa-bookmark"></i> 
                            {{ $isSaved ? 'Saved' : 'Save' }}
                        </button>
                    @else
                        <button class="btn-save-place-disabled-enhanced" onclick="showToast('Please login to save locations', 'info');">
                            <i class="fas fa-lock"></i> Save
                        </button>
                    @endauth
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
    /* Search Input Styles */
    .search-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .search-input {
        flex: 1;
        padding: 11px 14px;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-size: 13px;
        font-family: 'Poppins', sans-serif;
        background: var(--white);
        transition: all 0.2s;
    }
    
    .search-input:focus {
        outline: none;
        border-color: var(--teal);
        box-shadow: 0 0 0 2px rgba(14,138,110,0.1);
    }
    
    .map-picker-btn {
        width: 40px;
        height: 40px;
        background: var(--sand);
        border: 1.5px solid var(--border);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--teal);
    }
    
    .map-picker-btn:hover {
        background: var(--teal-light);
        border-color: var(--teal);
        transform: scale(1.02);
    }
    
    .suggestions-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 40px;
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        max-height: 250px;
        overflow-y: auto;
        z-index: 100;
        margin-top: 4px;
    }
    
    .suggestions-dropdown.hidden {
        display: none;
    }
    
    .suggestion-item {
        padding: 10px 14px;
        cursor: pointer;
        transition: background 0.2s;
        border-bottom: 1px solid var(--border);
    }
    
    .suggestion-item:last-child {
        border-bottom: none;
    }
    
    .suggestion-item:hover {
        background: var(--sand);
    }
    
    .suggestion-name {
        font-size: 13px;
        font-weight: 500;
        color: var(--text-dark);
    }
    
    .suggestion-address {
        font-size: 10px;
        color: var(--text-muted);
        margin-top: 2px;
    }
    
    /* Modal Search Styles */
    .overlay-header-left {
        display: flex;
        flex-direction: column;
        gap: 10px;
        flex: 1;
    }
    
    .modal-search-wrapper {
        position: relative;
        width: 100%;
        max-width: 350px;
    }
    
    .modal-search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 12px;
    }
    
    .modal-search-input {
        width: 100%;
        padding: 8px 12px 8px 32px;
        border: 1.5px solid var(--border);
        border-radius: 20px;
        font-size: 12px;
        font-family: 'Poppins', sans-serif;
        background: var(--white);
        transition: all 0.2s;
    }
    
    .modal-search-input:focus {
        outline: none;
        border-color: var(--teal);
        box-shadow: 0 0 0 2px rgba(14,138,110,0.1);
    }
    
    .modal-suggestions-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        max-height: 250px;
        overflow-y: auto;
        z-index: 10001;
        margin-top: 4px;
    }
    
    .modal-suggestions-dropdown.hidden {
        display: none;
    }
    
    .modal-suggestion-item {
        padding: 10px 14px;
        cursor: pointer;
        transition: background 0.2s;
        border-bottom: 1px solid var(--border);
    }
    
    .modal-suggestion-item:last-child {
        border-bottom: none;
    }
    
    .modal-suggestion-item:hover {
        background: var(--sand);
    }
    
    .modal-suggestion-name {
        font-size: 12px;
        font-weight: 500;
        color: var(--text-dark);
    }
    
    .modal-suggestion-address {
        font-size: 10px;
        color: var(--text-muted);
        margin-top: 2px;
    }
    
    /* Map Overlay - Large Modal Styles */
    .map-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }
    
    .map-overlay.hidden {
        display: none;
    }
    
    .overlay-content {
        background: var(--white);
        border-radius: 12px;
        overflow: hidden;
        animation: slideUp 0.3s ease;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    
    .overlay-content-large {
        width: 85vw;
        height: 85vh;
        max-width: 1200px;
        max-height: 800px;
        display: flex;
        flex-direction: column;
    }
    
    @keyframes slideUp {
        from {
            transform: translateY(30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    .overlay-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 18px 24px;
        background: var(--sand);
        border-bottom: 1px solid var(--border);
        flex-shrink: 0;
        gap: 16px;
    }
    
    .overlay-header h3 {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-dark);
        margin: 0;
    }
    
    .overlay-close {
        background: none;
        border: none;
        font-size: 28px;
        cursor: pointer;
        color: var(--text-muted);
        transition: color 0.2s;
        line-height: 1;
        padding: 0 8px;
        flex-shrink: 0;
    }
    
    .overlay-close:hover {
        color: var(--text-dark);
    }
    
    .overlay-body {
        padding: 20px 24px;
        text-align: center;
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 0;
    }
    
    .overlay-body p {
        font-size: 14px;
        color: var(--text-mid);
        margin-bottom: 16px;
        flex-shrink: 0;
    }
    
    .mini-map-container {
        flex: 1;
        width: 100%;
        min-height: 300px;
        border-radius: 12px;
        border: 1.5px solid var(--border);
        margin-bottom: 16px;
        overflow: hidden;
    }
    
    .marker-status {
        font-size: 13px;
        padding: 10px 12px;
        background: var(--sand);
        border-radius: 8px;
        color: var(--text-muted);
        flex-shrink: 0;
    }
    
    .marker-status.has-marker {
        background: #d1fae5;
        color: #065f46;
    }
    
    .overlay-footer {
        padding: 16px 24px;
        border-top: 1px solid var(--border);
        display: flex;
        gap: 16px;
        justify-content: flex-end;
        flex-shrink: 0;
    }
    
    .overlay-btn {
        padding: 12px 28px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .overlay-btn.primary {
        background: var(--teal);
        color: #fff;
        border: none;
    }
    
    .overlay-btn.primary:hover:not(:disabled) {
        background: #0c7a60;
        transform: translateY(-1px);
    }
    
    .overlay-btn.primary:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .overlay-btn.secondary {
        background: var(--sand);
        color: var(--text-mid);
        border: none;
    }
    
    .overlay-btn.secondary:hover {
        background: #e5e8eb;
    }
    
    @media (max-width: 768px) {
        .overlay-content-large {
            width: 95vw;
            height: 90vh;
        }
        
        .overlay-header {
            flex-direction: column;
            align-items: stretch;
            padding: 14px 18px;
        }
        
        .overlay-header h3 {
            font-size: 16px;
        }
        
        .overlay-header-left {
            width: 100%;
        }
        
        .modal-search-wrapper {
            max-width: 100%;
        }
        
        .overlay-body {
            padding: 16px;
        }
        
        .overlay-footer {
            padding: 14px 18px;
            flex-direction: column-reverse;
        }
        
        .overlay-btn {
            width: 100%;
            text-align: center;
            padding: 12px 16px;
        }
        
        .mini-map-container {
            min-height: 250px;
        }
    }
    
    @media (max-width: 480px) {
        .overlay-content-large {
            width: 98vw;
            height: 92vh;
        }
        
        .mini-map-container {
            min-height: 200px;
        }
    }
    
    .grid-layout {
        display: grid;
        grid-template-columns: 360px 1fr;
        gap: 24px;
        align-items: start;
        width: 100%;
        margin-bottom: 32px;
    }
    
    .control-panel {
        position: sticky;
        top: 20px;
    }
    
    .map-section {
        width: 100%;
    }
    
    #map {
        height: 500px;
        width: 100%;
        border-radius: 12px;
    }
    
    .map-panel {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
    }
    
    .map-footer {
        padding: 10px 18px;
        display: flex;
        justify-content: space-between;
        border-top: 1px solid var(--border);
        background: var(--white);
    }
    
    .map-hint {
        font-size: 11px;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    /* Results Section */
    #results-section {
        margin-top: 32px;
        margin-bottom: 48px;
    }
    
    .results-header {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px 24px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }
    
    .results-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .results-summary {
        display: flex;
        gap: 16px;
    }
    
    .summary-badge {
        background: var(--sand);
        padding: 8px 16px;
        border-radius: 40px;
        font-size: 14px;
        font-weight: 500;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .places-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .view-all-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 500;
        color: var(--teal);
        text-decoration: none;
        transition: all 0.2s;
    }

    .view-all-link:hover {
        gap: 12px;
        color: var(--navy);
    }
    
    .summary-badge i {
        color: var(--teal);
    }
    
    .summary-badge span {
        font-weight: 700;
        color: var(--navy);
    }
    
    .vehicles-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }
    
    .vehicle-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .vehicle-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    }
    
    .vehicle-card-header {
        padding: 16px 20px;
        background: linear-gradient(135deg, var(--sand) 0%, var(--white) 100%);
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .vehicle-icon-large {
        width: 48px;
        height: 48px;
        background: var(--teal-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: var(--teal);
    }
    
    .vehicle-title {
        flex: 1;
    }
    
    .vehicle-name {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0 0 4px 0;
    }
    
    .vehicle-price {
        font-size: 24px;
        font-weight: 700;
        color: var(--teal);
    }
    
    .vehicle-card-body {
        padding: 16px 20px;
    }
    
    .fare-breakdown {
        margin-bottom: 8px;
    }
    
    .breakdown-line {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        padding: 6px 0;
        color: var(--text-mid);
        border-bottom: 1px dashed var(--border);
    }
    
    .breakdown-line:last-child {
        border-bottom: none;
    }
    
    .night-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #fef3c7;
        color: #92400e;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
        margin-top: 8px;
    }
    
    .save-route-btn {
        width: 100%;
        padding: 14px;
        background: #f59e0b;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: background 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    .save-route-btn:hover {
        background: #d97706;
    }
    
    .login-note {
        text-align: center;
        padding: 14px;
        background: var(--sand);
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 13px;
        color: var(--text-muted);
    }
    
    .login-note i {
        margin-right: 8px;
        color: var(--teal);
    }
    
    .card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
    }
    
    .card-header {
        padding: 18px 20px 14px;
        border-bottom: 1px solid var(--border);
    }
    
    .card-title {
        font-size: 15px;
        font-weight: 600;
        color: var(--text-dark);
    }
    
    .card-sub {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 2px;
    }
    
    .card-body {
        padding: 18px 20px 20px;
    }
    
    .field {
        margin-bottom: 14px;
    }
    
    .field-label {
        font-size: 11px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: var(--text-muted);
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .field-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    
    .field-dot.green { background: #22c55e; }
    .field-dot.red { background: #ef4444; }
    
    .night-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 11px 14px;
        background: var(--sand);
        border: 1.5px solid var(--border);
        border-radius: 8px;
        margin-bottom: 18px;
    }
    
    .night-row-label {
        font-size: 13px;
        font-weight: 500;
        color: var(--text-mid);
    }
    
    .night-row-sub {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 1px;
    }
    
    .btn-find {
        width: 100%;
        padding: 13px;
        background: var(--navy);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: background .15s;
    }
    
    .btn-find:hover:not(:disabled) {
        background: var(--navy-mid);
    }
    
    .btn-find:disabled {
        opacity: .4;
        cursor: not-allowed;
    }
    
    .btn-reset {
        width: 100%;
        padding: 9px;
        background: none;
        border: none;
        font-size: 12px;
        font-family: 'Poppins', sans-serif;
        color: var(--text-muted);
        cursor: pointer;
        margin-top: 8px;
        border-radius: 8px;
        transition: color .15s, background .15s;
    }
    
    .btn-reset:hover {
        background: var(--sand);
        color: var(--text-dark);
    }
    
    /* Enhanced Places Section - Professional Style */
    .places-section {
        margin-top: 48px;
    }
    
    .section-title {
        font-size: 20px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 20px;
    }
    
    .section-title span {
        color: var(--teal);
    }
    
    .places-grid-enhanced {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 24px;
    }
    
    /* Professional Card Style - Minimal rounding, subtle elevation */
    .place-card-enhanced {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .place-card-enhanced:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    
    .place-img-enhanced {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }
    
    .place-img-fallback-enhanced {
        width: 100%;
        height: 180px;
        background: linear-gradient(135deg, #c8e6d8, #a8d4ee);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6aa8c0;
        font-size: 42px;
    }
    
    .place-body-enhanced {
        padding: 20px;
    }
    
    .place-header-enhanced {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 8px;
    }
    
    .place-name-enhanced {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
    }
    
    .place-category-enhanced {
        font-size: 10px;
        font-weight: 600;
        padding: 4px 10px;
        background: var(--teal-light);
        color: var(--teal);
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .place-desc-enhanced {
        font-size: 13px;
        color: var(--text-mid);
        line-height: 1.55;
        margin-bottom: 14px;
    }
    
    .place-stats-enhanced {
        display: flex;
        gap: 16px;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border);
    }
    
    .place-stats-item-enhanced {
        font-size: 11px;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .place-stats-item-enhanced i {
        color: var(--teal);
        font-size: 11px;
    }
    
    .place-buttons-enhanced {
        display: flex;
        gap: 10px;
    }
    
    .btn-route-enhanced {
        flex: 2;
        padding: 10px;
        background: var(--teal-light);
        color: var(--teal);
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: background .15s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }
    
    .btn-route-enhanced:hover {
        background: #b8e0d0;
    }
    
    .btn-save-place-enhanced {
        flex: 1;
        padding: 10px;
        background: #fef3c7;
        color: #92400e;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all .15s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }
    
    .btn-save-place-enhanced:hover {
        background: #fde68a;
        transform: translateY(-1px);
    }
    
    .btn-save-place-enhanced.saved {
        background: #f59e0b;
        color: #fff;
        cursor: default;
        opacity: 0.8;
    }
    
    .btn-save-place-disabled-enhanced {
        flex: 1;
        padding: 10px;
        background: #f3f4f6;
        color: #9ca3af;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }
    
    @media (max-width: 1024px) {
        .grid-layout {
            grid-template-columns: 320px 1fr;
            gap: 20px;
        }
        
        .vehicles-grid {
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        }
        
        .places-grid-enhanced {
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }
    }
    
    @media (max-width: 860px) {
        .grid-layout {
            grid-template-columns: 1fr;
        }
        
        .control-panel {
            position: static;
        }
        
        .results-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .places-grid-enhanced {
            grid-template-columns: 1fr;
            gap: 16px;
        }
        
        .place-stats-enhanced {
            flex-direction: column;
            gap: 6px;
        }
    }
</style>
@endsection

@push('scripts')
<script>
    // Main map initialization
    let map, originMarker = null, destMarker = null, routingControl = null;
    let originSet = false, destSet = false;
    let originLat, originLng, destLat, destLng;
    let searchTimeout = null;
    let modalSearchTimeout = null;
    
    // Mini map variables
    let miniMap = null;
    let tempMarker = null;
    let currentPickerType = null;
    let selectedLat = null, selectedLng = null, selectedAddress = null;
    
    // EXPANDED BOHOL BOUNDS
    var boholBounds = L.latLngBounds([
        [9.30, 123.50],
        [10.20, 124.80]
    ]);
    
    map = L.map('map', {
        minZoom: 9,
        maxBounds: boholBounds,
        maxBoundsViscosity: 1.0
    }).fitBounds(boholBounds, { padding: [25, 25] });
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    const greenIcon = L.divIcon({
        html: '<div style="width:13px;height:13px;background:#22c55e;border-radius:50%;border:2.5px solid #fff;box-shadow:0 2px 5px rgba(0,0,0,.25)"></div>',
        iconSize: [13, 13], iconAnchor: [6, 6]
    });
    
    const redIcon = L.divIcon({
        html: '<div style="width:13px;height:13px;background:#ef4444;border-radius:50%;border:2.5px solid #fff;box-shadow:0 2px 5px rgba(0,0,0,.25)"></div>',
        iconSize: [13, 13], iconAnchor: [6, 6]
    });
    
    const tempIcon = L.divIcon({
        html: '<div style="width:16px;height:16px;background:#3b82f6;border-radius:50%;border:3px solid #fff;box-shadow:0 0 0 2px #3b82f6"></div>',
        iconSize: [16, 16], iconAnchor: [8, 8]
    });
    
    // Search Functionality for main inputs
    function setupSearch(inputId, suggestionsId, onSelect) {
        const input = document.getElementById(inputId);
        const suggestions = document.getElementById(suggestionsId);
        
        input.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length < 3) {
                suggestions.classList.add('hidden');
                return;
            }
            
            searchTimeout = setTimeout(() => {
                searchLocations(query, suggestions, onSelect);
            }, 500);
        });
        
        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !suggestions.contains(e.target)) {
                suggestions.classList.add('hidden');
            }
        });
    }
    
    // Search Functionality for modal (pans only, doesn't select)
    function setupModalSearch() {
        const input = document.getElementById('modal-search-input');
        const suggestions = document.getElementById('modal-suggestions');
        
        input.addEventListener('input', function() {
            clearTimeout(modalSearchTimeout);
            const query = this.value.trim();
            
            if (query.length < 3) {
                suggestions.classList.add('hidden');
                return;
            }
            
            modalSearchTimeout = setTimeout(() => {
                searchLocationsForModal(query, suggestions);
            }, 500);
        });
        
        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !suggestions.contains(e.target)) {
                suggestions.classList.add('hidden');
            }
        });
    }
    
    async function searchLocationsForModal(query, suggestionsEl) {
        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&addressdetails=1&countrycodes=PH&bounded=1&viewbox=123.5,10.3,124.9,9.2`);
            const data = await response.json();
            
            if (data && data.length > 0) {
                displayModalSuggestions(data, suggestionsEl);
            } else {
                suggestionsEl.classList.add('hidden');
            }
        } catch (error) {
            console.error('Search error:', error);
            suggestionsEl.classList.add('hidden');
        }
    }
    
    function displayModalSuggestions(results, suggestionsEl) {
        suggestionsEl.innerHTML = '';
        
        results.forEach(result => {
            const item = document.createElement('div');
            item.className = 'modal-suggestion-item';
            item.innerHTML = `
                <div class="modal-suggestion-name">${result.display_name.split(',')[0]}</div>
                <div class="modal-suggestion-address">${result.display_name.split(',').slice(1, 4).join(',')}</div>
            `;
            item.addEventListener('click', () => {
                const lat = parseFloat(result.lat);
                const lng = parseFloat(result.lon);
                // PAN THE MINI-MAP to this location (does NOT set as selected)
                if (miniMap) {
                    miniMap.setView([lat, lng], 14);
                }
                suggestionsEl.classList.add('hidden');
                document.getElementById('modal-search-input').value = '';
                showToast('Map panned to location', 'info');
            });
            suggestionsEl.appendChild(item);
        });
        
        suggestionsEl.classList.remove('hidden');
    }
    
    async function searchLocations(query, suggestionsEl, onSelect) {
        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&addressdetails=1&countrycodes=PH&bounded=1&viewbox=123.5,10.3,124.9,9.2`);
            const data = await response.json();
            
            if (data && data.length > 0) {
                displaySuggestions(data, suggestionsEl, onSelect);
            } else {
                suggestionsEl.classList.add('hidden');
            }
        } catch (error) {
            console.error('Search error:', error);
            suggestionsEl.classList.add('hidden');
        }
    }
    
    function displaySuggestions(results, suggestionsEl, onSelect) {
        suggestionsEl.innerHTML = '';
        
        results.forEach(result => {
            const item = document.createElement('div');
            item.className = 'suggestion-item';
            item.innerHTML = `
                <div class="suggestion-name">${result.display_name.split(',')[0]}</div>
                <div class="suggestion-address">${result.display_name.split(',').slice(1, 4).join(',')}</div>
            `;
            item.addEventListener('click', () => {
                onSelect(parseFloat(result.lat), parseFloat(result.lon), result.display_name);
                suggestionsEl.classList.add('hidden');
            });
            suggestionsEl.appendChild(item);
        });
        
        suggestionsEl.classList.remove('hidden');
    }
    
    // Set Origin from Search - WITH MAP PANNING
    function setOriginFromSearch(lat, lng, address) {
        originLat = lat;
        originLng = lng;
        originSet = true;
        
        if (originMarker) map.removeLayer(originMarker);
        originMarker = L.marker([lat, lng], { icon: greenIcon }).addTo(map);
        
        document.getElementById('origin-input').value = address.split(',')[0];
        document.getElementById('origin-address').value = address;
        document.getElementById('origin-lat').value = lat;
        document.getElementById('origin-lng').value = lng;
        
        map.setView([lat, lng], 14);
        
        checkReady();
        showToast('Origin set!', 'success');
    }
    
    // Set Destination from Search - WITH MAP PANNING
    function setDestinationFromSearch(lat, lng, address) {
        destLat = lat;
        destLng = lng;
        destSet = true;
        
        if (destMarker) map.removeLayer(destMarker);
        destMarker = L.marker([lat, lng], { icon: redIcon }).addTo(map);
        
        document.getElementById('dest-input').value = address.split(',')[0];
        document.getElementById('dest-address').value = address;
        document.getElementById('dest-lat').value = lat;
        document.getElementById('dest-lng').value = lng;
        
        map.setView([lat, lng], 14);
        
        checkReady();
        showToast('Destination set!', 'success');
    }
    
    // Map Picker Modal Functions
    function openMapPicker(type) {
        currentPickerType = type;
        selectedLat = null;
        selectedLng = null;
        selectedAddress = null;
        
        const modal = document.getElementById('map-picker-modal');
        const modalTitle = document.getElementById('modal-title');
        const applyTypeSpan = document.getElementById('apply-type');
        const markerStatus = document.getElementById('temp-marker-status');
        const applyBtn = document.getElementById('apply-selection');
        const modalSearchInput = document.getElementById('modal-search-input');
        
        modalTitle.textContent = type === 'origin' ? 'Select Origin Location' : 'Select Destination Location';
        applyTypeSpan.textContent = type === 'origin' ? 'Origin' : 'Destination';
        markerStatus.textContent = '📍 Click on the map to select a location, or search above to pan';
        markerStatus.classList.remove('has-marker');
        applyBtn.disabled = true;
        modalSearchInput.value = '';
        
        modal.classList.remove('hidden');
        
        if (!miniMap) {
            setTimeout(() => {
                initMiniMap();
            }, 100);
        } else {
            if (tempMarker) {
                miniMap.removeLayer(tempMarker);
                tempMarker = null;
            }
            miniMap.setView([9.85, 124.2], 9);
        }
    }
    
    function initMiniMap() {
        const container = document.getElementById('mini-map');
        if (!container) return;
        
        if (miniMap) {
            miniMap.remove();
            miniMap = null;
        }
        
        miniMap = L.map('mini-map', {
            minZoom: 9,
            maxBounds: boholBounds,
            maxBoundsViscosity: 1.0
        }).setView([9.85, 124.2], 9);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(miniMap);
        
        miniMap.on('click', onMiniMapClick);
        
        setTimeout(() => {
            miniMap.invalidateSize();
        }, 100);
    }
    
    async function onMiniMapClick(e) {
        selectedLat = e.latlng.lat;
        selectedLng = e.latlng.lng;
        
        if (tempMarker) {
            miniMap.removeLayer(tempMarker);
        }
        tempMarker = L.marker([selectedLat, selectedLng], { icon: tempIcon }).addTo(miniMap);
        
        try {
            const address = await reverseGeocode(selectedLat, selectedLng);
            selectedAddress = address;
            const markerStatus = document.getElementById('temp-marker-status');
            const shortAddress = address.length > 60 ? address.substring(0, 57) + '...' : address;
            markerStatus.textContent = `✅ Selected: ${shortAddress}`;
            markerStatus.classList.add('has-marker');
            document.getElementById('apply-selection').disabled = false;
        } catch (error) {
            console.error('Reverse geocoding error:', error);
            selectedAddress = `${selectedLat.toFixed(4)}, ${selectedLng.toFixed(4)}`;
            const markerStatus = document.getElementById('temp-marker-status');
            markerStatus.textContent = `✅ Selected: ${selectedAddress}`;
            markerStatus.classList.add('has-marker');
            document.getElementById('apply-selection').disabled = false;
        }
    }
    
    async function reverseGeocode(lat, lng) {
        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`);
        const data = await response.json();
        return data.display_name || `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
    }
    
    function applyMapSelection() {
        if (!selectedLat || !selectedLng) return;
        
        if (currentPickerType === 'origin') {
            originLat = selectedLat;
            originLng = selectedLng;
            originSet = true;
            
            if (originMarker) map.removeLayer(originMarker);
            originMarker = L.marker([selectedLat, selectedLng], { icon: greenIcon }).addTo(map);
            
            const addressShort = selectedAddress.split(',')[0];
            document.getElementById('origin-input').value = addressShort;
            document.getElementById('origin-address').value = selectedAddress;
            document.getElementById('origin-lat').value = selectedLat;
            document.getElementById('origin-lng').value = selectedLng;
            
            showToast('Origin set from map!', 'success');
        } else {
            destLat = selectedLat;
            destLng = selectedLng;
            destSet = true;
            
            if (destMarker) map.removeLayer(destMarker);
            destMarker = L.marker([selectedLat, selectedLng], { icon: redIcon }).addTo(map);
            
            const addressShort = selectedAddress.split(',')[0];
            document.getElementById('dest-input').value = addressShort;
            document.getElementById('dest-address').value = selectedAddress;
            document.getElementById('dest-lat').value = selectedLat;
            document.getElementById('dest-lng').value = selectedLng;
            
            showToast('Destination set from map!', 'success');
        }
        
        closeMapPicker();
        checkReady();
        map.setView([selectedLat, selectedLng], 13);
    }
    
    function closeMapPicker() {
        const modal = document.getElementById('map-picker-modal');
        modal.classList.add('hidden');
        
        if (miniMap) {
            miniMap.off('click', onMiniMapClick);
            miniMap.remove();
            miniMap = null;
        }
        
        currentPickerType = null;
        selectedLat = null;
        selectedLng = null;
        selectedAddress = null;
        tempMarker = null;
    }
    
    function checkReady() {
        document.getElementById('find-route-btn').disabled = !(originSet && destSet);
    }
    
    // URL Parameter Handling
    function handleUrlParams() {
        const urlParams = new URLSearchParams(window.location.search);
        const destLat = urlParams.get('dest_lat');
        const destLng = urlParams.get('dest_lng');
        const destName = urlParams.get('dest_name');
        
        if (destLat && destLng && destName) {
            const decodedName = decodeURIComponent(destName);
            setDestinationFromSearch(parseFloat(destLat), parseFloat(destLng), decodedName);
            map.setView([parseFloat(destLat), parseFloat(destLng)], 13);
            showToast(`Destination set to ${decodedName}!`, 'success');
        }
    }
    
    // Event Listeners
    setupSearch('origin-input', 'origin-suggestions', setOriginFromSearch);
    setupSearch('dest-input', 'dest-suggestions', setDestinationFromSearch);
    setupModalSearch();
    
    document.querySelectorAll('.map-picker-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            openMapPicker(this.dataset.type);
        });
    });
    
    document.getElementById('close-modal-btn').addEventListener('click', closeMapPicker);
    document.getElementById('cancel-selection').addEventListener('click', closeMapPicker);
    document.getElementById('apply-selection').addEventListener('click', applyMapSelection);
    
    document.getElementById('map-picker-modal').addEventListener('click', function(e) {
        if (e.target === this) closeMapPicker();
    });
    
    document.getElementById('reset-btn').addEventListener('click', function() {
        resetSelection();
        showToast('Map has been reset', 'info');
    });
    
    document.getElementById('find-route-btn').addEventListener('click', async function() {
        const btn = this;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Calculating...';
        btn.disabled = true;
        
        try {
            await calculateRoute();
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    });
    
    async function calculateRoute() {
        if (!originSet || !destSet) {
            showToast('Please set both origin and destination first', 'warning');
            return;
        }
        
        if (routingControl) map.removeControl(routingControl);
        
        routingControl = L.Routing.control({
            waypoints: [L.latLng(originLat, originLng), L.latLng(destLat, destLng)],
            lineOptions: { styles: [{ color: '#0c2340', weight: 5, opacity: .85 }] },
            addWaypoints: false, draggableWaypoints: false, show: false
        }).addTo(map);
        
        routingControl.on('routesfound', async function(e) {
            const route = e.routes[0];
            const distanceKm = (route.summary.totalDistance / 1000).toFixed(2);
            const durationMin = Math.round(route.summary.totalTime / 60);
            
            document.getElementById('stat-dist').textContent = distanceKm;
            document.getElementById('stat-time').textContent = durationMin;
            
            const isNight = document.getElementById('night-travel').checked;
            await fetchFares(distanceKm, isNight);
            
            map.fitBounds(L.latLngBounds([
                L.latLng(originLat, originLng),
                L.latLng(destLat, destLng)
            ]), { padding: [50, 50] });
            
            document.getElementById('results-section').style.display = 'block';
            showToast('Route calculated successfully!', 'success');
            
            fetch('{{ route("log-search") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({
                    origin_lat: originLat, origin_lng: originLng,
                    dest_lat: destLat, dest_lng: destLng,
                    distance_km: distanceKm, duration_minutes: durationMin
                })
            });
        });
        
        routingControl.on('routingerror', function(e) {
            showToast('Could not find a route between these points. Try different locations.', 'error');
        });
    }
    
    async function fetchFares(distanceKm, isNight) {
        const res = await fetch('{{ route("calculate-fares") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ distance_km: distanceKm, is_night: isNight })
        });
        const fares = await res.json();
        
        document.getElementById('fare-list').innerHTML = fares.map(v => `
            <div class="vehicle-card">
                <div class="vehicle-card-header">
                    <div class="vehicle-icon-large"><i class="${v.icon}"></i></div>
                    <div class="vehicle-title">
                        <div class="vehicle-name">${v.name}</div>
                        <div class="vehicle-price">₱${v.fare}</div>
                    </div>
                </div>
                <div class="vehicle-card-body">
                    <div class="fare-breakdown">
                        <div class="breakdown-line">
                            <span>Base fare (first ${v.base_km}km)</span>
                            <span>₱${v.base_fare}</span>
                        </div>
                        <div class="breakdown-line">
                            <span>Additional distance (${(distanceKm - v.base_km).toFixed(2)}km × ₱${v.per_km_rate})</span>
                            <span>₱${((distanceKm - v.base_km) * v.per_km_rate).toFixed(2)}</span>
                        </div>
                        ${v.is_night_applied ? `
                        <div class="breakdown-line" style="color: #92400e;">
                            <span>🌙 Night surcharge (8PM-5AM)</span>
                            <span>+₱${v.night_surcharge}</span>
                        </div>` : ''}
                    </div>
                    ${v.is_night_applied ? '<div class="night-badge"><i class="fas fa-moon"></i> Night surcharge applied</div>' : ''}
                </div>
            </div>
        `).join('');
        
        @auth
            const saveBtn = document.getElementById('save-route-btn');
            if (saveBtn) saveBtn.style.display = 'flex';
        @else
            const loginNote = document.getElementById('login-note');
            if (loginNote) loginNote.style.display = 'block';
        @endauth
    }
    
    function resetSelection() {
        if (originMarker) map.removeLayer(originMarker);
        if (destMarker) map.removeLayer(destMarker);
        if (routingControl) map.removeControl(routingControl);
        
        @auth
            const saveBtn = document.getElementById('save-route-btn');
            if (saveBtn) saveBtn.style.display = 'none';
        @else
            const loginNote = document.getElementById('login-note');
            if (loginNote) loginNote.style.display = 'none';
        @endauth
        
        originMarker = destMarker = routingControl = null;
        originSet = destSet = false;
        originLat = originLng = destLat = destLng = null;
        
        document.getElementById('origin-input').value = '';
        document.getElementById('origin-address').value = '';
        document.getElementById('origin-lat').value = '';
        document.getElementById('origin-lng').value = '';
        
        document.getElementById('dest-input').value = '';
        document.getElementById('dest-address').value = '';
        document.getElementById('dest-lat').value = '';
        document.getElementById('dest-lng').value = '';
        
        document.getElementById('find-route-btn').disabled = true;
        document.getElementById('results-section').style.display = 'none';
        
        map.fitBounds(boholBounds, { padding: [25, 25] });
    }
    
    // Set destination from enhanced place card
    document.querySelectorAll('.set-destination-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const lat = parseFloat(this.dataset.lat);
            const lng = parseFloat(this.dataset.lng);
            const name = this.dataset.name;
            
            setDestinationFromSearch(lat, lng, name);
            map.setView([lat, lng], 13);
        });
    });
    
    // Save route for authenticated users
    @auth
    document.body.addEventListener('click', function(e) {
        const saveBtn = e.target.closest('#save-route-btn');
        if (!saveBtn) return;
        
        if (!originSet || !destSet) {
            showToast('Please set both origin and destination first', 'warning');
            return;
        }
        
        const originName = document.getElementById('origin-input').value || 'Origin';
        const destName = document.getElementById('dest-input').value || 'Destination';
        const routeName = `${originName} → ${destName}`;
        
        const originalText = saveBtn.innerHTML;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        saveBtn.disabled = true;
        
        fetch('{{ route("user.save-route") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                name: routeName,
                origin_lat: originLat, origin_lng: originLng,
                origin_address: document.getElementById('origin-address').value,
                dest_lat: destLat, dest_lng: destLng,
                dest_address: document.getElementById('dest-address').value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Route saved successfully!', 'success');
            } else {
                showToast(data.message || 'Error saving route', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error saving route', 'error');
        })
        .finally(() => {
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
        });
    });
    @endauth
    
    // Save place button handlers for enhanced cards - TOGGLE VERSION
    document.querySelectorAll('.save-place-btn').forEach(btn => {
        // Remove any existing disabled attribute
        btn.disabled = false;
        
        btn.addEventListener('click', async function(e) {
            e.preventDefault();
            
            // Prevent double clicks
            if (this.disabled) return;
            this.disabled = true;
            
            const placeId = this.dataset.id;
            const placeName = this.dataset.name;
            const placeLat = this.dataset.lat;
            const placeLng = this.dataset.lng;
            const wasSaved = this.classList.contains('saved');
            const originalText = this.innerHTML;
            
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ...';
            
            try {
                const response = await fetch('{{ route("user.toggle-bookmark") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        place_id: placeId,
                        name: placeName,
                        latitude: placeLat,
                        longitude: placeLng
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    const card = this.closest('.place-card-enhanced, .place-card');
                    const savesSpan = card.querySelector('.place-stats-item-enhanced:last-child span, .place-stats-item:last-child span');
                    
                    if (data.action === 'saved') {
                        this.innerHTML = '<i class="fas fa-bookmark"></i> Saved';
                        this.classList.add('saved');
                        showToast('Location saved!', 'success');
                    } else {
                        this.innerHTML = '<i class="fas fa-bookmark"></i> Save';
                        this.classList.remove('saved');
                        showToast('Location removed from saved', 'info');
                    }
                    
                    if (savesSpan && data.saved_count !== undefined) {
                        savesSpan.textContent = data.saved_count;
                    }
                } else {
                    this.innerHTML = originalText;
                    if (wasSaved) {
                        this.classList.add('saved');
                    } else {
                        this.classList.remove('saved');
                    }
                    showToast(data.message || 'Error', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                this.innerHTML = originalText;
                if (wasSaved) {
                    this.classList.add('saved');
                } else {
                    this.classList.remove('saved');
                }
                showToast('Error saving location', 'error');
            } finally {
                this.disabled = false;
            }
        });
    });
    
    // Initialize URL parameter handling
    document.addEventListener('DOMContentLoaded', function() {
        handleUrlParams();
    });
</script>
@endpush