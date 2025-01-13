<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\BeverageManagement\Events\UpdateBillOfMaterial;

class UpdateBillOfMaterialLis
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
    public function handle(UpdateBillOfMaterial $event)
    {
        $bill_of_material = $event->bill_of_material;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Beverage';
        $activity['sub_module']     = 'Bill OF Material';
        $activity['description']    = __('Bill OF Material Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $bill_of_material->workspace;
        $activity['created_by']     = $bill_of_material->created_by;
        $activity->save();
    }
}
