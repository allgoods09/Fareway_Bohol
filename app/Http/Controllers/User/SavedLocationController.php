<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SavedRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RecommendedPlace;

class SavedLocationController extends Controller
{
    public function save(Request $request)
    {
        $request->validate([
            'place_id' => 'required|exists:recommended_places,id',
            'name' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric'
        ]);
        
        // Check if already saved
        $exists = SavedRoute::where('user_id', Auth::id())
            ->where('recommended_place_id', $request->place_id)
            ->where('type', 'recommended_place')
            ->exists();
        
        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Location already saved']);
        }
        
        SavedRoute::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'origin_lat' => 0,
            'origin_lng' => 0,
            'dest_lat' => $request->latitude,
            'dest_lng' => $request->longitude,
            'dest_address' => $request->name,
            'type' => 'recommended_place',
            'recommended_place_id' => $request->place_id
        ]);

        $place = RecommendedPlace::find($request->place_id);
        $updatedCount = $place->savedRoutes()->count();
        
        return response()->json([
            'success' => true,
            'saved_count' => $updatedCount,
            'message' => 'Location saved successfully'
        ]);
    }
}