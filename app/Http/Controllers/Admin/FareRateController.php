<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use App\Traits\LogsActivity;

class FareRateController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $vehicleTypes = VehicleType::all();
        return view('admin.fare-rates.index', compact('vehicleTypes'));
    }

    public function create()
    {
        return view('admin.fare-rates.create');
    }

    public function store(Request $request)
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
        ]);

        $vehicle = VehicleType::create($validated);  // Store the result in a variable

        $this->logActivity(
            'create_vehicle',
            'vehicle_type',
            $vehicle->id,  // Now $vehicle is defined
            "Created vehicle type: {$vehicle->name}"
        );

        return redirect()->route('admin.fare-rates.index')
            ->with('success', 'Vehicle type created successfully.');
    }

    public function edit(VehicleType $fareRate)
    {
        return view('admin.fare-rates.edit', compact('fareRate'));
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

        $this->logActivity(
            'update_vehicle',
            'vehicle_type',
            $fareRate->id,
            "Updated vehicle type: {$fareRate->name}"
        );
        
        return redirect()->route('admin.fare-rates.index')
            ->with('success', 'Vehicle type updated successfully.');
    }

    public function destroy(VehicleType $fareRate)
    {
        $fareRate->delete();

        $this->logActivity(
            'delete_vehicle',
            'vehicle_type',
            $fareRate->id,
            "Deleted vehicle type: {$fareRate->name}"
        );

        return redirect()->route('admin.fare-rates.index')
            ->with('success', 'Vehicle type deleted successfully.');
    }

    public function toggleStatus(VehicleType $fareRate)
    {
        $fareRate->is_active = !$fareRate->is_active;
        $fareRate->save();

        return response()->json(['success' => true]);
    }
}