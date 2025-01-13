<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Newspaper\Events\UpdateNewspaper;

class UpdateNewspaperLis
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
    public function handle(UpdateNewspaper $event)
    {
        $newspaper = $event->newspaper;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Newspaper';
        $activity['sub_module']     = 'News Paper';
        $activity['description']    = __('News Paper Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $newspaper->workspace;
        $activity['created_by']     = $newspaper->created_by;
        $activity->save();
    }
}
