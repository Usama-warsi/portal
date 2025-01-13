<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Assets\Events\CreateAssets;

class CreateAssetsLis
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
    public function handle(CreateAssets $event)
    {
        if (module_is_active('ActivityLog')) {
            $assets = $event->assets;

            $activity                   = new AllActivityLog();
            $activity['module']         = 'Assets';
            $activity['sub_module']     = '--';
            $activity['description']    = __('New Asset created by the ');
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $assets->workspace_id;
            $activity['created_by']     = $assets->created_by;
            $activity->save();
        }
    }
}
