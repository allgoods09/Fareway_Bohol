<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Report;
use App\Models\SavedRoute;
use App\Models\RouteSearchLog;
use App\Models\RecommendedPlace;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        
        $selectedMonth = $request->get('month', Carbon::now()->format('Y-m'));
        $startDate = Carbon::parse($selectedMonth . '-01')->startOfMonth();
        $endDate = Carbon::parse($selectedMonth . '-01')->endOfMonth();

        // Metrics
        $totalSearches = RouteSearchLog::whereBetween('created_at', [$startDate, $endDate])->count();
        $uniqueUsers = RouteSearchLog::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('user_id')
            ->distinct('user_id')
            ->count('user_id');
        
        $totalReports = Report::whereBetween('created_at', [$startDate, $endDate])->count();
        $resolvedReports = Report::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['resolved', 'rejected'])
            ->count();
        
        $avgDistance = RouteSearchLog::whereBetween('created_at', [$startDate, $endDate])
            ->avg('distance_km');
        
        $avgDuration = RouteSearchLog::whereBetween('created_at', [$startDate, $endDate])
            ->avg('duration_minutes');

        // Most searched routes
        $mostSearchedRoutes = RouteSearchLog::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('origin_lat, origin_lng, dest_lat, dest_lng, COUNT(*) as search_count')
            ->groupBy('origin_lat', 'origin_lng', 'dest_lat', 'dest_lng')
            ->orderBy('search_count', 'desc')
            ->limit(10)
            ->get();

        // Popular destinations (based on saved routes)
        $popularDestinations = RecommendedPlace::withCount(['savedRoutes' => function($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }])->orderBy('saved_routes_count', 'desc')->limit(10)->get();

        // Daily search trend
        $dailyTrend = RouteSearchLog::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Vehicle type usage (from fare_estimates JSON)
        $vehicleUsage = $this->getVehicleUsageStats($startDate, $endDate);

        $availableMonths = $this->getAvailableMonths();

        // Additional Metrics
        $newUsersThisMonth = User::whereBetween('created_at', [$startDate, $endDate])->count();
        $activeUsers = RouteSearchLog::whereBetween('created_at', [$startDate, $endDate])
            ->distinct('user_id')
            ->count('user_id');
        
        $usersWithSavedRoutes = SavedRoute::distinct('user_id')->count('user_id');
        $totalUsers = User::count();
        $engagementRate = $totalUsers > 0 ? ($usersWithSavedRoutes / $totalUsers) * 100 : 0;
        
        // Peak hour
        $peakHourData = RouteSearchLog::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('count', 'desc')
            ->first();
        $peakHour = $peakHourData ? $peakHourData->hour . ':00' : 'N/A';
        
        // Report type distribution
        $reportTypeData = Report::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get();
        
        $reportTypeDistribution = [];
        $typeLabels = [
            'wrong_fare' => 'Wrong Fare',
            'road_closure' => 'Road Closure',
            'vehicle_unavailable' => 'Vehicle Unavailable',
            'technical_issue' => 'Technical Issue',
            'other' => 'Other'
        ];
        
        foreach ($reportTypeData as $data) {
            $label = $typeLabels[$data->type] ?? $data->type;
            $reportTypeDistribution[$label] = $data->count;
        }
        
        // Hourly activity (0-23 hours)
        $hourlyData = RouteSearchLog::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();
        
        $hourlyActivity = [];
        for ($i = 0; $i <= 23; $i++) {
            $hourlyActivity[$i . ':00'] = 0;
        }
        
        foreach ($hourlyData as $data) {
            $hourlyActivity[$data->hour . ':00'] = $data->count;
        }

        return view('admin.analytics.index', compact(
            'selectedMonth', 'totalSearches', 'uniqueUsers', 'totalReports',
            'resolvedReports', 'avgDistance', 'avgDuration', 'mostSearchedRoutes',
            'popularDestinations', 'dailyTrend', 'vehicleUsage', 'availableMonths',
            'newUsersThisMonth', 'activeUsers', 'engagementRate', 'peakHour',
            'reportTypeDistribution', 'hourlyActivity'
        ));
    }

    public function exportPdf(Request $request)
    {
        $selectedMonth = $request->get('month', Carbon::now()->format('Y-m'));
        $startDate = Carbon::parse($selectedMonth . '-01')->startOfMonth();
        $endDate = Carbon::parse($selectedMonth . '-01')->endOfMonth();

        // Same queries as above
        $totalSearches = RouteSearchLog::whereBetween('created_at', [$startDate, $endDate])->count();
        $uniqueUsers = RouteSearchLog::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('user_id')
            ->distinct('user_id')
            ->count('user_id');
        
        $totalReports = Report::whereBetween('created_at', [$startDate, $endDate])->count();
        $resolvedReports = Report::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['resolved', 'rejected'])
            ->count();
        
        $avgDistance = round(RouteSearchLog::whereBetween('created_at', [$startDate, $endDate])->avg('distance_km') ?? 0, 2);
        $avgDuration = round(RouteSearchLog::whereBetween('created_at', [$startDate, $endDate])->avg('duration_minutes') ?? 0);
        
        $popularDestinations = RecommendedPlace::withCount(['savedRoutes' => function($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }])->orderBy('saved_routes_count', 'desc')->limit(5)->get();

        // Additional metrics for PDF
        $newUsersThisMonth = User::whereBetween('created_at', [$startDate, $endDate])->count();
        $engagementRate = User::count() > 0 ? (SavedRoute::distinct('user_id')->count('user_id') / User::count()) * 100 : 0;
        
        // Report type distribution for PDF
        $reportTypeData = Report::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get();
        
        $typeLabels = [
            'wrong_fare' => 'Wrong Fare',
            'road_closure' => 'Road Closure',
            'vehicle_unavailable' => 'Vehicle Unavailable',
            'technical_issue' => 'Technical Issue',
            'other' => 'Other'
        ];
        
        $reportTypeSummary = [];
        foreach ($reportTypeData as $data) {
            $label = $typeLabels[$data->type] ?? $data->type;
            $reportTypeSummary[] = $label . ': ' . $data->count;
        }

        // ========== NEW: Chart Data ==========
        // Daily trend data for chart
        $dailyTrendRaw = RouteSearchLog::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        $dailyTrendLabels = [];
        $dailyTrendValues = [];
        foreach ($dailyTrendRaw as $item) {
            $dailyTrendLabels[] = Carbon::parse($item->date)->format('M d');
            $dailyTrendValues[] = $item->count;
        }
        
        // Vehicle usage data for chart
        $vehicleUsage = $this->getVehicleUsageStats($startDate, $endDate);

        $data = [
            'month' => Carbon::parse($selectedMonth)->format('F Y'),
            'totalSearches' => $totalSearches,
            'uniqueUsers' => $uniqueUsers,
            'totalReports' => $totalReports,
            'resolvedReports' => $resolvedReports,
            'avgDistance' => $avgDistance,
            'avgDuration' => $avgDuration,
            'popularDestinations' => $popularDestinations,
            'newUsersThisMonth' => $newUsersThisMonth,
            'engagementRate' => round($engagementRate, 1),
            'reportTypeSummary' => $reportTypeSummary,
            'generated_at' => now()->format('Y-m-d H:i:s'),
            // New chart data
            'dailyTrendLabels' => $dailyTrendLabels,
            'dailyTrendValues' => $dailyTrendValues,
            'vehicleUsage' => $vehicleUsage,
        ];

        $pdf = Pdf::loadView('admin.analytics.export-pdf', $data);
        return $pdf->download("fareway-analytics-{$selectedMonth}.pdf");
    }

    private function getVehicleUsageStats($startDate, $endDate)
    {
        $logs = RouteSearchLog::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('fare_estimates')
            ->get();
        
        $usage = [];
        foreach ($logs as $log) {
            $estimates = $log->fare_estimates;
            if (is_array($estimates)) {
                foreach ($estimates as $vehicle => $fare) {
                    if (!isset($usage[$vehicle])) {
                        $usage[$vehicle] = 0;
                    }
                    $usage[$vehicle]++;
                }
            }
        }
        
        arsort($usage);
        return $usage;
    }

    private function getAvailableMonths()
    {
        // MySQL compatible version
        $months = RouteSearchLog::selectRaw('DISTINCT DATE_FORMAT(created_at, "%Y-%m") as month')
            ->orderBy('month', 'desc')
            ->pluck('month');
        
        if ($months->isEmpty()) {
            return [Carbon::now()->format('Y-m')];
        }
        
        return $months;
    }

    public function exportCsv(Request $request)
    {
        $selectedMonth = $request->get('month', Carbon::now()->format('Y-m'));
        $startDate = Carbon::parse($selectedMonth . '-01')->startOfMonth();
        $endDate = Carbon::parse($selectedMonth . '-01')->endOfMonth();
        
        $searches = RouteSearchLog::whereBetween('created_at', [$startDate, $endDate])
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $filename = "fareway-searches-{$selectedMonth}.csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];
        
        $callback = function() use ($searches) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'Date', 
                'Time', 
                'User', 
                'Email',
                'Origin Lat', 
                'Origin Lng', 
                'Dest Lat', 
                'Dest Lng', 
                'Distance (km)', 
                'Duration (min)'
            ]);
            
            // Add data rows
            foreach ($searches as $search) {
                fputcsv($file, [
                    $search->created_at->format('Y-m-d'),
                    $search->created_at->format('H:i:s'),
                    $search->user?->name ?? 'Guest',
                    $search->user?->email ?? 'N/A',
                    $search->origin_lat,
                    $search->origin_lng,
                    $search->dest_lat,
                    $search->dest_lng,
                    $search->distance_km,
                    $search->duration_minutes
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}