<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Recruitment\Events\JobApplicationArchive;

class JobApplicationArchiveLis
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
    public function handle(JobApplicationArchive $event)
    {
        if (module_is_active('ActivityLog')) {
            $UpdateJobBoard = $event->UpdateJobBoard;

            $activity                   = new AllActivityLog();
            $activity['module']         = 'HRM';
            $activity['sub_module']     = 'Recruitment';
            if ($UpdateJobBoard->is_archive == 1) {
                $activity['description']    = __('Job Application Add to Archive by the ');
            } else {
                $activity['description']    = __('Job Application Unarchive by the ');
            }
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $UpdateJobBoard->workspace;
            $activity['created_by']     = $UpdateJobBoard->created_by;
            $activity->save();
        }
    }
}
