<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Hrm\Entities\Employee;
use Modules\Hrm\Events\UpdateBulkAttendance;

class UpdateBulkAttendanceLis
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
    public function handle(UpdateBulkAttendance $event)
    {
        if (module_is_active('ActivityLog')) {
            $employeeAttendance = $event->employeeAttendance;

            $activity                   = new AllActivityLog();
            $activity['module']         = 'HRM';
            $activity['sub_module']     = 'Leave Management';
            $activity['description']    = __('Bulk Attendance Updated of employee ') . Employee::employeeIdFormat($employeeAttendance->employee_id) . __(' by the ');
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $employeeAttendance->workspace;
            $activity['created_by']     = $employeeAttendance->created_by;
            $activity->save();
        }
    }
}
