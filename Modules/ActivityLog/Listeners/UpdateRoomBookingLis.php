<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Holidayz\Entities\RoomBooking;
use Modules\Holidayz\Events\UpdateRoomBooking;

class UpdateRoomBookingLis
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
    public function handle(UpdateRoomBooking $event)
    {
        $booking = $event->booking;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Hotel&Room';
        $activity['sub_module']     = 'Booking';
        $activity['description']    = __('Booking ') . RoomBooking::bookingNumberFormat($booking->getBookingDetails->booking_number) . __(' Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $booking->workspace;
        $activity['created_by']     = Auth::user()->id;
        $activity->save();
    }
}
