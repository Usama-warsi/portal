<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use App\Events\ConvertToInvoice;

class ConvertToInvoiceLis
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
    public function handle(ConvertToInvoice $event)
    {
        if (module_is_active('ActivityLog')) {
            $convertInvoice = $event->convertInvoice;
            if ($convertInvoice->invoice_module == 'rent') {
                $activity                   = new AllActivityLog();
                $activity['module']         = 'Rental';
                $activity['sub_module']     = '--';
                $activity['description']    = __('Rental Converted to Invoice by the ');
                $activity['user_id']        =  Auth::user()->id;
                $activity['url']            = '';
                $activity['workspace']      = $convertInvoice->workspace;
                $activity['created_by']     = $convertInvoice->created_by;
                $activity->save();
            } else {
                $activity                   = new AllActivityLog();
                $activity['module']         = 'Proposal';
                $activity['sub_module']     = '--';
                $activity['description']    = __('Proposal Converted to Invoice by the ');
                $activity['user_id']        =  Auth::user()->id;
                $activity['url']            = '';
                $activity['workspace']      = $convertInvoice->workspace;
                $activity['created_by']     = $convertInvoice->created_by;
                $activity->save();
            }
        }
    }
}
