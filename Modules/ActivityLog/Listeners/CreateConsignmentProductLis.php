<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\ConsignmentManagement\Events\CreateProduct;

class CreateConsignmentProductLis
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
    public function handle(CreateProduct $event)
    {
        $product = $event->product;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Consignment';
        $activity['sub_module']     = 'Product';
        $activity['description']    = __('New Product Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $product->workspace_id;
        $activity['created_by']     = $product->created_by;
        $activity->save();
    }
}
