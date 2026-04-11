<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Moderator\DashboardController;
use App\Http\Controllers\Moderator\FareRateController;
use App\Http\Controllers\Moderator\RecommendedPlaceController;
use App\Http\Controllers\Moderator\ReportController;

Route::middleware(['auth', 'role:moderator,admin'])->prefix('moderator')->name('moderator.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Fare Rates (Edit only)
    Route::get('/fare-rates', [FareRateController::class, 'index'])->name('fare-rates.index');
    Route::get('/fare-rates/{fareRate}/edit', [FareRateController::class, 'edit'])->name('fare-rates.edit');
    Route::put('/fare-rates/{fareRate}', [FareRateController::class, 'update'])->name('fare-rates.update');
    
    // Recommended Places (Full CRUD)
    Route::resource('recommended-places', RecommendedPlaceController::class);
    
    // Reports (View and Update only)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
    Route::post('/reports/{report}/update-status', [ReportController::class, 'updateStatus'])->name('reports.update-status');
});