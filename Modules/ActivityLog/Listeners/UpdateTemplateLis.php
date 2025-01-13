<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Feedback\Events\UpdateTemplate;

class UpdateTemplateLis
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
    public function handle(UpdateTemplate $event)
    {
        if (module_is_active('ActivityLog')) {
            $template = $event->template;

            $activity                   = new AllActivityLog();
            $activity['module']         = 'Feedback';
            $activity['sub_module']     = 'Template';
            $activity['description']    = __('Template Updated by the ');
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $template->workspace;
            $activity['created_by']     = $template->created_by;
            $activity->save();
        }
    }
}
