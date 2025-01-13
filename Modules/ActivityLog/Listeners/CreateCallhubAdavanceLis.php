<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\CallHub\Events\CreateCallhubAdavance;

class CreateCallhubAdavanceLis
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
    public function handle(CreateCallhubAdavance $event)
    {
        $calls = $event->calls;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Call Hub';
        $activity['sub_module']     = 'Call List';
        $activity['description']    = __('New Call Advanced Option Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $calls->workspace_id;
        $activity['created_by']     = $calls->created_by;
        $activity->save();
    }
}
