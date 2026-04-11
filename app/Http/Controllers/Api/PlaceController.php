<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RecommendedPlace;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    public function show($id)
    {
        $place = RecommendedPlace::findOrFail($id);
        return response()->json([
            'name' => $place->name,
            'description' => $place->description,
            'latitude' => $place->latitude,
            'longitude' => $place->longitude,
            'category' => $place->category,
            'image_url' => $place->image_url
        ]);
    }
}