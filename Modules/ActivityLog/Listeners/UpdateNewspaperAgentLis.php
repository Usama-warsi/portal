<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Newspaper\Events\UpdateNewspaperAgent;

class UpdateNewspaperAgentLis
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
    public function handle(UpdateNewspaperAgent $event)
    {
        $agentdetail = $event->agentdetail;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Newspaper';
        $activity['sub_module']     = 'Agent';
        $activity['description']    = __('Agent Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $agentdetail->workspace;
        $activity['created_by']     = $agentdetail->created_by;
        $activity->save();
    }
}