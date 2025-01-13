<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Lead\Events\LeadUploadFile;

class LeadUploadFileLis
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
    public function handle(LeadUploadFile $event)
    {
        if (module_is_active('ActivityLog')) {
            $lead = $event->lead;

            $activity                   = new AllActivityLog();
            $activity['module']         = 'CRM';
            $activity['sub_module']     = 'Lead';
            $activity['description']    = __('New File Uploaded in lead ') . $lead->name . __(' by the ');
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $lead->workspace_id;
            $activity['created_by']     = $lead->created_by;
            $activity->save();
        }
    }
}
