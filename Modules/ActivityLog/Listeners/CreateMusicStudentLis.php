<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\MusicInstitute\Events\CreateMusicStudent;

class CreateMusicStudentLis
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
    public function handle(CreateMusicStudent $event)
    {
        $student = $event->student;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Music Institute';
        $activity['sub_module']     = 'Student';
        $activity['description']    = __('New Student Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $student->workspace;
        $activity['created_by']     = $student->created_by;
        $activity->save();
    }
}
