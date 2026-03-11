<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Teacher\{
    DashboardController
};

Route::middleware(['role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
});