<?php

namespace Modules\Calender\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Calender\Entities\GoogleCalender;
use Modules\Lead\Events\CreateDealTask;

class CreateDealTaskLis
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
    public function handle(CreateDealTask $event)
    {
        // Google Calender
        if($event->request->get('synchronize_type')  == 'google_calender')
        {
            $dealTask = $event->dealTask;
            $type ='task';
            $dealTask->title=$event->request->name;
            $dealTask->start_date=$event->request->date;
            $dealTask->end_date=$event->request->date;
            \Modules\Calender\Entities\CalenderUtility::addCalendarData($dealTask , $type);
        }
    }
}
