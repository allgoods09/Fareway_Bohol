<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\User\ReportController;
use App\Http\Controllers\Api\PlaceController;

Route::get('/', [WelcomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/report', [ReportController::class, 'create'])->name('user.report.create');
    Route::post('/report', [ReportController::class, 'store'])->name('user.report.store');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/user/save-location', [App\Http\Controllers\User\SavedLocationController::class, 'save'])->name('user.save-location');
});

Route::get('/api/place/{id}', [PlaceController::class, 'show']);

Route::get('/about', function () {
    return view('about');
})->name('about');

// Include admin routes
require __DIR__.'/admin.php';
require __DIR__.'/moderator.php';
require __DIR__.'/user.php';
// Include moderator routes (to be created)
// require __DIR__.'/moderator.php';
// Include user routes (to be created)
// require __DIR__.'/user.php';

require __DIR__.'/auth.php';