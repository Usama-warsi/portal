<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\CarDealership\Events\UpdateDealershipProduct;

class UpdateDealershipProductLis
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
    public function handle(UpdateDealershipProduct $event)
    {
        $dealershipProduct = $event->dealershipProduct;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Car Dealership';
        $activity['sub_module']     = 'Dealership Product';
        $activity['description']    = __('Dealership Product Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $dealershipProduct->workspace_id;
        $activity['created_by']     = $dealershipProduct->created_by;
        $activity->save();
    }
}
