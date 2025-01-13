<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\PropertyManagement\Events\UpdatePropertyUnit;

class UpdatePropertyUnitLis
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
    public function handle(UpdatePropertyUnit $event)
    {
        $propertyUnit = $event->propertyUnit;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Property Manage';
        $activity['sub_module']     = 'Units';
        $activity['description']    = __('Unit Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $propertyUnit->workspace;
        $activity['created_by']     = $propertyUnit->created_by;
        $activity->save();
    }
}
