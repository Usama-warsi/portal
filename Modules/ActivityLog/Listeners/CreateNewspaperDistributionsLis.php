<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Newspaper\Events\CreateNewspaperDistributions;

class CreateNewspaperDistributionsLis
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
    public function handle(CreateNewspaperDistributions $event)
    {
        $distribution = $event->distribution;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Newspaper';
        $activity['sub_module']     = 'Distribution Center';
        $activity['description']    = __('New Distribution Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $distribution->workspace;
        $activity['created_by']     = $distribution->created_by;
        $activity->save();
    }
}
