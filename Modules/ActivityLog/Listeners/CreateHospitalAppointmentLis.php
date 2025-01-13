<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\HospitalManagement\Entities\Patient;
use Modules\HospitalManagement\Events\CreateHospitalAppointment;

class CreateHospitalAppointmentLis
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
    public function handle(CreateHospitalAppointment $event)
    {
        $hospitalappointment = $event->hospitalappointment;
        $patient = Patient::find($hospitalappointment->patient_id);

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Hospital';
        $activity['sub_module']     = 'Appointments';
        $activity['description']    = __('New Appointment Created of patient ') . $patient->name . __(' by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $hospitalappointment->workspace;
        $activity['created_by']     = $hospitalappointment->created_by;
        $activity->save();
    }
}
