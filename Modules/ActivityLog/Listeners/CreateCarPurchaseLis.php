<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\CarDealership\Events\CreateCarPurchase;

class CreateCarPurchaseLis
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
    public function handle(CreateCarPurchase $event)
    {
        $car_purchase = $event->car_purchase;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Car Dealership';
        $activity['sub_module']     = 'Car Purchase';
        $activity['description']    = __('New Car Purchase Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $car_purchase->workspace;
        $activity['created_by']     = $car_purchase->created_by;
        $activity->save();
    }
}