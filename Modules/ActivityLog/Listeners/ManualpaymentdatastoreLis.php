<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\CourierManagement\Events\Manualpaymentdatastore;

class ManualpaymentdatastoreLis
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
    public function handle(Manualpaymentdatastore $event)
    {
        $courierPayment = $event->courierPayment;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Courier';
        $activity['sub_module']     = 'Create Courier';
        $activity['description']    = __('Add Payment in Courier ') . $courierPayment->tracking_id . __(' by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $courierPayment->workspace_id;
        $activity['created_by']     = $courierPayment->created_by;
        $activity->save();
    }
}
