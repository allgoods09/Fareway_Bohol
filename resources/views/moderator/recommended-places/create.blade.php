{{-- resources/views/moderator/recommended-places/create.blade.php --}}
@extends('layouts.moderator')

@section('title', 'Add Recommended Place')

@section('content')
<div>
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Add Recommended Place</h1>
            <p class="text-gray-500 text-sm mt-1">Add a new tourist spot or popular destination</p>
        </div>
        <a href="{{ route('moderator.recommended-places.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Create Form -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <form action="{{ route('moderator.recommended-places.store') }}" method="POST" id="place-form">
            @csrf
            
            <div class="p-6 space-y-6">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-md font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Place Name *</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('name') border-red-500 @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('category') border-red-500 @enderror" 
                                   id="category" name="category" value="{{ old('category') }}" placeholder="Beach, Historical, Nature, etc.">
                            @error('category')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Location Picker with Map -->
                <div>
                    <h3 class="text-md font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Location Picker</h3>
                    <p class="text-sm text-gray-600 mb-3">Click on the map to set the location of this place</p>
                    
                    <!-- Map Container -->
                    <div id="location-map" style="height: 400px; width: 100%;" class="rounded-lg border border-gray-200 mb-4"></div>
                    
                    <!-- Coordinates Display -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="latitude" class="block text-sm font-medium text-gray-700 mb-1">Latitude *</label>
                            <input type="number" step="0.00000001" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('latitude') border-red-500 @enderror" 
                                   id="latitude" name="latitude" value="{{ old('latitude', '9.9210') }}" readonly required>
                            <p class="text-xs text-gray-500 mt-1">Click on map to set coordinates</p>
                            @error('latitude')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">Longitude *</label>
                            <input type="number" step="0.00000001" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('longitude') border-red-500 @enderror" 
                                   id="longitude" name="longitude" value="{{ old('longitude', '124.2900') }}" readonly required>
                            <p class="text-xs text-gray-500 mt-1">Click on map to set coordinates</p>
                            @error('longitude')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Media & Description -->
                <div>
                    <h3 class="text-md font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Media & Description</h3>
                    <div class="space-y-5">
                        <div>
                            <label for="image_url" class="block text-sm font-medium text-gray-700 mb-1">Image URL</label>
                            <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('image_url') border-red-500 @enderror" 
                                   id="image_url" name="image_url" value="{{ old('image_url') }}" placeholder="https://example.com/image.jpg">
                            <p class="text-xs text-gray-500 mt-1">Provide a direct link to an image of the place</p>
                            @error('image_url')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('description') border-red-500 @enderror" 
                                      id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex gap-3">
                <button type="submit" class="px-5 py-2 bg-emerald-500 text-white rounded-lg text-sm font-medium hover:bg-emerald-600 transition">
                    <i class="fas fa-save mr-2"></i> Create Place
                </button>
                <a href="{{ route('moderator.recommended-places.index') }}" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-300 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Initialize map centered on Bohol
    var map = L.map('location-map').setView([9.9210, 124.2900], 11);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Variable to store the marker
    var locationMarker = null;
    
    // Function to update coordinates when clicking on map
    map.on('click', function(e) {
        var lat = e.latlng.lat.toFixed(7);
        var lng = e.latlng.lng.toFixed(7);
        
        // Update input fields
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        
        // Remove existing marker if any
        if (locationMarker) {
            map.removeLayer(locationMarker);
        }
        
        // Add new marker
        locationMarker = L.marker([lat, lng]).addTo(map);
        locationMarker.bindPopup('<strong>Selected Location</strong><br>Lat: ' + lat + '<br>Lng: ' + lng).openPopup();
    });
    
    // If there are existing coordinates (like from old input), add marker
    var existingLat = document.getElementById('latitude').value;
    var existingLng = document.getElementById('longitude').value;
    if (existingLat && existingLng && existingLat !== '9.9210') {
        locationMarker = L.marker([existingLat, existingLng]).addTo(map);
        map.setView([existingLat, existingLng], 13);
    }
</script>
@endpush
@endsection