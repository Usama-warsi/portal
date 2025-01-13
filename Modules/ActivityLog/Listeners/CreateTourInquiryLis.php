<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\TourTravelManagement\Events\CreateTourInquiry;

class CreateTourInquiryLis
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
    public function handle(CreateTourInquiry $event)
    {
        $tour = $event->tour;
        $activity                   = new AllActivityLog();
        $activity['module']         = 'Tour & Travel';
        $activity['sub_module']     = 'Tourist Inquiry';
        $activity['description']    = __('New Tourist Inquiry Created by the ');
        $activity['user_id']        =  isset(Auth::user()->id) ? Auth::user()->id : $tour->created_by;
        $activity['url']            = '';
        $activity['workspace']      = $tour->workspace;
        $activity['created_by']     = $tour->created_by;
        $activity->save();
    }
}
