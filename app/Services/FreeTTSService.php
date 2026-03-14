<?php
// app/Services/FreeTTSService.php

namespace App\Services;

use App\Models\AudioListeningJobs;
use Illuminate\Support\Facades\Log;

class FreeTTSService
{
    /**
     * Create a browser-based TTS job
     */
    public function createBrowserAudioJob($lessonId, $userId, $type, $text, $metadata = [])
    {
        try {
            // Extract vocabulary words for metadata if it's vocabulary type
            if ($type === 'vocabulary') {
                $words = $this->extractWordsFromVocabulary($text);
                $metadata['words'] = $words;
            }
            
            // For dialogue, parse and structure the conversation
            if ($type === 'dialogue') {
                $metadata['dialogue_lines'] = $this->parseDialogue($text);
                $metadata['has_dialogue'] = true;
            }
            
            // Create a job record
            $audioJob = AudioListeningJobs::create([
                'lesson_id' => $lessonId,
                'user_id' => $userId,
                'audio_type' => $type,
                'original_text' => $text,
                'status' => 'completed',
                'metadata' => array_merge($metadata, [
                    'use_browser_tts' => true,
                    'created_at' => now()->toDateTimeString(),
                    'word_count' => str_word_count($text)
                ])
            ]);
            
            Log::info('Browser TTS job created', [
                'job_id' => $audioJob->job_id,
                'lesson_id' => $lessonId,
                'type' => $type
            ]);
            
            return $audioJob;
            
        } catch (\Exception $e) {
            Log::error('Failed to create browser TTS job', [
                'lesson_id' => $lessonId,
                'type' => $type,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }
    
    /**
     * Parse dialogue into structured lines with speakers
     */
    protected function parseDialogue($text)
    {
        $lines = [];
        $speakers = ['Waiter', 'Customer', 'Person A', 'Person B'];
        
        // Try to detect speaker patterns
        foreach (explode("\n", $text) as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            // Check for common speaker patterns
            $matched = false;
            foreach ($speakers as $speaker) {
                if (strpos($line, $speaker . ':') === 0) {
                    $lines[] = [
                        'speaker' => $speaker,
                        'text' => trim(substr($line, strlen($speaker) + 1))
                    ];
                    $matched = true;
                    break;
                }
            }
            
            // If no speaker detected, try to infer from common patterns
            if (!$matched) {
                if (strpos($line, '?') !== false) {
                    $lines[] = ['speaker' => 'Customer', 'text' => $line];
                } elseif (preg_match('/^[A-Z][a-z]+:/', $line)) {
                    $parts = explode(':', $line, 2);
                    $lines[] = ['speaker' => trim($parts[0]), 'text' => trim($parts[1])];
                } else {
                    // Alternate between speakers for undetected lines
                    $speaker = count($lines) % 2 == 0 ? 'Waiter' : 'Customer';
                    $lines[] = ['speaker' => $speaker, 'text' => $line];
                }
            }
        }
        
        return $lines;
    }
    
    /**
     * Extract individual words from vocabulary text
     */
    protected function extractWordsFromVocabulary($text)
    {
        $words = [];
        
        // Split by common delimiters
        $lines = explode("\n", $text);
        
        foreach ($lines as $line) {
            // Look for patterns like "word." or "word -"
            if (preg_match('/([a-zA-Z]+)[\.\-\s]/', $line, $matches)) {
                $words[] = ucfirst($matches[1]);
            }
        }
        
        // If no words found, use default list
        if (empty($words)) {
            $words = ['Backpack', 'Basketball', 'Laptop', 'Headphones', 'Bicycle', 'Breakfast'];
        }
        
        return array_slice(array_unique($words), 0, 12);
    }
}