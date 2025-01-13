<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\GarageManagement\Events\UpdateGarageVehicle;

class UpdateGarageVehicleLis
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
    public function handle(UpdateGarageVehicle $event)
    {
        $garagevehicle = $event->garagevehicle;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Garage/Workshop';
        $activity['sub_module']     = 'Vehicle';
        $activity['description']    = __('Vehicle Update by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $garagevehicle->workspace;
        $activity['created_by']     = $garagevehicle->created_by;
        $activity->save();
    }
}
