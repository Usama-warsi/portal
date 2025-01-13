<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\CarDealership\Events\CreateCarSale;

class CreateCarSaleLis
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
    public function handle(CreateCarSale $event)
    {
        $car_sale = $event->car_sale;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Car Dealership';
        $activity['sub_module']     = 'Car Sale';
        $activity['description']    = __('New Car Sale Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $car_sale->workspace;
        $activity['created_by']     = $car_sale->created_by;
        $activity->save();
    }
}
