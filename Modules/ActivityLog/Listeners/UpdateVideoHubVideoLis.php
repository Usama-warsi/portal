<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\VideoHub\Events\UpdateVideoHubVideo;

class UpdateVideoHubVideoLis
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
    public function handle(UpdateVideoHubVideo $event)
    {
        $video = $event->video;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Video Hub';
        $activity['sub_module']     = '--';
        $activity['description']    = __('Video Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $video->workspace;
        $activity['created_by']     = $video->created_by;
        $activity->save();
    }
}
