<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\ParkingManagement\Events\CreateParkingPayment;

class CreateParkingPaymentLis
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
    public function handle(CreateParkingPayment $event)
    {
        $payment = $event->payment;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Parking';
        $activity['sub_module']     = 'Payment';
        $activity['description']    = __('New Payment Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $payment->workspace;
        $activity['created_by']     = $payment->created_by;
        $activity->save();
    }
}
