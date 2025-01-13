<?php

namespace Modules\ZoomMeeting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;


class ZoomMeeting extends Model
{
    use HasFactory;

    protected $fillable = [

        'id',
        'title',
        'meeting_id',
        'start_date',
        'duration',
        'start_url',
        'join_url',
        'password',
        'status',
        'created_by',
        'workspace_id'
    ];
    protected $table = 'zoom_meeting';

    protected static function newFactory()
    {
        return \Modules\ZoomMeeting\Database\factories\ZoomMeetingFactory::new();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'general_meeting', 'm_id', 'user_id');
    }

}
