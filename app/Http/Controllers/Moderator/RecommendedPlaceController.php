<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Models\RecommendedPlace;
use Illuminate\Http\Request;

class RecommendedPlaceController extends Controller
{
    public function index(Request $request)
    {
        $query = RecommendedPlace::query();
        
        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        // Filter by category
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }
        
        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('is_active', $request->status === 'active');
        }
        
        // Sort options
        switch ($request->get('sort', 'latest')) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'most_saved':
                $query->withCount('savedRoutes')->orderBy('saved_routes_count', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }
        
        $places = $query->paginate(10)->withQueryString();
        
        // Get unique categories for filter dropdown
        $categories = RecommendedPlace::whereNotNull('category')
            ->distinct()
            ->pluck('category');
        
        return view('moderator.recommended-places.index', compact('places', 'categories'));
    }

    public function create()
    {
        return view('moderator.recommended-places.create');
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

        return redirect()->route('moderator.recommended-places.index')
            ->with('success', 'Recommended place created successfully.');
    }

    public function edit(RecommendedPlace $recommendedPlace)
    {
        return view('moderator.recommended-places.edit', compact('recommendedPlace'));
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

        return redirect()->route('moderator.recommended-places.index')
            ->with('success', 'Recommended place updated successfully.');
    }

    public function destroy(RecommendedPlace $recommendedPlace)
    {
        $recommendedPlace->delete();
        return redirect()->route('moderator.recommended-places.index')
            ->with('success', 'Recommended place deleted successfully.');
    }
}