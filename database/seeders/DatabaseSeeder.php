<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            VehicleTypeSeeder::class,
            AdminUserSeeder::class,
            RecommendedPlaceSeeder::class,
            DemoUserSeeder::class,
            RouteSearchLogSeeder::class,
            ReportSeeder::class,
            SavedRouteSeeder::class,
        ]);
    }
}