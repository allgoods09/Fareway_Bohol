<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function create()
    {
        return view('user.report');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:wrong_fare,road_closure,vehicle_unavailable,technical_issue,other',
            'description' => 'required|string|min:5',
            'origin_info' => 'nullable|string',
            'dest_info' => 'nullable|string',
            'screenshot' => 'nullable|image|max:5120' // max 5MB
        ]);
        
        Report::create([
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'description' => $validated['description'],
            'origin_lat' => null,
            'origin_lng' => null,
            'dest_lat' => null,
            'dest_lng' => null,
            'status' => 'pending'
        ]);
        
        return redirect()->route('home')->with('success', 'Report submitted successfully. Thank you for helping us improve!');
    }
}