<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceTasks extends Model
{
    protected $table = 'performance_tasks';
    protected $primaryKey = 'task_id';
    protected $fillable = [
        'analysis_id',
        'task_name',
        'task_description',
        'status',
    ];
}
