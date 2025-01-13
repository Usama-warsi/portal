<?php

namespace Modules\GoogleMeet\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Rawilk\Settings\Settings;
use Rawilk\Settings\Support\Context;
use Google\Client;
use Google\Service\Calendar;
use Carbon\Carbon;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;

class GoogleMeet extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'title','description', 'meeting_id', 'start_date', 'duration', 'start_url', 'join_url', 'status', 'created_by', 'workspace_id','member_ids'
    ];
    
    protected $table = 'google_meet';

    protected static function newFactory()
    {
        return \Modules\GoogleMeet\Database\factories\GoogleMeetFactory::new();
    }

    public function created_info()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }

    public function invitees()
    {
        return $this->belongsToMany(\App\Models\User::class, 'general_meeting', 'm_id', 'user_id');
    }

    public static function get_mettings()
    {

        $list = self::with('invitees')->where('workspace_id', getActiveWorkSpace())->get();
        return $list;
    }

    public function getMembers(){
        if(!empty($this->member_ids)){
            $members_ids = explode(',',$this->member_ids);
            $members = User::select('id','name','avatar')->whereIn('id',$members_ids)->get();
            return $members;
        }else{
            return [];
        }
    }

    public function invit()
    {
        return $this->belongsToMany(\App\Models\User::class, 'general_meeting', 'm_id', 'user_id');
    }


    
}
