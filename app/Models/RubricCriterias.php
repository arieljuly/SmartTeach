<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RubricCriterias extends Model
{
    protected $table = 'rubrics_criterias';
    protected $primaryKey = 'criteria_id';
    
    protected $fillable = [
        'rubric_id',
        'criteria_name',
        'criteria_description',
        'score'
    ];

    /**
     * Get the rubric that owns this criteria
     */
    public function rubric()
    {
        return $this->belongsTo(Rubrics::class, 'rubric_id', 'rubric_id');
    }
}