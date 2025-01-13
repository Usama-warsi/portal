<?php

namespace Modules\AIDocument\Listeners;

use App\Events\GivePermissionToRole;
use Modules\AIDocument\Entities\AIDocumentUtility;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
     * @param  object  $event
     * @return void
     */
    public function handle(GivePermissionToRole $event)
    {
        $role_id = $event->role_id;
        $rolename = $event->rolename;
        $user_module = $event->user_module;
        if (!empty($user_module)) {
            if (in_array("AIDocument", $user_module)) {
                AIDocumentUtility::GivePermissionToRoles($role_id, $rolename);
            }
        }
    }
}
