<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\InnovationCenter\Events\UpdateChallenge;

class UpdateChallengeLis
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
    public function handle(UpdateChallenge $event)
    {
        $Challenge = $event->Challenge;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Innovation Center';
        $activity['sub_module']     = 'Upcoming Challenges';
        $activity['description']    = __('Upcoming Challenge Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $Challenge->workspace;
        $activity['created_by']     = $Challenge->created_by;
        $activity->save();
    }
}
