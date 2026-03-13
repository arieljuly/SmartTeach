<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('show.login');
    Route::post('/login', [AuthController::class, 'login'])->name('store.login');
    
    // Google OAuth routes
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');
    
    // Registration routes (optional)
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('show.register');
    Route::post('/register', [AuthController::class, 'register'])->name('store.register');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
});

// Home route
Route::get('/', function () {
    return view('landing.welcome');
});