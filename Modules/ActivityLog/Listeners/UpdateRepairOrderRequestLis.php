<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\RepairManagementSystem\Events\UpdateRepairOrderRequest;

class UpdateRepairOrderRequestLis
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
    public function handle(UpdateRepairOrderRequest $event)
    {
        $repair_order_request = $event->repair_order_request;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Repair';
        $activity['sub_module']     = 'Repair Order Request';
        $activity['description']    = __('Repair Order Request Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $repair_order_request->workspace;
        $activity['created_by']     = $repair_order_request->created_by;
        $activity->save();
    }
}
