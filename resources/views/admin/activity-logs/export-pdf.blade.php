<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Activity Logs Export</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #0e8a6e;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #0c2340;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            color: #666;
            margin: 5px 0 0;
            font-size: 11px;
        }
        .filters {
            background: #f5f5f5;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 11px;
        }
        .filters strong {
            color: #0c2340;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background: #0c2340;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 11px;
        }
        td {
            border-bottom: 1px solid #ddd;
            padding: 8px;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #999;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-admin { background: #fee2e2; color: #991b1b; }
        .badge-moderator { background: #fef3c7; color: #92400e; }
        .badge-user { background: #dbeafe; color: #1e40af; }
        .text-muted { color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Fareway Bohol - Activity Logs Report</h1>
        <p>Generated on {{ $exportDate->format('F j, Y g:i A') }}</p>
    </div>
    
    @if($filters['action'] || $filters['month'])
    <div class="filters">
        <strong>Filters Applied:</strong><br>
        @if($filters['action'])
            Action: {{ ucfirst(str_replace('_', ' ', $filters['action'])) }}<br>
        @endif
        @if($filters['month'])
            Month: {{ \Carbon\Carbon::createFromFormat('Y-m', $filters['month'])->format('F Y') }}
        @endif
    </div>
    @endif
    
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>User</th>
                <th>Role</th>
                <th>Action</th>
                <th>Details</th>
                <th>IP Address</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr>
                <td>{{ $log->created_at->format('Y-m-d') }}</td>
                <td>{{ $log->created_at->format('H:i:s') }}</td>
                <td>{{ $log->user_name ?? 'System' }}</td>
                <td>
                    @if($log->user_role)
                        <span class="badge badge-{{ $log->user_role }}">
                            {{ ucfirst($log->user_role) }}
                        </span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>{{ str_replace('_', ' ', ucfirst($log->action)) }}</td>
                <td>{{ Str::limit($log->details ?? '—', 80) }}</td>
                <td>{{ $log->ip_address ?? '—' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px;">
                    No activity logs found for the selected filters.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footer">
        <p>Total Logs: {{ $logs->count() }} | Fareway Bohol - Public Transport Navigator</p>
        <p>This report is confidential and for administrative use only.</p>
    </div>
</body>
</html>