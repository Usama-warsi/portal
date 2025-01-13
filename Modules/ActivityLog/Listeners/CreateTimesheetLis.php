<?php

namespace Modules\ActivityLog\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Timesheet\Events\CreateTimesheet;

class CreateTimesheetLis
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
    public function handle(CreateTimesheet $event)
    {
        if (module_is_active('ActivityLog')) {
            $timesheet = $event->timesheet;
            $user = User::find($timesheet->user_id);

            $activity                   = new AllActivityLog();
            $activity['module']         = 'Timesheet';
            $activity['sub_module']     = '--';
            $activity['description']    = __('New Timesheet created of ') . $user->name . __(' by the ');
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $timesheet->workspace_id;
            $activity['created_by']     = $timesheet->created_by;
            $activity->save();
        }
    }
}
