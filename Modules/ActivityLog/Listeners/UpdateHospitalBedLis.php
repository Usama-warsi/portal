<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\HospitalManagement\Events\UpdateHospitalBed;

class UpdateHospitalBedLis
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
    public function handle(UpdateHospitalBed $event)
    {
        $hospitalbed = $event->hospitalbed;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Hospital';
        $activity['sub_module']     = 'Bed Management';
        $activity['description']    = __('Bed Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $hospitalbed->workspace;
        $activity['created_by']     = $hospitalbed->created_by;
        $activity->save();
    }
}