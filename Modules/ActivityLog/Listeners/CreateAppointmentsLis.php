<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Appointment\Events\CreateAppointments;

class CreateAppointmentsLis
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
    public function handle(CreateAppointments $event)
    {
        if (module_is_active('ActivityLog')) {
            $schedule = $event->schedule;

            $activity                   = new AllActivityLog();
            $activity['module']         = 'Appointment';
            $activity['sub_module']     = 'Public Appointments';
            $activity['description']    = __('New Public Appointment created by the ');
            $activity['user_id']        =  $schedule->created_by;
            $activity['url']            = '';
            $activity['workspace']      = $schedule->workspace;
            $activity['created_by']     = $schedule->created_by;
            $activity->save();
        }
    }
}
