<?php
// app/Http/Controllers/Admin/ActivityLogController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::latest();
        
        // Filter by action
        if ($request->action) {
            $query->where('action', $request->action);
        }
        
        // Filter by month/year
        if ($request->month) {
            $query->whereYear('created_at', substr($request->month, 0, 4))
                  ->whereMonth('created_at', substr($request->month, 5, 2));
        }
        
        $logs = $query->paginate(50);
        
        // Get available months for filter dropdown
        $availableMonths = ActivityLog::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month')
            ->distinct()
            ->orderBy('month', 'desc')
            ->pluck('month');
        
        return view('admin.activity-logs.index', compact('logs', 'availableMonths'));
    }
    
    public function exportCsv(Request $request)
    {
        $query = ActivityLog::latest();
        
        // Apply same filters as index
        if ($request->action) {
            $query->where('action', $request->action);
        }
        
        if ($request->month) {
            $query->whereYear('created_at', substr($request->month, 0, 4))
                  ->whereMonth('created_at', substr($request->month, 5, 2));
        }
        
        $logs = $query->get();
        
        $filename = 'activity-logs-' . now()->format('Y-m-d-His') . '.csv';
        
        $handle = fopen('php://temp', 'w+');
        
        // Add CSV headers
        fputcsv($handle, [
            'Date', 'Time', 'User', 'Role', 'Action', 'Details', 'IP Address', 'User Agent'
        ]);
        
        // Add data rows
        foreach ($logs as $log) {
            fputcsv($handle, [
                $log->created_at->format('Y-m-d'),
                $log->created_at->format('H:i:s'),
                $log->user_name ?? 'System',
                $log->user_role ?? 'N/A',
                $log->action,
                $log->details ?? '',
                $log->ip_address ?? '',
                $log->user_agent ?? '',
            ]);
        }
        
        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);
        
        return response($csvContent, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
    
    public function exportPdf(Request $request)
    {
        $query = ActivityLog::latest();
        
        // Apply same filters as index
        if ($request->action) {
            $query->where('action', $request->action);
        }
        
        if ($request->month) {
            $query->whereYear('created_at', substr($request->month, 0, 4))
                  ->whereMonth('created_at', substr($request->month, 5, 2));
        }
        
        $logs = $query->get();
        
        $pdf = Pdf::loadView('admin.activity-logs.export-pdf', [
            'logs' => $logs,
            'exportDate' => now(),
            'filters' => [
                'action' => $request->action,
                'month' => $request->month,
            ]
        ]);
        
        return $pdf->download('activity-logs-' . now()->format('Y-m-d-His') . '.pdf');
    }
}