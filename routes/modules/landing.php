<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;

// Landing page
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/program', [LandingController::class, 'program'])->name('program');
Route::get('/aboutUs', [LandingController::class, 'aboutUs'])->name('aboutUs');
Route::get('/joinUs', [LandingController::class, 'joinUs'])->name('joinUs');
