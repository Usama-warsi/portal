<?php

namespace Modules\Commission\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Sales\Entities\SalesInvoiceItem;
use Modules\Sales\Events\UpdateSalesInvoiceItem;

class UpdateAgentSalesInvoiceLis
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
    public function handle(UpdateSalesInvoiceItem $event)
    {
        if (module_is_active('Commission')) {
            $request = $event->request;

            $invoiceitem = $event->invoiceitem;
            $salesInvoiceItems = SalesInvoiceItem::where('invoice_id', $invoiceitem->invoice_id)->get();
            foreach ($salesInvoiceItems as $salesInvoiceItem) {
                if ($salesInvoiceItem) {
                    $salesInvoiceItem->commission_plan = isset($request->commission_plan) ? $request->commission_plan : '';
                    $salesInvoiceItem->agent           =  !empty($request->agent) ?  implode(',', [$request->agent]) : '';
                    $salesInvoiceItem->update();
                }
            }
        }
    }
}
