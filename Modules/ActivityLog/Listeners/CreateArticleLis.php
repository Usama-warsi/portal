<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Internalknowledge\Events\CreateArticle;

class CreateArticleLis
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
    public function handle(CreateArticle $event)
    {
        $article = $event->article;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Internalknowledge';
        $activity['sub_module']     = 'Article';
        $activity['description']    = __('New Article Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $article->workspace;
        $activity['created_by']     = $article->created_by;
        $activity->save();
    }
}
