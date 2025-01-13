<?php

namespace Modules\ZoomMeeting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GeneralMetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'm_id','user_id'
    ];

    protected $table = 'general_meeting';

    protected static function newFactory()
    {
        return \Modules\ZoomMeeting\Database\factories\GeneralMettingFactory::new();
    }
}
