<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Retainer\Events\RetainerDuplicate;

class RetainerDuplicateLis
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
    public function handle(RetainerDuplicate $event)
    {
        if (module_is_active('ActivityLog')) {
            $duplicateRetainer = $event->duplicateRetainer;

            $activity                   = new AllActivityLog();
            $activity['module']         = 'Retainer';
            $activity['sub_module']     = '--';
            $activity['description']    = __('Duplicate Retainer Created by the ');
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $duplicateRetainer->workspace;
            $activity['created_by']     = $duplicateRetainer->created_by;
            $activity->save();
        }
    }
}
