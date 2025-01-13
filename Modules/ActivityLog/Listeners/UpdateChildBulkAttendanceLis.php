<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\ChildcareManagement\Entities\Child;
use Modules\ChildcareManagement\Events\UpdateChildBulkAttendance;

class UpdateChildBulkAttendanceLis
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
    public function handle(UpdateChildBulkAttendance $event)
    {
        $childAttendance = $event->childAttendance;
        $child = Child::find($childAttendance->child_id);

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Childcare';
        $activity['sub_module']     = 'Bulk Attendance';
        $activity['description']    = __('Bulk Attendance Updated for child ') . $child->first_name . __(' ') . $child->last_name . __(' by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $childAttendance->workspace;
        $activity['created_by']     = $childAttendance->created_by;
        $activity->save();
    }
}
