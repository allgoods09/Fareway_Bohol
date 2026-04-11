<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'icon', 'base_fare', 'base_km', 'per_km_rate',
        'night_surcharge', 'night_start', 'night_end', 'is_active'
    ];

    protected $casts = [
        'base_fare' => 'decimal:2',
        'base_km' => 'decimal:2',
        'per_km_rate' => 'decimal:2',
        'night_surcharge' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function calculateFare($distance_km, $isNightTime = false)
    {
        if ($distance_km <= $this->base_km) {
            $fare = $this->base_fare;
        } else {
            $extraKm = $distance_km - $this->base_km;
            $fare = $this->base_fare + ($extraKm * $this->per_km_rate);
        }

        if ($isNightTime) {
            $fare += $this->night_surcharge;
        }

        return round($fare, 2);
    }
}