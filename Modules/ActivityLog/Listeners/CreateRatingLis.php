<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Feedback\Events\CreateRating;

class CreateRatingLis
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
    public function handle(CreateRating $event)
    {
        if (module_is_active('ActivityLog')) {
            $rating = $event->rating;

            $activity                   = new AllActivityLog();
            $activity['module']         = 'Feedback';
            $activity['sub_module']     = 'Template';
            $activity['description']    = __('New Rating created by the ');
            $activity['user_id']        =  $rating->created_by;
            $activity['url']            = '';
            $activity['workspace']      = $rating->workspace;
            $activity['created_by']     = $rating->created_by;
            $activity->save();
        }
    }
}
