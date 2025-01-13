<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\AgricultureManagement\Events\CreateAgricultureUser;

class CreateAgricultureUserLis
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
    public function handle(CreateAgricultureUser $event)
    {
        $agricultureuser = $event->agricultureuser;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Agriculture';
        $activity['sub_module']     = 'Agriculture User';
        $activity['description']    = __('New Agriculture User Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $agricultureuser->workspace;
        $activity['created_by']     = $agricultureuser->created_by;
        $activity->save();
    }
}
