<?php

namespace Modules\LMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseSubcategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'workspace_id',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Modules\LMS\Database\factories\CourseSubcategoryFactory::new();
    }
}
