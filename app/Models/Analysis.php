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
        'analysis_result',
        'status'
    ];

    /**
     * Get the lesson plan for this analysis
     */
    public function lessonPlan()
    {
        return $this->belongsTo(LessonPlans::class, 'lesson_id', 'lesson_id');
    }

    /**
     * Get the questions for this analysis
     */
    public function questions()
    {
        return $this->hasMany(Questions::class, 'analysis_id', 'analysis_id');
    }

    /**
     * Get the activities for this analysis
     */
    public function activities()
    {
        return $this->hasMany(Activities::class, 'analysis_id', 'analysis_id');
    }

    /**
     * Get the performance tasks for this analysis
     */
    public function performanceTasks()
    {
        return $this->hasMany(PerformanceTasks::class, 'analysis_id', 'analysis_id');
    }

    /**
     * Get the rubrics for this analysis
     */
    public function rubrics()
    {
        return $this->hasMany(Rubrics::class, 'analysis_id', 'analysis_id');
    }
}