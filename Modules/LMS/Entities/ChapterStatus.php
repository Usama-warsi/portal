<?php

namespace Modules\LMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChapterStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'chapter_id',
        'student_id',
        'course_id',
        'status',
    ];

    protected static function newFactory()
    {
        return \Modules\LMS\Database\factories\ChapterStatusFactory::new();
    }
}
