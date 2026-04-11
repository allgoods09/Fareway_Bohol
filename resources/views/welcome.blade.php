{{-- resources/views/welcome.blade.php --}}
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
            <div class="hero-stat-num">4</div>
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
                <div class="card-sub">Set your origin & destination on the map</div>
            </div>
            <div class="card-body">
                <div class="field">
                    <div class="field-label">
                        <div class="field-dot green"></div> Origin
                    </div>
                    <div id="origin-display" class="field-value">Left-click the map to set start point</div>
                    <input type="hidden" id="origin-lat">
                    <input type="hidden" id="origin-lng">
                    <input type="hidden" id="origin-address">
                </div>

                <div class="field">
                    <div class="field-label">
                        <div class="field-dot red"></div> Destination
                    </div>
                    <div id="dest-display" class="field-value">Right-click the map to set destination</div>
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
                    <div class="hint-dot" style="background:#22c55e"></div>
                    Left-click: origin
                </div>
                <div class="map-hint">
                    <div class="hint-dot" style="background:#ef4444"></div>
                    Right-click: destination
                </div>
                <div class="map-hint">
                    <div class="hint-dot" style="background:var(--navy)"></div>
                    Blue line: route
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Route Results Section (below map) --}}
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
    
    <div class="vehicles-grid" id="fare-list">
        <!-- Vehicle cards will be inserted here -->
    </div>
    
    @auth
        <button id="save-route-btn" class="save-route-btn">
            <i class="far fa-bookmark"></i> Save This Route
        </button>
    @else
        <div class="login-note">
            <i class="fas fa-lock"></i> Login to save routes
        </div>
    @endauth
</div>

{{-- Popular Places --}}
<div class="places-section">
    <div class="places-header">
        <h2 class="section-title">Top 5 <span>Tourist Spots</span> in Bohol</h2>
        <a href="{{ route('user.recommended-places') }}" class="view-all-link">
            View All <i class="fas fa-arrow-right"></i>
        </a>
    </div>
    <div class="places-grid">
        @foreach($recommendedPlaces as $place)
        <div class="place-card">
            @if($place->image_url)
                <img src="{{ $place->image_url }}" alt="{{ $place->name }}" class="place-img">
            @else
                <div class="place-img-fallback">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
            @endif
            <div class="place-body">
                <div class="place-name">{{ $place->name }}</div>
                <p class="place-desc">{{ Str::limit($place->description, 72) }}</p>
                <div class="place-buttons">
                    <button class="btn-route"
                            onclick="setDestinationFromPlace({{ $place->latitude }}, {{ $place->longitude }}, '{{ addslashes($place->name) }}')">
                        <i class="fas fa-route"></i> Route to here
                    </button>
                    @auth
                        <button class="btn-save-place save-place-btn" 
                                data-id="{{ $place->id }}"
                                data-name="{{ addslashes($place->name) }}"
                                data-lat="{{ $place->latitude }}"
                                data-lng="{{ $place->longitude }}">
                            <i class="far fa-bookmark"></i> Save
                        </button>
                    @else
                        <button class="btn-save-place-disabled" onclick="alert('Please login to save locations')">
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
        border-radius: 16px;
    }
    
    .map-panel {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 16px;
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
    
    .hint-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
    }
    
    /* Results Section */
    #results-section {
        margin-top: 32px;
        margin-bottom: 48px;
    }
    
    .results-header {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 16px;
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
        margin-bottom: 20px;
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
    
    /* Vehicle Cards Grid */
    .vehicles-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }
    
    .vehicle-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 16px;
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
        border-radius: 12px;
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
        border-radius: 12px;
        font-size: 13px;
        color: var(--text-muted);
    }
    
    .login-note i {
        margin-right: 8px;
        color: var(--teal);
    }
    
    /* Card styles */
    .card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 16px;
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
    
    .field-value {
        background: var(--sand);
        border: 1.5px solid var(--border);
        border-radius: 9px;
        padding: 11px 14px;
        font-size: 13px;
        font-family: 'Poppins', sans-serif;
        color: var(--text-muted);
        min-height: 50px;
        display: flex;
        align-items: center;
        font-style: italic;
        transition: border-color .15s;
    }
    
    .field-value.set {
        color: var(--text-dark);
        font-style: normal;
    }
    
    .night-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 11px 14px;
        background: var(--sand);
        border: 1.5px solid var(--border);
        border-radius: 9px;
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
        border-radius: 10px;
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
    
    /* Places Section */
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
    
    .places-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 20px;
    }
    
    .place-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 14px;
        overflow: hidden;
        transition: transform .2s, box-shadow .2s;
    }
    
    .place-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,.08);
    }
    
    .place-img {
        width: 100%;
        height: 136px;
        object-fit: cover;
    }
    
    .place-img-fallback {
        width: 100%;
        height: 136px;
        background: linear-gradient(135deg, #c8e6d8, #a8d4ee);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6aa8c0;
        font-size: 28px;
    }
    
    .place-body {
        padding: 14px;
    }
    
    .place-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 4px;
    }
    
    .place-desc {
        font-size: 12px;
        color: var(--text-muted);
        line-height: 1.55;
        margin-bottom: 12px;
    }
    
    .place-buttons {
        display: flex;
        gap: 8px;
    }
    
    .btn-route {
        flex: 2;
        padding: 8px;
        background: var(--teal-light);
        color: var(--teal);
        border: none;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: background .15s;
    }
    
    .btn-route:hover {
        background: #b8e0d0;
    }
    
    .btn-save-place {
        flex: 1;
        padding: 8px;
        background: #fef3c7;
        color: #92400e;
        border: none;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all .15s;
    }
    
    .btn-save-place:hover {
        background: #fde68a;
        transform: translateY(-1px);
    }
    
    .btn-save-place.saved {
        background: #f59e0b;
        color: #fff;
    }
    
    .btn-save-place-disabled {
        flex: 1;
        padding: 8px;
        background: #f3f4f6;
        color: #9ca3af;
        border: none;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
    }
    
    @media (max-width: 1024px) {
        .grid-layout {
            grid-template-columns: 320px 1fr;
            gap: 20px;
        }
        
        .vehicles-grid {
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
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
    }
</style>
@endsection

@push('scripts')
<script>
    // All your existing JavaScript remains exactly the same
    let map, originMarker = null, destMarker = null, routingControl = null;
    let originSet = false, destSet = false;
    let originLat, originLng, destLat, destLng;

    var boholBounds = L.latLngBounds([
        [9.40, 123.70],
        [10.05, 124.70]
    ]);
    map = L.map('map').fitBounds(boholBounds, { padding: [25, 25] });
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

    map.on('click', e => setOrigin(e.latlng.lat, e.latlng.lng));
    map.on('contextmenu', e => { e.originalEvent.preventDefault(); setDestination(e.latlng.lat, e.latlng.lng); });

    async function setOrigin(lat, lng) {
        originLat = lat; originLng = lng; originSet = true;
        if (originMarker) map.removeLayer(originMarker);
        originMarker = L.marker([lat, lng], { icon: greenIcon }).addTo(map);
        const address = await reverseGeocode(lat, lng);
        const el = document.getElementById('origin-display');
        el.classList.add('set');
        el.innerHTML = `<i class="fas fa-map-marker-alt" style="color:#22c55e;margin-right:8px;font-size:12px"></i>${address.substring(0, 55)}`;
        document.getElementById('origin-address').value = address;
        document.getElementById('origin-lat').value = lat;
        document.getElementById('origin-lng').value = lng;
        checkReady();
    }

    async function setDestination(lat, lng) {
        destLat = lat; destLng = lng; destSet = true;
        if (destMarker) map.removeLayer(destMarker);
        destMarker = L.marker([lat, lng], { icon: redIcon }).addTo(map);
        const address = await reverseGeocode(lat, lng);
        const el = document.getElementById('dest-display');
        el.classList.add('set');
        el.innerHTML = `<i class="fas fa-flag-checkered" style="color:#ef4444;margin-right:8px;font-size:12px"></i>${address.substring(0, 55)}`;
        document.getElementById('dest-address').value = address;
        document.getElementById('dest-lat').value = lat;
        document.getElementById('dest-lng').value = lng;
        checkReady();
    }

    async function reverseGeocode(lat, lng) {
        try {
            const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18`);
            const data = await res.json();
            return data.display_name?.split(',')[0] || `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
        } catch {
            return `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
        }
    }

    function checkReady() {
        document.getElementById('find-route-btn').disabled = !(originSet && destSet);
    }

    document.getElementById('find-route-btn').addEventListener('click', calculateRoute);
    document.getElementById('reset-btn').addEventListener('click', resetSelection);

    async function calculateRoute() {
        if (!originSet || !destSet) return;
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
        @endauth
    }

    function resetSelection() {
        if (originMarker) map.removeLayer(originMarker);
        if (destMarker)   map.removeLayer(destMarker);
        if (routingControl) map.removeControl(routingControl);

        originMarker = destMarker = routingControl = null;
        originSet = destSet = false;

        const originEl = document.getElementById('origin-display');
        originEl.classList.remove('set');
        originEl.textContent = 'Left-click the map to set start point';

        const destEl = document.getElementById('dest-display');
        destEl.classList.remove('set');
        destEl.textContent = 'Right-click the map to set destination';

        ['origin-lat','origin-lng','dest-lat','dest-lng','origin-address','dest-address']
            .forEach(id => { document.getElementById(id).value = ''; });

        document.getElementById('find-route-btn').disabled = true;
        document.getElementById('results-section').style.display = 'none';
        
        var boholBounds = L.latLngBounds([
            [9.40, 123.70],
            [10.05, 124.70]
        ]);
        map.fitBounds(boholBounds, { padding: [25, 25] });
    }

    function setDestinationFromPlace(lat, lng, name) {
        setDestination(lat, lng);
        map.setView([lat, lng], 13);
        const el = document.getElementById('dest-display');
        el.classList.add('set');
        el.innerHTML = `<i class="fas fa-flag-checkered" style="color:#ef4444;margin-right:8px;font-size:12px"></i>${name}`;
    }

    @auth
    document.getElementById('save-route-btn')?.addEventListener('click', function () {
        const routeName = prompt(
            'Name this route:',
            document.getElementById('origin-address').value.split(',')[0]
            + ' → '
            + document.getElementById('dest-address').value.split(',')[0]
        );
        if (!routeName) return;
        fetch('{{ route("user.save-route") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({
                name: routeName,
                origin_lat: originLat, origin_lng: originLng,
                origin_address: document.getElementById('origin-address').value,
                dest_lat: destLat, dest_lng: destLng,
                dest_address: document.getElementById('dest-address').value
            })
        }).then(() => alert('Route saved!'));
    });

    document.querySelectorAll('.save-place-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const placeId = this.dataset.id;
            const placeName = this.dataset.name;
            const placeLat = this.dataset.lat;
            const placeLng = this.dataset.lng;
            
            try {
                const response = await fetch('{{ route("user.save-location") }}', {
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
                    this.innerHTML = '<i class="fas fa-bookmark"></i> Saved';
                    this.classList.add('saved');
                    this.disabled = true;
                    showNotification('Location saved!', 'success');
                }
            } catch (error) {
                console.error('Error saving place:', error);
                showNotification('Error saving location', 'error');
            }
        });
    });
    @endauth

    window.setDestinationFromPlace = setDestinationFromPlace;
</script>
@endpush