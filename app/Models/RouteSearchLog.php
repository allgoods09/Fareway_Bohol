<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteSearchLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'origin_lat', 'origin_lng', 'dest_lat', 'dest_lng',
        'distance_km', 'duration_minutes', 'fare_estimates'
    ];

    protected $casts = [
        'fare_estimates' => 'array',
    ];
}