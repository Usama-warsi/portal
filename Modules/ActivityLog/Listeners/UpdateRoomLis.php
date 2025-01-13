<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Holidayz\Events\UpdateRoom;

class UpdateRoomLis
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
    public function handle(UpdateRoom $event)
    {
        $room = $event->room;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Hotel&Room';
        $activity['sub_module']     = 'Room Types';
        $activity['description']    = __('Room Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $room->workspace;
        $activity['created_by']     = $room->created_by;
        $activity->save();
    }
}
