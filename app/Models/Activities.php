<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activities extends Model
{
    protected $table = 'activities';
    protected $primaryKey = 'activity_id';
    protected $fillable = [
        'analysis_id',
        'activity_name',
        'activity_description',
        'estimated_duration',
        'status',
    ];
}
