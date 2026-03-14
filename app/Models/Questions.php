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

    /**
     * Get the question type for this question
     */
    public function questionType()
    {
        return $this->belongsTo(QuestionTypes::class, 'question_type_id', 'question_type_id');
    }

    /**
     * Get the options for this question (for multiple choice)
     */
    public function options()
    {
        return $this->hasMany(QuestionOptions::class, 'question_id', 'question_id');
    }

    /**
     * Get the analysis that owns this question
     */
    public function analysis()
    {
        return $this->belongsTo(Analysis::class, 'analysis_id', 'analysis_id');
    }
}