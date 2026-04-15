@extends('layouts.admin')

@section('title', 'Recommended Places')

@section('content')
<div>
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Recommended Places</h1>
            <p class="text-gray-500 text-sm mt-1">Manage tourist spots and popular destinations in Bohol</p>
        </div>
        <a href="{{ route('admin.recommended-places.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 text-white rounded-lg text-sm font-medium hover:bg-emerald-600 transition shadow-sm">
            <i class="fas fa-plus"></i> Add Place
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search by name..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            
            <div class="w-48">
                <label class="block text-xs font-medium text-gray-600 mb-1">Category</label>
                <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <option value="all">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="w-40">
                <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <option value="all">All</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            
            <div class="w-40">
                <label class="block text-xs font-medium text-gray-600 mb-1">Sort By</label>
                <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                    <option value="most_saved" {{ request('sort') == 'most_saved' ? 'selected' : '' }}>Most Saved</option>
                </select>
            </div>
            
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-emerald-500 text-white rounded-lg text-sm font-medium hover:bg-emerald-600 transition">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="{{ route('admin.recommended-places.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-300 transition">
                    <i class="fas fa-undo"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Places Table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Saved</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($places as $place)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-3">
                            @if($place->image_url)
                                <img src="{{ $place->image_url }}" alt="{{ $place->name }}" 
                                     class="w-12 h-12 rounded-lg object-cover">
                            @else
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400 text-xl"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ $place->name }}</td>
                        <td class="px-6 py-3 text-sm">
                            <span class="inline-flex px-2 py-1 rounded-lg text-xs font-medium bg-purple-100 text-purple-700">
                                {{ $place->category ?? 'Uncategorized' }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-600">
                            <code class="text-xs">{{ number_format($place->latitude, 6) }}, {{ number_format($place->longitude, 6) }}</code>
                        </td>
                        <td class="px-6 py-3 text-sm">
                            @if($place->is_active)
                                <span class="inline-flex px-2 py-1 rounded-lg text-xs font-medium bg-emerald-100 text-emerald-700">Active</span>
                            @else
                                <span class="inline-flex px-2 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-700">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-600">
                            {{ $place->saved_routes_count ?? 0 }}
                        </td>
                        <td class="px-6 py-3 text-sm">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.recommended-places.edit', $place) }}" 
                                   class="text-emerald-600 hover:text-emerald-800 transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="text-red-600 hover:text-red-800 transition delete-btn" 
                                        data-id="{{ $place->id }}" 
                                        data-name="{{ $place->name }}" 
                                        title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-map-marker-alt text-3xl text-gray-300 mb-2 block"></i>
                            No recommended places found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $places->links() }}
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
                form.action = `/admin/recommended-places/${id}`;
                form.submit();
            }
        });
    });
</script>
@endpush
@endsection