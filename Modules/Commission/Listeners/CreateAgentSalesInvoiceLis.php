<?php

namespace Modules\Commission\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Sales\Entities\SalesInvoiceItem;
use Modules\Sales\Events\CreateSalesInvoiceItem;

class CreateAgentSalesInvoiceLis
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
    public function handle(CreateSalesInvoiceItem $event)
    {
        if (module_is_active('Commission')) {
            $request = $event->request;
            $invoiceitem = $event->invoiceitem;

            if ($invoiceitem) {
                $invoiceitem->commission_plan = !empty($request->commission_plan) ? $request->commission_plan : '';
                $invoiceitem->agent  = !empty($request->agent) ?  implode(',', [$request->agent]) : '';
                $invoiceitem->save();
            }
        }
    }
}
