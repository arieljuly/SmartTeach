<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rubrics extends Model
{
    protected $table = 'rubrics';
    protected $primaryKey = 'rubric_id';
    
    protected $fillable = [
        'task_id',
        'analysis_id',
        'rubric_title',
        'total_score',
        'status'
    ];

    /**
     * Get the criteria for this rubric
     */
    public function criterias()
    {
        return $this->hasMany(RubricCriterias::class, 'rubric_id', 'rubric_id');
    }

    /**
     * Get the performance task associated with this rubric
     */
    public function performanceTask()
    {
        return $this->belongsTo(PerformanceTasks::class, 'task_id', 'task_id');
    }

    /**
     * Get the analysis associated with this rubric
     */
    public function analysis()
    {
        return $this->belongsTo(Analysis::class, 'analysis_id', 'analysis_id');
    }
}