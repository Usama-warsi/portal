<?php

namespace Modules\LMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseVisitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'method',
        'referer',
        'languages',
        'useragent',
        'device',
        'platform',
        'browser',
        'ip',
        'slug',
        'pageview',
    ];

    protected static function newFactory()
    {
        return \Modules\LMS\Database\factories\CourseVisitorFactory::new();
    }
}
