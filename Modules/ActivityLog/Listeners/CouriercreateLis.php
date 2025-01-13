<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\CourierManagement\Events\Couriercreate;

class CouriercreateLis
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
    public function handle(Couriercreate $event)
    {
        $courierPackageInfo = $event->courierPackageInfo;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Courier';
        $activity['sub_module']     = 'Create Courier';
        $activity['description']    = __('New Courier Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $courierPackageInfo->workspace_id;
        $activity['created_by']     = $courierPackageInfo->created_by;
        $activity->save();
    }
}
