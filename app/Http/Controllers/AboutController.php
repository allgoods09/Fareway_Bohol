<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\VehicleType;
use App\Models\RouteSearchLog;

class AboutController extends Controller  // Note: Should be AboutController, not NewsController
{
    public function index()
    {   
        // Get all active vehicle types (no withCount needed)
        // $vehicles = VehicleType::where('is_active', true)->get();
        
        // Or if you just want the count:
        $vehicleCount = VehicleType::where('is_active', true)->count();

        $totalRoutesCalculated = RouteSearchLog::count();
        
        return view('about', compact('vehicleCount', 'totalRoutesCalculated'));
    }
}