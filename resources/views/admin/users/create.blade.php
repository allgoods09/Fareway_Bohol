{{-- resources/views/admin/users/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Add User')

@section('content')
<div>
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Add New User</h1>
            <p class="text-gray-500 text-sm mt-1">Create a new system user account</p>
        </div>
        <a href="{{ route('admin.users.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Create Form -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            
            <div class="p-6 space-y-6">
                <!-- Account Information -->
                <div>
                    <h3 class="text-md font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Account Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('name') border-red-500 @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                            <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('email') border-red-500 @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                            <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('password') border-red-500 @enderror" 
                                   id="password" name="password" required>
                            <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password *</label>
                            <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>
                </div>

                <!-- Contact & Role -->
                <div>
                    <h3 class="text-md font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Contact & Role</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('phone') border-red-500 @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" placeholder="09991234567">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('role') border-red-500 @enderror" 
                                    id="role" name="role" required>
                                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                                <option value="moderator" {{ old('role') == 'moderator' ? 'selected' : '' }}>Moderator</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('role')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex gap-3">
                <button type="submit" class="px-5 py-2 bg-emerald-500 text-white rounded-lg text-sm font-medium hover:bg-emerald-600 transition">
                    <i class="fas fa-save mr-2"></i> Create User
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-300 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection