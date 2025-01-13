<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\CMMS\Events\CreateWorkrequest;

class CreateWorkrequestLis
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
    public function handle(CreateWorkrequest $event)
    {
        if (module_is_active('ActivityLog')) {
            $workorder = $event->workorder;

            $activity                   = new AllActivityLog();
            $activity['module']         = 'CMMS';
            $activity['sub_module']     = 'Work Request';
            $activity['description']    = __('New Work Request Created by the ');
            $activity['user_id']        =  $workorder->company_id;
            $activity['url']            = '';
            $activity['workspace']      = $workorder->workspace;
            $activity['created_by']     = $workorder->company_id;
            $activity->save();
        }
    }
}
