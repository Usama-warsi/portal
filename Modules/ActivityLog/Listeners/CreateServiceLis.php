<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\GarageManagement\Events\CreateService;

class CreateServiceLis
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
    public function handle(CreateService $event)
    {
        $service = $event->service;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Garage/Workshop';
        $activity['sub_module']     = 'Service';
        $activity['description']    = __('New Service Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $service->workspace;
        $activity['created_by']     = $service->created_by;
        $activity->save();
    }
}
