<?php

namespace Modules\LMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PracticesFiles extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'file_name',
        'files',
    ];

    protected static function newFactory()
    {
        return \Modules\LMS\Database\factories\PracticesFilesFactory::new();
    }
}
