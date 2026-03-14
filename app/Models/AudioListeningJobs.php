<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AudioListeningJobs extends Model
{
    protected $table = 'audio_listening_jobs';
    protected $primaryKey = 'job_id';

        protected $fillable = [
        'lesson_id',
        'user_id',
        'audio_type',
        'original_text',
        'audio_url',
        'audio_filename',
        'voice_type',
        'speed',
        'status',
        'duration_seconds',
        'file_size',
        'metadata'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'speed' => 'float',
        'duration_seconds' => 'integer',
        'file_size' => 'integer',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    /**
     * The default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'voice_type' => 'nova',
        'speed' => 1.0,
        'status' => 'pending'
    ];
    
    /**
     * Get the lesson that owns the audio job.
     */
    public function lesson()
    {
        return $this->belongsTo(LessonPlans::class, 'lesson_id', 'lesson_id');
    }
    
    /**
     * Get the user that owns the audio job.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
    
    /**
     * Scope a query to only include completed jobs.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
    
    /**
     * Scope a query to only include pending jobs.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    /**
     * Scope a query to only include processing jobs.
     */
    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }
    
    /**
     * Scope a query to only include failed jobs.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
    
    /**
     * Scope a query to filter by audio type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('audio_type', $type);
    }
    
    /**
     * Get the audio URL with proper CDN if configured.
     */
    public function getAudioUrlAttribute($value)
    {
        if (!$value) {
            return null;
        }
        
        // If it's already a full URL, return as is
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        
        // Otherwise, generate URL using storage
        return asset('storage/' . $value);
    }
    
    /**
     * Get formatted duration (MM:SS).
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration_seconds) {
            return '00:00';
        }
        
        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;
        
        return sprintf('%02d:%02d', $minutes, $seconds);
    }
    
    /**
     * Get file size in human readable format.
     */
    public function getFormattedFileSizeAttribute()
    {
        if (!$this->file_size) {
            return '0 B';
        }
        
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;
        
        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }
        
        return round($size, 2) . ' ' . $units[$unit];
    }
    
    /**
     * Get status badge class for UI.
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'completed' => 'bg-green-100 text-green-800',
            'processing' => 'bg-yellow-100 text-yellow-800',
            'pending' => 'bg-gray-100 text-gray-800',
            'failed' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
    
    /**
     * Get status icon for UI.
     */
    public function getStatusIconAttribute()
    {
        return match($this->status) {
            'completed' => '<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linecap="round" d="M5 13l4 4L19 7"></path></svg>',
            'processing' => '<svg class="w-5 h-5 text-yellow-500 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linecap="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>',
            'pending' => '<svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linecap="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
            'failed' => '<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linecap="round" d="M6 18L18 6M6 6l12 12"></path></svg>',
            default => '<svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linecap="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
        };
    }
    
    /**
     * Get voice display name.
     */
    public function getVoiceDisplayNameAttribute()
    {
        $voices = [
            'alloy' => 'Alloy (Versatile)',
            'echo' => 'Echo (Male, Deep)',
            'fable' => 'Fable (British, Expressive)',
            'onyx' => 'Onyx (Male, Deep)',
            'nova' => 'Nova (Female, Warm)',
            'shimmer' => 'Shimmer (Female, Clear)'
        ];
        
        return $voices[$this->voice_type] ?? ucfirst($this->voice_type);
    }
    
    /**
     * Get audio type display name.
     */
    public function getAudioTypeDisplayNameAttribute()
    {
        $types = [
            'script' => 'Teacher Script',
            'vocabulary' => 'Vocabulary Words',
            'dialogue' => 'Role Play Dialogue',
            'story' => 'Story Narration'
        ];
        
        return $types[$this->audio_type] ?? ucfirst(str_replace('_', ' ', $this->audio_type));
    }
    
    /**
     * Check if the audio is ready for playback.
     */
    public function isReady(): bool
    {
        return $this->status === 'completed' && !empty($this->audio_url);
    }
    
    /**
     * Mark the job as failed with error message.
     */
    public function markAsFailed(string $error): bool
    {
        $metadata = $this->metadata ?? [];
        $metadata['error'] = $error;
        $metadata['failed_at'] = now()->toDateTimeString();
        
        return $this->update([
            'status' => 'failed',
            'metadata' => $metadata
        ]);
    }
    
    /**
     * Mark the job as completed with file info.
     */
    public function markAsCompleted(string $audioUrl, string $filename, int $duration, int $fileSize): bool
    {
        return $this->update([
            'status' => 'completed',
            'audio_url' => $audioUrl,
            'audio_filename' => $filename,
            'duration_seconds' => $duration,
            'file_size' => $fileSize
        ]);
    }
    
    /**
     * Get the metadata value for a specific key.
     */
    public function getMetadata(string $key, $default = null)
    {
        return $this->metadata[$key] ?? $default;
    }
    
    /**
     * Set a metadata value.
     */
    public function setMetadata(string $key, $value): void
    {
        $metadata = $this->metadata ?? [];
        $metadata[$key] = $value;
        $this->metadata = $metadata;
    }
    
    /**
     * Get the job duration in minutes with seconds.
     */
    public function getDurationInMinutes(): string
    {
        if (!$this->duration_seconds) {
            return '0:00';
        }
        
        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;
        
        return $minutes . ':' . str_pad($seconds, 2, '0', STR_PAD_LEFT);
    }
    
    /**
     * Get the estimated cost of this audio job (approximate).
     * OpenAI TTS costs $0.015 per 1,000 characters
     */
    public function getEstimatedCostAttribute(): float
    {
        $charCount = strlen($this->original_text);
        // $0.015 per 1000 characters
        return round(($charCount / 1000) * 0.015, 4);
    }
    
    /**
     * Get the word count of the original text.
     */
    public function getWordCountAttribute(): int
    {
        return str_word_count($this->original_text);
    }
    
    /**
     * Regenerate this audio job.
     */
    public function regenerate(): bool
    {
        return $this->update([
            'status' => 'pending',
            'audio_url' => null,
            'audio_filename' => null,
            'duration_seconds' => null,
            'file_size' => null,
            'metadata' => array_merge($this->metadata ?? [], ['regenerated_at' => now()->toDateTimeString()])
        ]);
    }
}
