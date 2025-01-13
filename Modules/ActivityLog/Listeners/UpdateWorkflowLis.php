<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Workflow\Events\UpdateWorkflow;

class UpdateWorkflowLis
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
    public function handle(UpdateWorkflow $event)
    {
        $workflow = $event->workflow;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Workflow';
        $activity['sub_module']     = '--';
        $activity['description']    = __('Workflow Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $workflow->workspace;
        $activity['created_by']     = $workflow->created_by;
        $activity->save();
    }
}
