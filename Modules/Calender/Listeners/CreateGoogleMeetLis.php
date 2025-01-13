<?php

namespace Modules\Calender\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Calender\Entities\GoogleCalender;
use Modules\GoogleMeet\Events\CreateGoogleMeet;
 
class CreateGoogleMeetLis
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

    public function handle(CreateGoogleMeet $event)
    {
        // Google Calender
        if ($event->request->get('synchronize_type')  == 'google_calender')
        {
            $new = $event->new;
            $type = 'google_meet';
            $new->start_date = $event->request->start_date;
            $new->end_date = date('Y-m-d H:i', strtotime(+$event->request->duration . "minutes", strtotime($event->request->start_date)));
            \Modules\Calender\Entities\CalenderUtility::addCalendarData($new , $type);
        }
    }
}
