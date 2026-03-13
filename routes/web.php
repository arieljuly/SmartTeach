<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;


require __DIR__ . '/modules/landing.php';
require __DIR__ . '/modules/auth.php';

Route::middleware(['auth',])->group(function () {
    require __DIR__ . '/modules/admin.php';
    require __DIR__ . '/modules/teacher.php';

    // for the default
    Route::get('/user/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');

});
