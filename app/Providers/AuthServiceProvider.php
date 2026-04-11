<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Admin gate
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });
        
        // Moderator gate (includes admin since admin can do everything)
        Gate::define('moderator', function (User $user) {
            return in_array($user->role, ['admin', 'moderator']);
        });
        
        // Check if user can manage reports
        Gate::define('manage-reports', function (User $user) {
            return in_array($user->role, ['admin', 'moderator']);
        });
        
        // Check if user can manage fares
        Gate::define('manage-fares', function (User $user) {
            return in_array($user->role, ['admin', 'moderator']);
        });
        
        // Check if user can manage places (moderators can too)
        Gate::define('manage-places', function (User $user) {
            return in_array($user->role, ['admin', 'moderator']);
        });
        
        // Only admin can manage users
        Gate::define('manage-users', function (User $user) {
            return $user->role === 'admin';
        });
        
        // Only admin can view analytics
        Gate::define('view-analytics', function (User $user) {
            return $user->role === 'admin';
        });
    }
}