<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionTypes extends Model
{
    protected $table = 'question_types';
    protected $primaryKey = 'question_type_id';
    protected $fillable = [
        'type_name',
    ];
}
