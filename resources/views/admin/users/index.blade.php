{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<div>
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">User Management</h1>
            <p class="text-gray-500 text-sm mt-1">Manage system users and their roles</p>
        </div>
        <a href="{{ route('admin.users.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 text-white rounded-lg text-sm font-medium hover:bg-emerald-600 transition shadow-sm">
            <i class="fas fa-plus"></i> Add User
        </a>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-3 text-sm text-gray-600">#{{ $user->id }}</td>
                        <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ $user->email }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ $user->phone ?? '—' }}</td>
                        <td class="px-6 py-3 text-sm">
                            @php
                                $roleStyles = [
                                    'admin' => 'bg-red-100 text-red-700',
                                    'moderator' => 'bg-amber-100 text-amber-700',
                                    'user' => 'bg-blue-100 text-blue-700'
                                ];
                                $roleStyle = $roleStyles[$user->role] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="inline-flex px-2 py-1 rounded-lg text-xs font-medium {{ $roleStyle }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-500">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-3 text-sm">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="text-emerald-600 hover:text-emerald-800 transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                    <button type="button" 
                                            class="text-red-600 hover:text-red-800 transition delete-btn" 
                                            data-id="{{ $user->id }}" 
                                            data-name="{{ $user->name }}" 
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-users text-3xl text-gray-300 mb-2 block"></i>
                            No users found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $users->links() }}
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
            if(confirm(`Are you sure you want to delete user "${name}"? This action cannot be undone.`)) {
                const form = document.getElementById('delete-form');
                form.action = `/admin/users/${id}`;
                form.submit();
            }
        });
    });
</script>
@endpush
@endsection