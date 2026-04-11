<?php
// app/Http/Controllers/User/RecommendedPlacesController.php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\RecommendedPlace;
use Illuminate\Http\Request;

class RecommendedPlacesController extends Controller
{
    public function index()
    {
        $places = RecommendedPlace::where('is_active', true)
            ->latest()
            ->paginate(20);
        
        return view('user.recommended-places', compact('places'));
    }
}