<?php

namespace Modules\Calender\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Calender\Entities\GoogleCalender;
use Modules\Hrm\Events\CreateLeave;

class CreateLeaveLis
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
    public function handle(CreateLeave $event)
    {
        if($event->request->get('synchronize_type')  == 'google_calender')
        {
            $leave = $event->leave;
            $type ='leave';
            $leave->title=$event->request->leave_reason;
            \Modules\Calender\Entities\CalenderUtility::addCalendarData($leave , $type);
        }
    }
}
