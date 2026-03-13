<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    UserController,
    ExtractionController,
    AIModuleController,
    LessonPlanController
};

Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
    
    // this is for the users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'userAdministration'])->name('administration');
        Route::get('/audit-logs', [UserController::class, 'auditLogs'])->name('audit');
    });

    // this is for the ai processing
    Route::prefix('AI')->name('ai.')->group(function () {
        Route::get('/', [AIModuleController::class, 'index'])->name('aiProcess');
    });
    // this is for the extraction
    Route::prefix('extraction')->name('extraction.')->group(function () {
        Route::get('/', [ExtractionController::class, 'index'])->name('pdf');
        Route::get('/pdfToAudio', [ExtractionController::class, 'pdfToAudio'])->name('pdfToAudio');
        Route::get('/pdfToVideo', [ExtractionController::class, 'pdfToVideo'])->name('pdfToVideo');
        Route::get('/output', [ExtractionController::class, 'output'])->name('output');
    });

    // this is for the lesson plan
    Route::prefix('lessonPlans')->name('lesson-plans.')->group(function () {
        Route::get('/', [LessonPlanController::class, 'index'])->name('index');
        
    });
});