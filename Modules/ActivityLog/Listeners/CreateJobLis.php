<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Hrm\Entities\Branch;
use Modules\Recruitment\Events\CreateJob;

class CreateJobLis
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
    public function handle(CreateJob $event)
    {
        if (module_is_active('ActivityLog')) {
            $job = $event->job;
            $branch = Branch::find($job->branch);

            $activity                   = new AllActivityLog();
            $activity['module']         = 'HRM';
            $activity['sub_module']     = 'Recruitment';
            $activity['description']    = __('New Job ') . $job->title . __(' created in branch ') . $branch->name . __(' by the ');
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $job->workspace;
            $activity['created_by']     = $job->created_by;
            $activity->save();
        }
    }
}
