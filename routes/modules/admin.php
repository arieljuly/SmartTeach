<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    UserController,
    ExtractionController,
    AIModuleController,
    PdfExtractionController,
    LessonPlanController
};

Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
    
    // User Management Routes
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'userAdministration'])->name('administration');
        Route::post('/', [UserController::class, 'storeUser'])->name('store');
        Route::get('/{user}', [UserController::class, 'showUser'])->name('show'); 
        Route::put('/{user}', [UserController::class, 'updateUser'])->name('update');
        Route::patch('/{user}/archive', [UserController::class, 'archiveUser'])->name('archive');
        Route::patch('/{user}/restore', [UserController::class, 'restoreUser'])->name('restore');

        // Audit Logs
        Route::get('/audit-logs', [UserController::class, 'auditLogs'])->name('audit');
        Route::get('/audit-logs/{user}', [UserController::class, 'showAuditLog'])->name('audit.show');
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

    Route::get('/pdf-extraction', [PdfExtractionController::class, 'index'])->name('pdf.extraction');
    Route::post('/pdf/upload', [PdfExtractionController::class, 'upload'])->name('pdf.upload');
    Route::post('/pdf/analyze', [PdfExtractionController::class, 'analyze'])->name('pdf.analyze');
    Route::post('/pdf/generate', [PdfExtractionController::class, 'generateDocument'])->name('pdf.generate');
    Route::get('/analysis-progress/{lessonId}', [PdfExtractionController::class, 'checkProgress'])->name('analysis.progress');
    
    
    // this is for the lesson plan
    Route::prefix('lessonPlans')->name('lesson-plans.')->group(function () {
        Route::get('/', [LessonPlanController::class, 'index'])->name('index');
        
    });
    Route::get('/get-audio-text/{jobId}', [ExtractionController::class, 'getAudioText'])->name('get-audio-text');
    Route::get('/pdf-to-audio/{lessonId?}', [ExtractionController::class, 'pdfToAudio'])->name('pdf-to-audio');
    Route::get('/listening-session/{lessonId}', [ExtractionController::class, 'getListeningSession'])->name('listening-session');
    Route::post('/generate-audio/{lessonId}/{type}', [ExtractionController::class, 'generateAudioType'])->name('generate-audio');
    Route::post('/generate-all-audio/{lessonId}', [ExtractionController::class, 'generateAllAudio'])->name('generate-all-audio');
    Route::get('/audio-status/{audioId}', [ExtractionController::class, 'getAudioStatus'])->name('audio-status');
    Route::get('/pdf/test-tokens/{lessonId}', [PdfExtractionController::class, 'testPdfTokens'])->name('pdf.test-tokens');
});
