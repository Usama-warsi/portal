<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\LaundryManagement\Events\LaundryRequestCreate;

class LaundryRequestCreateLis
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
    public function handle(LaundryRequestCreate $event)
    {
        $laundryrequest = $event->laundryrequest;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Laundry';
        $activity['sub_module']     = 'Laundry Request';
        $activity['description']    = __('New Laundry Request Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $laundryrequest->workspace_id;
        $activity['created_by']     = $laundryrequest->created_by;
        $activity->save();
    }
}
