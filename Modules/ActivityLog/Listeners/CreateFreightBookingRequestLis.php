<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\FreightManagementSystem\Events\CreateFreightBookingRequest;

class CreateFreightBookingRequestLis
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
    public function handle(CreateFreightBookingRequest $event)
    {
        $booking_request = $event->booking_request;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Freight';
        $activity['sub_module']     = 'Booking';
        $activity['description']    = __('New Booking Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $booking_request->workspace;
        $activity['created_by']     = $booking_request->created_by;
        $activity->save();
    }
}
