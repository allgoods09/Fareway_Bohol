{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.user')

@section('title', 'Profile Settings')

@section('hero-content')
<div class="hero-content">
    <div class="hero-tag">
        <div class="hero-tag-dot"></div>
        Account Settings
    </div>
    <h1>Profile <span>Settings</span></h1>
    <p>Manage your account information and security preferences</p>
</div>
@endsection

@section('content')
<div class="profile-container">

    <div class="profile-grid">
        {{-- Update Profile Information --}}
        <div class="profile-card">
            <div class="profile-card-header">
                <h3 class="profile-card-title">Profile Information</h3>
                <p class="profile-card-subtitle">Update your account's profile information and email address.</p>
            </div>
            <div class="profile-card-body">
                <form method="post" action="{{ route('profile.update') }}" class="profile-form">
                    @csrf
                    @method('patch')

                    <div class="form-group">
                        <label for="name" class="form-label">Name</label>
                        <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                        @error('name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email', $user->email) }}" required autocomplete="username">
                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div class="email-verification">
                                <p class="email-verification-text">
                                    Your email address is unverified.
                                    <button form="send-verification" class="verify-link">Click here to re-send the verification email.</button>
                                </p>
                                @if (session('status') === 'verification-link-sent')
                                    <p class="verification-sent">A new verification link has been sent to your email address.</p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Save Changes</button>
                        @if (session('status') === 'profile-updated')
                            <span class="saved-indicator">Saved!</span>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Update Password --}}
        <div class="profile-card">
            <div class="profile-card-header">
                <h3 class="profile-card-title">Update Password</h3>
                <p class="profile-card-subtitle">Ensure your account is using a long, random password to stay secure.</p>
            </div>
            <div class="profile-card-body">
                <form method="post" action="{{ route('password.update') }}" class="profile-form">
                    @csrf
                    @method('put')

                    <div class="form-group">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input id="current_password" name="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" 
                               autocomplete="current-password">
                        @error('current_password')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">New Password</label>
                        <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                               autocomplete="new-password">
                        @error('password')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" 
                               autocomplete="new-password">
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Update Password</button>
                        @if (session('status') === 'password-updated')
                            <span class="saved-indicator">Saved!</span>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Delete Account --}}
        <div class="profile-card delete-card">
            <div class="profile-card-header">
                <h3 class="profile-card-title text-danger">Delete Account</h3>
                <p class="profile-card-subtitle">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
            </div>
            <div class="profile-card-body">
                <button type="button" class="btn-danger" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
                    Delete Account
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Delete Account Confirmation Modal --}}
<div id="confirm-user-deletion" class="modal" style="display: none;" x-data="{ open: false }" x-on:open-modal.window="if ($event.detail === 'confirm-user-deletion') open = true" x-show="open" x-cloak>
    <div class="modal-overlay" x-on:click="open = false"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">Delete Account</h3>
            <button class="modal-close" x-on:click="open = false">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete your account? Once deleted, all data will be permanently removed.</p>
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" name="password" type="password" class="form-control" placeholder="Enter your password to confirm" required>
                    @error('password', 'userDeletion')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" x-on:click="open = false">Cancel</button>
                    <button type="submit" class="btn-danger">Delete Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .profile-container {
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
    }

    .profile-header {
        margin-bottom: 32px;
    }

    .profile-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .profile-subtitle {
        font-size: 14px;
        color: var(--text-muted);
    }

    .profile-grid {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .profile-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
    }

    .profile-card-header {
        padding: 20px 24px;
        background: var(--sand);
        border-bottom: 1px solid var(--border);
    }

    .profile-card-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 4px;
    }

    .profile-card-title.text-danger {
        color: #dc2626;
    }

    .profile-card-subtitle {
        font-size: 12px;
        color: var(--text-muted);
    }

    .profile-card-body {
        padding: 24px;
    }

    .profile-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 500;
        color: var(--text-mid);
    }

    .form-control {
        padding: 12px 16px;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
        transition: all 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--teal);
        box-shadow: 0 0 0 3px rgba(14,138,110,0.1);
    }

    .form-control.is-invalid {
        border-color: #dc2626;
    }

    .form-error {
        font-size: 12px;
        color: #dc2626;
    }

    .form-actions {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .btn-primary {
        padding: 10px 20px;
        background: var(--navy);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-primary:hover {
        background: var(--navy-mid);
    }

    .btn-danger {
        padding: 10px 20px;
        background: #dc2626;
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-danger:hover {
        background: #b91c1c;
    }

    .btn-secondary {
        padding: 10px 20px;
        background: var(--sand);
        color: var(--text-mid);
        border: none;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-secondary:hover {
        background: #e5e8eb;
    }

    .saved-indicator {
        font-size: 13px;
        color: var(--teal);
    }

    .email-verification {
        margin-top: 8px;
    }

    .email-verification-text {
        font-size: 12px;
        color: #f59e0b;
    }

    .verify-link {
        background: none;
        border: none;
        color: var(--teal);
        cursor: pointer;
        text-decoration: underline;
        font-size: 12px;
    }

    .verification-sent {
        font-size: 12px;
        color: var(--teal);
        margin-top: 8px;
    }

    .delete-card {
        border-color: #fecaca;
    }

    @media (max-width: 768px) {
        .profile-container {
            padding: 0;
        }
        
        .profile-card-header,
        .profile-card-body {
            padding: 16px;
        }
        
        .form-actions {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endsection