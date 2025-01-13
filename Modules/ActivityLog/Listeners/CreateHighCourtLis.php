<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\LegalCaseManagement\Events\CreateHighCourt;

class CreateHighCourtLis
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
    public function handle(CreateHighCourt $event)
    {
        $highCourt = $event->highCourt;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Legal Case';
        $activity['sub_module']     = 'High Courts';
        $activity['description']    = __('New High Court Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $highCourt->workspace;
        $activity['created_by']     = $highCourt->created_by;
        $activity->save();
    }
}
