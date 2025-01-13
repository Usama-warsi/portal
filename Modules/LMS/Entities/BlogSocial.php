<?php

namespace Modules\LMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogSocial extends Model
{
    use HasFactory;

    protected $fillable = [
        'enable_social_button',
        'enable_email',
        'enable_twitter',
        'enable_facebook',
        'enable_googleplus',
        'enable_linkedIn',
        'enable_pinterest',
        'enable_stumbleupon',
        'enable_whatsapp',
        'workspace_id',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Modules\LMS\Database\factories\BlogSocialFactory::new();
    }
}
