<?php

namespace App\Http\Controllers;

use App\Models\RecommendedPlace;
use App\Models\SavedRoute;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function index()
    {
        $recommendedPlaces = RecommendedPlace::where('is_active', true)
            ->withCount('savedRoutes')
            ->latest()
            ->limit(5)
            ->get();
        
        // Get saved place IDs for authenticated user
        $savedPlaceIds = [];
        if (Auth::check()) {
            $savedPlaceIds = SavedRoute::where('user_id', Auth::id())
                ->where('type', 'recommended_place')
                ->pluck('recommended_place_id')
                ->toArray();
        }
            
        return view('welcome', compact('recommendedPlaces', 'savedPlaceIds'));
    }
}