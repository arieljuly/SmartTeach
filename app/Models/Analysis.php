<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Analysis extends Model
{
    protected $table = 'analysis';
    protected $primaryKey = 'analysis_id';
    protected $fillable = [
        'lesson_id',
        'topic_summary',
        'key_concepts',
    ];
}
