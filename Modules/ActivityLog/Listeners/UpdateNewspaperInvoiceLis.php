<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Newspaper\Entities\NewspaperInvoice;
use Modules\Newspaper\Events\UpdateNewspaperInvoice;

class UpdateNewspaperInvoiceLis
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
    public function handle(UpdateNewspaperInvoice $event)
    {
        $invoice = $event->invoice;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Newspaper';
        $activity['sub_module']     = 'Invoice';
        $activity['description']    = __('Invoice ') . NewspaperInvoice::invoiceNumberFormat($invoice->invoice_id) . __(' Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $invoice->workspace;
        $activity['created_by']     = $invoice->created_by;
        $activity->save();
    }
}
