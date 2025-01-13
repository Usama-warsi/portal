<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\PropertyManagement\Events\CreateTenant;

class CreateTenantLis
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
    public function handle(CreateTenant $event)
    {
        $tenant = $event->tenant;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Property Manage';
        $activity['sub_module']     = 'Tenant';
        $activity['description']    = __('New Tenant Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $tenant->workspace;
        $activity['created_by']     = $tenant->created_by;
        $activity->save();
    }
}
