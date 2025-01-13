<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Fleet\Events\CreateVehicle;

class CreateVehicleLis
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
    public function handle(CreateVehicle $event)
    {
        $Vehicle = $event->Vehicle;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Fleet';
        $activity['sub_module']     = 'Vehicle';
        $activity['description']    = __('New Vehicle Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $Vehicle->workspace;
        $activity['created_by']     = $Vehicle->created_by;
        $activity->save();
    }
}
