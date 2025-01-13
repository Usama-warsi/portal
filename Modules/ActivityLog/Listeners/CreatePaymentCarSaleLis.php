<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\CarDealership\Entities\CarSale;
use Modules\CarDealership\Events\CreatePaymentCarSale;

class CreatePaymentCarSaleLis
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
    public function handle(CreatePaymentCarSale $event)
    {
        $car_sale = $event->car_sale;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Car Dealership';
        $activity['sub_module']     = 'Car Sale';
        $activity['description']    =  __('Add payment in Car Sale ') . CarSale::saleInvoiceNumberFormat($car_sale->sale_id) . __(' by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $car_sale->workspace;
        $activity['created_by']     = $car_sale->created_by;
        $activity->save();
    }
}
