<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;


require __DIR__ . '/modules/landing.php';
require __DIR__ . '/modules/auth.php';
Route::get('/csrf-token', function() {
    return response()->json(['token' => csrf_token()]);
});
Route::middleware(['auth',])->group(function () {
    require __DIR__ . '/modules/admin.php';
    require __DIR__ . '/modules/teacher.php';

    // for the default
    Route::get('/user/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');

});


// test end points for ai 
// Route::get('/test-deepseek', function() {
//     try {
//         $deepseek = new App\Services\AI\DeepSeekService();
        
//         if (!$deepseek->isAvailable()) {
//             return response()->json([
//                 'error' => 'DeepSeek API is not available',
//                 'solution' => 'Check your API key in .env file'
//             ], 500);
//         }
        
//         $response = $deepseek->generate(
//             'Generate a JSON response with a message saying "Hello, DeepSeek is working!"',
//             0.7,
//             100
//         );
        
//         return response()->json([
//             'success' => true,
//             'raw_response' => $response,
//             'parsed' => json_decode($response, true)
//         ]);
        
//     } catch (\Exception $e) {
//         return response()->json([
//             'error' => $e->getMessage()
//         ], 500);
//     }
// });

// Add to routes/web.php (outside middleware)
// Route::get('/test-groq', function() {
//     try {
//         $groq = new App\Services\AI\GroqService();
        
//         if (!$groq->isAvailable()) {
//             return response()->json([
//                 'error' => 'Groq API is not available',
//                 'solution' => 'Check your GROQ_API_KEY in .env file'
//             ], 500);
//         }
        
//         // Test simple generation
//         $response = $groq->generate(
//             'Generate a JSON response with a message saying "Hello, Groq is working!"',
//             0.7,
//             100
//         );
        
//         return response()->json([
//             'success' => true,
//             'raw_response' => $response,
//             'parsed' => json_decode($response, true),
//             'models' => $groq->getAvailableModels()
//         ]);
        
//     } catch (\Exception $e) {
//         return response()->json([
//             'error' => $e->getMessage(),
//             'trace' => $e->getTraceAsString()
//         ], 500);
//     }
// });

