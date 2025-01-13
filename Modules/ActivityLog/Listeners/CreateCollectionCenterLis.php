<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\BeverageManagement\Events\CreateCollectionCenter;

class CreateCollectionCenterLis
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
    public function handle(CreateCollectionCenter $event)
    {
        $collection_center = $event->collection_center;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Beverage';
        $activity['sub_module']     = 'Collection Center';
        $activity['description']    = __('New Collection Center Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $collection_center->workspace;
        $activity['created_by']     = $collection_center->created_by;
        $activity->save();
    }
}
