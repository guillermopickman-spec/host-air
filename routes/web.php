<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/guests', function () {
    return Inertia::render('GuestLibrary');
})->name('guests.library');
