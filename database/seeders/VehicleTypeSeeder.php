<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('vehicle_types')->insert([
            [
                'name' => 'Tricycle',
                'icon' => 'fa-solid fa-motorcycle',
                'base_fare' => 12.00, // common minimum fare
                'base_km' => 1.00,
                'per_km_rate' => 4.00, // realistic dagdag
                'night_surcharge' => 5.00, // small increase only
                'night_start' => '20:00:00',
                'night_end' => '05:00:00',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Motorcycle',
                'icon' => 'fa-solid fa-motorcycle',
                'base_fare' => 20.00, // habal-habal usually starts higher
                'base_km' => 1.50,
                'per_km_rate' => 6.00,
                'night_surcharge' => 10.00,
                'night_start' => '20:00:00',
                'night_end' => '05:00:00',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Multi-cab',
                'icon' => 'fa-solid fa-van-shuttle',
                'base_fare' => 13.00, // modern jeepney-like fare
                'base_km' => 2.00, // usually covers short route already
                'per_km_rate' => 2.50, // low since shared ride
                'night_surcharge' => 5.00,
                'night_start' => '20:00:00',
                'night_end' => '05:00:00',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bus',
                'icon' => 'fa-solid fa-bus',
                'base_fare' => 15.00, // provincial bus minimum
                'base_km' => 5.00, // buses usually start long-distance
                'per_km_rate' => 1.80, // realistic provincial rate
                'night_surcharge' => 10.00,
                'night_start' => '20:00:00',
                'night_end' => '05:00:00',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}