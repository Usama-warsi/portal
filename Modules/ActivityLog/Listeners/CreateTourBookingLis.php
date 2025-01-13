<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\TourTravelManagement\Events\CreateTourBooking;

class CreateTourBookingLis
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
    public function handle(CreateTourBooking $event)
    {
        $tour_booking = $event->tour_booking;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Tour & Travel';
        $activity['sub_module']     = 'Tour';
        $activity['description']    = __('Tour Converted to Booking by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $tour_booking->workspace;
        $activity['created_by']     = $tour_booking->created_by;
        $activity->save();
    }
}
