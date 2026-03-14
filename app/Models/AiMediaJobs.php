<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiMediaJobs extends Model
{
    protected $table = 'ai_media_jobs';
    protected $primaryKey = 'media_id';
    protected $fillable = [
        'lesson_id',
        'prompt_id',
        'user_id',
        'media_type',
        'media_url',
        'media_name',
        'status',
        'file_size',
    ];
}
