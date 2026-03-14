<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionOptions extends Model
{
    protected $table = 'questions_options';
    protected $primaryKey = 'option_id';
    protected $fillable = [
        'question_id',
        'option_text',
        'is_correct',
    ];
}
