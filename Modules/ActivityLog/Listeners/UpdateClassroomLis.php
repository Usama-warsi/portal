<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\School\Events\UpdateClassroom;

class UpdateClassroomLis
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
    public function handle(UpdateClassroom $event)
    {
        $classroom = $event->classroom;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'School & Institute';
        $activity['sub_module']     = 'Class';
        $activity['description']    = __('Class Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $classroom->workspace;
        $activity['created_by']     = $classroom->created_by;
        $activity->save();
    }
}
