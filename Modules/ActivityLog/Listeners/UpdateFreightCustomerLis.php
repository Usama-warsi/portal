<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\FreightManagementSystem\Events\UpdateFreightCustomer;

class UpdateFreightCustomerLis
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
    public function handle(UpdateFreightCustomer $event)
    {
        $customer = $event->customer;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Freight';
        $activity['sub_module']     = 'Customer';
        $activity['description']    = __('Customer Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $customer->workspace;
        $activity['created_by']     = $customer->created_by;
        $activity->save();
    }
}
