<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = Report::with(['user', 'resolver']);
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $reports = $query->latest()->paginate(15);
        
        return view('admin.reports.index', compact('reports', 'status'));
    }

    public function show(Report $report)
    {
        $report->load(['user', 'resolver']);
        return view('admin.reports.show', compact('report'));
    }

    public function updateStatus(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,resolved,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        $report->status = $validated['status'];
        $report->admin_notes = $validated['admin_notes'];
        
        if (in_array($validated['status'], ['resolved', 'rejected'])) {
            $report->resolved_by = Auth::id();
            $report->resolved_at = now();
        }
        
        $report->save();

        return redirect()->route('admin.reports.show', $report)
            ->with('success', 'Report status updated successfully.');
    }

    public function destroy(Report $report)
    {
        $report->delete();
        return redirect()->route('admin.reports.index')
            ->with('success', 'Report deleted successfully.');
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'reports' => 'required|array',
            'reports.*' => 'exists:reports,id',
            'status' => 'required|in:pending,in_progress,resolved,rejected',
        ]);

        Report::whereIn('id', $request->reports)->update([
            'status' => $request->status,
            'resolved_by' => in_array($request->status, ['resolved', 'rejected']) ? Auth::id() : null,
            'resolved_at' => in_array($request->status, ['resolved', 'rejected']) ? now() : null,
        ]);

        return response()->json(['success' => true]);
    }
}