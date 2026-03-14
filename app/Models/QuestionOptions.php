<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionOptions extends Model
{
    protected $table = 'questions_options'; // Note: this matches your database table
    protected $primaryKey = 'option_id';
    
    protected $fillable = [
        'question_id',
        'option_text',
        'is_correct',
    ];

    /**
     * Get the question that owns this option
     */
    public function question()
    {
        return $this->belongsTo(Questions::class, 'question_id', 'question_id');
    }
}