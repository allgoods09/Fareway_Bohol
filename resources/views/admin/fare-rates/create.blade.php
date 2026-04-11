{{-- resources/views/admin/fare-rates/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Add Vehicle Type')

@section('content')
<div>
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Add Vehicle Type</h1>
            <p class="text-gray-500 text-sm mt-1">Create a new vehicle fare structure</p>
        </div>
        <a href="{{ route('admin.fare-rates.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Create Form -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <form action="{{ route('admin.fare-rates.store') }}" method="POST">
            @csrf
            
            <div class="p-6 space-y-6">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-md font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Vehicle Name *</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('name') border-red-500 @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="icon" class="block text-sm font-medium text-gray-700 mb-1">Icon Class *</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('icon') border-red-500 @enderror" 
                                   id="icon" name="icon" value="{{ old('icon', 'fas fa-bus') }}" required>
                            <p class="text-xs text-gray-500 mt-1">Font Awesome icon class (e.g., fas fa-bus, fas fa-motorcycle)</p>
                            @error('icon')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Fare Structure -->
                <div>
                    <h3 class="text-md font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Fare Structure</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label for="base_fare" class="block text-sm font-medium text-gray-700 mb-1">Base Fare (₱) *</label>
                            <input type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('base_fare') border-red-500 @enderror" 
                                   id="base_fare" name="base_fare" value="{{ old('base_fare') }}" required>
                            @error('base_fare')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="base_km" class="block text-sm font-medium text-gray-700 mb-1">Base Kilometer (km) *</label>
                            <input type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('base_km') border-red-500 @enderror" 
                                   id="base_km" name="base_km" value="{{ old('base_km') }}" required>
                            @error('base_km')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="per_km_rate" class="block text-sm font-medium text-gray-700 mb-1">Per Kilometer Rate (₱) *</label>
                            <input type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('per_km_rate') border-red-500 @enderror" 
                                   id="per_km_rate" name="per_km_rate" value="{{ old('per_km_rate') }}" required>
                            @error('per_km_rate')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Night Surcharge -->
                <div>
                    <h3 class="text-md font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Night Travel Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label for="night_surcharge" class="block text-sm font-medium text-gray-700 mb-1">Night Surcharge (₱) *</label>
                            <input type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('night_surcharge') border-red-500 @enderror" 
                                   id="night_surcharge" name="night_surcharge" value="{{ old('night_surcharge', 0) }}" required>
                            @error('night_surcharge')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="night_start" class="block text-sm font-medium text-gray-700 mb-1">Night Start Time *</label>
                            <input type="time" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('night_start') border-red-500 @enderror" 
                                   id="night_start" name="night_start" value="{{ old('night_start', '20:00') }}" required>
                            @error('night_start')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="night_end" class="block text-sm font-medium text-gray-700 mb-1">Night End Time *</label>
                            <input type="time" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('night_end') border-red-500 @enderror" 
                                   id="night_end" name="night_end" value="{{ old('night_end', '05:00') }}" required>
                            @error('night_end')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex gap-3">
                <button type="submit" class="px-5 py-2 bg-emerald-500 text-white rounded-lg text-sm font-medium hover:bg-emerald-600 transition">
                    <i class="fas fa-save mr-2"></i> Create Vehicle Type
                </button>
                <a href="{{ route('admin.fare-rates.index') }}" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-300 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection