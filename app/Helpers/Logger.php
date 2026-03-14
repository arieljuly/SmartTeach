<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Auth;

class Logger
{
    public static function log($message, $data = [])
    {
        $logFile = storage_path('logs/pdf-upload-debug.log');
        $timestamp = date('Y-m-d H:i:s');
        $logData = [
            'message' => $message,
            'data' => $data,
            'session_id' => session()->getId(),
            'user_id' => Auth::id(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
        ];
        
        file_put_contents(
            $logFile, 
            "[$timestamp] " . json_encode($logData, JSON_PRETTY_PRINT) . "\n\n", 
            FILE_APPEND
        );
    }
}