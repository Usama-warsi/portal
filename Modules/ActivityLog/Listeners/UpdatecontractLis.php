<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Contract\Entities\Contract;
use Modules\contract\Events\Updatecontract;

class UpdatecontractLis
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
    public function handle(Updatecontract $event)
    {
        if (module_is_active('ActivityLog')) {
            $contract = $event->contract;

            $activity                   = new AllActivityLog();
            $activity['module']         = 'Contract';
            $activity['sub_module']     = 'Contract';
            $activity['description']    = __('Contract ') . Contract::contractNumberFormat($contract->id) . __(' Updated by the ');
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $contract->workspace;
            $activity['created_by']     = $contract->created_by;
            $activity->save();
        }
    }
}
