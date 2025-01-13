<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\MachineRepairManagement\Events\UpdateRepairRequest;

class UpdateRepairRequestLis
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
    public function handle(UpdateRepairRequest $event)
    {
        $repair_request = $event->repair_request;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Machine Repair';
        $activity['sub_module']     = 'Repair Request';
        $activity['description']    = __('Repair Request Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $repair_request->workspace;
        $activity['created_by']     = $repair_request->created_by;
        $activity->save();
    }
}
