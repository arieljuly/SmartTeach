<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiPrompts extends Model
{
    protected $table = 'ai_prompts';
    protected $primaryKey = 'prompt_id';
    protected $fillable = [
        'lesson_id',
        'analysis_id',
        'user_id',
        'prompt_type',
        'prompt_version',
        'prompt_content',
        'token_estimate',
    ];
}
