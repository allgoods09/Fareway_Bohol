{{-- resources/views/admin/analytics/export-pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fareway Analytics Report - {{ $month }}</title>
    <style>
        /* Professional Corporate Style */
        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #ffffff;
            color: #1a1a2e;
            line-height: 1.4;
        }

        /* Header */
        .report-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #0c2340;
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .logo-icon {
            width: 45px;
            height: 45px;
            background: #0c2340;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 22px;
            font-weight: bold;
        }

        .title-section {
            flex: 1;
        }

        .main-title {
            font-size: 24px;
            font-weight: 700;
            color: #0c2340;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .report-type {
            font-size: 14px;
            color: #0e8a6e;
            font-weight: 500;
            margin: 4px 0 0 0;
            letter-spacing: 0.5px;
        }

        .report-meta {
            text-align: right;
            font-size: 11px;
            color: #6b7280;
            margin-top: 10px;
        }

        .report-month {
            font-size: 18px;
            font-weight: 600;
            color: #1a3a5c;
            margin: 5px 0 0 0;
        }

        /* Executive Summary */
        .executive-summary {
            background: #f8fafc;
            border-left: 4px solid #0e8a6e;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 4px;
        }

        .executive-summary h2 {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin: 0 0 8px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .executive-summary p {
            font-size: 13px;
            color: #4b5563;
            margin: 0;
            line-height: 1.5;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 35px;
        }

        .stat-card {
            border: 1px solid #e5e7eb;
            padding: 18px;
            background: #ffffff;
            border-radius: 8px;
        }

        .stat-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #0c2340;
            line-height: 1.2;
        }

        .stat-unit {
            font-size: 12px;
            font-weight: 400;
            color: #9ca3af;
            margin-left: 4px;
        }

        .stat-trend {
            font-size: 11px;
            margin-top: 8px;
            color: #10b981;
        }

        /* Section Styles */
        .section {
            margin-bottom: 35px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #0c2340;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
            display: inline-block;
        }

        .section-description {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 15px;
        }

        /* Chart Styles */
        .chart-container {
            margin: 20px 0;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
        }

        .chart-title {
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 15px;
            text-align: center;
        }

        .bar-chart {
            width: 100%;
            margin: 10px 0;
        }

        .bar-row {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            gap: 10px;
        }

        .bar-label {
            width: 100px;
            font-size: 11px;
            font-weight: 500;
            color: #4b5563;
        }

        .bar-wrapper {
            flex: 1;
            height: 24px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }

        .bar-fill {
            height: 100%;
            background: #0e8a6e;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 8px;
            color: white;
            font-size: 10px;
            font-weight: 600;
        }

        .bar-value {
            min-width: 40px;
            font-size: 11px;
            font-weight: 600;
            color: #0c2340;
            text-align: right;
        }

        /* Daily Trend Table */
        .trend-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        .trend-table th,
        .trend-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e5e7eb;
            text-align: center;
        }

        .trend-table th {
            background: #f1f5f9;
            font-weight: 600;
            color: #1e293b;
        }

        /* Table Styles */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .data-table th {
            background: #f1f5f9;
            color: #1e293b;
            font-weight: 600;
            padding: 10px 12px;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
        }

        .data-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
        }

        /* Rank Badge */
        .rank-badge {
            display: inline-block;
            width: 24px;
            height: 24px;
            background: #0c2340;
            color: #fff;
            border-radius: 4px;
            text-align: center;
            line-height: 24px;
            font-size: 11px;
            font-weight: 600;
        }

        .rank-badge.top-1 { background: #f59e0b; }
        .rank-badge.top-2 { background: #94a3b8; }
        .rank-badge.top-3 { background: #cd7f32; }

        /* Footer */
        .report-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 9px;
            color: #9ca3af;
        }

        .footer-left, .footer-right {
            text-align: left;
        }
        .footer-right {
            text-align: right;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="report-header">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div class="logo-area">
                <div class="logo-icon">FB</div>
                <div class="title-section">
                    <h1 class="main-title">Fareway Bohol</h1>
                    <p class="report-type">Analytics Report</p>
                </div>
            </div>
            <div class="report-meta">
                <div>Generated: {{ $generated_at }}</div>
                <div class="report-month">{{ $month }}</div>
            </div>
        </div>
    </div>

    <!-- Executive Summary -->
    <div class="executive-summary">
        <h2>Executive Summary</h2>
        <p>This report provides a comprehensive analysis of Fareway Bohol's system usage for {{ $month }}. 
        Key highlights include {{ number_format($totalSearches) }} total route searches performed by {{ number_format($uniqueUsers) }} unique users, 
        with an average travel distance of {{ number_format($avgDistance, 2) }} kilometers per route. 
        The platform received {{ number_format($totalReports) }} user reports, of which {{ number_format($resolvedReports) }} have been resolved, 
        demonstrating a {{ $totalReports > 0 ? round(($resolvedReports / $totalReports) * 100) : 0 }}% resolution rate.</p>
    </div>

    <!-- Key Metrics Grid -->
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
            <div class="stat-label">Avg. Distance</div>
            <div class="stat-value">{{ number_format($avgDistance, 2) }}<span class="stat-unit">km</span></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Reports</div>
            <div class="stat-value">{{ number_format($totalReports) }}</div>
            <div class="stat-trend">{{ number_format($resolvedReports) }} resolved</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Avg. Travel Time</div>
            <div class="stat-value">{{ number_format($avgDuration) }}<span class="stat-unit">min</span></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Engagement Rate</div>
            <div class="stat-value">{{ number_format($engagementRate ?? 0, 1) }}<span class="stat-unit">%</span></div>
        </div>
    </div>

    <!-- Daily Search Trend Chart (SVG) -->
    @if(isset($dailyTrendData) && count($dailyTrendData) > 0)
    <div class="section">
        <h2 class="section-title">Daily Search Trend</h2>
        <p class="section-description">Route searches per day for {{ $month }}</p>
        <div class="chart-container">
            @php
                $maxCount = max($dailyTrendData);
                $barWidth = 900 / count($dailyTrendData);
                $barWidth = min($barWidth, 30);
            @endphp
            <svg width="100%" height="300" viewBox="0 0 900 300" preserveAspectRatio="none" style="background: #ffffff;">
                @foreach($dailyTrendData as $index => $count)
                    @php
                        $barHeight = ($count / max(1, $maxCount)) * 200;
                        $x = ($index * $barWidth) + 10;
                        $y = 250 - $barHeight;
                    @endphp
                    <rect x="{{ $x }}" y="{{ $y }}" width="{{ max($barWidth - 2, 5) }}" height="{{ $barHeight }}" fill="#0e8a6e" rx="2"/>
                    <text x="{{ $x + ($barWidth / 2) }}" y="270" font-size="8" text-anchor="middle" fill="#6b7280" transform="rotate(-45, {{ $x + ($barWidth / 2) }}, 270)">{{ $dailyTrendLabels[$index] ?? '' }}</text>
                    <text x="{{ $x + ($barWidth / 2) }}" y="{{ $y - 3 }}" font-size="8" text-anchor="middle" fill="#0c2340" font-weight="bold">{{ $count }}</text>
                @endforeach
            </svg>
        </div>
    </div>
    @endif

    <!-- Vehicle Type Usage Chart (Bar Chart) -->
    @if(isset($vehicleUsage) && count($vehicleUsage) > 0)
    <div class="section">
        <h2 class="section-title">Vehicle Type Usage</h2>
        <p class="section-description">Number of searches per vehicle type</p>
        <div class="chart-container">
            @php
                $maxUsage = max($vehicleUsage);
            @endphp
            @foreach($vehicleUsage as $vehicle => $count)
            <div class="bar-row">
                <div class="bar-label">{{ $vehicle }}</div>
                <div class="bar-wrapper">
                    @php $percentage = ($count / max(1, $maxUsage)) * 100; @endphp
                    <div class="bar-fill" style="width: {{ $percentage }}%;">
                        @if($percentage > 15)
                            <span>{{ $count }}</span>
                        @endif
                    </div>
                </div>
                <div class="bar-value">{{ number_format($count) }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Popular Destinations -->
    <div class="section">
        <h2 class="section-title">Popular Destinations</h2>
        <p class="section-description">Most frequently bookmarked tourist spots in Bohol</p>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 60px;">Rank</th>
                    <th>Destination</th>
                    <th style="width: 100px; text-align: center;">Saves</th>
                </tr>
            </thead>
            <tbody>
                @foreach($popularDestinations as $index => $place)
                <tr>
                    <td>
                        <span class="rank-badge 
                            @if($index == 0) top-1
                            @elseif($index == 1) top-2
                            @elseif($index == 2) top-3
                            @endif">
                            {{ $index + 1 }}
                        </span>
                    </td>
                    <td>{{ $place->name }}</td>
                    <td style="text-align: center; font-weight: 600;">{{ number_format($place->saved_routes_count ?? 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Report Type Summary -->
    @if(isset($reportTypeSummary) && count($reportTypeSummary) > 0)
    <div class="section">
        <h2 class="section-title">Report Categories</h2>
        <p class="section-description">Breakdown of user reports by issue type</p>
        <div class="report-types">
            @foreach($reportTypeSummary as $report)
            @php
                $parts = explode(': ', $report);
                $name = $parts[0] ?? '';
                $count = $parts[1] ?? 0;
            @endphp
            <div class="report-type-item">
                <div class="report-type-name">{{ $name }}</div>
                <div class="report-type-count">{{ $count }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Footer -->
    <div class="report-footer">
        <div class="footer-left">
            Fareway Bohol — Public Transport Navigation System
        </div>
        <div class="footer-right">
            Confidential — For Internal Use Only
        </div>
    </div>
</body>
</html>