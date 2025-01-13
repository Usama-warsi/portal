<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\LMS\Events\UpdateBlog;

class UpdateBlogLis
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
    public function handle(UpdateBlog $event)
    {
        $blog = $event->blog;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'LMS';
        $activity['sub_module']     = 'Blog';
        $activity['description']    = __('Blog Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $blog->workspace_id;
        $activity['created_by']     = $blog->created_by;
        $activity->save();
    }
}
