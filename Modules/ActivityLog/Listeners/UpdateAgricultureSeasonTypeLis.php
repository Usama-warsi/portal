<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\AgricultureManagement\Events\UpdateAgricultureSeasonType;

class UpdateAgricultureSeasonTypeLis
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
    public function handle(UpdateAgricultureSeasonType $event)
    {
        $agricultureseasontype = $event->agricultureseasontype;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Agriculture';
        $activity['sub_module']     = 'Agriculture Season Type';
        $activity['description']    = __('Agriculture Season Type Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $agricultureseasontype->workspace;
        $activity['created_by']     = $agricultureseasontype->created_by;
        $activity->save();
    }
}