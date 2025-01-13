<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\BeautySpaManagement\Events\UpdateBeautyService;

class UpdateBeautyServiceLis
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
    public function handle(UpdateBeautyService $event)
    {
        $beautyservice = $event->beautyservice;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Beauty Spa';
        $activity['sub_module']     = 'Service';
        $activity['description']    = __('Service Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $beautyservice->workspace;
        $activity['created_by']     = $beautyservice->created_by;
        $activity->save();
    }
}
