<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\FixEquipment\Events\CreateAccessories;

class CreateAccessoriesLis
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
    public function handle(CreateAccessories $event)
    {
        $accessories = $event->accessories;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Fix Equipment';
        $activity['sub_module']     = 'Accessories';
        $activity['description']    = __('New Accessories Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $accessories->workspace;
        $activity['created_by']     = $accessories->created_by;
        $activity->save();
    }
}