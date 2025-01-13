<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\VisitorManagement\Events\UpdateVisitReason;

class UpdateVisitReasonLis
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
    public function handle(UpdateVisitReason $event)
    {
        $visitReason = $event->visitReason;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Visitors';
        $activity['sub_module']     = 'Visit Purpose';
        $activity['description']    = __('Visit Purpose Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $visitReason->workspace;
        $activity['created_by']     = $visitReason->created_by;
        $activity->save();
    }
}
