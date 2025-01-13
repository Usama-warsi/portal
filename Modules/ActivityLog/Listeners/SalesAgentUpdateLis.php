<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\SalesAgent\Events\SalesAgentUpdate;
use Modules\SalesAgent\Entities\SalesAgent;

class SalesAgentUpdateLis
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
    public function handle(SalesAgentUpdate $event)
    {
        $salesagent = $event->salesagent;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Sales Agent';
        $activity['sub_module']     = 'Sales Agents';
        $activity['description']    = __('Sales Agent ') . SalesAgent::salesagentNumberFormat($salesagent->customer_id) . __(' Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $salesagent->workspace;
        $activity['created_by']     = $salesagent->created_by;
        $activity->save();
    }
}
