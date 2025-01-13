<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\RepairManagementSystem\Entities\RepairOrderRequest;
use Modules\RepairManagementSystem\Events\UpdateRepairPart;

class UpdateRepairPartLis
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
    public function handle(UpdateRepairPart $event)
    {
        $repair_part = $event->repair_part;
        $reapair_request = RepairOrderRequest::find($repair_part->repair_id);

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Repair';
        $activity['sub_module']     = 'Repair Order Request';
        $activity['description']    = __('Product Part Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $reapair_request->workspace;
        $activity['created_by']     = $reapair_request->created_by;
        $activity->save();
    }
}
