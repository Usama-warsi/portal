<?php

namespace Modules\Calender\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Calender\Entities\GoogleCalender;
use Modules\VCard\Events\CreateAppointment;

class CreateAppointmentLis
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
         // Google Calender
             $appointment = $event->appointment;
             $type ='appointment';
             $appointment->title=$event->request->name;
             $appointment->start_date=$event->request->date;
             $appointment->end_date=$event->request->date;
             \Modules\Calender\Entities\CalenderUtility::addCalendarData($appointment , $type);
    }
}
