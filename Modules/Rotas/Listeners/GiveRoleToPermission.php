<?php

namespace Modules\Rotas\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\GivePermissionToRole;
 use Modules\Rotas\Entities\RotaUtility;


class GiveRoleToPermission
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param PostWasCreated $event
     * @return void
     */
    public function handle(GivePermissionToRole $event)
    {
        $role_id = $event->role_id;
        $rolename = $event->rolename;
        $user_module = $event->user_module;
        if(!empty($user_module))
        {
            if (in_array("Rotas", $user_module))
            {
                RotaUtility::GivePermissionToRoles($role_id, $rolename);
            }
        }
    }
}