<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\AgricultureManagement\Events\CreateAgricultureCanal;

class CreateAgricultureCanalLis
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
    public function handle(CreateAgricultureCanal $event)
    {
        $agriculturecanal = $event->agriculturecanal;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Agriculture';
        $activity['sub_module']     = 'Agriculture Canal';
        $activity['description']    = __('New Agriculture Canal Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $agriculturecanal->workspace;
        $activity['created_by']     = $agriculturecanal->created_by;
        $activity->save();
    }
}