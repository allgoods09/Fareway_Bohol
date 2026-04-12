<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\RecommendedPlace;
use App\Models\SavedRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecommendedPlacesController extends Controller
{
    public function index()
    {
        $places = RecommendedPlace::where('is_active', true)
            ->withCount('savedRoutes')
            ->latest()
            ->paginate(30);
        
        // Get saved place IDs for authenticated user
        $savedPlaceIds = [];
        if (Auth::check()) {
            $savedPlaceIds = SavedRoute::where('user_id', Auth::id())
                ->where('type', 'recommended_place')
                ->pluck('recommended_place_id')
                ->toArray();
        }
        
        return view('user.recommended-places', compact('places', 'savedPlaceIds'));
    }
    
    // ... rest of the controller methods
}