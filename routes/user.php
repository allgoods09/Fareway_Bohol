<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\SavedRouteController;
use App\Http\Controllers\User\FindRouteController;
use App\Http\Controllers\User\RecommendedPlacesController;
use App\Http\Controllers\User\ReportController;

// Public routes (no auth required for browsing)
Route::get('/find-route', [FindRouteController::class, 'index'])->name('find-route');
Route::post('/calculate-fares', [FindRouteController::class, 'calculateFares'])
    ->middleware('throttle:30,1')  // 30 requests per minute
    ->name('calculate-fares');
Route::post('/log-search', [FindRouteController::class, 'logSearch'])
    ->middleware('throttle:60,1')  // 60 requests per minute
    ->name('log-search');
Route::get('/recommended-places', [RecommendedPlacesController::class, 'index'])->name('user.recommended-places');

// Authenticated user routes
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    // Saved Routes
    Route::get('/saved-routes', [SavedRouteController::class, 'savedRoutes'])->name('saved-routes');
    Route::delete('/saved-routes/{id}', [SavedRouteController::class, 'deleteSavedRoute'])->name('delete-saved-route');
    
    // Save route
    Route::post('/save-route', [FindRouteController::class, 'saveRoute'])->name('save-route');
    
    // Bookmark place
    Route::post('/bookmark-place', [RecommendedPlacesController::class, 'bookmarkPlace'])->name('bookmark-place');
    

    Route::get('/my-reports', [ReportController::class, 'myReports'])->name('my-reports');
    Route::post('/reports/{id}/resolve', [ReportController::class, 'markAsResolved'])->name('resolve-report');

    // Report issue (commented out - using the web.php route instead)
    // Route::get('/report-issue', [ReportController::class, 'index'])->name('report-issue');
    // Route::post('/submit-report', [ReportController::class, 'submit'])->name('submit-report');
});