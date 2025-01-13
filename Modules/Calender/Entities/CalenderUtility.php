<?php

namespace Modules\Calender\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Role;
use App\Models\Permission;
use Spatie\GoogleCalendar\Event as GoogleEvent;
use Illuminate\Support\Carbon;
use PhpParser\Node\Stmt\TryCatch;

class CalenderUtility extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Calender\Database\factories\CalenderUtilityFactory::new();
    }

    public static function GivePermissionToRoles($role_id = null, $rolename = null)
    {
        $client_permissions = [

            'calander manage',
            'calander show'
        ];

        $staff_permissions = [

            'calander manage',
        ];

        if ($role_id == Null) {
            // client
            $roles_c = Role::where('name', 'client')->get();
            foreach ($roles_c as $role) {
                foreach ($client_permissions as $permission_c) {
                    $permission = Permission::where('name', $permission_c)->first();
                    if(!$role->hasPermission($permission_c))
                    {
                        $role->givePermission($permission);
                    }
                }
            }


            // staff
            $roles_v = Role::where('name', 'staff')->get();

            foreach ($roles_v as $role) {
                foreach ($staff_permissions as $permission_v) {
                    $permission = Permission::where('name', $permission_v)->first();
                    if(!$role->hasPermission($permission_v))
                    {
                        $role->givePermission($permission);
                    }
                }
            }
        } else {
            if ($rolename == 'client') {
                $roles_c = Role::where('name', 'client')->where('id', $role_id)->first();
                foreach ($client_permissions as $permission_c) {
                    $permission = Permission::where('name', $permission_c)->first();
                    if(!$roles_c->hasPermission($permission_c))
                    {
                        $roles_c->givePermission($permission);
                    }
                }
            } elseif ($rolename == 'staff') {
                $roles_v = Role::where('name', 'staff')->where('id', $role_id)->first();
                foreach ($staff_permissions as $permission_v) {
                    $permission = Permission::where('name', $permission_v)->first();
                    if(!$roles_v->hasPermission($permission_v))
                    {
                        $roles_v->givePermission($permission);
                    }
                }
            }
        }
    }
    // google calendar functions

    public static function colorCodeData($type)
    {
        if($type == 'leave' || $type == 'hearing')
        {
            return 1;
        }
        elseif ($type == 'meeting')
        {
            return 2;
        }
        elseif ($type == 'task')
        {
            return 3;
        }
        elseif ($type == 'projecttask')
        {
            return 11;
        }
        elseif ($type == 'rotas')
        {
            return 3;
        }
        elseif ($type == 'event')
        {
            return 4;
        }
        elseif ($type == 'holiday')
        {
            return 5;
        }
        elseif ($type == 'zoom_meeting')
        {
            return 7;
        }
        elseif ($type == 'lead')
        {
            return 6;
        }
        elseif ($type == 'appointment')
        {
            return 10;
        }
        elseif ($type == 'work_order')
        {
            return 7;
        }
        elseif ($type == 'call')
        {
            return 8;
        }
        elseif ($type == 'interview_schedule')
        {
            return 9;
        }
        else{
            return 11;
        }

    }

    public static $colorCode=[
        1=>'event-warning',
        2=>'event-secondary',
        3=>'event-info',
        4=>'event-warning',
        5=>'event-danger',
        6=>'event-dark',
        7=>'event-black',
        8=>'event-info',
        9=>'event-dark',
        10=>'event-success',
        11=>'event-warning',
    ];

    public static function googleCalendarConfig()
    {
        if(check_file(company_setting('google_calender_json_file')) == false){
            return 'false';
        }else{
            $path = realpath(company_setting('google_calender_json_file'));

            if($path)
            {
                config([
                'google-calendar.default_auth_profile' => 'service_account',
                'google-calendar.auth_profiles.service_account.credentials_json' => $path,
                'google-calendar.auth_profiles.oauth.credentials_json' => $path,
                'google-calendar.auth_profiles.oauth.token_json' => $path,
                'google-calendar.calendar_id' => company_setting('google_calender_id') ? company_setting('google_calender_id') :'',
                'google-calendar.user_to_impersonate' => '',
                ]);
            }
        }

    }

    public static function addCalendarData($request, $type)
    {


        if ((!empty(company_setting('google_calendar_enable', $request->created_by, $request->workspace)) ? company_setting('google_calendar_enable', $request->created_by, $request->workspace) : 'off') == "on") {
            if(Self::googleCalendarConfig() == 'false'){
                return ['error'=> 'Configuration not set properly'];
            }else{
                Self::googleCalendarConfig();
                $event = new GoogleEvent();
                $event->name = $request->title;
                $event->startDateTime = Carbon::parse($request->start_date);
                $event->endDateTime = Carbon::parse($request->end_date);
                $event->colorId = Self::colorCodeData($type);
                $event->save();

            }
        }
    }

    public static function getCalendarData($type)
    {
        try {
            Self::googleCalendarConfig();
            $data= GoogleEvent::get();
            $type = (!empty($type)) ? Self::colorCodeData($type) : 'all';
            $arrayJson = [];
            foreach($data as $val)
            {
                $end_date=date_create($val->endDateTime);
                date_add($end_date,date_interval_create_from_date_string("1 days"));
                if($val->colorId=="$type" || $type == 'all'){

                    $arrayJson[] = [
                        "id"=> $val->id,
                        "title" => $val->summary,
                        "start" => $val->startDateTime,
                        "end" => date_format($end_date,"Y-m-d H:i:s"),
                        "className" => Self::$colorCode[$val->colorId],
                        "allDay" => true,

                    ];
                }
            }
        return $arrayJson;
        } catch (\Throwable $th) {
            return ['error'=> $th->getMessage()];
        }

    }
}
