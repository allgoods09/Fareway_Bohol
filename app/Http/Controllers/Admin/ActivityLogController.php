<?php
// app/Http/Controllers/Admin/ActivityLogController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::latest();
        
        if ($request->action) {
            $query->where('action', $request->action);
        }
        
        $logs = $query->paginate(50);
        
        return view('admin.activity-logs.index', compact('logs'));
    }
}