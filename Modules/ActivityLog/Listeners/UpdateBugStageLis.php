<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Taskly\Entities\BugStage;
use Modules\Taskly\Entities\Project;
use Modules\Taskly\Events\UpdateBugStage;

class UpdateBugStageLis
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
    public function handle(UpdateBugStage $event)
    {
        if (module_is_active('ActivityLog')) {
            $bug = $event->bug;
            $project = Project::find($bug->project_id);
            $status = BugStage::find($bug->status);

            $activity                   = new AllActivityLog();
            $activity['module']         = 'Projects';
            $activity['sub_module']     = 'Bug';
            $activity['description']    = __('Bug ') . $bug->title . __(' moved to ') . $status->name  . __(' in project ') . $project->name . __(' by the ');
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $project->workspace;
            $activity['created_by']     = $project->created_by;
            $activity->save();
        }
    }
}
