<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Fleet\Entities\Vehicle;
use Modules\Fleet\Events\CreateInsurance;

class CreateInsuranceLis
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
    public function handle(CreateInsurance $event)
    {
        $insurance = $event->insurance;
        $vehicle = Vehicle::find($insurance->vehicle_name);

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Fleet';
        $activity['sub_module']     = 'Insurance';
        $activity['description']    = __('Insurance Created of vehicle ') . $vehicle->name . __(' by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $insurance->workspace;
        $activity['created_by']     = $insurance->created_by;
        $activity->save();
    }
}
