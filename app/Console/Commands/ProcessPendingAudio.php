<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AudioListeningJobs;
use App\Services\AudioGenerationService;

class ProcessPendingAudio extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audio:process-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process pending audio jobs';

    /**
     * Execute the console command.
     */
    public function handle(AudioGenerationService $audioService)
    {
        $pendingJobs = AudioListeningJobs::where('status', 'pending')->get();
        
        $this->info("Found {$pendingJobs->count()} pending jobs");
        
        foreach ($pendingJobs as $job) {
            $this->info("Processing job ID: {$job->job_id} - Type: {$job->audio_type}");
            
            try {
                $audioService->generateAudio($job);
                $this->info("✓ Job {$job->job_id} completed");
            } catch (\Exception $e) {
                $this->error("✗ Job {$job->job_id} failed: " . $e->getMessage());
            }
        }
        
        return Command::SUCCESS;
    }
}
