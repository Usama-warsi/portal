<?php

namespace Modules\Commission\Listeners;

use App\Events\CreateInvoice;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateAgentInvoiceLis
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
    public function handle(CreateInvoice $event)
    {
        if (module_is_active('Commission')) {

            $request = $event->request;
            $invoice = $event->invoice;
            if ($invoice->invoice_module == 'taskly' || $invoice->invoice_module == 'account') {
                if ($invoice) {
                    $invoice->commission_plan = !empty($request->commission_plan) ? $request->commission_plan : '';
                    $invoice->agent           = !empty($request->agent) ? implode(",", [$request->agent]) : '';
                    $invoice->save();
                }
            }
        }
    }
}
