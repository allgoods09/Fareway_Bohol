<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Traits\LogsActivity;

class AuthenticatedSessionController extends Controller
{

    use LogsActivity;
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Custom redirect based on role
        $user = Auth::user();

        $this->logActivity('login', 'user', Auth::id(), 'User logged in');
        
        if ($user->role === 'admin') {
            return redirect()->intended(route('home'));
        } elseif ($user->role === 'moderator') {
            return redirect()->intended(route('home'));
        } else {
            // Regular user - redirect to user dashboard
            return redirect()->intended(route('home'));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $this->logActivity('logout', 'user', Auth::id(), 'User logged out');

        return redirect('/');

    }
}
