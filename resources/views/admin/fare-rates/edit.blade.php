{{-- resources/views/admin/fare-rates/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Vehicle Type')

@section('content')
<div>
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Vehicle Type</h1>
            <p class="text-gray-500 text-sm mt-1">Update fare structure for {{ $fareRate->name }}</p>
        </div>
        <a href="{{ route('admin.fare-rates.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <form action="{{ route('admin.fare-rates.update', $fareRate) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="p-6 space-y-6">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-md font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Vehicle Name *</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('name') border-red-500 @enderror" 
                                   id="name" name="name" value="{{ old('name', $fareRate->name) }}" required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="icon" class="block text-sm font-medium text-gray-700 mb-1">Icon Class *</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('icon') border-red-500 @enderror" 
                                   id="icon" name="icon" value="{{ old('icon', $fareRate->icon) }}" required>
                            <p class="text-xs text-gray-500 mt-1">Font Awesome icon class</p>
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
                                   id="base_fare" name="base_fare" value="{{ old('base_fare', $fareRate->base_fare) }}" required>
                            @error('base_fare')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="base_km" class="block text-sm font-medium text-gray-700 mb-1">Base Kilometer (km) *</label>
                            <input type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('base_km') border-red-500 @enderror" 
                                   id="base_km" name="base_km" value="{{ old('base_km', $fareRate->base_km) }}" required>
                            @error('base_km')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="per_km_rate" class="block text-sm font-medium text-gray-700 mb-1">Per Kilometer Rate (₱) *</label>
                            <input type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('per_km_rate') border-red-500 @enderror" 
                                   id="per_km_rate" name="per_km_rate" value="{{ old('per_km_rate', $fareRate->per_km_rate) }}" required>
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
                                   id="night_surcharge" name="night_surcharge" value="{{ old('night_surcharge', $fareRate->night_surcharge) }}" required>
                            @error('night_surcharge')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="night_start" class="block text-sm font-medium text-gray-700 mb-1">Night Start Time *</label>
                            <input type="time" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('night_start') border-red-500 @enderror" 
                                   id="night_start" name="night_start" value="{{ old('night_start', $fareRate->night_start) }}" required>
                            @error('night_start')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="night_end" class="block text-sm font-medium text-gray-700 mb-1">Night End Time *</label>
                            <input type="time" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('night_end') border-red-500 @enderror" 
                                   id="night_end" name="night_end" value="{{ old('night_end', $fareRate->night_end) }}" required>
                            @error('night_end')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500" 
                               {{ $fareRate->is_active ? 'checked' : '' }}>
                        <span class="text-sm font-medium text-gray-700">Active</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1">Inactive vehicles won't appear in fare calculations</p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex gap-3">
                <button type="submit" class="px-5 py-2 bg-emerald-500 text-white rounded-lg text-sm font-medium hover:bg-emerald-600 transition">
                    <i class="fas fa-save mr-2"></i> Update
                </button>
                <a href="{{ route('admin.fare-rates.index') }}" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-300 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Preview Card -->
    <div class="mt-6 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-200 p-5">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center">
                <i class="{{ $fareRate->icon }} text-white text-xl"></i>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-gray-800">{{ $fareRate->name }}</h3>
                <p class="text-sm text-gray-600">Base fare: ₱{{ number_format($fareRate->base_fare, 2) }} for first {{ $fareRate->base_km }}km</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-500">Preview</p>
                <p class="text-lg font-bold text-emerald-600">₱{{ number_format($fareRate->base_fare, 2) }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    @if(session('success'))
        showToast('{{ session('success') }}', 'success');
    @endif

    @if(session('error'))
        showToast('{{ session('error') }}', 'error');
    @endif
</script>
@endpush