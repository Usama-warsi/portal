<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Workflow\Events\CreateWorkflow;

class CreateWorkflowLis
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
    public function handle(CreateWorkflow $event)
    {
        $Workflow = $event->Workflow;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Workflow';
        $activity['sub_module']     = '--';
        $activity['description']    = __('New Workflow Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $Workflow->workspace;
        $activity['created_by']     = $Workflow->created_by;
        $activity->save();
    }
}
