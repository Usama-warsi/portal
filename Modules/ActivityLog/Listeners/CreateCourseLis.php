<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\LMS\Events\CreateCourse;

class CreateCourseLis
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
    public function handle(CreateCourse $event)
    {
        $course = $event->course;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'LMS';
        $activity['sub_module']     = 'Course';
        $activity['description']    = __('New Course Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $course->workspace_id;
        $activity['created_by']     = $course->created_by;
        $activity->save();
    }
}
