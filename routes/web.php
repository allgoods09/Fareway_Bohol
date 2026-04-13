<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\User\ReportController;
use App\Http\Controllers\Api\PlaceController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\AboutController;

// Public routes
Route::get('/', [WelcomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::get('/news', [NewsController::class, 'index'])->name('user.news');
Route::get('/news/fetch', [NewsController::class, 'fetchNews'])->name('user.news.fetch');
Route::get('/news/refresh', [NewsController::class, 'refresh'])->name('user.news.refresh');

Route::get('/api/place/{id}', [PlaceController::class, 'show']);

// Authenticated user routes
Route::middleware(['auth'])->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Report
    Route::get('/report', [ReportController::class, 'create'])->name('user.report.create');
    Route::post('/report', [ReportController::class, 'store'])->name('user.report.store');
    
    // Save location
    Route::post('/user/save-location', [App\Http\Controllers\User\SavedLocationController::class, 'save'])->name('user.save-location');
});

Route::get('/api/user/report/{id}', [ReportController::class, 'getReportApi'])->middleware('auth');

// Role-based routes (admin, moderator, user)
require __DIR__.'/admin.php';
require __DIR__.'/moderator.php';
require __DIR__.'/user.php';
require __DIR__.'/auth.php';