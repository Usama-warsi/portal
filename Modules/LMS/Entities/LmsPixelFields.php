<?php

namespace Modules\LMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LmsPixelFields extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform',
        'pixel_id',
        'store_id',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Modules\LMS\Database\factories\LmsPixelFieldsFactory::new();
    }
}
