<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Holidayz\Events\UpdateHotelCustomer;

class UpdateHotelCustomerLis
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
    public function handle(UpdateHotelCustomer $event)
    {
        $hotel = $event->hotel;
        
        $activity                   = new AllActivityLog();
        $activity['module']         = 'Hotel&Room';
        $activity['sub_module']     = 'Hotel Customer';
        $activity['description']    = __('Hotel Customer Updated by the ');
        $activity['user_id']        = isset($hotel) && !empty($hotel) ? $hotel->created_by : Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = isset($hotel) && !empty($hotel) ? $hotel->workspace : Auth::user()->workspace_id;
        $activity['created_by']     = isset($hotel) && !empty($hotel) ? $hotel->created_by : Auth::user()->id;
        $activity->save();
    }
}
