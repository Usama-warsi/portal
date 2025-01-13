<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\AgricultureManagement\Events\UpdateAgricultureServiceProduct;

class UpdateAgricultureServiceProductLis
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
    public function handle(UpdateAgricultureServiceProduct $event)
    {
        $serviceproduct = $event->serviceproduct;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Agriculture';
        $activity['sub_module']     = 'Agriculture Service Product';
        $activity['description']    = __('Agriculture Service Product Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $serviceproduct->workspace;
        $activity['created_by']     = $serviceproduct->created_by;
        $activity->save();
    }
}
