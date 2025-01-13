<?php

namespace Modules\LMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LmsQr extends Model
{
    use HasFactory;

    protected $fillable = [
        'foreground_color',
        'background_color',
        'radius',
        'qr_type',
        'qr_text',
        'qr_text_color',
        'image',
        'size',
        'store_id',
        'created_by'
    ];

    protected static function newFactory()
    {
        return \Modules\LMS\Database\factories\LmsQrFactory::new();
    }
}
