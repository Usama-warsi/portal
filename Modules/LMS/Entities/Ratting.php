<?php

namespace Modules\LMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ratting extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'product_id',
        'title',
        'ratting',
        'description',
    ];

    protected static function newFactory()
    {
        return \Modules\LMS\Database\factories\RattingFactory::new();
    }
}
