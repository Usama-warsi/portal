<?php

namespace Modules\LMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'workspace_id',
    ];

    protected static function newFactory()
    {
        return \Modules\LMS\Database\factories\SubscriptionFactory::new();
    }
}
