<?php
// app/Jobs/GenerateAudio.php

namespace App\Jobs;

use App\Models\AudioListeningJobs;
use App\Services\AudioGenerationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateAudio implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $audioJob;

    public $tries = 3;
    public $backoff = 5;

    public function __construct(AudioListeningJobs $audioJob)
    {
        $this->audioJob = $audioJob;
    }

    public function handle(AudioGenerationService $audioService)
    {
        try {
            Log::info('Starting audio generation', [
                'job_id' => $this->audioJob->job_id,
                'type' => $this->audioJob->audio_type
            ]);
            
            $audioService->generateAudio($this->audioJob);
            
            Log::info('Audio generation completed', [
                'job_id' => $this->audioJob->job_id,
                'audio_url' => $this->audioJob->fresh()->audio_url
            ]);
            
        } catch (\Exception $e) {
            Log::error('Audio generation failed', [
                'job_id' => $this->audioJob->job_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error('GenerateAudio job failed permanently', [
            'job_id' => $this->audioJob->job_id,
            'error' => $exception->getMessage()
        ]);
        
        $this->audioJob->update([
            'status' => 'failed',
            'metadata' => array_merge($this->audioJob->metadata ?? [], [
                'job_failed' => true,
                'job_error' => $exception->getMessage(),
                'failed_at' => now()->toDateTimeString()
            ])
        ]);
    }
}