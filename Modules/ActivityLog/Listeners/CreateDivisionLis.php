<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\LegalCaseManagement\Events\CreateDivision;

class CreateDivisionLis
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
    public function handle(CreateDivision $event)
    {
        $division = $event->division;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Legal Case';
        $activity['sub_module']     = 'Circuit/Division';
        $activity['description']    = __('New Division Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $division->workspace;
        $activity['created_by']     = $division->created_by;
        $activity->save();
    }
}