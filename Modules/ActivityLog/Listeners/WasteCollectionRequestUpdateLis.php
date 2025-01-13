<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\WasteManagement\Events\WasteCollectionRequestUpdate;

class WasteCollectionRequestUpdateLis
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
    public function handle(WasteCollectionRequestUpdate $event)
    {
        $WasteCollection = $event->WasteCollection;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Waste Management';
        $activity['sub_module']     = 'Collection Request';
        $activity['description']    = __('Collection Request ') . $WasteCollection->request_id . __(' Updated by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $WasteCollection->workspace_id;
        $activity['created_by']     = $WasteCollection->created_by;
        $activity->save();
    }
}
