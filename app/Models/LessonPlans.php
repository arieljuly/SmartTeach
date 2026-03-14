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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function extractContent()
    {
        return $this->hasOne(ExtractContents::class, 'lesson_id', 'lesson_id');
    }

    public function analysis()
    {
        return $this->hasOne(Analysis::class, 'lesson_id', 'lesson_id');
    }

    public function documents()
    {
        return $this->hasMany(GeneratedDocuments::class, 'lesson_id', 'lesson_id');
    }
}
