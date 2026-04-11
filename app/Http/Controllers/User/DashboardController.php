<?php
// app/Http/Controllers/User/DashboardController.php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SavedRoute;
use App\Models\Report;
use App\Models\RouteSearchLog;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $savedRoutes = SavedRoute::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        
        $reportsCount = Report::where('user_id', Auth::id())->count();
        $searchesCount = RouteSearchLog::where('user_id', Auth::id())->count();
            
        return view('user.dashboard', compact('savedRoutes', 'reportsCount', 'searchesCount'));
    }
    
    public function savedRoutes()
    {
        $savedRoutes = SavedRoute::where('user_id', Auth::id())
            ->latest()
            ->get();
            
        return view('user.saved-routes', compact('savedRoutes'));
    }
    
    public function deleteSavedRoute($id)
    {
        $route = SavedRoute::where('user_id', Auth::id())->findOrFail($id);
        $route->delete();
        
        return redirect()->back()->with('success', 'Route deleted successfully.');
    }
}