<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\VehicleInspectionManagement\Events\CreateInspectionRequest;

class CreateInspectionRequestLis
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
    public function handle(CreateInspectionRequest $event)
    {
        $inspectionRequest = $event->inspectionRequest;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Vehicle Inspection';
        $activity['sub_module']     = 'Inspection Request';
        $activity['description']    = __('New Inspection Request Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $inspectionRequest->workspace;
        $activity['created_by']     = $inspectionRequest->created_by;
        $activity->save();
    }
}