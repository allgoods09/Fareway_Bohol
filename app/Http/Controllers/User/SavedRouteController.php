<?php
// app/Http/Controllers/User/SavedRouteController.php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SavedRoute;
use Illuminate\Support\Facades\Auth;

class SavedRouteController extends Controller
{
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
        
        return response()->json(['success' => true]);
    }
}