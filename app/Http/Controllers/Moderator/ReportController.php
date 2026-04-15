<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReportStatusUpdated;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = Report::with(['user', 'resolver']);
        
        // Filter by status
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        // Filter by type
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }
        
        // Search by user name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('description', 'like', "%{$search}%");
        }
        
        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $reports = $query->latest()->paginate(15)->withQueryString();
        
        // Get unique report types for filter dropdown
        $reportTypes = Report::distinct()->pluck('type');
        
        return view('moderator.reports.index', compact('reports', 'status', 'reportTypes'));
    }

    public function show(Report $report)
    {
        $report->load(['user', 'resolver']);
        return view('moderator.reports.show', compact('report'));
    }

    public function updateStatus(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,resolved,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        $oldStatus = $report->status;
        $report->status = $validated['status'];
        $report->admin_notes = $validated['admin_notes'];
        
        if (in_array($validated['status'], ['resolved', 'rejected'])) {
            $report->resolved_by = Auth::id();
            $report->resolved_at = now();
        }
        
        $report->save();

        // Send email notification to user if status changed
        if ($oldStatus !== $report->status && $report->user && $report->user->email) {
            try {
                Mail::to($report->user->email)->send(new ReportStatusUpdated($report));
            } catch (\Exception $e) {
                Log::error('Failed to send email: ' . $e->getMessage());
            }
        }

        return redirect()->route('moderator.reports.show', $report)
            ->with('success', 'Report status updated successfully.');
    }

    // NO DESTROY METHOD FOR MODERATORS
}