<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneratedDocuments extends Model
{
    protected $table = 'generated_documents';
    protected $primaryKey = 'document_id';
    protected $fillable = [
        'lesson_id',
        'document_name',
        'file_path',
    ];
}
