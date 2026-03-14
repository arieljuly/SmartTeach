<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonPlans extends Model
{
    protected $table = 'lesson_plans';
    protected $primaryKey = 'lesson_id';
    protected $fillable = [
        'user_id',
        'file_name',
        'file_path',
        'status',
    ];
}
