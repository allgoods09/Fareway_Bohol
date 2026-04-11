<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Report;
use App\Models\RouteSearchLog;
use App\Models\VehicleType;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalModerators = User::where('role', 'moderator')->count();
        $totalReports = Report::count();
        $pendingReports = Report::where('status', 'pending')->count();
        $totalSearches = RouteSearchLog::count();
        $todaySearches = RouteSearchLog::whereDate('created_at', today())->count();
        $activeVehicles = VehicleType::where('is_active', true)->count();
        
        // Get recent records
        $recentUsers = User::latest()->limit(5)->get();
        $recentReports = Report::with('user')->latest()->limit(5)->get();
        
        return view('admin.dashboard', compact(
            'totalUsers', 'totalModerators', 'totalReports', 
            'pendingReports', 'totalSearches', 'todaySearches',
            'activeVehicles', 'recentUsers', 'recentReports'
        ));
    }
}