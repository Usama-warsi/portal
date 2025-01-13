<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\FixEquipment\Events\CreateLicence;

class CreateLicenceLis
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
    public function handle(CreateLicence $event)
    {
        $license = $event->license;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Fix Equipment';
        $activity['sub_module']     = 'Licenses';
        $activity['description']    = __('New License Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $license->workspace;
        $activity['created_by']     = $license->created_by;
        $activity->save();
    }
}
