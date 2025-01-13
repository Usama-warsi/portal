<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Taskly\Events\CreateProject;

class CreateProjectLis
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
    public function handle(CreateProject $event)
    {
        if(module_is_active('ActivityLog'))
        {
        $project = $event->project;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Projects';
        $activity['sub_module']     = 'Project';
        $activity['description']    = __('New Project created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $project->workspace;
        $activity['created_by']     = $project->created_by;
        $activity->save();
        }
    }
}
