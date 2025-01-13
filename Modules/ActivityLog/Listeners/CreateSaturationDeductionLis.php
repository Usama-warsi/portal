<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Hrm\Entities\Employee;
use Modules\Hrm\Events\CreateSaturationDeduction;

class CreateSaturationDeductionLis
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
    public function handle(CreateSaturationDeduction $event)
    {
        if (module_is_active('ActivityLog')) {
            $saturationdeduction = $event->saturationdeduction;

            $activity                   = new AllActivityLog();
            $activity['module']         = 'HRM';
            $activity['sub_module']     = 'Payroll';
            $activity['description']    = __('New Saturation Deduction created of employee ') . Employee::employeeIdFormat($saturationdeduction->employee_id) . __(' by the ');
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $saturationdeduction->workspace;
            $activity['created_by']     = $saturationdeduction->created_by;
            $activity->save();
        }
    }
}
