<?php

namespace Modules\SalesAgent\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\SalesAgent\Entities\SalesAgentUtility;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

class CreateSalesAgentRole
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
        if(module_is_active('SalesAgent'))
        {
            $company    = $event->user;
            $role       = Role::where('name','salesagent')->where('created_by',$company->id)->where('guard_name','web')->exists();
            if(!$role)
            {
                $role                   = new Role();
                $role->name             = 'salesagent';
                $role->guard_name       = 'web';
                $role->module           = 'SalesAgent';
                $role->created_by       = $company->id;
                $role->save();
            }
            SalesAgentUtility::GivePermissionToRoles($role->id);
        }
    }
}
