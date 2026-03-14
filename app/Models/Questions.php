<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    protected $table = 'questions';
    protected $primaryKey = 'question_id';
    protected $fillable = [
        'question_type_id',
        'analysis_id',
        'question_text',
        'points',
        'status',
    ];
}
