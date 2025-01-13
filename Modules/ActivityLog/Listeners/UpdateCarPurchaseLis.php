<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\CarDealership\Entities\CarPurchase;
use Modules\CarDealership\Events\UpdateCarPurchase;

class UpdateCarPurchaseLis
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
    public function handle(UpdateCarPurchase $event)
    {
        $car_purchase = $event->car_purchase;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Car Dealership';
        $activity['sub_module']     = 'Car Purchase';
        $activity['description']    =  __('Car Purchase ') . CarPurchase::purchaseInvoiceNumberFormat($car_purchase->purchase_id) . __(' Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $car_purchase->workspace;
        $activity['created_by']     = $car_purchase->created_by;
        $activity->save();
    }
}
