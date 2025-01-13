<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;

class UpdateHistoryLis
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
    public function handle($event)
    {
        if (module_is_active('ActivityLog')) {
            $aiDocument = $event->request;

            $activity                   = new AllActivityLog();
            $activity['module']         = 'Ai';
            $activity['sub_module']     = 'AI Document History';
            $activity['description']    = __('Ai Document Updated by the ');
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $aiDocument->workspace_id;
            $activity['created_by']     = $aiDocument->created_by;
            $activity->save();
        }
    }
}
