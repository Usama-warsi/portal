<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Holidayz\Events\CreateRoomFacility;

class CreateRoomFacilityLis
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
    public function handle(CreateRoomFacility $event)
    {
        $facility = $event->facility;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Hotel&Room';
        $activity['sub_module']     = 'Facilities';
        $activity['description']    = __('New Room Facility Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $facility->workspace;
        $activity['created_by']     = $facility->created_by;
        $activity->save();
    }
}