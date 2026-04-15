<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FareRateController;
use App\Http\Controllers\Admin\RecommendedPlaceController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\ActivityLogController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Fare Rates (Vehicle Types)
    Route::resource('fare-rates', FareRateController::class);
    Route::post('/fare-rates/{fareRate}/toggle-status', [FareRateController::class, 'toggleStatus'])->name('fare-rates.toggle-status');
    
    // Recommended Places
    Route::resource('recommended-places', RecommendedPlaceController::class);
    
    // Users
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
    Route::post('/reports/{report}/update-status', [ReportController::class, 'updateStatus'])->name('reports.update-status');
    Route::delete('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');
    Route::post('/reports/bulk-update', [ReportController::class, 'bulkUpdate'])->name('reports.bulk-update');
    
    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/export-pdf', [AnalyticsController::class, 'exportPdf'])->name('analytics.export-pdf');
    Route::get('/analytics/export-csv', [AnalyticsController::class, 'exportCsv'])->name('analytics.export-csv'); // Move this inside the group

    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/export-csv', [ActivityLogController::class, 'exportCsv'])->name('activity-logs.export-csv');
    Route::get('/activity-logs/export-pdf', [ActivityLogController::class, 'exportPdf'])->name('activity-logs.export-pdf');
    });