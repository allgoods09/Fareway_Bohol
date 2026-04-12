<?php
// database/seeders/RouteSearchLogSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RouteSearchLog;
use App\Models\User;
use Carbon\Carbon;

class RouteSearchLogSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        
        $routes = [
            // Tagbilaran to Panglao
            ['origin' => [9.9210, 124.2900], 'dest' => [9.5489, 123.7731], 'distance' => 45.2, 'duration' => 75],
            // Tagbilaran to Chocolate Hills
            ['origin' => [9.9210, 124.2900], 'dest' => [9.9167, 124.1333], 'distance' => 22.5, 'duration' => 40],
            // Panglao to Loboc River
            ['origin' => [9.5489, 123.7731], 'dest' => [9.6381, 124.0347], 'distance' => 32.8, 'duration' => 55],
            // Tagbilaran to Alona Beach
            ['origin' => [9.9210, 124.2900], 'dest' => [9.5489, 123.7731], 'distance' => 45.2, 'duration' => 75],
            // Loboc to Tarsier Sanctuary
            ['origin' => [9.6381, 124.0347], 'dest' => [9.6872, 123.9522], 'distance' => 15.3, 'duration' => 28],
            // Tagbilaran to Danao
            ['origin' => [9.9210, 124.2900], 'dest' => [9.9453, 124.2439], 'distance' => 12.5, 'duration' => 25],
            // Panglao to Hinagdanan Cave
            ['origin' => [9.5489, 123.7731], 'dest' => [9.6203, 123.7694], 'distance' => 18.7, 'duration' => 32],
            // Tagbilaran to Bilar Forest
            ['origin' => [9.9210, 124.2900], 'dest' => [9.7089, 124.1039], 'distance' => 28.4, 'duration' => 48],
        ];

        $vehicleNames = ['Tricycle', 'Motorcycle', 'Multi-cab', 'Bus'];
        
        for ($i = 0; $i < 200; $i++) {
            $route = $routes[array_rand($routes)];
            $user = $users->random();
            $date = Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23));
            $isNight = $date->hour >= 20 || $date->hour < 5;
            
            // Generate fare estimates
            $fareEstimates = [];
            foreach ($vehicleNames as $vehicle) {
                $baseFare = rand(10, 20);
                $perKm = rand(2, 10);
                $fareEstimates[$vehicle] = $baseFare + ($route['distance'] * $perKm);
                if ($isNight) {
                    $fareEstimates[$vehicle] += rand(10, 20);
                }
            }
            
            RouteSearchLog::create([
                'user_id' => $user->id,
                'origin_lat' => $route['origin'][0],
                'origin_lng' => $route['origin'][1],
                'dest_lat' => $route['dest'][0],
                'dest_lng' => $route['dest'][1],
                'distance_km' => $route['distance'],
                'duration_minutes' => $route['duration'],
                'fare_estimates' => $fareEstimates,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }
}