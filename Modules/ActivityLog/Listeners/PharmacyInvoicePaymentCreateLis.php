<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\PharmacyManagement\Entities\PharmacyInvoice;
use Modules\PharmacyManagement\Events\PharmacyInvoicePaymentCreate;

class PharmacyInvoicePaymentCreateLis
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
    public function handle(PharmacyInvoicePaymentCreate $event)
    {
        $pharmacyInvoice = $event->pharmacyInvoice;
        if ($pharmacyInvoice) {
            $activity                   = new AllActivityLog();
            $activity['module']         = 'Pharmacy';
            $activity['sub_module']     = 'Invoice';
            $activity['description']    = __('Add Payment in ') . $pharmacyInvoice->invoice . __(' Updated by the ');
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = Auth::user()->workspace_id;
            $activity['created_by']     = Auth::user()->id;
            $activity->save();
        }
    }
}
