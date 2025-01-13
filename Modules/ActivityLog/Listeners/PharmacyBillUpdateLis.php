<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\PharmacyManagement\Events\PharmacyBillUpdate;

class PharmacyBillUpdateLis
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
    public function handle(PharmacyBillUpdate $event)
    {
        $PharmacyBill = $event->PharmacyBill;
        if ($PharmacyBill) {
            $activity                   = new AllActivityLog();
            $activity['module']         = 'Pharmacy';
            $activity['sub_module']     = 'Bill';
            $activity['description']    = __('Bill #BILL0000') . $PharmacyBill->id . __(' Updated by the ');
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $PharmacyBill->workspace_id;
            $activity['created_by']     = $PharmacyBill->created_by;
            $activity->save();
        }
    }
}
