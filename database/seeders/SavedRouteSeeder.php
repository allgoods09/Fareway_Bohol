<?php
// database/seeders/SavedRouteSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SavedRoute;
use App\Models\User;
use App\Models\RecommendedPlace;
use Carbon\Carbon;

class SavedRouteSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $places = RecommendedPlace::where('is_active', true)->get();
        
        $customRoutes = [
            [
                'name' => 'Home to Work',
                'origin_lat' => 9.9210,
                'origin_lng' => 124.2900,
                'origin_address' => 'Tagbilaran City',
                'dest_lat' => 9.5489,
                'dest_lng' => 123.7731,
                'dest_address' => 'Panglao',
            ],
            [
                'name' => 'School Run',
                'origin_lat' => 9.6381,
                'origin_lng' => 124.0347,
                'origin_address' => 'Loboc',
                'dest_lat' => 9.9210,
                'dest_lng' => 124.2900,
                'dest_address' => 'Tagbilaran City',
            ],
            [
                'name' => 'Weekend Trip',
                'origin_lat' => 9.9210,
                'origin_lng' => 124.2900,
                'origin_address' => 'Tagbilaran City',
                'dest_lat' => 9.9167,
                'dest_lng' => 124.1333,
                'dest_address' => 'Chocolate Hills',
            ],
            [
                'name' => 'Beach Day',
                'origin_lat' => 9.9210,
                'origin_lng' => 124.2900,
                'origin_address' => 'Tagbilaran City',
                'dest_lat' => 9.5489,
                'dest_lng' => 123.7731,
                'dest_address' => 'Alona Beach',
            ],
        ];

        foreach ($users as $user) {
            // Save 2-4 custom routes per user
            $numCustom = rand(2, 4);
            for ($i = 0; $i < $numCustom; $i++) {
                $route = $customRoutes[array_rand($customRoutes)];
                SavedRoute::create([
                    'user_id' => $user->id,
                    'name' => $route['name'] . ' ' . ($i + 1),
                    'origin_lat' => $route['origin_lat'],
                    'origin_lng' => $route['origin_lng'],
                    'origin_address' => $route['origin_address'],
                    'dest_lat' => $route['dest_lat'],
                    'dest_lng' => $route['dest_lng'],
                    'dest_address' => $route['dest_address'],
                    'type' => 'custom_route',
                    'created_at' => Carbon::now()->subDays(rand(0, 30)),
                ]);
            }
            
            // Save 2-4 recommended places per user
            $numPlaces = rand(2, 4);
            $randomPlaces = $places->random($numPlaces);
            foreach ($randomPlaces as $place) {
                SavedRoute::create([
                    'user_id' => $user->id,
                    'name' => $place->name,
                    'origin_lat' => 0,
                    'origin_lng' => 0,
                    'dest_lat' => $place->latitude,
                    'dest_lng' => $place->longitude,
                    'dest_address' => $place->name,
                    'type' => 'recommended_place',
                    'recommended_place_id' => $place->id,
                    'created_at' => Carbon::now()->subDays(rand(0, 30)),
                ]);
            }
        }
    }
}