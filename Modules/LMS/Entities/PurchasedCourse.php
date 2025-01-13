<?php

namespace Modules\LMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchasedCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'order_id',
    ];

    protected static function newFactory()
    {
        return \Modules\LMS\Database\factories\PurchasedCourseFactory::new();
    }
}
