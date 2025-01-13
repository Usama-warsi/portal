<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Taskly\Entities\Project;
use Modules\Taskly\Entities\Stage;
use Modules\Taskly\Events\UpdateTaskStage;

class UpdateTaskStageLis
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
    public function handle(UpdateTaskStage $event)
    {
        if (module_is_active('ActivityLog')) {
            $task = $event->task;
            $project = Project::find($task->project_id);
            $status = Stage::find($task->status);

            $activity                   = new AllActivityLog();
            $activity['module']         = 'Projects';
            $activity['sub_module']     = 'Task';
            $activity['description']    = __('Task ') . $task->title . __(' moved to ') . $status->name  . __(' in project ') . $project->name . __(' by the ');
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $task->workspace;
            $activity['created_by']     = $project->created_by;
            $activity->save();
        }
    }
}
