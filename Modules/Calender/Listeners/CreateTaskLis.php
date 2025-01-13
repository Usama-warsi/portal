<?php

namespace Modules\Calender\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Calender\Entities\GoogleCalender;
use Modules\Taskly\Events\CreateTask;


class CreateTaskLis
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
    public function handle(CreateTask $event)
    {
        // Google Calender
        if($event->request->get('synchronize_type')  == 'google_calender')
        {
            $task = $event->task;
            $type ='projecttask';
            $task->end_date=$event->request->due_date;
            \Modules\Calender\Entities\CalenderUtility::addCalendarData($task , $type);
        }
    }
}
