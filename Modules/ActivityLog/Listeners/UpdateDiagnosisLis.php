<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\MachineRepairManagement\Entities\MachineInvoice;
use Modules\MachineRepairManagement\Events\UpdateDiagnosis;

class UpdateDiagnosisLis
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
    public function handle(UpdateDiagnosis $event)
    {
        $invoice = $event->repair_request;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Machine Repair';
        $activity['sub_module']     = 'Diagnosis';
        $activity['description']    = __('Diagnosis ') . MachineInvoice::machineInvoiceNumberFormat($invoice->invoice_id) . __(' Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $invoice->workspace;
        $activity['created_by']     = $invoice->created_by;
        $activity->save();
    }
}
