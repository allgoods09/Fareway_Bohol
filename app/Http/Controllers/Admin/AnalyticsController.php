<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Report;
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

        return view('admin.analytics.index', compact(
            'selectedMonth', 'totalSearches', 'uniqueUsers', 'totalReports',
            'resolvedReports', 'avgDistance', 'avgDuration', 'mostSearchedRoutes',
            'popularDestinations', 'dailyTrend', 'vehicleUsage', 'availableMonths'
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

        $data = [
            'month' => Carbon::parse($selectedMonth)->format('F Y'),
            'totalSearches' => $totalSearches,
            'uniqueUsers' => $uniqueUsers,
            'totalReports' => $totalReports,
            'resolvedReports' => $resolvedReports,
            'avgDistance' => $avgDistance,
            'avgDuration' => $avgDuration,
            'popularDestinations' => $popularDestinations,
            'generated_at' => now()->format('Y-m-d H:i:s'),
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
        $months = RouteSearchLog::selectRaw('DISTINCT DATE_FORMAT(created_at, "%Y-%m") as month')
            ->orderBy('month', 'desc')
            ->pluck('month');
        
        if ($months->isEmpty()) {
            return [Carbon::now()->format('Y-m')];
        }
        
        return $months;
    }
}