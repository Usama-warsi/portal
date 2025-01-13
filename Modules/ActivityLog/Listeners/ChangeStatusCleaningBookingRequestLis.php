<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\CleaningManagement\Events\ChangeStatusCleaningBookingRequest;

class ChangeStatusCleaningBookingRequestLis
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
    public function handle(ChangeStatusCleaningBookingRequest $event)
    {
        $booking = $event->booking;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Cleaning';
        $activity['sub_module']     = 'Booking Request';
        $activity['description']    = __('Booking Request Status Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $booking->workspace;
        $activity['created_by']     = $booking->created_by;
        $activity->save();
    }
}
