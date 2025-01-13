<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\VCard\Events\BusinessStatus;

class BusinessStatusLis
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
    public function handle(BusinessStatus $event)
    {
        if (module_is_active('ActivityLog')) {
            $status = $event->status;

            $activity                   = new AllActivityLog();
            $activity['module']         = 'vCard';
            $activity['sub_module']     = 'Business';
            if ($status->status == 'active') {
                $activity['description']    = __('Business Status Activated by the ');
            } else {
                $activity['description']    = __('Business Status ') . $status->status . __(' by the ');
            }
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $status->workspace;
            $activity['created_by']     = $status->created_by;
            $activity->save();
        }
    }
}
