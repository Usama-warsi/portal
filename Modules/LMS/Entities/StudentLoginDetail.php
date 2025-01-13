<?php

namespace Modules\LMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentLoginDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'ip',
        'date',
        'details',
        'workspace_id',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Modules\LMS\Database\factories\StudentLoginDetailFactory::new();
    }
}
