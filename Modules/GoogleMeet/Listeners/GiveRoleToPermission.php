<?php

namespace Modules\GoogleMeet\Listeners;

use App\Events\GivePermissionToRole;
use Modules\GoogleMeet\Entities\GoogleMeetUtility;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GiveRoleToPermission
{
    public function __construct()
    {
        //
    }

    public function handle(GivePermissionToRole $event)
    {
        $role_id = $event->role_id;
        $rolename = $event->rolename;
        $user_module = $event->user_module;
        if(!empty($user_module))
        {
            if (in_array("GoogleMeet", $user_module))
            {
                GoogleMeetUtility::GivePermissionToRoles($role_id,$rolename);
            }
        }
    }
}
