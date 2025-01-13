<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Fleet\Entities\Vehicle;
use Modules\Fleet\Events\UpdateMaintenances;

class UpdateMaintenancesLis
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
    public function handle(UpdateMaintenances $event)
    {
        $maintenances = $event->maintenance;
        $vehicle = Vehicle::find($maintenances->vehicle_name);

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Fleet';
        $activity['sub_module']     = 'Maintenance';
        $activity['description']    = __('Maintenance Updated of vehicle ') . $vehicle->name . __(' by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $maintenances->workspace;
        $activity['created_by']     = $maintenances->created_by;
        $activity->save();
    }
}
