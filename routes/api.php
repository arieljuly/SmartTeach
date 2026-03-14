<?php
use App\Http\Controllers\AIController;
use Illuminate\Support\Facades\Route;

Route::post('/ai/ask', [AIController::class, 'ask']);