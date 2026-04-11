<?php
// app/Http/Controllers/WelcomeController.php

namespace App\Http\Controllers;

use App\Models\RecommendedPlace;

class WelcomeController extends Controller
{
    public function index()
    {
        // Get only top 5 active places
        $recommendedPlaces = RecommendedPlace::where('is_active', true)
            ->latest()
            ->limit(5)
            ->get();
            
        return view('welcome', compact('recommendedPlaces'));
    }
}