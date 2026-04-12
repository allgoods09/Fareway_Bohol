<?php
// app/Traits/LogsActivity.php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected function logActivity($action, $entityType = null, $entityId = null, $details = null)
    {
        try {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'user_name' => Auth::user()?->name,
                'user_role' => Auth::user()?->role,
                'action' => $action,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'details' => $details,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ]);
        } catch (\Exception $e) {
            // Silently fail - don't break the app for logging
        }
    }
}