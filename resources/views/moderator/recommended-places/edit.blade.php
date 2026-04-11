{{-- resources/views/moderator/recommended-places/edit.blade.php --}}
@extends('layouts.moderator')

@section('title', 'Edit Recommended Place')

@section('content')
<div>
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Place</h1>
            <p class="text-gray-500 text-sm mt-1">Update information for {{ $recommendedPlace->name }}</p>
        </div>
        <a href="{{ route('moderator.recommended-places.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <form action="{{ route('moderator.recommended-places.update', $recommendedPlace) }}" method="POST" id="place-form">
            @csrf
            @method('PUT')
            
            <div class="p-6 space-y-6">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-md font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Place Name *</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('name') border-red-500 @enderror" 
                                   id="name" name="name" value="{{ old('name', $recommendedPlace->name) }}" required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('category') border-red-500 @enderror" 
                                   id="category" name="category" value="{{ old('category', $recommendedPlace->category) }}" placeholder="Beach, Historical, Nature, etc.">
                            @error('category')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Location Picker with Map -->
                <div>
                    <h3 class="text-md font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Location Picker</h3>
                    <p class="text-sm text-gray-600 mb-3">Click on the map to adjust the location of this place</p>
                    
                    <!-- Map Container -->
                    <div id="location-map" style="height: 400px; width: 100%;" class="rounded-lg border border-gray-200 mb-4"></div>
                    
                    <!-- Coordinates Display -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="latitude" class="block text-sm font-medium text-gray-700 mb-1">Latitude *</label>
                            <input type="number" step="0.00000001" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('latitude') border-red-500 @enderror" 
                                   id="latitude" name="latitude" value="{{ old('latitude', $recommendedPlace->latitude) }}" readonly required>
                            <p class="text-xs text-gray-500 mt-1">Click on map to adjust coordinates</p>
                            @error('latitude')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">Longitude *</label>
                            <input type="number" step="0.00000001" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('longitude') border-red-500 @enderror" 
                                   id="longitude" name="longitude" value="{{ old('longitude', $recommendedPlace->longitude) }}" readonly required>
                            <p class="text-xs text-gray-500 mt-1">Click on map to adjust coordinates</p>
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
                                   id="image_url" name="image_url" value="{{ old('image_url', $recommendedPlace->image_url) }}" placeholder="https://example.com/image.jpg">
                            @error('image_url')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('description') border-red-500 @enderror" 
                                      id="description" name="description" rows="5" required>{{ old('description', $recommendedPlace->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500" 
                               {{ $recommendedPlace->is_active ? 'checked' : '' }}>
                        <span class="text-sm font-medium text-gray-700">Active</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1">Inactive places won't appear on the user welcome page</p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex gap-3">
                <button type="submit" class="px-5 py-2 bg-emerald-500 text-white rounded-lg text-sm font-medium hover:bg-emerald-600 transition">
                    <i class="fas fa-save mr-2"></i> Update Place
                </button>
                <a href="{{ route('moderator.recommended-places.index') }}" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-300 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Preview Card -->
    <div class="mt-6 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-200 p-5">
        <div class="flex items-center gap-4">
            @if($recommendedPlace->image_url)
                <img src="{{ $recommendedPlace->image_url }}" alt="{{ $recommendedPlace->name }}" 
                     class="w-16 h-16 rounded-xl object-cover">
            @else
                <div class="w-16 h-16 bg-gray-200 rounded-xl flex items-center justify-center">
                    <i class="fas fa-image text-gray-400 text-2xl"></i>
                </div>
            @endif
            <div class="flex-1">
                <h3 class="font-semibold text-gray-800">{{ $recommendedPlace->name }}</h3>
                <p class="text-sm text-gray-600">{{ Str::limit($recommendedPlace->description, 100) }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    📍 {{ number_format($recommendedPlace->latitude, 6) }}, {{ number_format($recommendedPlace->longitude, 6) }}
                </p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-500">Status</p>
                <p class="text-sm font-semibold {{ $recommendedPlace->is_active ? 'text-emerald-600' : 'text-gray-500' }}">
                    {{ $recommendedPlace->is_active ? 'Active' : 'Inactive' }}
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Initialize map centered on the place's coordinates
    var initialLat = {{ $recommendedPlace->latitude }};
    var initialLng = {{ $recommendedPlace->longitude }};
    
    var map = L.map('location-map').setView([initialLat, initialLng], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Add marker at the current location
    var locationMarker = L.marker([initialLat, initialLng]).addTo(map);
    locationMarker.bindPopup('<strong>{{ $recommendedPlace->name }}</strong><br>Current location').openPopup();
    
    // Function to update coordinates when clicking on map
    map.on('click', function(e) {
        var lat = e.latlng.lat.toFixed(7);
        var lng = e.latlng.lng.toFixed(7);
        
        // Update input fields
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        
        // Update marker position
        map.removeLayer(locationMarker);
        locationMarker = L.marker([lat, lng]).addTo(map);
        locationMarker.bindPopup('<strong>New Location</strong><br>Lat: ' + lat + '<br>Lng: ' + lng).openPopup();
    });
</script>
@endpush
@endsection