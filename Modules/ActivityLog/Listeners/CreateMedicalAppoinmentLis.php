<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\MedicalLabManagement\Entities\Patient;
use Modules\MedicalLabManagement\Events\CreateMedicalAppoinment;

class CreateMedicalAppoinmentLis
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
    public function handle(CreateMedicalAppoinment $event)
    {
        $labAppoinment = $event->labAppoinment;
        $patient = Patient::find($labAppoinment->patient_id);

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Medical Lab';
        $activity['sub_module']     = 'Appointment';
        $activity['description']    = __('New Appointment Created of Patient ') . $patient->first_name . __(' ') . $patient->last_name . __(' by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $labAppoinment->workspace_id;
        $activity['created_by']     = $labAppoinment->created_by;
        $activity->save();
    }
}
