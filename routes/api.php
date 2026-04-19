<?php

use App\Http\Controllers\HostairTestController;
use App\Http\Controllers\GuestController;
use Illuminate\Support\Facades\Route;

// 1. Specific route for the bookings dashboard
Route::get('/bookings', [HostairTestController::class, 'getBookings']);

// 2. Resource route for all Guest CRUD operations
Route::apiResource('guests', GuestController::class);

// 3. Attach/detach guests from bookings
Route::post('/guests/{guest}/bookings/{booking}', [GuestController::class, 'attachToBooking']);
Route::delete('/guests/{guest}/bookings/{booking}', [GuestController::class, 'detachFromBooking']);