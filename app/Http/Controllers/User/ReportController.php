<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function create()
    {
        return view('user.report');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:wrong_fare,road_closure,vehicle_unavailable,technical_issue,other',
            'description' => 'required|string|min:5',
            'origin_info' => 'nullable|string',
            'dest_info' => 'nullable|string',
            'origin_lat' => 'nullable|numeric',
            'origin_lng' => 'nullable|numeric',
            'dest_lat' => 'nullable|numeric',
            'dest_lng' => 'nullable|numeric',
            'screenshot' => 'nullable|image|max:5120'
        ]);
        
        Report::create([
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'description' => $validated['description'],
            'origin_lat' => $validated['origin_lat'] ?? null,
            'origin_lng' => $validated['origin_lng'] ?? null,
            'dest_lat' => $validated['dest_lat'] ?? null,
            'dest_lng' => $validated['dest_lng'] ?? null,
            'status' => 'pending'
        ]);
        
        return response()->json(['success' => true]);
    }

    public function myReports()
    {
        $reports = Report::where('user_id', Auth::id())
            ->with('resolver')
            ->latest()
            ->paginate(10);
        
        $totalReports = Report::where('user_id', Auth::id())->count();
        $pendingReports = Report::where('user_id', Auth::id())->where('status', 'pending')->count();
        $resolvedReports = Report::where('user_id', Auth::id())->whereIn('status', ['resolved', 'closed'])->count();
        
        return view('user.my-reports', compact('reports', 'totalReports', 'pendingReports', 'resolvedReports'));
    }

    public function getReportApi($id)
    {
        $report = Report::where('user_id', Auth::id())
            ->with('resolver')
            ->findOrFail($id);
        
        return response()->json([
            'id' => $report->id,
            'type' => $report->type,
            'description' => $report->description,
            'status' => $report->status,
            'origin_lat' => $report->origin_lat,
            'origin_lng' => $report->origin_lng,
            'dest_lat' => $report->dest_lat,
            'dest_lng' => $report->dest_lng,
            'admin_notes' => $report->admin_notes,
            'resolver_name' => $report->resolver?->name,
            'created_at' => $report->created_at,
            'updated_at' => $report->updated_at,
            'resolved_at' => $report->resolved_at,
        ]);
    }

    public function markAsResolved($id)
    {
        $report = Report::where('user_id', Auth::id())->findOrFail($id);
        
        // Only allow marking as resolved if not already resolved, rejected, or closed
        if (!in_array($report->status, ['resolved', 'rejected', 'closed'])) {
            $report->status = 'resolved';
            $report->save();
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Cannot resolve this report']);
    }
}