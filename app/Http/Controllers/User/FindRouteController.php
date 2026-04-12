<?php
// app/Http/Controllers/User/FindRouteController.php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\VehicleType;
use App\Models\RouteSearchLog;
use App\Models\SavedRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\LogsActivity;

class FindRouteController extends Controller
{
    use LogsActivity;

    public function index()
    {
        return view('welcome');
    }
    
    public function calculateFares(Request $request)
    {
        $request->validate([
            'distance_km' => 'required|numeric',
            'is_night' => 'boolean'
        ]);
        
        $vehicles = VehicleType::where('is_active', true)->get();
        $isNight = $request->is_night;
        
        $results = [];
        foreach ($vehicles as $vehicle) {
            $fare = $vehicle->calculateFare($request->distance_km, $isNight);
            $results[] = [
                'name' => $vehicle->name,
                'icon' => $vehicle->icon,
                'fare' => number_format($fare, 2),
                'base_fare' => $vehicle->base_fare,
                'base_km' => $vehicle->base_km,
                'per_km_rate' => $vehicle->per_km_rate,
                'night_surcharge' => $vehicle->night_surcharge,
                'is_night_applied' => $isNight && $fare > $vehicle->calculateFare($request->distance_km, false)
            ];
        }
        
        return response()->json($results);
    }
    
    public function logSearch(Request $request)
    {
        $request->validate([
            'origin_lat' => 'required|numeric',
            'origin_lng' => 'required|numeric',
            'dest_lat' => 'required|numeric',
            'dest_lng' => 'required|numeric',
            'distance_km' => 'required|numeric',
            'duration_minutes' => 'required|integer'
        ]);
        
        // Get fare estimates for logging
        $vehicles = VehicleType::where('is_active', true)->get();
        $isNight = now()->hour >= 20 || now()->hour < 5;
        $fareEstimates = [];
        
        foreach ($vehicles as $vehicle) {
            $fare = $vehicle->calculateFare($request->distance_km, $isNight);
            $fareEstimates[$vehicle->name] = $fare;
        }
        
        RouteSearchLog::create([
            'user_id' => Auth::id(),
            'origin_lat' => $request->origin_lat,
            'origin_lng' => $request->origin_lng,
            'dest_lat' => $request->dest_lat,
            'dest_lng' => $request->dest_lng,
            'distance_km' => $request->distance_km,
            'duration_minutes' => $request->duration_minutes,
            'fare_estimates' => $fareEstimates // Add this line
        ]);

        $this->logActivity(
            'route_search',
            'route',
            null,
            "Searched route from ({$request->origin_lat}, {$request->origin_lng}) to ({$request->dest_lat}, {$request->dest_lng})"
        );
        
        return response()->json(['success' => true]);
    }
    
    public function saveRoute(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'origin_lat' => 'required|numeric',
            'origin_lng' => 'required|numeric',
            'origin_address' => 'nullable|string',
            'dest_lat' => 'required|numeric',
            'dest_lng' => 'required|numeric',
            'dest_address' => 'nullable|string',
        ]);
        
        $savedRoute = SavedRoute::create([  // Store the result in a variable
            'user_id' => Auth::id(),
            'name' => $request->name,
            'origin_lat' => $request->origin_lat,
            'origin_lng' => $request->origin_lng,
            'origin_address' => $request->origin_address,
            'dest_lat' => $request->dest_lat,
            'dest_lng' => $request->dest_lng,
            'dest_address' => $request->dest_address,
            'type' => 'custom_route'
        ]);

        $this->logActivity(
            'save_route',
            'saved_route',
            $savedRoute->id,  // Now $savedRoute is defined
            "Saved route: {$request->name}"
        );
        
        return response()->json(['success' => true]);
    }
}