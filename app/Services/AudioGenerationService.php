<?php
// app/Services/AudioGenerationService.php

namespace App\Services;

use App\Models\LessonPlans;
use App\Models\AudioListeningJobs;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AudioGenerationService
{
    protected $openaiApiKey;
    protected $disk;

    public function __construct()
    {
        $this->openaiApiKey = env('OPENAI_API_KEY');
        $this->disk = env('FILESYSTEM_DISK', 'public');
    }

    /**
     * Generate audio for a specific lesson's listening activities
     */
    public function generateLessonAudio(LessonPlans $lesson, $userId)
    {
        $audios = [];
        
        // Extract the listening script from the PDF content
        $content = $this->extractListeningContent($lesson);
        
        // Generate audio for the teacher script (Weekend Plans)
        if (!empty($content['teacher_script'])) {
            $audios[] = $this->createAudioJob(
                $lesson->lesson_id,
                $userId,
                'script',
                $content['teacher_script'],
                ['section' => 'weekend_plans', 'speaker' => 'teacher'],
                0.9
            );
        }
        
        // Generate vocabulary words
        if (!empty($content['vocabulary'])) {
            $vocabText = $this->formatVocabularyForAudio($content['vocabulary']);
            $audios[] = $this->createAudioJob(
                $lesson->lesson_id,
                $userId,
                'vocabulary',
                $vocabText,
                ['section' => 'word_bank', 'words' => $content['vocabulary']],
                0.8
            );
        }
        
        // Generate cafe dialogue (role play)
        if (!empty($content['cafe_dialogue'])) {
            $audios[] = $this->createAudioJob(
                $lesson->lesson_id,
                $userId,
                'dialogue',
                $content['cafe_dialogue'],
                ['section' => 'cafe_role_play', 'speakers' => ['waiter', 'customer']],
                1.0
            );
        }
        
        // Generate cloze test passage
        if (!empty($content['cloze_text'])) {
            $audios[] = $this->createAudioJob(
                $lesson->lesson_id,
                $userId,
                'story',
                $content['cloze_text'],
                ['section' => 'busy_day_school'],
                0.95
            );
        }
        
        return $audios;
    }

    /**
     * Create an audio job and dispatch to queue
     */
    public function createAudioJob($lessonId, $userId, $type, $text, $metadata = [], $speed = 1.0)
    {
        $audioJob = AudioListeningJobs::create([
            'lesson_id' => $lessonId,
            'user_id' => $userId,
            'audio_type' => $type,
            'original_text' => $text,
            'voice_type' => $this->getVoiceForType($type),
            'speed' => $speed,
            'status' => 'pending',
            'metadata' => $metadata
        ]);
        
        // Dispatch job to generate audio
        \App\Jobs\GenerateAudio::dispatch($audioJob);
        
        return $audioJob;
    }

    /**
     * Generate audio using OpenAI TTS
     */
    public function generateAudio(AudioListeningJobs $audioJob)
    {
        try {
            $audioJob->update(['status' => 'processing']);
            
            $client = \OpenAI::client($this->openaiApiKey);
            
            $response = $client->audio()->speech([
                'model' => 'tts-1',
                'input' => $audioJob->original_text,
                'voice' => $audioJob->voice_type,
                'speed' => $audioJob->speed,
                'response_format' => 'mp3',
            ]);
            
            // Generate filename
            $filename = 'listening/' . date('Y/m/d/') . 
                        Str::slug($audioJob->audio_type . '-' . $audioJob->job_id) . '-' . 
                        time() . '.mp3';
            
            // Save to storage
            Storage::disk($this->disk)->put($filename, $response);
            
            // Get file size
            $fileSize = Storage::disk($this->disk)->size($filename);
            
            // Get duration (approximate)
            $duration = $this->estimateDuration($audioJob->original_text, $audioJob->speed);
            
            // Generate the URL based on disk type
            $audioUrl = $this->getAudioUrl($filename);
            
            $audioJob->markAsCompleted($audioUrl, $filename, $duration, $fileSize);
            
            return $audioJob;
            
        } catch (\Exception $e) {
            $audioJob->markAsFailed($e->getMessage());
            
            throw $e;
        }
    }

    /**
     * Get the audio URL based on disk type
     */
    protected function getAudioUrl($filename)
    {
        try {
            $disk = Storage::disk($this->disk);
            
            // Check if this is a public disk that supports URL generation
            if (method_exists($disk, 'url')) {
                return $disk->url($filename);
            }
            
            // For local public disk
            if ($this->disk === 'public') {
                return asset('storage/' . str_replace('public/', '', $filename));
            }
            
            // Fallback to path
            return $disk->path($filename);
            
        } catch (\Exception $e) {
            Log::error('Error generating audio URL', [
                'disk' => $this->disk,
                'filename' => $filename,
                'error' => $e->getMessage()
            ]);
            
            // Ultimate fallback
            return asset('storage/' . str_replace('public/', '', $filename));
        }
    }

    /**
     * Extract listening content from lesson
     */
    protected function extractListeningContent(LessonPlans $lesson)
    {
        // You'll need to implement actual PDF parsing here
        // For now, returning sample data based on your PDF structure
        
        return [
            'teacher_script' => "On the weekend, I wake up at 9:00 AM. I like to visit my grandmother on Saturday morning. In the afternoon, I go to the park with my friends. We usually play soccer for two hours. On Sunday, I stay home and watch movies with my family.",
            
            'vocabulary' => ['watch', 'play', 'visit', 'park', 'family', 'backpack', 'basketball', 'laptop', 'headphones', 'bicycle', 'breakfast'],
            
            'cafe_dialogue' => "Waiter: Hello! Can I help you?\nCustomer: I want a hamburger and fries, please.\nWaiter: Anything to drink?\nCustomer: Can I have orange juice?\nWaiter: Here you are.\nCustomer: Thank you!",
            
            'cloze_text' => "Every morning, I walk to school with my brother. My first class is math and it is very difficult. During lunch, I eat a sandwich and talk with my friends. After classes, I stay on the court to play basketball with the team. When I get home, I must finish my homework before I can play video games."
        ];
    }

    /**
     * Format vocabulary words for audio
     */
    protected function formatVocabularyForAudio($words)
    {
        $formatted = "Let's learn new words. Listen and repeat.\n\n";
        foreach ($words as $word) {
            $formatted .= $word . ". " . $this->getExampleSentence($word) . "\n\n";
        }
        return $formatted;
    }

    /**
     * Get example sentence for vocabulary word
     */
    protected function getExampleSentence($word)
    {
        $examples = [
            'watch' => 'I watch TV in the evening.',
            'play' => 'Children play in the park.',
            'visit' => 'We visit grandma on Sundays.',
            'family' => 'My family is very kind.',
            'backpack' => 'I carry my books in my backpack.',
            'basketball' => 'He plays basketball after school.',
            'laptop' => 'She uses a laptop for homework.',
            'headphones' => 'I listen to music with headphones.',
            'bicycle' => 'He rides his bicycle to the park.',
            'breakfast' => 'We eat breakfast every morning.'
        ];
        
        return $examples[$word] ?? "Let's practice saying {$word}.";
    }

    /**
     * Get voice based on audio type
     */
    protected function getVoiceForType($type)
    {
        $voices = [
            'script' => 'nova',
            'dialogue' => 'alloy',
            'vocabulary' => 'shimmer',
            'story' => 'fable'
        ];
        
        return $voices[$type] ?? 'nova';
    }

    /**
     * Estimate duration of audio in seconds
     */
    protected function estimateDuration($text, $speed)
    {
        // Average speaking rate: 150 words per minute at normal speed
        $wordCount = str_word_count($text);
        return round(($wordCount / 150) / $speed * 60);
    }
}