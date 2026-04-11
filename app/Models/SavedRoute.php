<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'origin_lat', 'origin_lng', 'origin_address',
        'dest_lat', 'dest_lng', 'dest_address', 'type', 'recommended_place_id'
    ];
}