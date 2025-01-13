<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\HospitalManagement\Events\UpdateHospitalMedicine;

class UpdateHospitalMedicineLis
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
    public function handle(UpdateHospitalMedicine $event)
    {
        $medicine = $event->medicine;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Hospital';
        $activity['sub_module']     = 'Medicines';
        $activity['description']    = __('Medicine Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $medicine->workspace;
        $activity['created_by']     = $medicine->created_by;
        $activity->save();
    }
}
