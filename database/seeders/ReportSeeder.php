<?php
// database/seeders/ReportSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        
        $reports = [
            [
                'type' => 'wrong_fare',
                'description' => 'The fare from Tagbilaran to Panglao showed ₱120 but the actual fare was only ₱80. Please update the fare rates.',
                'status' => 'resolved',
            ],
            [
                'type' => 'road_closure',
                'description' => 'The road going to Chocolate Hills is closed for repairs. Please update the route.',
                'status' => 'in_progress',
            ],
            [
                'type' => 'vehicle_unavailable',
                'description' => 'No multi-cabs available on this route during night time. Only tricycles are operating.',
                'status' => 'pending',
            ],
            [
                'type' => 'technical_issue',
                'description' => 'The map is not loading properly on my mobile device. Using Chrome browser.',
                'status' => 'in_progress',
            ],
            [
                'type' => 'wrong_fare',
                'description' => 'The bus fare calculator seems off. It\'s showing ₱200 for a 30km trip which is too high.',
                'status' => 'pending',
            ],
            [
                'type' => 'road_closure',
                'description' => 'The alternate route via Dauis is not showing on the map. Please add this option.',
                'status' => 'resolved',
            ],
            [
                'type' => 'other',
                'description' => 'Can you add jeepneys as a vehicle option? They are very common in Bohol.',
                'status' => 'pending',
            ],
            [
                'type' => 'technical_issue',
                'description' => 'The save route feature is not working. It says error saving route.',
                'status' => 'resolved',
            ],
        ];

        $statuses = ['pending', 'in_progress', 'resolved', 'rejected'];
        $admin = User::where('role', 'admin')->first();
        
        for ($i = 0; $i < 50; $i++) {
            $report = $reports[array_rand($reports)];
            $user = $users->random();
            $date = Carbon::now()->subDays(rand(0, 45));
            $status = $statuses[array_rand($statuses)];
            
            $reportData = [
                'user_id' => $user->id,
                'type' => $report['type'],
                'description' => $report['description'],
                'status' => $status,
                'created_at' => $date,
                'updated_at' => $date,
            ];
            
            // Add coordinates for some reports
            if (rand(0, 1)) {
                $reportData['origin_lat'] = 9.9210 + (rand(-100, 100) / 1000);
                $reportData['origin_lng'] = 124.2900 + (rand(-100, 100) / 1000);
            }
            
            // Add resolution info for resolved reports
            if ($status === 'resolved' && $admin) {
                $reportData['resolved_by'] = $admin->id;
                $reportData['resolved_at'] = Carbon::parse($date)->addDays(rand(1, 7));
                $reportData['admin_notes'] = 'Thank you for your report. This issue has been addressed.';
            } elseif ($status === 'in_progress' && $admin) {
                $reportData['admin_notes'] = 'Our team is currently investigating this issue.';
            }
            
            Report::create($reportData);
        }
    }
}