<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\HospitalManagement\Entities\Patient;
use Modules\HospitalManagement\Events\HospitalAppointmentStatus;

class HospitalAppointmentStatusLis
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
    public function handle(HospitalAppointmentStatus $event)
    {
        $appointment = $event->appointment;
        $patient = Patient::find($appointment->patient_id);

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Hospital';
        $activity['sub_module']     = 'Appointments';
        $activity['description']    = __('Appointment Status Updated of patient ') . $patient->name . __(' by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $appointment->workspace;
        $activity['created_by']     = $appointment->created_by;
        $activity->save();
    }
}
