@extends('layouts.user')

@section('title', 'Report an Issue')

@section('hero-content')
<div class="hero-content">
    <div class="hero-tag">
        <div class="hero-tag-dot"></div>
        Report Issue
    </div>
    <h1>Report <span>an Issue</span></h1>
    <p>Help us improve Fareway Bohol by reporting any problems you encounter</p>
</div>
@endsection

@section('content')
<div class="report-container">
    <div class="my-reports-link">
        <a href="{{ route('user.my-reports') }}" class="my-reports-btn">
            <i class="fas fa-list-alt"></i> View My Reports
        </a>
    </div>
    <div class="report-card">
        <form action="{{ route('user.report.store') }}" method="POST" enctype="multipart/form-data" class="report-form" id="report-form">
            @csrf

            <div class="form-group">
                <label for="type" class="form-label">
                    <i class="fas fa-tag"></i> Issue Type *
                </label>
                <select name="type" id="type" class="form-control" required>
                    <option value="">Select issue type</option>
                    <option value="wrong_fare">Wrong Fare Calculation</option>
                    <option value="road_closure">Road Closure / Detour</option>
                    <option value="vehicle_unavailable">Vehicle Not Available</option>
                    <option value="technical_issue">Technical Issue with App</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">
                    <i class="fas fa-align-left"></i> Description *
                </label>
                <textarea name="description" id="description" rows="6" class="form-control" 
                          placeholder="Please describe the issue in detail..." required></textarea>
            </div>

            <!-- Location Section -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-map-marker-alt"></i> Location (Optional)
                </label>
                <button type="button" id="pick-on-map-btn" class="pick-map-btn">
                    <i class="fas fa-map-marker-alt"></i> Pick location on map
                </button>
                
                <!-- Selected Locations Display -->
                <div class="selected-locations" id="selected-locations" style="display: none;">
                    <div class="location-display" id="origin-display">
                        <i class="fas fa-map-marker-alt" style="color: #22c55e;"></i>
                        <span class="location-label">Origin:</span>
                        <span class="location-name" id="origin-name">Not set</span>
                    </div>
                    <div class="location-display" id="dest-display" style="display: none;">
                        <i class="fas fa-flag-checkered" style="color: #ef4444;"></i>
                        <span class="location-label">Destination:</span>
                        <span class="location-name" id="dest-name">Not set</span>
                    </div>
                </div>
                <input type="hidden" id="origin_lat" name="origin_lat">
                <input type="hidden" id="origin_lng" name="origin_lng">
                <input type="hidden" id="origin_address" name="origin_info">
                <input type="hidden" id="dest_lat" name="dest_lat">
                <input type="hidden" id="dest_lng" name="dest_lng">
                <input type="hidden" id="dest_address" name="dest_info">
            </div>

            <div class="form-group">
                <label for="screenshot" class="form-label">
                    <i class="fas fa-image"></i> Screenshot (Optional)
                </label>
                <input type="file" name="screenshot" id="screenshot" class="form-control" accept="image/*">
                <p class="form-hint">Upload a screenshot if it helps explain the issue (max 5MB)</p>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane"></i> Submit Report
                </button>
                <a href="{{ route('user.report.create') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Map Modal -->
<div id="map-modal" class="map-modal">
    <div class="map-modal-overlay"></div>
    <div class="map-modal-content">
        <div class="map-modal-header">
            <h3 id="map-modal-title">Pick Location</h3>
            <button type="button" id="close-map-modal" class="map-modal-close">&times;</button>
        </div>
        <div id="pick-map" style="height: 400px; width: 100%; border-radius: 12px;"></div>
        <div class="map-modal-footer">
            <div class="mode-buttons">
                <button id="set-origin-mode" class="mode-btn active">📍 Set Origin</button>
                <button id="set-dest-mode" class="mode-btn">🏁 Set Destination</button>
            </div>
            <div class="selection-status">
                <span id="origin-status" class="status-badge">Origin: Not set</span>
                <span id="dest-status" class="status-badge">Destination: Not set</span>
            </div>
            <button id="apply-selection" class="apply-btn">✓ Apply Selection & Close</button>
        </div>
    </div>
</div>

<style>
    .report-container {
        max-width: 700px;
        margin: 0 auto;
    }

    .my-reports-link {
        text-align: right;
        margin-bottom: 16px;
    }

    .my-reports-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: var(--white);
        color: var(--teal);
        border: 1px solid var(--border);
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
    }

    .my-reports-btn:hover {
        background: var(--teal-light);
        border-color: var(--teal);
        transform: translateY(-1px);
    }

    .report-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 32px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .report-form {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-mid);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-label i {
        color: var(--teal);
        font-size: 12px;
    }

    .form-control {
        padding: 12px 16px;
        border: 1.5px solid var(--border);
        border-radius: 12px;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
        transition: all 0.2s;
        width: 100%;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--teal);
        box-shadow: 0 0 0 3px rgba(14,138,110,0.1);
    }

    select.form-control {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
    }

    textarea.form-control {
        resize: vertical;
    }

    .form-hint {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    .form-actions {
        display: flex;
        gap: 16px;
        margin-top: 8px;
    }

    .btn-submit {
        flex: 1;
        padding: 12px 24px;
        background: var(--teal);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-submit:hover {
        background: #0c7a60;
        transform: translateY(-1px);
    }

    .btn-cancel {
        flex: 1;
        padding: 12px 24px;
        background: var(--sand);
        color: var(--text-mid);
        border: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-cancel:hover {
        background: #e5e8eb;
        transform: translateY(-1px);
    }

    /* Pick on Map Button */
    .pick-map-btn {
        width: 100%;
        padding: 12px 16px;
        background: var(--sand);
        color: var(--teal);
        border: 1px solid var(--border);
        border-radius: 12px;
        font-size: 14px;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .pick-map-btn:hover {
        background: var(--teal-light);
        border-color: var(--teal);
    }

    /* Selected Locations Display */
    .selected-locations {
        margin-top: 12px;
        background: var(--sand);
        border-radius: 12px;
        padding: 12px;
    }

    .location-display {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 0;
        border-bottom: 1px solid var(--border);
    }

    .location-display:last-child {
        border-bottom: none;
    }

    .location-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-mid);
    }

    .location-name {
        font-size: 13px;
        color: var(--text-dark);
        font-weight: 500;
    }

    /* Map Modal */
    .map-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .map-modal.active {
        display: flex;
    }

    .map-modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
    }

    .map-modal-content {
        position: relative;
        background: var(--white);
        border-radius: 20px;
        width: 90%;
        max-width: 900px;
        overflow: hidden;
        animation: modalSlideIn 0.3s ease;
        z-index: 10000;
    }

    #toast-container {
        z-index: 10001 !important;
    }

    .map-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 20px;
        background: var(--sand);
        border-bottom: 1px solid var(--border);
    }

    .map-modal-header h3 {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-dark);
        margin: 0;
    }

    .map-modal-close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: var(--text-muted);
        transition: color 0.2s;
    }

    .map-modal-close:hover {
        color: var(--text-dark);
    }

    .map-modal-footer {
        padding: 16px 20px;
        background: var(--sand);
        border-top: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .mode-buttons {
        display: flex;
        gap: 12px;
        justify-content: center;
    }

    .mode-btn {
        padding: 8px 20px;
        background: var(--white);
        color: var(--text-mid);
        border: 1px solid var(--border);
        border-radius: 25px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .mode-btn.active {
        background: var(--teal);
        color: #fff;
        border-color: var(--teal);
    }

    .mode-btn:hover:not(.active) {
        background: var(--teal-light);
        border-color: var(--teal);
    }

    .selection-status {
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .status-badge {
        font-size: 11px;
        padding: 4px 12px;
        border-radius: 20px;
        background: var(--white);
        color: var(--text-mid);
    }

    .status-badge.set {
        background: #d1fae5;
        color: #065f46;
    }

    .apply-btn {
        padding: 10px 20px;
        background: var(--teal);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .apply-btn:hover {
        background: #0c7a60;
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .report-card {
            padding: 24px;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .map-modal-content {
            width: 95%;
        }
        
        .selection-status {
            flex-direction: column;
            align-items: center;
        }
        
        .my-reports-link {
            text-align: center;
        }
    }
</style>

@push('scripts')
<script>
    let pickMap = null;
    let pickOriginMarker = null;
    let pickDestMarker = null;
    let currentMode = 'origin';
    let tempOrigin = null;
    let tempDest = null;
    let tempOriginAddress = '';
    let tempDestAddress = '';

    // Get current saved locations from hidden inputs
    function getCurrentSavedLocations() {
        const originLat = document.getElementById('origin_lat').value;
        const originLng = document.getElementById('origin_lng').value;
        const destLat = document.getElementById('dest_lat').value;
        const destLng = document.getElementById('dest_lng').value;
        const originAddr = document.getElementById('origin_address').value;
        const destAddr = document.getElementById('dest_address').value;
        
        if (originLat && originLng) {
            tempOrigin = { lat: parseFloat(originLat), lng: parseFloat(originLng) };
            tempOriginAddress = originAddr;
            updateLocationDisplay();
        }
        if (destLat && destLng) {
            tempDest = { lat: parseFloat(destLat), lng: parseFloat(destLng) };
            tempDestAddress = destAddr;
            updateLocationDisplay();
        }
        updateStatusDisplay();
    }

    // Update the display of selected locations on the main form
    function updateLocationDisplay() {
        const locationsDiv = document.getElementById('selected-locations');
        const originDisplay = document.getElementById('origin-display');
        const destDisplay = document.getElementById('dest-display');
        const originNameSpan = document.getElementById('origin-name');
        const destNameSpan = document.getElementById('dest-name');
        
        if (tempOrigin || tempDest) {
            locationsDiv.style.display = 'block';
        }
        
        if (tempOrigin) {
            originDisplay.style.display = 'flex';
            originNameSpan.textContent = tempOriginAddress.substring(0, 50);
        } else {
            originDisplay.style.display = 'flex';
            originNameSpan.textContent = 'Not set';
        }
        
        if (tempDest) {
            destDisplay.style.display = 'flex';
            destNameSpan.textContent = tempDestAddress.substring(0, 50);
        } else {
            destDisplay.style.display = 'none';
        }
    }

    // Open map modal
    document.getElementById('pick-on-map-btn').addEventListener('click', function() {
        openMapModal();
    });

    // Mode switching
    document.getElementById('set-origin-mode').addEventListener('click', function() {
        currentMode = 'origin';
        document.getElementById('set-origin-mode').classList.add('active');
        document.getElementById('set-dest-mode').classList.remove('active');
        document.getElementById('map-modal-title').textContent = 'Set Origin';
        showToast('Click on map to set origin', 'info');
    });

    document.getElementById('set-dest-mode').addEventListener('click', function() {
        currentMode = 'destination';
        document.getElementById('set-dest-mode').classList.add('active');
        document.getElementById('set-origin-mode').classList.remove('active');
        document.getElementById('map-modal-title').textContent = 'Set Destination';
        showToast('Click on map to set destination', 'info');
    });

    // Apply selection and close
    document.getElementById('apply-selection').addEventListener('click', function() {
        if (tempOrigin) {
            document.getElementById('origin_address').value = tempOriginAddress;
            document.getElementById('origin_lat').value = tempOrigin.lat;
            document.getElementById('origin_lng').value = tempOrigin.lng;
        }
        if (tempDest) {
            document.getElementById('dest_address').value = tempDestAddress;
            document.getElementById('dest_lat').value = tempDest.lat;
            document.getElementById('dest_lng').value = tempDest.lng;
        }
        updateLocationDisplay();
        closeMapModal();
        if (tempOrigin || tempDest) {
            showToast('Locations saved!', 'success');
        }
    });

    // Close modal on overlay click
    document.querySelector('.map-modal-overlay').addEventListener('click', closeMapModal);
    document.getElementById('close-map-modal').addEventListener('click', closeMapModal);
    
    function closeMapModal() {
        document.getElementById('map-modal').classList.remove('active');
        if (pickMap) {
            pickMap.remove();
            pickMap = null;
        }
        pickOriginMarker = null;
        pickDestMarker = null;
    }

    function openMapModal() {
        getCurrentSavedLocations();
        updateStatusDisplay();
        document.getElementById('map-modal').classList.add('active');
        setTimeout(() => initPickMap(), 100);
    }

    function updateStatusDisplay() {
        const originStatus = document.getElementById('origin-status');
        const destStatus = document.getElementById('dest-status');
        
        if (tempOrigin) {
            originStatus.textContent = `Origin: ${tempOriginAddress.substring(0, 30)}${tempOriginAddress.length > 30 ? '...' : ''}`;
            originStatus.classList.add('set');
        } else {
            originStatus.textContent = 'Origin: Not set';
            originStatus.classList.remove('set');
        }
        
        if (tempDest) {
            destStatus.textContent = `Destination: ${tempDestAddress.substring(0, 30)}${tempDestAddress.length > 30 ? '...' : ''}`;
            destStatus.classList.add('set');
        } else {
            destStatus.textContent = 'Destination: Not set';
            destStatus.classList.remove('set');
        }
    }

    function initPickMap() {
        if (pickMap) {
            pickMap.invalidateSize();
            if (tempOrigin && !pickOriginMarker) {
                const greenIcon = L.divIcon({
                    html: '<div style="width:14px;height:14px;background:#22c55e;border-radius:50%;border:2px solid #fff;box-shadow:0 0 0 2px #22c55e"></div>',
                    iconSize: [14, 14]
                });
                pickOriginMarker = L.marker([tempOrigin.lat, tempOrigin.lng], { icon: greenIcon }).addTo(pickMap);
                pickMap.setView([tempOrigin.lat, tempOrigin.lng], 13);
            }
            if (tempDest && !pickDestMarker) {
                const redIcon = L.divIcon({
                    html: '<div style="width:14px;height:14px;background:#ef4444;border-radius:50%;border:2px solid #fff;box-shadow:0 0 0 2px #ef4444"></div>',
                    iconSize: [14, 14]
                });
                pickDestMarker = L.marker([tempDest.lat, tempDest.lng], { icon: redIcon }).addTo(pickMap);
                if (!tempOrigin) pickMap.setView([tempDest.lat, tempDest.lng], 13);
            }
            return;
        }

        var boholBounds = L.latLngBounds([
            [9.40, 123.70],
            [10.05, 124.70]
        ]);
        
        let initialView = boholBounds;
        if (tempOrigin) {
            initialView = L.latLngBounds([tempOrigin, tempOrigin]);
            if (tempDest) initialView.extend(tempDest);
        } else if (tempDest) {
            initialView = L.latLngBounds([tempDest, tempDest]);
        }
        
        pickMap = L.map('pick-map').fitBounds(initialView, { padding: [25, 25] });
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(pickMap);

        if (tempOrigin) {
            const greenIcon = L.divIcon({
                html: '<div style="width:14px;height:14px;background:#22c55e;border-radius:50%;border:2px solid #fff;box-shadow:0 0 0 2px #22c55e"></div>',
                iconSize: [14, 14]
            });
            pickOriginMarker = L.marker([tempOrigin.lat, tempOrigin.lng], { icon: greenIcon }).addTo(pickMap);
        }
        if (tempDest) {
            const redIcon = L.divIcon({
                html: '<div style="width:14px;height:14px;background:#ef4444;border-radius:50%;border:2px solid #fff;box-shadow:0 0 0 2px #ef4444"></div>',
                iconSize: [14, 14]
            });
            pickDestMarker = L.marker([tempDest.lat, tempDest.lng], { icon: redIcon }).addTo(pickMap);
        }

        pickMap.on('click', function(e) {
            if (currentMode === 'origin') {
                setTempOrigin(e.latlng.lat, e.latlng.lng);
            } else {
                setTempDestination(e.latlng.lat, e.latlng.lng);
            }
        });
    }

    async function setTempOrigin(lat, lng) {
        if (pickOriginMarker) pickMap.removeLayer(pickOriginMarker);
        
        const greenIcon = L.divIcon({
            html: '<div style="width:14px;height:14px;background:#22c55e;border-radius:50%;border:2px solid #fff;box-shadow:0 0 0 2px #22c55e"></div>',
            iconSize: [14, 14]
        });
        
        pickOriginMarker = L.marker([lat, lng], { icon: greenIcon }).addTo(pickMap);
        
        tempOrigin = { lat, lng };
        tempOriginAddress = await reverseGeocode(lat, lng);
        updateStatusDisplay();
        
        showToast('Origin set temporarily. Click "Apply Selection" to save.', 'success');
    }

    async function setTempDestination(lat, lng) {
        if (pickDestMarker) pickMap.removeLayer(pickDestMarker);
        
        const redIcon = L.divIcon({
            html: '<div style="width:14px;height:14px;background:#ef4444;border-radius:50%;border:2px solid #fff;box-shadow:0 0 0 2px #ef4444"></div>',
            iconSize: [14, 14]
        });
        
        pickDestMarker = L.marker([lat, lng], { icon: redIcon }).addTo(pickMap);
        
        tempDest = { lat, lng };
        tempDestAddress = await reverseGeocode(lat, lng);
        updateStatusDisplay();
        
        showToast('Destination set temporarily. Click "Apply Selection" to save.', 'success');
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

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeMapModal();
        }
    });

    // Handle form submission
    document.getElementById('report-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = document.querySelector('.btn-submit');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
        submitBtn.disabled = true;
        
        fetch('{{ route("user.report.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Report submitted successfully! Thank you for helping us improve.', 'success');
                document.getElementById('report-form').reset();
                tempOrigin = null;
                tempDest = null;
                tempOriginAddress = '';
                tempDestAddress = '';
                document.getElementById('selected-locations').style.display = 'none';
            } else {
                showToast(data.message || 'Error submitting report', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error submitting report. Please try again.', 'error');
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });
</script>
@endpush
@endsection