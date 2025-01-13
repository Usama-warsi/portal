<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\CateringManagement\Events\UpdateEventDetail;

class UpdateEventDetailLis
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
    public function handle(UpdateEventDetail $event)
    {
        $EventDetail = $event->EventDetail;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Catering';
        $activity['sub_module']     = 'Event Details';
        $activity['description']    = __('Event Detail Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $EventDetail->workspace_id;
        $activity['created_by']     = $EventDetail->created_by;
        $activity->save();
    }
}
