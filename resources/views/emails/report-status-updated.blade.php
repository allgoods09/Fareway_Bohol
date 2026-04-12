{{-- resources/views/emails/report-status-updated.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Report Status Updated</title>
</head>
<body style="font-family: Arial, sans-serif;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="background: #0c2340; padding: 20px; text-align: center; border-radius: 10px;">
            <h1 style="color: white;">Fareway Bohol</h1>
        </div>
        
        <div style="padding: 20px; background: #f5f7fa; border-radius: 10px; margin-top: 20px;">
            <h2>Report #{{ $report->id }} Status Updated</h2>
            <p>Dear {{ $report->user->name }},</p>
            <p>Your report has been updated to: <strong>{{ ucfirst($report->status) }}</strong></p>
            
            @if($report->admin_notes)
            <div style="background: #e1f5ee; padding: 15px; border-radius: 8px; margin: 15px 0;">
                <strong>Staff Note:</strong>
                <p>{{ $report->admin_notes }}</p>
            </div>
            @endif
            
            <p>You can view your report here:</p>
            <a href="{{ route('user.my-reports') }}" style="background: #0e8a6e; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">View My Reports</a>
        </div>
        
        <div style="text-align: center; margin-top: 20px; font-size: 12px; color: #666;">
            &copy; {{ date('Y') }} Fareway Bohol. All rights reserved.
        </div>
    </div>
</body>
</html>