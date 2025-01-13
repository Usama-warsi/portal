<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Recruitment\Events\UpdateJobBoard;

class UpdateJobBoardLis
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
    public function handle(UpdateJobBoard $event)
    {
        if (module_is_active('ActivityLog')) {
            $jobBoard = $event->jobBoard;

            $activity                   = new AllActivityLog();
            $activity['module']         = 'HRM';
            $activity['sub_module']     = 'Recruitment';
            $activity['description']    = __('Job On-Boarding Updated by the ');
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $jobBoard->workspace;
            $activity['created_by']     = $jobBoard->created_by;
            $activity->save();
        }
    }
}
