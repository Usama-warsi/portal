<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\MedicalLabManagement\Entities\Patient;
use Modules\MedicalLabManagement\Events\CreatePatientCard;

class CreatePatientCardLis
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
    public function handle(CreatePatientCard $event)
    {
        $patientCard = $event->patientCard;
        $patient = Patient::find($patientCard->patient_id);

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Medical Lab';
        $activity['sub_module']     = 'Patients';
        $activity['description']    = __('New Patient Card Created of Patient ') . $patient->first_name . __(' ') . $patient->last_name . __(' by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $patientCard->workspace_id;
        $activity['created_by']     = $patientCard->created_by;
        $activity->save();
    }
}
