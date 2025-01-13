<?php

namespace Modules\Calender\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Calender\Entities\GoogleCalender;
use Modules\Hrm\Events\CreateHolidays;

class CreateHolidaysLis
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
    public function handle(CreateHolidays $event)
    {
        // Google Calender

        if($event->request->get('synchronize_type')  == 'google_calender')
        {

            $holiday = $event->holiday;
            $type ='holiday';
            $holiday->title=$event->request->occasion;
            \Modules\Calender\Entities\CalenderUtility::addCalendarData($holiday , $type);
        }
    }
}
