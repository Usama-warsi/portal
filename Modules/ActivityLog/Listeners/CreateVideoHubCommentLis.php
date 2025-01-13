<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\VideoHub\Events\CreateVideoHubComment;

class CreateVideoHubCommentLis
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
    public function handle(CreateVideoHubComment $event)
    {
        $comments = $event->comments;
        $video = $event->video;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Video Hub';
        $activity['sub_module']     = '--';
        $activity['description']    = __('New Comment Add in video ') . $video->title . __(' by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $comments->workspace;
        $activity['created_by']     = $comments->comment_by;
        $activity->save();
    }
}
