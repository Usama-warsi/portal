<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Holidayz\Events\CreateRoomFeature;

class CreateRoomFeatureLis
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
    public function handle(CreateRoomFeature $event)
    {
        $feature = $event->feature;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Hotel&Room';
        $activity['sub_module']     = 'Room Features';
        $activity['description']    = __('New Room Feature Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $feature->workspace;
        $activity['created_by']     = $feature->created_by;
        $activity->save();
    }
}
