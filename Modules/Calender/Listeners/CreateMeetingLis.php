<?php

namespace Modules\Calender\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Calender\Entities\GoogleCalender;
use Modules\Sales\Events\CreateMeeting;

class CreateMeetingLis
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
    public function handle(CreateMeeting $event)
    {
        // Google Calender
        if($event->request->get('synchronize_type')  == 'google_calender')
        {
            $meeting = $event->meeting;
            $type ='meeting';
            $meeting->title=$event->request->name;
            \Modules\Calender\Entities\CalenderUtility::addCalendarData($meeting , $type);
        }
    }
}
