<?php

namespace Modules\LMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseOrderSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'student_id',
        'issue_date',
        'course_number',
        'status',
        'price',
        'course',
        'workspace',
        'created_by'
    ];

    protected static function newFactory()
    {
        return \Modules\LMS\Database\factories\CourseOrderSummaryFactory::new();
    }
}
