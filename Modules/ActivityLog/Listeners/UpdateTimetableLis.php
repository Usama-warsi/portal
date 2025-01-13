<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\School\Events\UpdateTimetable;

class UpdateTimetableLis
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
    public function handle(UpdateTimetable $event)
    {
        $timetable = $event->timetable;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'School & Institute';
        $activity['sub_module']     = 'Class Timetable';
        $activity['description']    = __('Timetable Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $timetable->workspace;
        $activity['created_by']     = $timetable->created_by;
        $activity->save();
    }
}
