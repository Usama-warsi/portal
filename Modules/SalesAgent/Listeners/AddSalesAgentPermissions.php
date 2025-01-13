<?php

namespace Modules\SalesAgent\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\SalesAgent\Entities\SalesAgentUtility;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

class AddSalesAgentPermissions
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
    public function handle($event)
    {
        $request      = $event->request;
        $role         = $event->role;
        $permissions  = $event->permissions;

        SalesAgentUtility::GivePermissionToRoles($role->id);
    }
}
