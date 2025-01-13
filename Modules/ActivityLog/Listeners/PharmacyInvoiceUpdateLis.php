<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\PharmacyManagement\Events\PharmacyInvoiceUpdate;

class PharmacyInvoiceUpdateLis
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
    public function handle(PharmacyInvoiceUpdate $event)
    {
        $pharmacyInvoice = $event->pharmacyInvoice;
        if ($pharmacyInvoice) {
            $activity                   = new AllActivityLog();
            $activity['module']         = 'Pharmacy';
            $activity['sub_module']     = 'Invoice';
            $activity['description']    = __('Invoice #INV0000') . $pharmacyInvoice->id . __(' Updated by the ');
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $pharmacyInvoice->workspace_id;
            $activity['created_by']     = $pharmacyInvoice->created_by;
            $activity->save();
        }
    }
}
