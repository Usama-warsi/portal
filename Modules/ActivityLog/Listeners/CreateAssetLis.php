<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\FixEquipment\Events\CreateAsset;

class CreateAssetLis
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
    public function handle(CreateAsset $event)
    {
        $asset = $event->asset;

        $activity                   = new AllActivityLog();
        $activity['module']         = 'Fix Equipment';
        $activity['sub_module']     = 'Assets';
        $activity['description']    = __('New Asset Created by the ');
        $activity['user_id']        =  Auth::user()->id;
        $activity['url']            = '';
        $activity['workspace']      = $asset->workspace;
        $activity['created_by']     = $asset->created_by;
        $activity->save();
    }
}