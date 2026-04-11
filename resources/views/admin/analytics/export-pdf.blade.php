<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fareway Analytics Report - {{ $month }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #1e3c72;
        }
        .subtitle {
            font-size: 16px;
            color: #666;
            margin-top: 5px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            background: #f9f9f9;
        }
        .stat-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }
        .stat-value {
            font-size: 28px;
            font-weight: bold;
            color: #333;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            color: #1e3c72;
            border-left: 4px solid #1e3c72;
            padding-left: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #999;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Fareway Bohol</div>
        <div class="subtitle">Monthly Analytics Report</div>
        <div class="subtitle">{{ $month }}</div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Route Searches</div>
            <div class="stat-value">{{ number_format($totalSearches) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Unique Users</div>
            <div class="stat-value">{{ number_format($uniqueUsers) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Reports</div>
            <div class="stat-value">{{ number_format($totalReports) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Resolved Reports</div>
            <div class="stat-value">{{ number_format($resolvedReports) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Average Distance</div>
            <div class="stat-value">{{ number_format($avgDistance, 2) }} km</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Average Duration</div>
            <div class="stat-value">{{ number_format($avgDuration) }} minutes</div>
        </div>
    </div>

    <div class="section-title">Popular Destinations</div>
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Place Name</th>
                <th>Times Saved</th>
            </tr>
        </thead>
        <tbody>
            @foreach($popularDestinations as $index => $place)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $place->name }}</td>
                <td>{{ $place->saved_routes_count ?? 0 }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ $generated_at }}<br>
        Fareway Bohol - Public Transport Route & Fare System
    </div>
</body>
</html>