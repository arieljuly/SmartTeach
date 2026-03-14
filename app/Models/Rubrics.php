<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rubrics extends Model
{
    protected $table = 'rubrics';
    protected $primaryKey = 'rubric_id';
    protected $fillable = [
        'task_id',
        'rubric_title',
        'total_score',
        'status',
    ];
}
