{{-- resources/views/find-route.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="hero-section">
    <div class="container text-center">
        <h1 class="display-4">Find Your Route</h1>
        <p class="lead">Pin your origin and destination to calculate fare, distance, and estimated travel time</p>
    </div>
</div>

<div class="container mt-4">
    <div class="row">
        <!-- Map Column -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <!-- Search Bar -->
                    <div class="mb-3">
                        <input type="text" id="search-location" class="form-control" placeholder="Search for a location... (pans map only)">
                        <small class="text-muted">Type a location to pan the map - does not set origin/destination</small>
                    </div>
                    
                    <!-- Map -->
                    <div id="map" class="map-container"></div>
                    
                    <!-- Instructions -->
                    <div class="mt-3 text-center">
                        <small class="text-muted">
                            <i class="fas fa-map-marker-alt text-success"></i> Click on map to set Origin | 
                            <i class="fas fa-flag-checkered text-danger"></i> Click for Destination
                        </small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Controls Column -->
        <div class="col-lg-4">
            <!-- Origin & Destination Inputs -->
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-map-marker-alt text-success"></i> Origin
                        </label>
                        <input type="text" id="origin-address" class="form-control" placeholder="Click on map to set origin" readonly>
                        <button id="use-current-location" class="btn btn-sm btn-outline-primary mt-2">
                            <i class="fas fa-location-dot"></i> Use My Current Location
                        </button>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-flag-checkered text-danger"></i> Destination
                        </label>
                        <input type="text" id="dest-address" class="form-control" placeholder="Click on map to set destination" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-moon"></i> Night Travel?
                        </label>
                        <div class="form-check">
                            <input type="checkbox" id="night-travel" class="form-check-input">
                            <label class="form-check-label">Apply night surcharge (8PM - 5AM)</label>
                        </div>
                    </div>
                    
                    <button id="find-route-btn" class="btn btn-primary w-100" disabled>
                        <i class="fas fa-search"></i> Find Route
                    </button>
                </div>
            </div>
            
            <!-- Route Results -->
            <div id="results" style="display: none;">
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-info-circle"></i> Route Summary</h6>
                    </div>
                    <div class="card-body">
                        <div id="route-summary"></div>
                    </div>
                </div>
                
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="fas fa-money-bill-wave"></i> Fare Estimates</h6>
                    </div>
                    <div class="card-body" id="fare-estimates"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize map centered on Bohol
    var map = L.map('map').setView([9.9210, 124.2900], 12);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Variables
    var originMarker = null;
    var destMarker = null;
    var originLat = null, originLng = null;
    var destLat = null, destLng = null;
    var routeLayer = null;
    
    // Search control
    var geocoder = L.Control.geocoder({
        defaultMarkGeocode: false
    }).on('markgeocode', function(e) {
        var bbox = e.geocode.bbox;
        var center = e.geocode.center;
        map.fitBounds(bbox);
    }).addTo(map);
    
    // Search input handler
    document.getElementById('search-location').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            var query = this.value;
            if (query) {
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.length > 0) {
                            map.setView([data[0].lat, data[0].lon], 14);
                        }
                    });
            }
        }
    });
    
    // Click on map to set origin (left click) or destination (right click)
    map.on('click', function(e) {
        setOrigin(e.latlng.lat, e.latlng.lng);
    });
    
    map.on('contextmenu', function(e) {
        e.originalEvent.preventDefault();
        setDestination(e.latlng.lat, e.latlng.lng);
    });
    
    function setOrigin(lat, lng) {
        originLat = lat;
        originLng = lng;
        
        if (originMarker) map.removeLayer(originMarker);
        originMarker = L.marker([lat, lng], {icon: L.divIcon({className: 'bg-success rounded-circle p-2', html: '<i class="fas fa-map-marker-alt text-white"></i>'})}).addTo(map);
        
        // Reverse geocode
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('origin-address').value = data.display_name || `${lat}, ${lng}`;
            });
        
        checkRouteReady();
    }
    
    function setDestination(lat, lng) {
        destLat = lat;
        destLng = lng;
        
        if (destMarker) map.removeLayer(destMarker);
        destMarker = L.marker([lat, lng], {icon: L.divIcon({className: 'bg-danger rounded-circle p-2', html: '<i class="fas fa-flag-checkered text-white"></i>'})}).addTo(map);
        
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('dest-address').value = data.display_name || `${lat}, ${lng}`;
            });
        
        checkRouteReady();
    }
    
    function checkRouteReady() {
        document.getElementById('find-route-btn').disabled = !(originLat && destLat);
    }
    
    document.getElementById('use-current-location').addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                setOrigin(position.coords.latitude, position.coords.longitude);
                map.setView([position.coords.latitude, position.coords.longitude], 14);
            }, function() {
                alert('Unable to get your location. Please click on the map to set origin.');
            });
        } else {
            alert('Geolocation not supported. Please click on the map to set origin.');
        }
    });
    
    document.getElementById('find-route-btn').addEventListener('click', function() {
        calculateRoute();
    });
    
    function calculateRoute() {
        const url = `https://router.project-osrm.org/route/v1/driving/${originLng},${originLat};${destLng},${destLat}?overview=full&geometries=geojson`;
        
        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (data.code !== 'Ok') {
                    alert('Could not calculate route. Please try different points.');
                    return;
                }
                
                const route = data.routes[0];
                const distanceKm = (route.distance / 1000).toFixed(2);
                const durationMin = Math.round(route.duration / 60);
                
                // Draw route on map
                if (routeLayer) map.removeLayer(routeLayer);
                routeLayer = L.geoJSON(route.geometry, {
                    style: {color: '#007bff', weight: 5, opacity: 0.7}
                }).addTo(map);
                map.fitBounds(routeLayer.getBounds());
                
                // Display route summary
                document.getElementById('route-summary').innerHTML = `
                    <p><strong>Distance:</strong> ${distanceKm} km</p>
                    <p><strong>Estimated Travel Time:</strong> ${durationMin} minutes</p>
                `;
                
                // Calculate fares for each vehicle type
                fetchFares(distanceKm);
                
                // Show results
                document.getElementById('results').style.display = 'block';
                
                // Log search
                fetch('{{ route("log-search") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        origin_lat: originLat,
                        origin_lng: originLng,
                        dest_lat: destLat,
                        dest_lng: destLng,
                        distance_km: distanceKm,
                        duration_minutes: durationMin
                    })
                });
            });
    }
    
    function fetchFares(distanceKm) {
        const isNight = document.getElementById('night-travel').checked;
        
        fetch('{{ route("calculate-fares") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                distance_km: distanceKm,
                is_night: isNight
            })
        })
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('fare-estimates');
            container.innerHTML = '';
            
            data.forEach(vehicle => {
                container.innerHTML += `
                    <div class="fare-card mb-3 p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="${vehicle.icon} vehicle-icon me-2"></i>
                                <strong>${vehicle.name}</strong>
                            </div>
                            <div>
                                <span class="h5 text-success">₱${vehicle.fare}</span>
                                @auth
                                    <button class="btn btn-sm btn-outline-warning ms-2 save-route-btn" 
                                            data-vehicle="${vehicle.name}"
                                            data-fare="${vehicle.fare}">
                                        <i class="far fa-bookmark"></i> Save
                                    </button>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary ms-2" 
                                            onclick="alert('Please login to save routes')">
                                        <i class="fas fa-lock"></i> Login to Save
                                    </button>
                                @endauth
                            </div>
                        </div>
                        <small class="text-muted">Base fare: ₱${vehicle.base_fare} | Base km: ${vehicle.base_km} | ₱${vehicle.per_km_rate}/km after</small>
                        ${vehicle.is_night_applied ? '<small class="text-warning d-block"><i class="fas fa-moon"></i> Night surcharge applied (₱' + vehicle.night_surcharge + ')</small>' : ''}
                    </div>
                `;
            });
            
            // Add save route handlers for logged-in users
            @auth
            document.querySelectorAll('.save-route-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    saveRoute();
                });
            });
            @endauth
        });
    }
    
    @auth
    function saveRoute() {
        const routeName = prompt('Enter a name for this route:', `${document.getElementById('origin-address').value.split(',')[0]} → ${document.getElementById('dest-address').value.split(',')[0]}`);
        if (!routeName) return;
        
        fetch('{{ route("user.save-route") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                name: routeName,
                origin_lat: originLat,
                origin_lng: originLng,
                origin_address: document.getElementById('origin-address').value,
                dest_lat: destLat,
                dest_lng: destLng,
                dest_address: document.getElementById('dest-address').value
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast('Route saved successfully!', 'success');
            }
        });
    }
    @endauth

    window.addEventListener('load', function() {
        const destLat = sessionStorage.getItem('dest_lat');
        const destLng = sessionStorage.getItem('dest_lng');
        const destName = sessionStorage.getItem('dest_name');
        
        if (destLat && destLng) {
            setDestination(parseFloat(destLat), parseFloat(destLng));
            sessionStorage.removeItem('dest_lat');
            sessionStorage.removeItem('dest_lng');
            sessionStorage.removeItem('dest_name');
            
            if (destName) {
                document.getElementById('dest-address').value = destName;
            }
            
            // Center map on destination
            map.setView([destLat, destLng], 13);
        }
    });
</script>
@endpush
@endsection