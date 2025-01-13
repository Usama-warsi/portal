<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Sales\Entities\SalesOrder;
use Modules\Sales\Events\UpdateSalesOrder;

class UpdateSalesOrderLis
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
    public function handle(UpdateSalesOrder $event)
    {
        if (module_is_active('ActivityLog')) {
            $salesOrder = $event->salesOrder;

            $activity                   = new AllActivityLog();
            $activity['module']         = 'Sales';
            $activity['sub_module']     = 'Sales Order';
            $activity['description']    = __('Sales Order ') . SalesOrder::salesorderNumberFormat($salesOrder->salesorder_id) . __(' Updated by the ');
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $salesOrder->workspace;
            $activity['created_by']     = $salesOrder->created_by;
            $activity->save();
        }
    }
}
