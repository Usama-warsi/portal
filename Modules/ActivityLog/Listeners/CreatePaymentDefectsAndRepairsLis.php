<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\VehicleInspectionManagement\Entities\InspectionDefectsAndRepairs;
use Modules\VehicleInspectionManagement\Events\CreatePaymentDefectsAndRepairs;

class CreatePaymentDefectsAndRepairsLis
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
    public function handle(CreatePaymentDefectsAndRepairs $event)
    {
        $invoice = $event->invoice;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Vehicle Inspection';
        $activity['sub_module']     = 'Defects And Repairs';
        $activity['description']    = __('Add Payment in Defects And Repairs ') . InspectionDefectsAndRepairs::inspectionInvoiceNumberFormat($invoice->invoice_id) . __(' by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $invoice->workspace;
        $activity['created_by']     = $invoice->created_by;
        $activity->save();
    }
}
