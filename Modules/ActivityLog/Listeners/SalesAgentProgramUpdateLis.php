<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\SalesAgent\Events\SalesAgentProgramUpdate;

class SalesAgentProgramUpdateLis
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
    public function handle(SalesAgentProgramUpdate $event)
    {
        $program = $event->program;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Sales Agent';
        $activity['sub_module']     = 'Programs';
        $activity['description']    = __('Program Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $program->workspace;
        $activity['created_by']     = $program->created_by;
        $activity->save();
    }
}
