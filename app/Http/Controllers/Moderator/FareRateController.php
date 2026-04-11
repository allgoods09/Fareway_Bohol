<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class FareRateController extends Controller
{
    public function index()
    {
        $vehicleTypes = VehicleType::all();
        return view('moderator.fare-rates.index', compact('vehicleTypes'));
    }

    public function edit(VehicleType $fareRate)
    {
        return view('moderator.fare-rates.edit', compact('fareRate'));
    }

    public function update(Request $request, VehicleType $fareRate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'required|string',
            'base_fare' => 'required|numeric|min:0',
            'base_km' => 'required|numeric|min:0',
            'per_km_rate' => 'required|numeric|min:0',
            'night_surcharge' => 'required|numeric|min:0',
            'night_start' => 'required',
            'night_end' => 'required',
            'is_active' => 'boolean',
        ]);

        $fareRate->update($validated);

        return redirect()->route('moderator.fare-rates.index')
            ->with('success', 'Vehicle type updated successfully.');
    }
    
    // NO store, NO create, NO destroy methods for moderator
}