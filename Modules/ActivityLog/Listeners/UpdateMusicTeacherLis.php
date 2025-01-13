<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\MusicInstitute\Events\UpdateMusicTeacher;

class UpdateMusicTeacherLis
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
    public function handle(UpdateMusicTeacher $event)
    {
        $teacher = $event->teacher;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Music Institute';
        $activity['sub_module']     = 'Teacher';
        $activity['description']    = __('Teacher Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $teacher->workspace;
        $activity['created_by']     = $teacher->created_by;
        $activity->save();
    }
}
