<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\TourTravelManagement\Events\UpdateTour;

class UpdateTourLis
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
    public function handle(UpdateTour $event)
    {
        $tour = $event->tour;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Tour & Travel';
        $activity['sub_module']     = 'Tour';
        $activity['description']    = __('Tour Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $tour->workspace;
        $activity['created_by']     = $tour->created_by;
        $activity->save();
    }
}
