<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\School\Events\CreateClassroom;

class CreateClassroomLis
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
    public function handle(CreateClassroom $event)
    {
        $classroom = $event->classroom;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'School & Institute';
        $activity['sub_module']     = 'Class';
        $activity['description']    = __('New Class Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $classroom->workspace;
        $activity['created_by']     = $classroom->created_by;
        $activity->save();
    }
}