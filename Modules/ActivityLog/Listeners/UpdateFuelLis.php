<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Fleet\Entities\Vehicle;
use Modules\Fleet\Events\UpdateFuel;

class UpdateFuelLis
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
    public function handle(UpdateFuel $event)
    {
        $fuel = $event->fuel;
        $vehicle = Vehicle::find($fuel->vehicle_name);

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Fleet';
        $activity['sub_module']     = 'Fuel History';
        $activity['description']    = __('Fuel Updated of vehicle ') . $vehicle->name . __(' by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $fuel->workspace;
        $activity['created_by']     = $fuel->created_by;
        $activity->save();
    }
}
