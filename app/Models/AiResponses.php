<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiResponses extends Model
{
    protected $table = 'ai_responses';
    protected $primaryKey = 'response_id';
    protected $fillable = [
        'prompt_id',
        'lesson_id',
        'raw_response_json',
        'parsed_success',
        'output_token_count',
        'processing_time_ms',
    ];
}
