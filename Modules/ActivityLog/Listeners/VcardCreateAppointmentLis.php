<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\VCard\Entities\Business;
use Modules\VCard\Events\CreateAppointment;

class VcardCreateAppointmentLis
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
    public function handle(CreateAppointment $event)
    {
        if (module_is_active('ActivityLog')) {
            $appointment = $event->appointment;
            $Business = Business::find($appointment->business_id);

            $activity                   = new AllActivityLog();
            $activity['module']         = 'vCard';
            $activity['sub_module']     = 'Appointment';
            $activity['description']    = __('New Appointment Created in Business ') . $Business->title . __(' by the ');
            $activity['user_id']        =  $appointment->created_by;
            $activity['url']            = '';
            $activity['workspace']      = $appointment->workspace;
            $activity['created_by']     = $appointment->created_by;
            $activity->save();
        }
    }
}
