<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\LegalCaseManagement\Events\CreateAdvocate;

class CreateAdvocateLis
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
    public function handle(CreateAdvocate $event)
    {
        $advocate = $event->advocate;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Legal Case';
        $activity['sub_module']     = 'Advocate';
        $activity['description']    = __('New Advocate Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $advocate->workspace;
        $activity['created_by']     = $advocate->created_by;
        $activity->save();
    }
}