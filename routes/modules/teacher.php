<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController
};

Route::middleware(['role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'teacherDashboard'])->name('dashboard');
    
});