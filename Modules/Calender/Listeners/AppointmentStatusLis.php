<?php

namespace Modules\Calender\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Calender\Entities\GoogleCalender;
use Modules\Appointment\Events\AppointmentStatus;


class AppointmentStatusLis
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
    public function handle(AppointmentStatus $event)
    {
        // Google Calender
        if ($event->request->get('synchronize_type') == 'google_calender') {
            $schedule = $event->schedule;
            $type = 'appointment';
            $schedule->title = $schedule->appointment->name;
            $schedule->start_date = $schedule->date;
            $schedule->end_date   = $schedule->date;
            if ($schedule->status == 'Approved') {
                \Modules\Calender\Entities\CalenderUtility::addCalendarData($schedule , $type);
            }
        }
    }
}
