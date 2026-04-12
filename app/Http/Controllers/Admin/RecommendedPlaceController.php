<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RecommendedPlace;
use Illuminate\Http\Request;
use App\Traits\LogsActivity;

class RecommendedPlaceController extends Controller
{

    use LogsActivity;

    public function index()
    {
        $places = RecommendedPlace::latest()->paginate(10);
        return view('admin.recommended-places.index', compact('places'));
    }

    public function create()
    {
        return view('admin.recommended-places.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'image_url' => 'nullable|url',
            'category' => 'nullable|string',
        ]);

        RecommendedPlace::create($validated);

        $place = RecommendedPlace::create($validated);
        
        $this->logActivity(
            'create_place',
            'recommended_place',
            $place->id,
            "Created place: {$place->name}"
        );

        return redirect()->route('admin.recommended-places.index')
            ->with('success', 'Recommended place created successfully.');
    }

    public function edit(RecommendedPlace $recommendedPlace)
    {
        return view('admin.recommended-places.edit', compact('recommendedPlace'));
    }

    public function update(Request $request, RecommendedPlace $recommendedPlace)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'image_url' => 'nullable|url',
            'category' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $recommendedPlace->update($validated);
        
        $this->logActivity(
            'update_place',
            'recommended_place',
            $recommendedPlace->id,
            "Updated place: {$recommendedPlace->name}"
        );

        return redirect()->route('admin.recommended-places.index')
            ->with('success', 'Recommended place updated successfully.');
    }

    public function destroy(RecommendedPlace $recommendedPlace)
    {
        $recommendedPlace->delete();

        $this->logActivity(
            'delete_place',
            'recommended_place',
            $recommendedPlace->id,
            "Deleted place: {$recommendedPlace->name}"
        );

        return redirect()->route('admin.recommended-places.index')
            ->with('success', 'Recommended place deleted successfully.');
    }
}