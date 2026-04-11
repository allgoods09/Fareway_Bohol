{{-- resources/views/moderator/fare-rates/index.blade.php --}}
@extends('layouts.moderator')

@section('title', 'Fare Rates Management')

@section('content')
<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Fare Rates Management</h1>
        <p class="text-gray-500 text-sm mt-1">View and edit vehicle fare rates</p>
    </div>

    <!-- Fare Rates Table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Icon</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Vehicle Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Base Fare</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Base KM</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Per KM Rate</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Night Surcharge</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Night Hours</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($vehicleTypes as $vehicle)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-3 text-sm">
                            <i class="{{ $vehicle->icon }} text-2xl text-gray-600"></i>
                        </td>
                        <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ $vehicle->name }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">₱{{ number_format($vehicle->base_fare, 2) }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ number_format($vehicle->base_km, 2) }} km</td>
                        <td class="px-6 py-3 text-sm text-gray-600">₱{{ number_format($vehicle->per_km_rate, 2) }}/km</td>
                        <td class="px-6 py-3 text-sm text-gray-600">₱{{ number_format($vehicle->night_surcharge, 2) }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">
                            {{ date('g:i A', strtotime($vehicle->night_start)) }} - {{ date('g:i A', strtotime($vehicle->night_end)) }}
                        </td>
                        <td class="px-6 py-3 text-sm">
                            @if($vehicle->is_active)
                                <span class="inline-flex px-2 py-1 rounded-lg text-xs font-medium bg-emerald-100 text-emerald-700">Active</span>
                            @else
                                <span class="inline-flex px-2 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-700">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-sm">
                            <a href="{{ route('moderator.fare-rates.edit', $vehicle) }}" 
                               class="inline-flex items-center gap-1 text-emerald-600 hover:text-emerald-800 font-medium transition">
                                <i class="fas fa-edit text-xs"></i> Edit
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-tag text-3xl text-gray-300 mb-2 block"></i>
                            No vehicle types found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection