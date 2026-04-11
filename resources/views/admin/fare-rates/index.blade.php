{{-- resources/views/admin/fare-rates/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Fare Rates Management')

@section('content')
<div>
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Fare Rates Management</h1>
            <p class="text-gray-500 text-sm mt-1">Manage vehicle types and fare structures</p>
        </div>
        <a href="{{ route('admin.fare-rates.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 text-white rounded-lg text-sm font-medium hover:bg-emerald-600 transition shadow-sm">
            <i class="fas fa-plus"></i> Add Vehicle Type
        </a>
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
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer toggle-status" data-id="{{ $vehicle->id }}" {{ $vehicle->is_active ? 'checked' : '' }}>
                                <div class="w-9 h-5 bg-gray-300 rounded-full peer peer-checked:bg-emerald-500 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all"></div>
                            </label>
                        </td>
                        <td class="px-6 py-3 text-sm">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.fare-rates.edit', $vehicle) }}" 
                                   class="text-emerald-600 hover:text-emerald-800 transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="text-red-600 hover:text-red-800 transition delete-btn" 
                                        data-id="{{ $vehicle->id }}" 
                                        data-name="{{ $vehicle->name }}" 
                                        title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
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

<!-- Delete Form -->
<form id="delete-form" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            if(confirm(`Are you sure you want to delete "${name}"?`)) {
                const form = document.getElementById('delete-form');
                form.action = `/admin/fare-rates/${id}`;
                form.submit();
            }
        });
    });

    document.querySelectorAll('.toggle-status').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const id = this.dataset.id;
            fetch(`/admin/fare-rates/${id}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            }).then(response => response.json()).then(data => {
                if(data.success) {
                    // Optional: Show a toast notification
                    console.log('Status updated');
                }
            });
        });
    });
</script>
@endpush
@endsection