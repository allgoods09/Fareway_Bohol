<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class NewsController extends Controller
{
    public function index()
    {
        // Return the view immediately (no waiting for API)
        $lastUpdated = now();
        return view('user.news', compact('lastUpdated'));
    }
    
    // New AJAX endpoint for fetching news
    public function fetchNews()
    {
        $articles = Cache::remember('gnews_ph_relevant', 900, function () {
            $apiKey = config('services.gnews.api_key');
            
            $response = Http::get('https://gnews.io/api/v4/search', [
                'q' => '(transportation OR transport OR jeepney OR bus OR tricycle OR commute OR traffic) OR (tourism OR tourist OR travel OR destination) OR (fuel OR oil price OR gas) OR (Bohol OR Cebu OR Panglao)',
                'country' => 'ph',
                'lang' => 'en',
                'max' => 25,
                'apikey' => $apiKey,
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                return $data['articles'] ?? [];
            }
            
            return [];
        });
        
        return response()->json([
            'success' => true,
            'articles' => $articles,
            'lastUpdated' => now()->format('F j, Y g:i A')
        ]);
    }
    
    public function refresh()
    {
        Cache::forget('gnews_ph_relevant');
        return redirect()->route('user.news')->with('success', 'News refreshed!');
    }
}