<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\FixEquipment\Events\UpdateAudit;

class UpdateAuditLis
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
    public function handle(UpdateAudit $event)
    {
        $audit = $event->audit;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Fix Equipment';
        $activity['sub_module']     = 'Audit';
        $activity['description']    = __('Audit Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $audit->workspace;
        $activity['created_by']     = $audit->created_by;
        $activity->save();
    }
}