<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Newspaper\Events\UpdateNewspaperJournalistInfo;

class UpdateNewspaperJournalistInfoLis
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
    public function handle(UpdateNewspaperJournalistInfo $event)
    {
        $information = $event->information;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Newspaper';
        $activity['sub_module']     = 'Journalist information';
        $activity['description']    = __('Journalist information Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $information->workspace;
        $activity['created_by']     = $information->created_by;
        $activity->save();
    }
}
