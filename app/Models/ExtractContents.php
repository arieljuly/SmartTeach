<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtractContents extends Model
{
    protected $table = 'extract_contents';
    protected $primaryKey = 'content_id';
    protected $fillable = [
        'lesson_id',
        'extracted_text',
    ];
}
