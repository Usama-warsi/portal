<?php

namespace Modules\LMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseFaq extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'question',
        'answer',
    ];

    protected static function newFactory()
    {
        return \Modules\LMS\Database\factories\CourseFaqFactory::new();
    }
}
