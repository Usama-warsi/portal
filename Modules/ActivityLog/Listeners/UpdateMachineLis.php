<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\MachineRepairManagement\Events\UpdateMachine;

class UpdateMachineLis
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
    public function handle(UpdateMachine $event)
    {
        $machine = $event->machine;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Machine Repair';
        $activity['sub_module']     = 'Machine';
        $activity['description']    = __('Machine Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $machine->workspace;
        $activity['created_by']     = $machine->created_by;
        $activity->save();
    }
}
