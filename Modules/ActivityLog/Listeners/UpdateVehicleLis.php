<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Fleet\Events\UpdateVehicle;

class UpdateVehicleLis
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
    public function handle(UpdateVehicle $event)
    {
        $vehicle = $event->vehicle;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Fleet';
        $activity['sub_module']     = 'Vehicle';
        $activity['description']    = __('Vehicle Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $vehicle->workspace;
        $activity['created_by']     = $vehicle->created_by;
        $activity->save();
    }
}
