<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\PharmacyManagement\Events\PharmacyInvoiceCreate;

class PharmacyInvoiceCreateLis
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
    public function handle(PharmacyInvoiceCreate $event)
    {
        $pharmacyInvoice = $event->pharmacyInvoice;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Pharmacy';
        $activity['sub_module']     = 'Invoice';
        $activity['description']    = __('New Invoice Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $pharmacyInvoice->workspace_id;
        $activity['created_by']     = $pharmacyInvoice->created_by;
        $activity->save();
    }
}
