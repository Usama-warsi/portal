<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\VehicleBookingManagement\Events\CreateVehicleRoute;

class CreateVehicleRouteLis
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
    public function handle(CreateVehicleRoute $event)
    {
        $route = $event->route;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Vehicle Booking';
        $activity['sub_module']     = 'Route Manage';
        $activity['description']    = __('New Route Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $route->workspace;
        $activity['created_by']     = $route->created_by;
        $activity->save();
    }
}
