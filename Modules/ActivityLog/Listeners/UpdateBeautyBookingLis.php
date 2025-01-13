<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\BeautySpaManagement\Events\UpdateBeautyBooking;

class UpdateBeautyBookingLis
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
    public function handle(UpdateBeautyBooking $event)
    {
        $beautybooking = $event->beautybooking;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Beauty Spa';
        $activity['sub_module']     = 'Booking';
        $activity['description']    = __('Booking Updated by the ');
        $activity['user_id']        =  isset(Auth::user()->id) ? Auth::user()->id : $beautybooking->created_by;
        $activity['url']            = '';
        $activity['workspace']      = $beautybooking->workspace;
        $activity['created_by']     = $beautybooking->created_by;
        $activity->save();
    }
}
