<?php

use App\Http\Controllers\Admin\BookingAdminController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PassengerController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RouteController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('routes', RouteController::class)->except('show');

    Route::get('cities', [CityController::class, 'index'])->name('cities.index');
    Route::post('cities', [CityController::class, 'store'])->name('cities.store');
    Route::patch('cities/{city}', [CityController::class, 'update'])->name('cities.update');
    Route::delete('cities/{city}', [CityController::class, 'destroy'])->name('cities.destroy');

    Route::get('passengers', [PassengerController::class, 'index'])->name('passengers.index');
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

    Route::get('bookings', [BookingAdminController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{booking}', [BookingAdminController::class, 'show'])->name('bookings.show');
    Route::get('bookings/{booking}/edit', [BookingAdminController::class, 'edit'])->name('bookings.edit');
    Route::patch('bookings/{booking}', [BookingAdminController::class, 'update'])->name('bookings.update');
    Route::patch('bookings/{booking}/selesai', [BookingAdminController::class, 'toggleSelesai'])->name('bookings.selesai');
    Route::delete('bookings/{booking}', [BookingAdminController::class, 'destroy'])->name('bookings.destroy');
});
