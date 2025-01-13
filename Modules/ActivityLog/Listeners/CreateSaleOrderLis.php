<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\ConsignmentManagement\Events\CreateSaleOrder;

class CreateSaleOrderLis
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
    public function handle(CreateSaleOrder $event)
    {
        $saleOrder = $event->saleOrder;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Consignment';
        $activity['sub_module']     = 'Sale Order';
        $activity['description']    = __('New Sale Order Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $saleOrder->workspace_id;
        $activity['created_by']     = $saleOrder->created_by;
        $activity->save();
    }
}
