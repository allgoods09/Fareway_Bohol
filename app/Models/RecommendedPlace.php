<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecommendedPlace extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'latitude', 'longitude', 
        'image_url', 'category', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function savedRoutes()
    {
        return $this->hasMany(SavedRoute::class, 'recommended_place_id');
    }
}