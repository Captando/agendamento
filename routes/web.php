<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Provider;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Client dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'role:client'])->name('dashboard');

// Provider routes
Route::middleware(['auth', 'verified', 'role:provider'])
    ->prefix('provider')
    ->name('provider.')
    ->group(function () {
        Route::get('/dashboard', [Provider\DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('services', Provider\ServiceController::class)
            ->except(['show']);

        Route::resource('business-hours', Provider\BusinessHourController::class)
            ->only(['index', 'store', 'update', 'destroy']);

        Route::resource('blocked-dates', Provider\BlockedDateController::class)
            ->only(['index', 'store', 'destroy']);

        Route::get('/appointments', [Provider\AppointmentController::class, 'index'])
            ->name('appointments.index');

        Route::patch('/appointments/{appointment}/status', [Provider\AppointmentController::class, 'updateStatus'])
            ->name('appointments.updateStatus');
    });

// Client routes
Route::middleware(['auth', 'verified', 'role:client'])
    ->prefix('client')
    ->name('client.')
    ->group(function () {
        Route::get('/appointments', [\App\Http\Controllers\Client\AppointmentController::class, 'index'])
            ->name('appointments.index');

        Route::post('/book/{user:slug}/{service}', [\App\Http\Controllers\Client\BookingController::class, 'store'])
            ->name('booking.store');

        Route::patch('/appointments/{appointment}/cancel', [\App\Http\Controllers\Client\AppointmentController::class, 'cancel'])
            ->name('appointments.cancel');
    });

// Public provider page
Route::get('/p/{user:slug}', [\App\Http\Controllers\PublicPage\ProviderPageController::class, 'show'])
    ->name('provider.public');

// Profile routes (both roles)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
