<?php
// app/Http/Controllers/Moderator/DashboardController.php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\RecommendedPlace;
use App\Models\VehicleType;

class DashboardController extends Controller
{
    public function index()
    {
        $totalReports = Report::count();
        $pendingReports = Report::where('status', 'pending')->count();
        $totalPlaces = RecommendedPlace::count();
        $activeVehicles = VehicleType::where('is_active', true)->count();
        
        $recentReports = Report::with('user')->latest()->limit(5)->get();
        
        return view('moderator.dashboard', compact(
            'totalReports', 'pendingReports', 'totalPlaces', 
            'activeVehicles', 'recentReports'
        ));
    }
}