<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Commission\Events\UpdateCommissionPlan;

class UpdateCommissionPlanLis
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
    public function handle(UpdateCommissionPlan $event)
    {
        $commissionPlan = $event->commissionPlanId;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Commission';
        $activity['sub_module']     = 'Commission Plan';
        $activity['description']    = __('Commission Plan Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $commissionPlan->workspace;
        $activity['created_by']     = $commissionPlan->created_by;
        $activity->save();
    }
}
