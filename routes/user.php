<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\FindRouteController;
use App\Http\Controllers\User\RecommendedPlacesController;
use App\Http\Controllers\User\ReportController;

// Public routes (no auth required for browsing)
Route::get('/find-route', [FindRouteController::class, 'index'])->name('find-route');
Route::post('/calculate-fares', [FindRouteController::class, 'calculateFares'])->name('calculate-fares');
Route::post('/log-search', [FindRouteController::class, 'logSearch'])->name('log-search');
Route::get('/recommended-places', [RecommendedPlacesController::class, 'index'])->name('user.recommended-places');

// Authenticated user routes
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/saved-routes', [DashboardController::class, 'savedRoutes'])->name('saved-routes');
    Route::delete('/saved-routes/{id}', [DashboardController::class, 'deleteSavedRoute'])->name('delete-saved-route');
    
    // Save route
    Route::post('/save-route', [FindRouteController::class, 'saveRoute'])->name('save-route');
    
    // Bookmark place
    Route::post('/bookmark-place', [RecommendedPlacesController::class, 'bookmarkPlace'])->name('bookmark-place');
    
    // Report issue
    Route::get('/report-issue', [ReportController::class, 'index'])->name('report-issue');
    Route::post('/submit-report', [ReportController::class, 'submit'])->name('submit-report');
});